<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SubscriptionPlanResource;
use App\Http\Resources\Api\V1\UserSubscriptionResource;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Services\Payments\PaymentManager;
use App\Services\Subscriptions\SubscriptionAccessService;
use App\Support\FrontendUrlResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SubscriptionController extends Controller
{
    public function index()
    {
        return SubscriptionPlanResource::collection(
            SubscriptionPlan::query()
                ->active()
                ->orderBy('sort_order')
                ->orderBy('price')
                ->get()
        );
    }

    public function mine(Request $request, SubscriptionAccessService $subscriptions)
    {
        $active = $subscriptions->activeSubscriptionFor($request->user());

        return response()->json([
            'data' => [
                'active' => $active ? new UserSubscriptionResource($active->loadMissing('plan')) : null,
                'history' => UserSubscriptionResource::collection(
                    $request->user()
                        ->subscriptions()
                        ->with('plan')
                        ->latest()
                        ->limit(10)
                        ->get()
                ),
            ],
        ]);
    }

    public function checkout(Request $request, PaymentManager $paymentManager)
    {
        $validated = $request->validate([
            'subscription_plan_id' => ['required', 'integer', 'exists:subscription_plans,id'],
            'gateway' => ['nullable', 'string', 'in:iotec,flutterwave'],
            'phone' => ['nullable', 'string', 'max:40'],
        ]);

        $plan = SubscriptionPlan::query()
            ->active()
            ->findOrFail($validated['subscription_plan_id']);

        $gateway = $validated['gateway'] ?? config('payments.default_gateway', env('PAYMENT_DEFAULT_GATEWAY', 'iotec'));
        $phone = $validated['phone'] ?? $request->user()->phone;
        $frontendUrl = FrontendUrlResolver::resolveFromRequest($request);

        $checkout = DB::transaction(function () use ($request, $plan, $gateway, $phone, $frontendUrl) {
            $subscription = UserSubscription::create([
                'user_id' => $request->user()->id,
                'subscription_plan_id' => $plan->id,
                'status' => (float) $plan->price <= 0 ? 'active' : 'pending',
                'starts_at' => (float) $plan->price <= 0 ? now() : null,
                'expires_at' => (float) $plan->price <= 0 ? now()->addDays(max(1, (int) $plan->duration_days)) : null,
                'metadata' => [
                    'checkout_source' => 'subscription_api',
                ],
            ]);

            $payment = Payment::create([
                'user_id' => $request->user()->id,
                'gateway' => (float) $plan->price <= 0 ? 'free' : $gateway,
                'gateway_reference' => $subscription->uuid,
                'status' => (float) $plan->price <= 0 ? 'completed' : 'pending',
                'currency' => $plan->currency ?: 'UGX',
                'amount' => $plan->price,
                'metadata' => [
                    'purchase_type' => 'subscription',
                    'subscription_plan_id' => $plan->id,
                    'user_subscription_id' => $subscription->id,
                ],
            ]);

            $transaction = PaymentTransaction::create([
                'user_id' => $request->user()->id,
                'payment_id' => $payment->id,
                'purchase_type' => 'subscription',
                'subscription_plan_id' => $plan->id,
                'user_subscription_id' => $subscription->id,
                'gateway' => (float) $plan->price <= 0 ? 'free' : $gateway,
                'gateway_reference' => $subscription->uuid,
                'amount' => $plan->price,
                'currency' => $plan->currency ?: 'UGX',
                'phone' => $phone,
                'email' => $request->user()->email,
                'customer_name' => $request->user()->name,
                'status' => (float) $plan->price <= 0 ? PaymentTransaction::SUCCESSFUL : PaymentTransaction::PENDING,
                'metadata' => [
                    'checkout_source' => 'subscription_api',
                    'requires_polling' => $gateway === 'iotec',
                    'frontend_url' => $frontendUrl,
                ],
            ]);

            $payment->update([
                'payment_transaction_id' => $transaction->id,
                'transaction_id' => $transaction->internal_reference,
            ]);

            $subscription->update(['payment_transaction_id' => $transaction->id]);

            return compact('subscription', 'transaction');
        });

        $transaction = $checkout['transaction'];

        if ($transaction->status !== PaymentTransaction::SUCCESSFUL) {
            try {
                $transaction = $paymentManager->gateway($gateway)->initiate($transaction);
            } catch (RuntimeException) {
                return response()->json([
                    'message' => 'We could not start this subscription payment right now. Please try again or contact Nara Promotionz support.',
                    'data' => [
                        'subscription' => new UserSubscriptionResource($checkout['subscription']->refresh()->load('plan')),
                        'payment' => $this->paymentPayload($transaction->refresh()),
                    ],
                ], 422);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Subscription checkout started.',
            'data' => [
                'transaction_reference' => $transaction->internal_reference,
                'gateway' => $transaction->gateway,
                'status' => $transaction->status,
                'checkout_url' => $transaction->checkout_url,
                'requires_polling' => (bool) data_get($transaction->metadata, 'requires_polling', $transaction->gateway === 'iotec'),
                'subscription' => new UserSubscriptionResource($checkout['subscription']->refresh()->load('plan')),
                'payment' => $this->paymentPayload($transaction),
            ],
        ]);
    }

    private function paymentPayload(PaymentTransaction $transaction): array
    {
        return [
            'id' => $transaction->id,
            'reference' => $transaction->internal_reference,
            'gateway' => $transaction->gateway,
            'status' => $transaction->status,
            'amount' => (float) $transaction->amount,
            'currency' => $transaction->currency,
            'checkout_url' => $transaction->checkout_url,
            'paid_at' => $transaction->paid_at?->toIso8601String(),
        ];
    }
}

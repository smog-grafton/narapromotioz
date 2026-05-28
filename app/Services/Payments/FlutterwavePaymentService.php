<?php

namespace App\Services\Payments;

use App\Models\PaymentTransaction;
use App\Support\FrontendUrlResolver;
use App\Services\Tickets\TicketUnlockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class FlutterwavePaymentService implements PaymentGatewayInterface
{
    public function __construct(private readonly TicketUnlockService $ticketUnlockService)
    {
    }

    public function initiate(PaymentTransaction $transaction): PaymentTransaction
    {
        $secret = config('services.flutterwave.secret_key');

        if (! $secret) {
            throw new RuntimeException('Flutterwave is not configured.');
        }

        $frontendUrl = (string) data_get($transaction->metadata, 'frontend_url', '');
        $frontendUrl = FrontendUrlResolver::isAllowed($frontendUrl)
            ? $frontendUrl
            : FrontendUrlResolver::fallbackFrontend();

        $redirectUrl = config('services.flutterwave.redirect_url')
            ?: rtrim($frontendUrl, '/') . '/payments/callback/flutterwave';

        $title = $transaction->subscriptionPlan?->name
            ?: $transaction->event?->name
            ?: $transaction->video?->title
            ?: 'Nara Promotionz access';

        $payload = [
            'tx_ref' => $transaction->internal_reference,
            'amount' => (float) $transaction->amount,
            'currency' => $transaction->currency,
            'redirect_url' => $redirectUrl,
            'payment_options' => 'card,mobilemoneyuganda',
            'customer' => [
                'email' => $transaction->email ?: $transaction->user?->email,
                'phonenumber' => $transaction->phone ?: $transaction->user?->phone,
                'name' => $transaction->customer_name ?: $transaction->user?->name,
            ],
            'customizations' => [
                'title' => 'Nara Promotionz Fight Night',
                'description' => $title,
                'logo' => asset('assets/images/nara-logo.png'),
            ],
            'meta' => [
                'ticket_purchase_id' => $transaction->ticket_purchase_id,
                'event_id' => $transaction->boxing_event_id,
                'subscription_plan_id' => $transaction->subscription_plan_id,
                'user_subscription_id' => $transaction->user_subscription_id,
                'video_id' => $transaction->boxing_video_id,
                'purchase_type' => $transaction->purchase_type,
            ],
        ];

        $response = Http::withToken($secret)
            ->acceptJson()
            ->post(rtrim(config('services.flutterwave.base_url'), '/') . '/payments', $payload);

        $json = $response->json() ?? [];

        if (! $response->successful() || data_get($json, 'status') !== 'success') {
            $transaction->update([
                'status' => PaymentTransaction::FAILED,
                'provider_status' => data_get($json, 'status'),
                'initiation_payload' => $json,
                'failure_reason' => data_get($json, 'message', 'Flutterwave checkout could not be started.'),
            ]);

            throw new RuntimeException('Flutterwave checkout could not be started.');
        }

        $transaction->update([
            'status' => PaymentTransaction::PENDING,
            'provider_status' => data_get($json, 'status'),
            'gateway_reference' => data_get($json, 'data.tx_ref', $transaction->internal_reference),
            'external_reference' => data_get($json, 'data.id'),
            'checkout_url' => data_get($json, 'data.link'),
            'initiation_payload' => $json,
            'expires_at' => now()->addMinutes(45),
        ]);

        return $transaction->refresh();
    }

    public function verify(PaymentTransaction $transaction, array $context = []): PaymentTransaction
    {
        $secret = config('services.flutterwave.secret_key');

        if (! $secret) {
            throw new RuntimeException('Flutterwave is not configured.');
        }

        $transactionId = $context['transaction_id'] ?? $transaction->external_reference;

        if ($transactionId) {
            $response = Http::withToken($secret)
                ->acceptJson()
                ->get(rtrim(config('services.flutterwave.base_url'), '/') . "/transactions/{$transactionId}/verify");
        } else {
            $response = Http::withToken($secret)
                ->acceptJson()
                ->get(rtrim(config('services.flutterwave.base_url'), '/') . '/transactions/verify_by_reference', [
                    'tx_ref' => $transaction->internal_reference,
                ]);
        }

        $json = $response->json() ?? [];
        $data = data_get($json, 'data', []);
        $providerStatus = strtolower((string) data_get($data, 'status', data_get($json, 'status', 'pending')));
        $referenceMatches = data_get($data, 'tx_ref') === $transaction->internal_reference;
        $amountMatches = (float) data_get($data, 'amount', 0) >= (float) $transaction->amount;
        $currencyMatches = strtoupper((string) data_get($data, 'currency', $transaction->currency)) === strtoupper($transaction->currency);

        $transaction->update([
            'provider_status' => $providerStatus,
            'external_reference' => data_get($data, 'id', $transaction->external_reference),
            'gateway_reference' => data_get($data, 'flw_ref', $transaction->gateway_reference),
            'verification_payload' => $json,
        ]);

        if ($response->successful() && $providerStatus === 'successful' && $referenceMatches && $amountMatches && $currencyMatches) {
            return $this->ticketUnlockService->unlockForSuccessfulTransaction($transaction->refresh(), $json);
        }

        if (in_array($providerStatus, ['failed', 'cancelled', 'canceled'], true)) {
            $this->ticketUnlockService->markTransactionFailed($transaction->refresh(), $providerStatus, $json);
        }

        return $transaction->refresh();
    }

    public function handleCallback(Request $request): ?PaymentTransaction
    {
        $secretHash = config('services.flutterwave.secret_hash');

        if ($secretHash && $request->header('verif-hash') !== $secretHash) {
            abort(401, 'Invalid webhook signature.');
        }

        $reference = $request->input('tx_ref')
            ?: $request->input('data.tx_ref')
            ?: $request->input('reference');

        if (! $reference) {
            return null;
        }

        $transaction = PaymentTransaction::where('internal_reference', $reference)->first();

        if (! $transaction) {
            return null;
        }

        $transaction->update(['callback_payload' => $request->all()]);

        return $this->verify($transaction, [
            'transaction_id' => $request->input('transaction_id') ?: $request->input('data.id'),
        ]);
    }
}

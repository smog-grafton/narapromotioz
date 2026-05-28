<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TicketCheckoutRequest;
use App\Http\Resources\Api\V1\TicketPurchaseResource;
use App\Models\AffiliateCoupon;
use App\Models\AffiliateCouponRedemption;
use App\Models\BoxingEvent;
use App\Models\EventTicket;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use App\Models\TicketPurchase;
use App\Services\EmailTemplateService;
use App\Services\Payments\PaymentManager;
use App\Support\FrontendUrlResolver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class TicketCheckoutController extends Controller
{
    public function checkout(TicketCheckoutRequest $request, BoxingEvent $event, PaymentManager $paymentManager)
    {
        $ticket = EventTicket::where('boxing_event_id', $event->id)
            ->where('id', $request->event_ticket_id)
            ->onSale()
            ->firstOrFail();

        $quantity = $request->integer('quantity');

        abort_if($quantity > $ticket->max_per_purchase, 422, 'Ticket quantity exceeds the maximum allowed.');
        abort_if($ticket->remaining_quantity < $quantity, 422, 'Not enough tickets are available.');

        $gateway = $request->input('gateway') ?: $request->input('payment_method') ?: config('payments.default_gateway', env('PAYMENT_DEFAULT_GATEWAY', 'flutterwave'));
        $holderPhone = $request->input('phone') ?: $request->input('holder.phone') ?: $request->user()->phone;
        $frontendUrl = FrontendUrlResolver::resolveFromRequest($request);

        $coupon = $this->resolveCoupon($request->input('coupon_code'), $event, $ticket);

        $checkout = DB::transaction(function () use ($request, $event, $ticket, $quantity, $gateway, $holderPhone, $coupon, $frontendUrl) {
            $unitPrice = (float) $ticket->price;
            $total = $unitPrice * $quantity;
            $currency = $ticket->currency ?: 'UGX';
            $discount = $coupon ? $coupon->discountFor($total, $currency) : 0.0;
            $fee = 0;
            $tax = 0;
            $grandTotal = max(0, $total - $discount + $fee + $tax);
            $commission = $coupon ? $coupon->commissionFor($grandTotal) : 0.0;
            $isExplicitlyFreeTicket = (bool) $ticket->is_complimentary || $unitPrice <= 0;
            $isZeroByDiscount = $coupon !== null && $total > 0 && $grandTotal <= 0;
            $isFreeAccessGrant = $isExplicitlyFreeTicket || $isZeroByDiscount;
            $paymentStatus = $isFreeAccessGrant ? 'completed' : 'pending';

            abort_if($gateway === 'iotec' && ! $isFreeAccessGrant && strtoupper((string) $currency) === 'UGX' && $grandTotal < 500, 422, 'Mobile money payments start from UGX 500. Please choose another pass or payment method.');
            abort_if(! $isFreeAccessGrant && $grandTotal <= 0, 422, 'This ticket total is invalid for paid checkout. Please refresh and try again.');

            $purchase = TicketPurchase::create([
                'event_ticket_id' => $ticket->id,
                'user_id' => $request->user()->id,
                'uuid' => (string) Str::uuid(),
                'order_number' => $this->generateOrderNumber(),
                'ticket_holder_name' => $request->input('holder.name', $request->user()->name),
                'ticket_holder_email' => $request->input('holder.email', $request->user()->email),
                'ticket_holder_phone' => $request->input('holder.phone'),
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $total,
                'discount_amount' => $discount,
                'affiliate_commission_amount' => $commission,
                'tax' => $tax,
                'fee' => $fee,
                'grand_total' => $grandTotal,
                'currency' => $currency,
                'affiliate_coupon_id' => $coupon?->id,
                'coupon_code' => $coupon?->code,
                'payment_method' => $isFreeAccessGrant ? 'free' : $gateway,
                'status' => $isFreeAccessGrant ? 'completed' : 'pending',
                'payment_status' => $paymentStatus,
                'access_status' => $isFreeAccessGrant ? 'unlocked' : 'locked',
                'ticket_channel' => $this->channelFor($ticket->access_type ?: $ticket->ticket_type),
                'allows_live_stream' => (bool) $ticket->grants_live_access,
                'allows_replay' => (bool) $ticket->grants_replay_access,
                'allows_venue_entry' => (bool) ($ticket->allows_venue_entry ?? in_array($ticket->access_type, ['venue', 'vip_ringside', 'complimentary', 'sponsor_media'], true)),
                'allows_prize_draw' => (bool) ($ticket->allows_prize_draw ?? ! $ticket->is_complimentary),
            ]);

            $payment = Payment::create([
                'user_id' => $request->user()->id,
                'ticket_purchase_id' => $purchase->id,
                'gateway' => $isFreeAccessGrant ? 'free' : $gateway,
                'gateway_reference' => $purchase->order_number,
                'status' => $isFreeAccessGrant ? 'completed' : $purchase->payment_status,
                'currency' => $purchase->currency,
                'amount' => $purchase->grand_total,
                'metadata' => [
                    'checkout_source' => 'api',
                    'coupon_code' => $coupon?->code,
                    'free_reason' => $isFreeAccessGrant ? ($isExplicitlyFreeTicket ? 'ticket_marked_free' : 'coupon_discounted_to_zero') : null,
                ],
            ]);

            $transaction = PaymentTransaction::create([
                'user_id' => $request->user()->id,
                'boxing_event_id' => $event->id,
                'event_ticket_id' => $ticket->id,
                'ticket_purchase_id' => $purchase->id,
                'payment_id' => $payment->id,
                'gateway' => $isFreeAccessGrant ? 'free' : $gateway,
                'gateway_reference' => $purchase->order_number,
                'amount' => $purchase->grand_total,
                'currency' => $purchase->currency,
                'phone' => $holderPhone,
                'email' => $request->input('holder.email', $request->user()->email),
                'customer_name' => $request->input('holder.name', $request->user()->name),
                'status' => $isFreeAccessGrant ? PaymentTransaction::SUCCESSFUL : PaymentTransaction::PENDING,
                'metadata' => [
                    'checkout_source' => 'api',
                    'quantity' => $quantity,
                    'coupon_code' => $coupon?->code,
                    'discount_amount' => $discount,
                    'affiliate_commission_amount' => $commission,
                    'frontend_url' => $frontendUrl,
                    'free_reason' => $isFreeAccessGrant ? ($isExplicitlyFreeTicket ? 'ticket_marked_free' : 'coupon_discounted_to_zero') : null,
                ],
            ]);

            $purchase->update([
                'payment_id' => $payment->id,
                'payment_transaction_id' => $transaction->id,
                'ticket_code' => $purchase->order_number,
                'transaction_id' => $transaction->internal_reference,
                'gateway_reference' => $transaction->gateway_reference,
            ]);

            $payment->update([
                'payment_transaction_id' => $transaction->id,
                'transaction_id' => $transaction->internal_reference,
            ]);

            if ($isFreeAccessGrant) {
                $ticket->increment('quantity_sold', $quantity);
                $purchase->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
            }

            if ($coupon) {
                AffiliateCouponRedemption::create([
                    'affiliate_coupon_id' => $coupon->id,
                    'ticket_purchase_id' => $purchase->id,
                    'user_id' => $request->user()->id,
                    'boxing_event_id' => $event->id,
                    'discount_amount' => $discount,
                    'commission_amount' => $commission,
                    'currency' => $currency,
                    'metadata' => [
                        'ticket_name' => $ticket->name,
                        'quantity' => $quantity,
                    ],
                ]);

                $coupon->increment('redeemed_count');
            }

            return [
                'purchase' => $purchase->load(['ticket.event', 'payments', 'paymentTransaction']),
                'transaction' => $transaction->load(['ticketPurchase.ticket.event', 'payment']),
            ];
        });

        $purchase = $checkout['purchase'];
        $transaction = $checkout['transaction'];

        if ($transaction->status !== PaymentTransaction::SUCCESSFUL) {
            try {
                $transaction = $paymentManager->gateway($gateway)->initiate($transaction);
                app(EmailTemplateService::class)->sendPaymentPending($transaction);
            } catch (RuntimeException $exception) {
                return response()->json([
                    'message' => 'We could not start checkout right now. Please try again or contact Nara Promotionz support.',
                    'data' => [
                        'ticket' => new TicketPurchaseResource($purchase->refresh()->load(['ticket.event', 'payments'])),
                        'payment' => $this->paymentPayload($transaction->refresh()),
                    ],
                ], 422);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Checkout started.',
            'data' => [
                'transaction_reference' => $transaction->internal_reference,
                'gateway' => $transaction->gateway,
                'status' => $transaction->status,
                'checkout_url' => $transaction->checkout_url,
                'requires_polling' => (bool) data_get($transaction->metadata, 'requires_polling', $transaction->gateway === 'iotec'),
                'ticket' => new TicketPurchaseResource($purchase->refresh()->load(['ticket.event', 'payments'])),
                'payment' => $this->paymentPayload($transaction),
            ],
        ]);
    }

    public function myTickets()
    {
        return TicketPurchaseResource::collection(
            request()->user()
                ->ticketPurchases()
                ->with(['ticket.event'])
                ->latest()
                ->paginate(12)
        );
    }

    public function show(TicketPurchase $ticket)
    {
        abort_unless($ticket->user_id === request()->user()->id || request()->user()->canAccessFilament(), 403);

        return new TicketPurchaseResource($ticket->load(['ticket.event', 'payments']));
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'NARA-' . now()->format('ymd') . '-' . strtoupper(Str::random(6));
        } while (TicketPurchase::where('order_number', $number)->exists());

        return $number;
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

    private function channelFor(?string $accessType): string
    {
        return match ($accessType) {
            'online', 'online_ppv', 'vip_online', 'replay' => 'online',
            'complimentary' => 'complimentary',
            'sponsor_media' => 'sponsor',
            default => 'venue',
        };
    }

    private function resolveCoupon(?string $code, BoxingEvent $event, EventTicket $ticket): ?AffiliateCoupon
    {
        $code = trim((string) $code);

        if ($code === '') {
            return null;
        }

        $coupon = AffiliateCoupon::query()
            ->active()
            ->whereRaw('LOWER(code) = ?', [strtolower($code)])
            ->where(function ($query) use ($event) {
                $query->whereNull('boxing_event_id')->orWhere('boxing_event_id', $event->id);
            })
            ->first();

        abort_if(! $coupon, 422, 'That fight-night code is not available for this ticket.');
        abort_if($coupon->discountFor((float) $ticket->price, $ticket->currency ?: 'UGX') <= 0 && (float) $coupon->commission_value <= 0, 422, 'That fight-night code cannot be applied to this ticket.');

        return $coupon;
    }
}

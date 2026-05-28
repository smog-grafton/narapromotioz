<?php

namespace App\Services\Payments;

use App\Models\PaymentTransaction;
use App\Services\Tickets\TicketUnlockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class IoTecPaymentService implements PaymentGatewayInterface
{
    public function __construct(private readonly TicketUnlockService $ticketUnlockService)
    {
    }

    public function initiate(PaymentTransaction $transaction): PaymentTransaction
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('ioTec is not configured.');
        }

        $phone = $this->normalizePhone((string) ($transaction->phone ?: $transaction->user?->phone));

        if (! $phone) {
            throw new RuntimeException('A mobile money phone number is required.');
        }

        $title = $transaction->subscriptionPlan?->name
            ?: $transaction->event?->name
            ?: $transaction->video?->title
            ?: 'Nara Promotionz access';

        $payload = [
            'category' => 'MobileMoney',
            'currency' => $transaction->currency,
            'walletId' => config('services.iotec.wallet_id'),
            'externalId' => $transaction->internal_reference,
            'payer' => $phone,
            'amount' => (int) round((float) $transaction->amount),
            'payerNote' => 'Nara Promotionz ' . str_replace('_', ' ', (string) ($transaction->purchase_type ?: 'access')),
            'payeeNote' => $title,
        ];

        $response = Http::withToken($this->token())
            ->acceptJson()
            ->post(rtrim((string) config('services.iotec.pay_base_url'), '/') . '/api/collections/collect', $payload);

        $json = $response->json() ?? [];

        if (! $response->successful()) {
            $transaction->update([
                'status' => PaymentTransaction::FAILED,
                'provider_status' => data_get($json, 'status'),
                'initiation_payload' => $json,
                'failure_reason' => data_get($json, 'message', 'Mobile money checkout could not be started.'),
            ]);

            throw new RuntimeException('Mobile money checkout could not be started.');
        }

        $providerReference = data_get($json, 'requestId')
            ?: data_get($json, 'id')
            ?: data_get($json, 'transactionId');

        $transaction->update([
            'status' => PaymentTransaction::PROCESSING,
            'provider_status' => data_get($json, 'status', 'processing'),
            'gateway_reference' => $providerReference,
            'external_reference' => $providerReference,
            'phone' => $phone,
            'initiation_payload' => $json,
            'expires_at' => now()->addMinutes(20),
            'metadata' => array_merge($transaction->metadata ?? [], ['requires_polling' => true]),
        ]);

        return $transaction->refresh();
    }

    public function verify(PaymentTransaction $transaction, array $context = []): PaymentTransaction
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('ioTec is not configured.');
        }

        $reference = $context['request_id'] ?? $transaction->external_reference ?? $transaction->gateway_reference;

        if (! $reference) {
            return $transaction;
        }

        $response = Http::withToken($this->token())
            ->acceptJson()
            ->get(rtrim((string) config('services.iotec.pay_base_url'), '/') . "/api/collections/status/{$reference}");

        $json = $response->json() ?? [];
        // ioTec often returns API-level "success" while the collection itself is still pending.
        // Always prioritize transaction-specific status fields to avoid premature ticket unlock.
        $providerStatus = strtolower((string) (
            data_get($json, 'data.status')
            ?: data_get($json, 'transactionStatus')
            ?: data_get($json, 'paymentStatus')
            ?: data_get($json, 'data.transactionStatus')
            ?: data_get($json, 'status')
            ?: 'pending'
        ));

        $mapped = $this->mapStatus($providerStatus);

        $transaction->update([
            'provider_status' => $providerStatus,
            'verification_payload' => $json,
        ]);

        if ($mapped === PaymentTransaction::SUCCESSFUL) {
            return $this->ticketUnlockService->unlockForSuccessfulTransaction($transaction->refresh(), $json);
        }

        if (in_array($mapped, [PaymentTransaction::FAILED, PaymentTransaction::CANCELLED, PaymentTransaction::EXPIRED], true)) {
            $this->ticketUnlockService->markTransactionFailed($transaction->refresh(), $mapped, $json);
        }

        return $transaction->refresh();
    }

    public function handleCallback(Request $request): ?PaymentTransaction
    {
        $reference = $request->input('externalId')
            ?: $request->input('data.externalId')
            ?: $request->input('reference')
            ?: $request->input('requestId')
            ?: $request->input('data.requestId');

        $transaction = $reference
            ? PaymentTransaction::where('internal_reference', $reference)
                ->orWhere('external_reference', $reference)
                ->orWhere('gateway_reference', $reference)
                ->first()
            : null;

        if (! $transaction) {
            return null;
        }

        $transaction->update(['callback_payload' => $request->all()]);

        return $this->verify($transaction, [
            'request_id' => $request->input('requestId') ?: $request->input('data.requestId') ?: $transaction->external_reference,
        ]);
    }

    private function isConfigured(): bool
    {
        return filled(config('services.iotec.client_id'))
            && filled(config('services.iotec.client_secret'))
            && filled(config('services.iotec.wallet_id'))
            && filled(config('services.iotec.pay_base_url'));
    }

    private function token(): string
    {
        $cacheKey = 'iotec_access_token';

        if ($token = Cache::get($cacheKey)) {
            return (string) $token;
        }

        $response = Http::asForm()
            ->acceptJson()
            ->post(rtrim((string) config('services.iotec.id_base_url'), '/') . '/connect/token', [
                'grant_type' => config('services.iotec.grant_type', 'client_credentials'),
                'client_id' => config('services.iotec.client_id'),
                'client_secret' => config('services.iotec.client_secret'),
            ]);

        if (! $response->successful() || ! $response->json('access_token')) {
            throw new RuntimeException('Could not authenticate with ioTec.');
        }

        $token = (string) $response->json('access_token');
        $expiresIn = max(60, ((int) $response->json('expires_in', 300)) - 30);

        Cache::put($cacheKey, $token, now()->addSeconds($expiresIn));

        return $token;
    }

    private function normalizePhone(string $value): ?string
    {
        $digits = preg_replace('/\D+/', '', $value);

        if (! $digits) {
            return null;
        }

        if ((str_starts_with($digits, '0') && strlen($digits) === 10)
            || (str_starts_with($digits, '256') && strlen($digits) === 12)) {
            return $digits;
        }

        return $digits;
    }

    private function mapStatus(string $status): string
    {
        return match ($status) {
            'success', 'successful', 'completed', 'paid', 'approved' => PaymentTransaction::SUCCESSFUL,
            'failed', 'failure', 'declined', 'rejected', 'rolledback' => PaymentTransaction::FAILED,
            'cancelled', 'canceled' => PaymentTransaction::CANCELLED,
            'expired', 'timeout', 'timed_out' => PaymentTransaction::EXPIRED,
            default => PaymentTransaction::PROCESSING,
        };
    }
}

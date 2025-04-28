<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AirtelMoneyService
{
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl;
    protected $callbackUrl;
    protected $merchantCode;
    protected $currency;
    protected $countryCode;
    protected $environment;

    /**
     * Create a new AirtelMoneyService instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->clientId = config('services.airtel.client_id');
        $this->clientSecret = config('services.airtel.client_secret');
        $this->baseUrl = config('services.airtel.base_url');
        $this->callbackUrl = config('services.airtel.callback_url');
        $this->merchantCode = config('services.airtel.merchant_code');
        $this->currency = config('services.airtel.currency', 'USD');
        $this->countryCode = config('services.airtel.country_code', 'KE');
        $this->environment = config('services.airtel.environment', 'sandbox');
    }

    /**
     * Generate access token for Airtel API.
     *
     * @return string|null
     */
    protected function getAccessToken()
    {
        try {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->post("{$this->baseUrl}/auth/oauth2/token", [
                    'grant_type' => 'client_credentials',
                ]);

            $data = $response->json();

            if ($response->successful() && isset($data['access_token'])) {
                return $data['access_token'];
            }

            Log::error('Airtel Money Auth Error', [
                'response' => $data,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Airtel Money Auth Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Initiate payment request to Airtel Money.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @param  array  $ticketData
     * @param  string  $phoneNumber
     * @param  string|null  $promoCode
     * @return array|null
     */
    public function initiatePayment(User $user, Event $event, array $ticketData, string $phoneNumber, ?string $promoCode = null)
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return null;
        }

        // Generate unique transaction reference
        $transactionRef = 'NARA' . strtoupper(Str::random(8));

        // Calculate total amount
        $totalAmount = collect($ticketData)->sum(function ($ticket) {
            return $ticket['price'] * $ticket['quantity'];
        });

        // Format phone number (remove country code if present)
        $phoneNumber = preg_replace('/^\+?(\d{1,3})/', '', $phoneNumber);

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ])->post("{$this->baseUrl}/merchant/v1/payments/", [
                'reference' => $transactionRef,
                'subscriber' => [
                    'country' => $this->countryCode,
                    'currency' => $this->currency,
                    'msisdn' => $phoneNumber,
                ],
                'transaction' => [
                    'amount' => $totalAmount,
                    'country' => $this->countryCode,
                    'currency' => $this->currency,
                    'id' => $transactionRef,
                ],
                'callback' => $this->callbackUrl,
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['data']['transaction']['id'])) {
                // Create payment record
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'amount' => $totalAmount,
                    'payment_method' => Payment::METHOD_AIRTEL_MONEY,
                    'transaction_id' => $data['data']['transaction']['id'],
                    'reference_id' => $transactionRef,
                    'status' => Payment::STATUS_PENDING,
                    'details' => [
                        'event_id' => $event->id,
                        'ticket_data' => $ticketData,
                        'promo_code' => $promoCode,
                        'phone_number' => $phoneNumber,
                    ],
                ]);

                return [
                    'payment' => $payment,
                    'transaction_id' => $data['data']['transaction']['id'],
                    'status' => 'pending',
                    'message' => 'Payment initiated. Please check your phone to complete the transaction.',
                ];
            }

            Log::error('Airtel Money Payment Initiation Error', [
                'response' => $data,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Airtel Money Payment Initiation Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Check payment status.
     *
     * @param  string  $transactionId
     * @return array|null
     */
    public function checkPaymentStatus($transactionId)
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ])->get("{$this->baseUrl}/standard/v1/payments/{$transactionId}");

            $data = $response->json();

            if ($response->successful() && isset($data['data']['transaction']['status'])) {
                return [
                    'status' => $this->mapAirtelStatus($data['data']['transaction']['status']),
                    'raw_status' => $data['data']['transaction']['status'],
                    'transaction_id' => $data['data']['transaction']['id'],
                ];
            }

            Log::error('Airtel Money Status Check Error', [
                'response' => $data,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Airtel Money Status Check Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Map Airtel status to our internal status.
     *
     * @param  string  $airtelStatus
     * @return string
     */
    protected function mapAirtelStatus($airtelStatus)
    {
        $statusMap = [
            'TS' => Payment::STATUS_COMPLETED,  // Transaction Success
            'TF' => Payment::STATUS_FAILED,     // Transaction Failed
            'TP' => Payment::STATUS_PENDING,    // Transaction Pending
            'TC' => Payment::STATUS_CANCELLED,  // Transaction Cancelled
            'PF' => Payment::STATUS_FAILED,     // Payment Failed
        ];

        return $statusMap[$airtelStatus] ?? Payment::STATUS_PENDING;
    }

    /**
     * Process callback from Airtel Money.
     *
     * @param  array  $data
     * @return bool
     */
    public function processCallback($data)
    {
        if (!isset($data['transaction']['id'])) {
            return false;
        }

        $transactionId = $data['transaction']['id'];
        
        // Get payment record
        $payment = Payment::where('transaction_id', $transactionId)
            ->where('payment_method', Payment::METHOD_AIRTEL_MONEY)
            ->first();
            
        if (!$payment) {
            Log::error('Airtel Money Callback: Payment not found', ['transaction_id' => $transactionId]);
            return false;
        }
        
        // Update payment status based on callback data
        $status = $this->mapAirtelStatus($data['transaction']['status']);
        
        // Update payment status
        $payment->status = $status;
        $payment->provider_payment_id = $data['transaction']['airtel_money_id'] ?? null;
        $payment->save();
        
        // If payment is completed, create tickets
        if ($payment->status === Payment::STATUS_COMPLETED) {
            $this->processCompletedPayment($payment);
        }
        
        return true;
    }

    /**
     * Process a completed payment.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    protected function processCompletedPayment(Payment $payment)
    {
        // Extract details from payment
        $details = $payment->details;
        $eventId = $details['event_id'];
        $ticketData = $details['ticket_data'];
        $promoCode = $details['promo_code'] ?? null;
        
        $event = Event::find($eventId);
        
        if (!$event) {
            Log::error('Airtel Money: Event not found', ['event_id' => $eventId]);
            return;
        }
        
        // Create tickets
        foreach ($ticketData as $ticket) {
            for ($i = 0; $i < $ticket['quantity']; $i++) {
                Ticket::create([
                    'user_id' => $payment->user_id,
                    'event_id' => $eventId,
                    'payment_id' => $payment->id,
                    'ticket_number' => 'TKT' . Str::random(8),
                    'ticket_type' => $ticket['type'],
                    'price' => $ticket['price'],
                    'promo_code' => $promoCode,
                    'checked_in' => false,
                ]);
            }
        }
        
        // Process promo code commission if applicable
        if ($promoCode) {
            $event->processPromoCode($promoCode, $payment->amount);
        }
    }
}
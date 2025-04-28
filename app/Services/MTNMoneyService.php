<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MTNMoneyService
{
    protected $apiKey;
    protected $apiUser;
    protected $apiPassword;
    protected $baseUrl;
    protected $callbackUrl;
    protected $environment;
    protected $currency;
    protected $countryCode;
    protected $subscriptionKey;

    /**
     * Create a new MTNMoneyService instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiKey = config('services.mtn.api_key');
        $this->apiUser = config('services.mtn.api_user');
        $this->apiPassword = config('services.mtn.api_password');
        $this->baseUrl = config('services.mtn.base_url');
        $this->callbackUrl = config('services.mtn.callback_url');
        $this->environment = config('services.mtn.environment', 'sandbox');
        $this->currency = config('services.mtn.currency', 'USD');
        $this->countryCode = config('services.mtn.country_code', 'UG');
        $this->subscriptionKey = config('services.mtn.subscription_key');
    }

    /**
     * Generate access token for MTN API.
     *
     * @return string|null
     */
    protected function getAccessToken()
    {
        try {
            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                'Authorization' => 'Basic ' . base64_encode("{$this->apiUser}:{$this->apiPassword}"),
            ])->post("{$this->baseUrl}/collection/token/");

            $data = $response->json();

            if ($response->successful() && isset($data['access_token'])) {
                return $data['access_token'];
            }

            Log::error('MTN Money Auth Error', [
                'response' => $data,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('MTN Money Auth Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Initiate payment request to MTN Money.
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

        // Generate unique reference ID
        $referenceId = Str::uuid()->toString();
        
        // Generate unique external ID
        $externalId = 'NARA' . strtoupper(Str::random(8));

        // Calculate total amount
        $totalAmount = collect($ticketData)->sum(function ($ticket) {
            return $ticket['price'] * $ticket['quantity'];
        });

        // Format phone number (must include country code without '+')
        $phoneNumber = preg_replace('/^\+?/', '', $phoneNumber);

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
                'X-Reference-Id' => $referenceId,
                'X-Target-Environment' => $this->environment,
                'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/collection/v1_0/requesttopay", [
                'amount' => $totalAmount,
                'currency' => $this->currency,
                'externalId' => $externalId,
                'payer' => [
                    'partyIdType' => 'MSISDN',
                    'partyId' => $phoneNumber,
                ],
                'payerMessage' => "Payment for tickets to {$event->name}",
                'payeeNote' => "Ticket purchase from {$user->name}",
            ]);

            // MTN returns 202 Accepted status for successful requests
            if ($response->status() === 202) {
                // Create payment record
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'amount' => $totalAmount,
                    'payment_method' => Payment::METHOD_MTN_MONEY,
                    'transaction_id' => $referenceId,
                    'reference_id' => $externalId,
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
                    'transaction_id' => $referenceId,
                    'status' => 'pending',
                    'message' => 'Payment initiated. Please check your phone to complete the transaction.',
                ];
            }

            $data = $response->json();
            Log::error('MTN Money Payment Initiation Error', [
                'response' => $data,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('MTN Money Payment Initiation Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Check payment status.
     *
     * @param  string  $referenceId
     * @return array|null
     */
    public function checkPaymentStatus($referenceId)
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
                'X-Target-Environment' => $this->environment,
                'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
            ])->get("{$this->baseUrl}/collection/v1_0/requesttopay/{$referenceId}");

            $data = $response->json();

            if ($response->successful()) {
                return [
                    'status' => $this->mapMTNStatus($data['status'] ?? 'PENDING'),
                    'raw_status' => $data['status'] ?? 'PENDING',
                    'transaction_id' => $referenceId,
                    'reason' => $data['reason'] ?? null,
                ];
            }

            Log::error('MTN Money Status Check Error', [
                'response' => $data,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('MTN Money Status Check Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Map MTN status to our internal status.
     *
     * @param  string  $mtnStatus
     * @return string
     */
    protected function mapMTNStatus($mtnStatus)
    {
        $statusMap = [
            'SUCCESSFUL' => Payment::STATUS_COMPLETED,
            'FAILED' => Payment::STATUS_FAILED,
            'PENDING' => Payment::STATUS_PENDING,
            'REJECTED' => Payment::STATUS_FAILED,
            'TIMEOUT' => Payment::STATUS_FAILED,
            'ONGOING' => Payment::STATUS_PENDING,
        ];

        return $statusMap[$mtnStatus] ?? Payment::STATUS_PENDING;
    }

    /**
     * Process callback from MTN Money.
     *
     * @param  array  $data
     * @return bool
     */
    public function processCallback($data)
    {
        if (!isset($data['referenceId'])) {
            return false;
        }

        $referenceId = $data['referenceId'];
        
        // Get payment record
        $payment = Payment::where('transaction_id', $referenceId)
            ->where('payment_method', Payment::METHOD_MTN_MONEY)
            ->first();
            
        if (!$payment) {
            Log::error('MTN Money Callback: Payment not found', ['reference_id' => $referenceId]);
            return false;
        }
        
        // Check payment status
        $statusData = $this->checkPaymentStatus($referenceId);
        
        if (!$statusData) {
            return false;
        }
        
        // Update payment status
        $payment->status = $statusData['status'];
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
            Log::error('MTN Money: Event not found', ['event_id' => $eventId]);
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
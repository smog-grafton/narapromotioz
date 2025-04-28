<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PesapalService
{
    protected $consumerKey;
    protected $consumerSecret;
    protected $baseUrl;
    protected $callbackUrl;
    protected $ipnUrl;

    /**
     * Create a new PesapalService instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->consumerKey = config('services.pesapal.consumer_key');
        $this->consumerSecret = config('services.pesapal.consumer_secret');
        $this->baseUrl = config('services.pesapal.base_url');
        $this->callbackUrl = config('services.pesapal.callback_url');
        $this->ipnUrl = config('services.pesapal.ipn_url');
    }

    /**
     * Generate authorization token for Pesapal API.
     *
     * @return string|null
     */
    protected function getAuthToken()
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/api/Auth/RequestToken", [
                'consumer_key' => $this->consumerKey,
                'consumer_secret' => $this->consumerSecret,
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['token'])) {
                return $data['token'];
            }

            Log::error('Pesapal Auth Error', [
                'response' => $data,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Pesapal Auth Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Register an IPN URL with Pesapal.
     *
     * @return string|null
     */
    public function registerIpn()
    {
        $token = $this->getAuthToken();

        if (!$token) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ])->post("{$this->baseUrl}/api/URLSetup/RegisterIPN", [
                'url' => $this->ipnUrl,
                'ipn_notification_type' => 'GET',
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['ipn_id'])) {
                return $data['ipn_id'];
            }

            Log::error('Pesapal IPN Registration Error', [
                'response' => $data,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Pesapal IPN Registration Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Submit an order to Pesapal.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @param  array  $ticketData
     * @param  string|null  $promoCode
     * @return array|null
     */
    public function submitOrder(User $user, Event $event, array $ticketData, ?string $promoCode = null)
    {
        $token = $this->getAuthToken();

        if (!$token) {
            return null;
        }

        $ipnId = $this->registerIpn();

        if (!$ipnId) {
            return null;
        }

        // Generate unique tracking ID
        $trackingId = 'NARA' . Str::uuid()->toString();

        // Calculate total amount
        $totalAmount = collect($ticketData)->sum(function ($ticket) {
            return $ticket['price'] * $ticket['quantity'];
        });

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ])->post("{$this->baseUrl}/api/Transactions/SubmitOrderRequest", [
                'id' => $trackingId,
                'currency' => 'USD',
                'amount' => $totalAmount,
                'description' => "Tickets for {$event->name}",
                'callback_url' => $this->callbackUrl,
                'notification_id' => $ipnId,
                'billing_address' => [
                    'email_address' => $user->email,
                    'phone_number' => $user->phone,
                    'country_code' => 'KE',
                    'first_name' => $user->name,
                    'last_name' => '',
                ],
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['order_tracking_id'])) {
                // Create payment record
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'amount' => $totalAmount,
                    'payment_method' => Payment::METHOD_PESAPAL,
                    'transaction_id' => $data['order_tracking_id'],
                    'reference_id' => $trackingId,
                    'status' => Payment::STATUS_PENDING,
                    'details' => [
                        'event_id' => $event->id,
                        'ticket_data' => $ticketData,
                        'promo_code' => $promoCode,
                    ],
                ]);

                return [
                    'payment' => $payment,
                    'redirect_url' => $data['redirect_url'],
                ];
            }

            Log::error('Pesapal Submit Order Error', [
                'response' => $data,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Pesapal Submit Order Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Check payment status.
     *
     * @param  string  $orderTrackingId
     * @return array|null
     */
    public function checkPaymentStatus($orderTrackingId)
    {
        $token = $this->getAuthToken();

        if (!$token) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ])->get("{$this->baseUrl}/api/Transactions/GetTransactionStatus", [
                'orderTrackingId' => $orderTrackingId,
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['payment_status_description'])) {
                return [
                    'status' => $this->mapPesapalStatus($data['payment_status_description']),
                    'payment_method' => $data['payment_method'] ?? null,
                    'payment_account' => $data['payment_account'] ?? null,
                    'transaction_id' => $data['payment_transaction_id'] ?? null,
                    'raw_status' => $data['payment_status_description'],
                ];
            }

            Log::error('Pesapal Check Status Error', [
                'response' => $data,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Pesapal Check Status Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Map Pesapal status to our internal status.
     *
     * @param  string  $pesapalStatus
     * @return string
     */
    protected function mapPesapalStatus($pesapalStatus)
    {
        $statusMap = [
            'COMPLETED' => Payment::STATUS_COMPLETED,
            'FAILED' => Payment::STATUS_FAILED,
            'INVALID' => Payment::STATUS_FAILED,
            'PENDING' => Payment::STATUS_PENDING,
        ];

        return $statusMap[$pesapalStatus] ?? Payment::STATUS_PENDING;
    }

    /**
     * Process IPN callback.
     *
     * @param  array  $data
     * @return bool
     */
    public function processIpnCallback($data)
    {
        if (!isset($data['OrderTrackingId'])) {
            return false;
        }

        $orderTrackingId = $data['OrderTrackingId'];
        
        // Get payment record
        $payment = Payment::where('transaction_id', $orderTrackingId)
            ->where('payment_method', Payment::METHOD_PESAPAL)
            ->first();
            
        if (!$payment) {
            Log::error('Pesapal IPN: Payment not found', ['tracking_id' => $orderTrackingId]);
            return false;
        }
        
        // Check payment status
        $statusData = $this->checkPaymentStatus($orderTrackingId);
        
        if (!$statusData) {
            return false;
        }
        
        // Update payment status
        $payment->status = $statusData['status'];
        $payment->provider_payment_id = $statusData['transaction_id'] ?? null;
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
            Log::error('Pesapal: Event not found', ['event_id' => $eventId]);
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
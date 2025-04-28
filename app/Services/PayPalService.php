<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\Stream;
use App\Models\StreamPurchase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PayPalService
{
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl;
    protected $returnUrl;
    protected $cancelUrl;
    protected $webhookId;
    protected $environment;

    /**
     * Create a new PayPalService instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        $this->baseUrl = config('services.paypal.environment') === 'sandbox' 
            ? 'https://api-m.sandbox.paypal.com' 
            : 'https://api-m.paypal.com';
        $this->returnUrl = config('services.paypal.return_url');
        $this->cancelUrl = config('services.paypal.cancel_url');
        $this->webhookId = config('services.paypal.webhook_id');
        $this->environment = config('services.paypal.environment', 'sandbox');
    }

    /**
     * Get an access token from PayPal.
     *
     * @return string|null
     */
    protected function getAccessToken()
    {
        try {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post("{$this->baseUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials',
                ]);

            $data = $response->json();

            if ($response->successful() && isset($data['access_token'])) {
                return $data['access_token'];
            }

            Log::error('PayPal Auth Error', [
                'response' => $data,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayPal Auth Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Create an order for ticket purchase.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @param  array  $ticketData
     * @param  string|null  $promoCode
     * @return array|null
     */
    public function createTicketOrder(User $user, Event $event, array $ticketData, ?string $promoCode = null)
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return null;
        }

        // Calculate total amount
        $totalAmount = 0;
        $items = [];

        foreach ($ticketData as $ticket) {
            $price = $ticket['price'];
            $quantity = $ticket['quantity'];
            $totalAmount += $price * $quantity;

            $items[] = [
                'name' => "{$event->name} - {$ticket['type']} Ticket",
                'description' => "Ticket for {$event->name} on " . $event->date->format('F j, Y'),
                'quantity' => (string) $quantity,
                'unit_amount' => [
                    'currency_code' => 'USD',
                    'value' => (string) $price,
                ],
                'category' => 'DIGITAL_GOODS',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ])->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => 'NARA' . strtoupper(Str::random(8)),
                        'description' => "Tickets for {$event->name}",
                        'custom_id' => "EVENT_{$event->id}_USER_{$user->id}" . ($promoCode ? "_PROMO_{$promoCode}" : ""),
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => (string) $totalAmount,
                            'breakdown' => [
                                'item_total' => [
                                    'currency_code' => 'USD',
                                    'value' => (string) $totalAmount,
                                ],
                            ],
                        ],
                        'items' => $items,
                    ],
                ],
                'application_context' => [
                    'brand_name' => 'Nara Promotionz',
                    'landing_page' => 'BILLING',
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                    'return_url' => $this->returnUrl . '?type=ticket',
                    'cancel_url' => $this->cancelUrl . '?type=ticket',
                ],
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['id'])) {
                // Create payment record
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'amount' => $totalAmount,
                    'payment_method' => Payment::METHOD_PAYPAL,
                    'payment_type' => Payment::TYPE_TICKET,
                    'transaction_id' => $data['id'],
                    'status' => Payment::STATUS_PENDING,
                    'currency' => 'USD',
                    'details' => [
                        'event_id' => $event->id,
                        'ticket_data' => $ticketData,
                        'promo_code' => $promoCode,
                    ],
                ]);

                // Find approval link
                $approvalLink = null;
                foreach ($data['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $approvalLink = $link['href'];
                        break;
                    }
                }

                return [
                    'payment' => $payment,
                    'order_id' => $data['id'],
                    'approval_url' => $approvalLink,
                ];
            }

            Log::error('PayPal Create Order Error', [
                'response' => $data,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayPal Create Order Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Create an order for stream purchase.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stream  $stream
     * @param  string|null  $promoCode
     * @return array|null
     */
    public function createStreamOrder(User $user, Stream $stream, ?string $promoCode = null)
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return null;
        }

        $price = $stream->price;
        $event = $stream->event;
        $eventName = $event ? $event->name : 'Boxing Event';

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ])->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => 'NARA_STREAM_' . strtoupper(Str::random(8)),
                        'description' => "Access to live stream: {$stream->title}",
                        'custom_id' => "STREAM_{$stream->id}_USER_{$user->id}" . ($promoCode ? "_PROMO_{$promoCode}" : ""),
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => (string) $price,
                            'breakdown' => [
                                'item_total' => [
                                    'currency_code' => 'USD',
                                    'value' => (string) $price,
                                ],
                            ],
                        ],
                        'items' => [
                            [
                                'name' => $stream->title,
                                'description' => "Live stream access for {$eventName}",
                                'quantity' => '1',
                                'unit_amount' => [
                                    'currency_code' => 'USD',
                                    'value' => (string) $price,
                                ],
                                'category' => 'DIGITAL_GOODS',
                            ],
                        ],
                    ],
                ],
                'application_context' => [
                    'brand_name' => 'Nara Promotionz',
                    'landing_page' => 'BILLING',
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                    'return_url' => $this->returnUrl . '?type=stream',
                    'cancel_url' => $this->cancelUrl . '?type=stream',
                ],
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['id'])) {
                // Create payment record
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'amount' => $price,
                    'payment_method' => Payment::METHOD_PAYPAL,
                    'payment_type' => Payment::TYPE_STREAM,
                    'transaction_id' => $data['id'],
                    'status' => Payment::STATUS_PENDING,
                    'currency' => 'USD',
                    'details' => [
                        'stream_id' => $stream->id,
                        'promo_code' => $promoCode,
                    ],
                ]);

                // Find approval link
                $approvalLink = null;
                foreach ($data['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $approvalLink = $link['href'];
                        break;
                    }
                }

                return [
                    'payment' => $payment,
                    'order_id' => $data['id'],
                    'approval_url' => $approvalLink,
                ];
            }

            Log::error('PayPal Create Stream Order Error', [
                'response' => $data,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayPal Create Stream Order Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Capture the authorized payment.
     *
     * @param  string  $orderId
     * @return array|null
     */
    public function capturePayment($orderId)
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ])->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture");

            $data = $response->json();

            if ($response->successful() && isset($data['status']) && $data['status'] === 'COMPLETED') {
                // Get the capture ID from the response
                $captureId = $data['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;

                // Get the payment from our database
                $payment = Payment::where('transaction_id', $orderId)
                    ->where('payment_method', Payment::METHOD_PAYPAL)
                    ->first();

                if (!$payment) {
                    Log::error('PayPal Capture: Payment not found', ['order_id' => $orderId]);
                    return null;
                }

                // Mark the payment as completed
                $payment->markAsCompleted($captureId);

                // Process the payment
                $this->processCompletedPayment($payment);

                return [
                    'success' => true,
                    'payment' => $payment,
                    'capture_id' => $captureId,
                ];
            }

            Log::error('PayPal Capture Error', [
                'response' => $data,
                'status' => $response->status(),
            ]);

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Failed to capture payment',
            ];
        } catch (\Exception $e) {
            Log::error('PayPal Capture Exception', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'An error occurred while processing the payment',
            ];
        }
    }

    /**
     * Process a completed payment.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    protected function processCompletedPayment(Payment $payment)
    {
        if ($payment->payment_type === Payment::TYPE_TICKET) {
            $this->processTicketPurchase($payment);
        } elseif ($payment->payment_type === Payment::TYPE_STREAM) {
            $this->processStreamPurchase($payment);
        }
    }

    /**
     * Process a completed ticket purchase.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    protected function processTicketPurchase(Payment $payment)
    {
        // Extract details from payment
        $details = $payment->details;
        $eventId = $details['event_id'];
        $ticketData = $details['ticket_data'];
        $promoCode = $details['promo_code'] ?? null;
        
        $event = Event::find($eventId);
        
        if (!$event) {
            Log::error('PayPal: Event not found', ['event_id' => $eventId]);
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

    /**
     * Process a completed stream purchase.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    protected function processStreamPurchase(Payment $payment)
    {
        // Extract details from payment
        $details = $payment->details;
        $streamId = $details['stream_id'];
        $promoCode = $details['promo_code'] ?? null;
        
        $stream = Stream::find($streamId);
        
        if (!$stream) {
            Log::error('PayPal: Stream not found', ['stream_id' => $streamId]);
            return;
        }
        
        // Create stream purchase
        $streamPurchase = StreamPurchase::create([
            'user_id' => $payment->user_id,
            'stream_id' => $streamId,
            'payment_id' => $payment->id,
            'promo_code' => $promoCode,
            'amount' => $payment->amount,
            'status' => StreamPurchase::STATUS_ACTIVE,
            'purchase_code' => Str::upper(Str::random(8)),
        ]);

        // Generate a unique purchase code
        $streamPurchase->generatePurchaseCode();
        
        // Process promo code commission if applicable and stream is part of an event
        if ($promoCode && $stream->event_id) {
            $stream->event->processPromoCode($promoCode, $payment->amount);
        }
    }

    /**
     * Process webhook notification.
     *
     * @param  array  $payload
     * @return bool
     */
    public function processWebhook($payload)
    {
        if (!isset($payload['event_type']) || !isset($payload['resource'])) {
            return false;
        }
        
        try {
            $eventType = $payload['event_type'];
            
            switch ($eventType) {
                case 'PAYMENT.CAPTURE.COMPLETED':
                    return $this->handleCaptureCompleted($payload['resource']);
                    
                case 'PAYMENT.CAPTURE.DENIED':
                case 'PAYMENT.CAPTURE.REFUNDED':
                case 'PAYMENT.CAPTURE.REVERSED':
                    return $this->handleCaptureNotCompleted($payload['resource'], $eventType);
                    
                default:
                    Log::info('PayPal Webhook: Unhandled event type', ['event_type' => $eventType]);
                    return true;
            }
        } catch (\Exception $e) {
            Log::error('PayPal Webhook Exception', ['message' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Handle a completed capture.
     *
     * @param  array  $resource
     * @return bool
     */
    protected function handleCaptureCompleted($resource)
    {
        $orderId = $resource['supplementary_data']['related_ids']['order_id'] ?? null;
        $captureId = $resource['id'] ?? null;
        
        if (!$orderId || !$captureId) {
            return false;
        }
        
        $payment = Payment::where('transaction_id', $orderId)
            ->where('payment_method', Payment::METHOD_PAYPAL)
            ->first();
            
        if (!$payment) {
            Log::error('PayPal Webhook: Payment not found', ['order_id' => $orderId]);
            return false;
        }
        
        // Only process if payment is still pending
        if ($payment->isPending()) {
            $payment->markAsCompleted($captureId);
            $this->processCompletedPayment($payment);
        }
        
        return true;
    }
    
    /**
     * Handle a non-completed capture.
     *
     * @param  array  $resource
     * @param  string  $eventType
     * @return bool
     */
    protected function handleCaptureNotCompleted($resource, $eventType)
    {
        $orderId = $resource['supplementary_data']['related_ids']['order_id'] ?? null;
        $captureId = $resource['id'] ?? null;
        
        if (!$orderId) {
            return false;
        }
        
        $payment = Payment::where('transaction_id', $orderId)
            ->where('payment_method', Payment::METHOD_PAYPAL)
            ->first();
            
        if (!$payment) {
            Log::error('PayPal Webhook: Payment not found', ['order_id' => $orderId]);
            return false;
        }
        
        switch ($eventType) {
            case 'PAYMENT.CAPTURE.DENIED':
                $payment->markAsFailed('Payment capture was denied by PayPal');
                break;
                
            case 'PAYMENT.CAPTURE.REFUNDED':
                $refundAmount = $resource['amount']['value'] ?? $payment->amount;
                $payment->refund($refundAmount, 'Refunded via PayPal');
                break;
                
            case 'PAYMENT.CAPTURE.REVERSED':
                $payment->markAsFailed('Payment was reversed by PayPal');
                break;
        }
        
        return true;
    }
    
    /**
     * Refund a payment.
     *
     * @param  \App\Models\Payment  $payment
     * @param  float  $amount
     * @param  string|null  $reason
     * @return array|null
     */
    public function refundPayment(Payment $payment, $amount, ?string $reason = null)
    {
        if ($payment->payment_method !== Payment::METHOD_PAYPAL || !$payment->isCompleted()) {
            return null;
        }
        
        $token = $this->getAccessToken();
        
        if (!$token) {
            return null;
        }
        
        // Get the capture ID from the provider_payment_id
        $captureId = $payment->provider_payment_id;
        
        if (!$captureId) {
            return [
                'success' => false,
                'message' => 'No capture ID found for this payment',
            ];
        }
        
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ])->post("{$this->baseUrl}/v2/payments/captures/{$captureId}/refund", [
                'amount' => [
                    'value' => (string) $amount,
                    'currency_code' => $payment->currency ?: 'USD',
                ],
                'note_to_payer' => $reason ?: 'Refund from Nara Promotionz',
            ]);
            
            $data = $response->json();
            
            if ($response->successful() && isset($data['id'])) {
                // Update payment in our system
                $payment->refund($amount, $reason);
                
                return [
                    'success' => true,
                    'refund_id' => $data['id'],
                    'message' => 'Refund processed successfully',
                ];
            }
            
            Log::error('PayPal Refund Error', [
                'response' => $data,
                'status' => $response->status(),
            ]);
            
            return [
                'success' => false,
                'message' => $data['message'] ?? 'Failed to process refund',
            ];
        } catch (\Exception $e) {
            Log::error('PayPal Refund Exception', ['message' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'An error occurred while processing the refund',
            ];
        }
    }
}
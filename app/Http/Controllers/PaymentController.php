<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Payment;
use App\Models\Ticket;
use App\Services\PesapalService;
use App\Services\AirtelMoneyService;
use App\Services\MTNMoneyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show payment method selection page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $eventId
     * @return \Illuminate\View\View
     */
    public function showPaymentMethods(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // Check if event is still open for ticket sales
        if (!$event->isTicketSalesOpen()) {
            return redirect()->route('events.show', $event->id)
                ->with('error', 'Ticket sales for this event are closed.');
        }
        
        // Get ticket data from session
        $ticketData = session('ticket_data');
        
        if (!$ticketData) {
            return redirect()->route('events.tickets', $event->id)
                ->with('error', 'Please select tickets before proceeding to payment.');
        }
        
        // Calculate total
        $totalAmount = 0;
        foreach ($ticketData as $ticket) {
            $totalAmount += $ticket['price'] * $ticket['quantity'];
        }
        
        // Get promo code if available
        $promoCode = session('promo_code');
        
        return view('payment.methods', compact('event', 'ticketData', 'totalAmount', 'promoCode'));
    }

    /**
     * Process Stripe Payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $eventId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function processStripe(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // Check if event is still open for ticket sales
        if (!$event->isTicketSalesOpen()) {
            return redirect()->route('events.show', $event->id)
                ->with('error', 'Ticket sales for this event are closed.');
        }
        
        // Get ticket data from session
        $ticketData = session('ticket_data');
        
        if (!$ticketData) {
            return redirect()->route('events.tickets', $event->id)
                ->with('error', 'Please select tickets before proceeding to payment.');
        }
        
        // Calculate total
        $totalAmount = 0;
        foreach ($ticketData as $ticket) {
            $totalAmount += $ticket['price'] * $ticket['quantity'];
        }
        
        // Get promo code if available
        $promoCode = session('promo_code');
        
        // If this is the initial request, show the payment form
        if ($request->isMethod('get')) {
            return view('payment.stripe', compact('event', 'ticketData', 'totalAmount', 'promoCode'));
        }
        
        // Otherwise, process the payment
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            
            $paymentIntent = PaymentIntent::create([
                'amount' => round($totalAmount * 100), // Convert to cents
                'currency' => 'usd',
                'description' => "Tickets for {$event->name}",
                'metadata' => [
                    'event_id' => $event->id,
                    'user_id' => Auth::id(),
                    'promo_code' => $promoCode,
                ],
            ]);
            
            // Create payment record
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'amount' => $totalAmount,
                'payment_method' => Payment::METHOD_STRIPE,
                'transaction_id' => $paymentIntent->id,
                'status' => Payment::STATUS_PENDING,
                'details' => [
                    'event_id' => $event->id,
                    'ticket_data' => $ticketData,
                    'promo_code' => $promoCode,
                ],
            ]);
            
            // Return client secret for frontend processing
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'payment_id' => $payment->id,
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Error', ['message' => $e->getMessage()]);
            
            return response()->json([
                'error' => 'Payment failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Process Pesapal Payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $eventId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function processPesapal(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // Check if event is still open for ticket sales
        if (!$event->isTicketSalesOpen()) {
            return redirect()->route('events.show', $event->id)
                ->with('error', 'Ticket sales for this event are closed.');
        }
        
        // Get ticket data from session
        $ticketData = session('ticket_data');
        
        if (!$ticketData) {
            return redirect()->route('events.tickets', $event->id)
                ->with('error', 'Please select tickets before proceeding to payment.');
        }
        
        // Get promo code if available
        $promoCode = session('promo_code');
        
        // Get current user
        $user = Auth::user();
        
        // Process payment with Pesapal
        $pesapalService = new PesapalService();
        $result = $pesapalService->submitOrder($user, $event, $ticketData, $promoCode);
        
        if (!$result) {
            return redirect()->route('payment.methods', $event->id)
                ->with('error', 'Failed to initiate payment with Pesapal. Please try again or choose a different payment method.');
        }
        
        // Store payment ID in session for later reference
        session(['payment_id' => $result['payment']->id]);
        
        // Redirect to Pesapal payment gateway
        return redirect($result['redirect_url']);
    }

    /**
     * Handle Pesapal callback.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pesapalCallback(Request $request)
    {
        $orderTrackingId = $request->get('OrderTrackingId');
        
        if (!$orderTrackingId) {
            return redirect()->route('home')
                ->with('error', 'Invalid payment reference received.');
        }
        
        // Get payment by transaction ID
        $payment = Payment::where('transaction_id', $orderTrackingId)
            ->where('payment_method', Payment::METHOD_PESAPAL)
            ->first();
            
        if (!$payment) {
            return redirect()->route('home')
                ->with('error', 'Payment record not found.');
        }
        
        // Check payment status
        $pesapalService = new PesapalService();
        $statusData = $pesapalService->checkPaymentStatus($orderTrackingId);
        
        if (!$statusData) {
            return redirect()->route('payment.status', $payment->id)
                ->with('warning', 'Payment status could not be verified at this time. Please check your tickets later.');
        }
        
        // Update payment status
        $payment->status = $statusData['status'];
        $payment->provider_payment_id = $statusData['transaction_id'] ?? null;
        $payment->save();
        
        // If payment is completed, create tickets
        if ($payment->status === Payment::STATUS_COMPLETED) {
            $this->createTickets($payment);
            
            return redirect()->route('payment.status', $payment->id)
                ->with('success', 'Payment successful! Your tickets are ready.');
        }
        
        // Otherwise, show appropriate message
        $statusMessage = $this->getStatusMessage($payment->status);
        
        return redirect()->route('payment.status', $payment->id)
            ->with($statusMessage['type'], $statusMessage['message']);
    }

    /**
     * Process Airtel Money Payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $eventId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function processAirtelMoney(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // Check if event is still open for ticket sales
        if (!$event->isTicketSalesOpen()) {
            return redirect()->route('events.show', $event->id)
                ->with('error', 'Ticket sales for this event are closed.');
        }
        
        // Get ticket data from session
        $ticketData = session('ticket_data');
        
        if (!$ticketData) {
            return redirect()->route('events.tickets', $event->id)
                ->with('error', 'Please select tickets before proceeding to payment.');
        }
        
        // Get promo code if available
        $promoCode = session('promo_code');
        
        // If this is the initial request, show the phone number form
        if ($request->isMethod('get')) {
            return view('payment.airtel-money', compact('event', 'ticketData', 'promoCode'));
        }
        
        // Validate phone number
        $request->validate([
            'phone_number' => 'required|string|min:9|max:15',
        ]);
        
        // Get current user
        $user = Auth::user();
        
        // Process payment with Airtel Money
        $airtelMoneyService = new AirtelMoneyService();
        $result = $airtelMoneyService->initiatePayment($user, $event, $ticketData, $request->phone_number, $promoCode);
        
        if (!$result) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Failed to initiate payment with Airtel Money. Please try again or choose a different payment method.'
                ], 500);
            }
            
            return redirect()->route('payment.methods', $event->id)
                ->with('error', 'Failed to initiate payment with Airtel Money. Please try again or choose a different payment method.');
        }
        
        // Store payment ID in session for later reference
        session(['payment_id' => $result['payment']->id]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'payment_id' => $result['payment']->id,
            ]);
        }
        
        return redirect()->route('payment.status', $result['payment']->id)
            ->with('info', $result['message']);
    }

    /**
     * Process MTN Money Payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $eventId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function processMTNMoney(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // Check if event is still open for ticket sales
        if (!$event->isTicketSalesOpen()) {
            return redirect()->route('events.show', $event->id)
                ->with('error', 'Ticket sales for this event are closed.');
        }
        
        // Get ticket data from session
        $ticketData = session('ticket_data');
        
        if (!$ticketData) {
            return redirect()->route('events.tickets', $event->id)
                ->with('error', 'Please select tickets before proceeding to payment.');
        }
        
        // Get promo code if available
        $promoCode = session('promo_code');
        
        // If this is the initial request, show the phone number form
        if ($request->isMethod('get')) {
            return view('payment.mtn-money', compact('event', 'ticketData', 'promoCode'));
        }
        
        // Validate phone number
        $request->validate([
            'phone_number' => 'required|string|min:9|max:15',
        ]);
        
        // Get current user
        $user = Auth::user();
        
        // Process payment with MTN Money
        $mtnMoneyService = new MTNMoneyService();
        $result = $mtnMoneyService->initiatePayment($user, $event, $ticketData, $request->phone_number, $promoCode);
        
        if (!$result) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Failed to initiate payment with MTN Money. Please try again or choose a different payment method.'
                ], 500);
            }
            
            return redirect()->route('payment.methods', $event->id)
                ->with('error', 'Failed to initiate payment with MTN Money. Please try again or choose a different payment method.');
        }
        
        // Store payment ID in session for later reference
        session(['payment_id' => $result['payment']->id]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'payment_id' => $result['payment']->id,
            ]);
        }
        
        return redirect()->route('payment.status', $result['payment']->id)
            ->with('info', $result['message']);
    }

    /**
     * Show payment status page.
     *
     * @param  int  $paymentId
     * @return \Illuminate\View\View
     */
    public function showPaymentStatus($paymentId)
    {
        $payment = Payment::with(['user'])->findOrFail($paymentId);
        
        // Check if current user owns this payment
        if ($payment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Get event from payment details
        $event = Event::find($payment->details['event_id'] ?? 0);
        
        // Check payment status
        $updatedStatus = false;
        
        if ($payment->status === Payment::STATUS_PENDING) {
            switch ($payment->payment_method) {
                case Payment::METHOD_PESAPAL:
                    $pesapalService = new PesapalService();
                    $statusData = $pesapalService->checkPaymentStatus($payment->transaction_id);
                    break;
                    
                case Payment::METHOD_AIRTEL_MONEY:
                    $airtelMoneyService = new AirtelMoneyService();
                    $statusData = $airtelMoneyService->checkPaymentStatus($payment->transaction_id);
                    break;
                    
                case Payment::METHOD_MTN_MONEY:
                    $mtnMoneyService = new MTNMoneyService();
                    $statusData = $mtnMoneyService->checkPaymentStatus($payment->transaction_id);
                    break;
                    
                default:
                    $statusData = null;
                    break;
            }
            
            if ($statusData && isset($statusData['status']) && $statusData['status'] !== $payment->status) {
                $payment->status = $statusData['status'];
                $payment->save();
                $updatedStatus = true;
                
                // If payment is completed, create tickets
                if ($payment->status === Payment::STATUS_COMPLETED) {
                    $this->createTickets($payment);
                }
            }
        }
        
        // Get tickets if payment is completed
        $tickets = [];
        if ($payment->status === Payment::STATUS_COMPLETED) {
            $tickets = Ticket::where('payment_id', $payment->id)->get();
        }
        
        // Get status message
        $statusMessage = $this->getStatusMessage($payment->status);
        
        return view('payment.status', compact('payment', 'event', 'tickets', 'statusMessage', 'updatedStatus'));
    }

    /**
     * Handle webhook from payment provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $provider
     * @return \Illuminate\Http\Response
     */
    public function handleWebhook(Request $request, $provider)
    {
        switch ($provider) {
            case 'stripe':
                $payload = $request->getContent();
                $sig_header = $request->header('Stripe-Signature');
                $endpoint_secret = config('services.stripe.webhook_secret');

                try {
                    $event = \Stripe\Webhook::constructEvent(
                        $payload, $sig_header, $endpoint_secret
                    );
                } catch (\UnexpectedValueException $e) {
                    return response('Invalid payload', 400);
                } catch (\Stripe\Exception\SignatureVerificationException $e) {
                    return response('Invalid signature', 400);
                }

                // Handle the event
                switch ($event->type) {
                    case 'payment_intent.succeeded':
                        $paymentIntent = $event->data->object;
                        $this->handleStripePaymentSuccess($paymentIntent);
                        break;
                    case 'payment_intent.payment_failed':
                        $paymentIntent = $event->data->object;
                        $this->handleStripePaymentFailure($paymentIntent);
                        break;
                }
                
                return response('Webhook handled', 200);
                
            case 'pesapal':
                $response = (new PesapalService())->processIpnCallback($request->all());
                return $response ? response('OK', 200) : response('Failed to process callback', 400);
                
            case 'airtel':
                $response = (new AirtelMoneyService())->processCallback($request->all());
                return $response ? response('OK', 200) : response('Failed to process callback', 400);
                
            case 'mtn':
                $response = (new MTNMoneyService())->processCallback($request->all());
                return $response ? response('OK', 200) : response('Failed to process callback', 400);
                
            default:
                return response('Unknown payment provider', 400);
        }
    }
    
    /**
     * Handle successful Stripe payment.
     *
     * @param  \Stripe\PaymentIntent  $paymentIntent
     * @return void
     */
    protected function handleStripePaymentSuccess($paymentIntent)
    {
        $payment = Payment::where('transaction_id', $paymentIntent->id)
            ->where('payment_method', Payment::METHOD_STRIPE)
            ->first();
            
        if (!$payment) {
            Log::error('Stripe webhook: Payment not found', ['payment_intent_id' => $paymentIntent->id]);
            return;
        }
        
        // Update payment status
        $payment->status = Payment::STATUS_COMPLETED;
        $payment->provider_payment_id = $paymentIntent->charges->data[0]->id ?? null;
        $payment->save();
        
        // Create tickets
        $this->createTickets($payment);
    }
    
    /**
     * Handle failed Stripe payment.
     *
     * @param  \Stripe\PaymentIntent  $paymentIntent
     * @return void
     */
    protected function handleStripePaymentFailure($paymentIntent)
    {
        $payment = Payment::where('transaction_id', $paymentIntent->id)
            ->where('payment_method', Payment::METHOD_STRIPE)
            ->first();
            
        if (!$payment) {
            Log::error('Stripe webhook: Payment not found', ['payment_intent_id' => $paymentIntent->id]);
            return;
        }
        
        // Update payment status
        $payment->status = Payment::STATUS_FAILED;
        $payment->save();
    }
    
    /**
     * Create tickets for a completed payment.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    protected function createTickets(Payment $payment)
    {
        // Extract details from payment
        $details = $payment->details;
        $eventId = $details['event_id'];
        $ticketData = $details['ticket_data'];
        $promoCode = $details['promo_code'] ?? null;
        
        $event = Event::find($eventId);
        
        if (!$event) {
            Log::error('CreateTickets: Event not found', ['event_id' => $eventId]);
            return;
        }
        
        // Create tickets
        foreach ($ticketData as $ticket) {
            for ($i = 0; $i < $ticket['quantity']; $i++) {
                Ticket::create([
                    'user_id' => $payment->user_id,
                    'event_id' => $eventId,
                    'payment_id' => $payment->id,
                    'ticket_number' => 'TKT' . uniqid(),
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
     * Get status message based on payment status.
     *
     * @param  string  $status
     * @return array
     */
    protected function getStatusMessage($status)
    {
        switch ($status) {
            case Payment::STATUS_COMPLETED:
                return [
                    'type' => 'success',
                    'message' => 'Payment successful! Your tickets are ready.',
                ];
                
            case Payment::STATUS_PENDING:
                return [
                    'type' => 'info',
                    'message' => 'Your payment is being processed. Please wait for confirmation.',
                ];
                
            case Payment::STATUS_FAILED:
                return [
                    'type' => 'error',
                    'message' => 'Payment failed. Please try again or choose a different payment method.',
                ];
                
            case Payment::STATUS_CANCELLED:
                return [
                    'type' => 'warning',
                    'message' => 'Payment was cancelled. Please try again if you still want to purchase tickets.',
                ];
                
            default:
                return [
                    'type' => 'info',
                    'message' => 'Payment status unknown. Please contact support if you have any questions.',
                ];
        }
    }
}
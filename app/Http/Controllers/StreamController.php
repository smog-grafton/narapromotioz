<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use App\Models\StreamPurchase;
use App\Models\StreamChatMessage;
use App\Models\StreamViewer;
use App\Models\Event;
use App\Models\User;
use App\Models\Payment;
use App\Services\PayPalService;
use App\Services\PesapalService;
use App\Services\AirtelMoneyService;
use App\Services\MTNMoneyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class StreamController extends Controller
{
    /**
     * Display a listing of available streams.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $liveStreams = Stream::liveNow()
            ->with('event')
            ->get();
            
        $upcomingStreams = Stream::upcoming()
            ->with('event')
            ->get();
            
        $pastStreams = Stream::past()
            ->with('event')
            ->take(10)
            ->get();
            
        return view('streams.index', compact('liveStreams', 'upcomingStreams', 'pastStreams'));
    }

    /**
     * Display the specified stream.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Request $request, Stream $stream)
    {
        $user = Auth::user();
        
        // Check if stream requires payment and if user has access
        if (!$stream->isFree() && (!$user || !$stream->canUserAccess($user))) {
            return redirect()->route('streams.purchase', $stream->id)
                ->with('info', 'This stream requires purchase to view. Please purchase it to continue.');
        }
        
        // Record viewer for analytics
        if ($user) {
            $this->recordViewer($stream, $user);
        }
        
        // Get recent chat messages
        $chatMessages = $stream->chatMessages()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->reverse();
            
        return view('streams.show', compact('stream', 'chatMessages'));
    }

    /**
     * Record a viewer for the stream.
     *
     * @param  \App\Models\Stream  $stream
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function recordViewer(Stream $stream, User $user)
    {
        // Check if this viewer is already recorded recently
        $cacheKey = "stream_viewer_{$stream->id}_{$user->id}";
        
        if (!Cache::has($cacheKey)) {
            // Look for existing viewer record
            $viewer = StreamViewer::where('stream_id', $stream->id)
                ->where('user_id', $user->id)
                ->first();
                
            if ($viewer) {
                // Update last active time
                $viewer->last_active_at = now();
                $viewer->view_count += 1;
                $viewer->save();
            } else {
                // Create new viewer record
                StreamViewer::create([
                    'stream_id' => $stream->id,
                    'user_id' => $user->id,
                    'first_joined_at' => now(),
                    'last_active_at' => now(),
                    'view_count' => 1,
                ]);
                
                // Increment stream viewer count
                $stream->incrementViewers();
            }
            
            // Cache for 5 minutes to prevent frequent updates
            Cache::put($cacheKey, true, now()->addMinutes(5));
        }
    }

    /**
     * Post a chat message to the stream.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\JsonResponse
     */
    public function postChatMessage(Request $request, Stream $stream)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Validate the request
        $request->validate([
            'message' => 'required|string|max:500',
        ]);
        
        // Check if user has access to this stream
        if (!$stream->canUserAccess($user)) {
            return response()->json(['error' => 'You do not have access to this stream'], 403);
        }
        
        // Create chat message
        $chatMessage = StreamChatMessage::create([
            'stream_id' => $stream->id,
            'user_id' => $user->id,
            'message' => $request->message,
        ]);
        
        // Load user relationship
        $chatMessage->load('user');
        
        // In a real application, we would broadcast this message via websockets
        // For now, we'll just return the created message
        
        return response()->json([
            'success' => true,
            'message' => $chatMessage,
        ]);
    }

    /**
     * Get recent chat messages for a stream.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChatMessages(Request $request, Stream $stream)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Check if user has access to this stream
        if (!$stream->canUserAccess($user)) {
            return response()->json(['error' => 'You do not have access to this stream'], 403);
        }
        
        // Get last message ID from request
        $lastId = $request->input('last_id', 0);
        
        // Get new messages
        $messages = $stream->chatMessages()
            ->with('user')
            ->where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->get();
            
        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Show the purchase page for a stream.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showPurchase(Stream $stream)
    {
        // Redirect if stream is free
        if ($stream->isFree()) {
            return redirect()->route('streams.show', $stream->id);
        }
        
        $user = Auth::user();
        
        // If user already has access, redirect to stream
        if ($user && $stream->canUserAccess($user)) {
            return redirect()->route('streams.show', $stream->id);
        }
        
        // If user is not logged in, redirect to login
        if (!$user) {
            return redirect()->route('login')
                ->with('info', 'Please login or register to purchase access to this stream');
        }
        
        return view('streams.purchase', compact('stream'));
    }

    /**
     * Process stream purchase with Stripe.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function purchaseWithStripe(Request $request, Stream $stream)
    {
        // Check if user is authenticated
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Check if stream is purchasable
        if ($stream->isFree() || $stream->canUserAccess($user)) {
            return response()->json([
                'error' => 'This stream is either free or you already have access to it',
            ], 400);
        }
        
        // Get promo code if available
        $promoCode = $request->input('promo_code');
        
        // If this is a GET request, show the payment form
        if ($request->isMethod('get')) {
            return view('streams.purchase-stripe', compact('stream', 'promoCode'));
        }
        
        // Otherwise, process the payment
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            
            $paymentIntent = PaymentIntent::create([
                'amount' => round($stream->price * 100), // Convert to cents
                'currency' => 'usd',
                'description' => "Access to stream: {$stream->title}",
                'metadata' => [
                    'stream_id' => $stream->id,
                    'user_id' => $user->id,
                    'promo_code' => $promoCode,
                ],
            ]);
            
            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'amount' => $stream->price,
                'payment_method' => Payment::METHOD_STRIPE,
                'payment_type' => Payment::TYPE_STREAM,
                'transaction_id' => $paymentIntent->id,
                'status' => Payment::STATUS_PENDING,
                'currency' => 'USD',
                'details' => [
                    'stream_id' => $stream->id,
                    'promo_code' => $promoCode,
                ],
            ]);
            
            // Return client secret for frontend processing
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'payment_id' => $payment->id,
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Stream Purchase Error', ['message' => $e->getMessage()]);
            
            return response()->json([
                'error' => 'Payment failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Process stream purchase with PayPal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\RedirectResponse
     */
    public function purchaseWithPayPal(Request $request, Stream $stream)
    {
        // Check if user is authenticated
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Check if stream is purchasable
        if ($stream->isFree() || $stream->canUserAccess($user)) {
            return redirect()->route('streams.show', $stream->id);
        }
        
        // Get promo code if available
        $promoCode = $request->input('promo_code');
        
        // Process payment with PayPal
        $paypalService = new PayPalService();
        $result = $paypalService->createStreamOrder($user, $stream, $promoCode);
        
        if (!$result) {
            return redirect()->route('streams.purchase', $stream->id)
                ->with('error', 'Failed to initiate payment with PayPal. Please try again or choose a different payment method.');
        }
        
        // Store payment ID in session for later reference
        session(['payment_id' => $result['payment']->id]);
        
        // Redirect to PayPal approval URL
        return redirect($result['approval_url']);
    }

    /**
     * Process stream purchase with African payment methods.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stream  $stream
     * @param  string  $method
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function purchaseWithAfricanMethod(Request $request, Stream $stream, $method)
    {
        // Check if user is authenticated
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Check if stream is purchasable
        if ($stream->isFree() || $stream->canUserAccess($user)) {
            return redirect()->route('streams.show', $stream->id);
        }
        
        // Get promo code if available
        $promoCode = $request->input('promo_code');
        
        // If this is the initial request, show the payment form
        if ($request->isMethod('get')) {
            return view('streams.purchase-african', compact('stream', 'promoCode', 'method'));
        }
        
        // Validate phone number
        $request->validate([
            'phone_number' => 'required|string|min:9|max:15',
        ]);
        
        // Process payment with the selected method
        $result = null;
        
        switch ($method) {
            case 'pesapal':
                $pesapalService = new PesapalService();
                $result = $pesapalService->submitOrder($user, $stream, [
                    [
                        'type' => 'Stream Access',
                        'price' => $stream->price,
                        'quantity' => 1,
                    ]
                ], $promoCode);
                
                if ($result) {
                    // Store payment ID in session for later reference
                    session(['payment_id' => $result['payment']->id]);
                    
                    // Redirect to Pesapal payment gateway
                    return redirect($result['redirect_url']);
                }
                break;
                
            case 'airtel':
                $airtelMoneyService = new AirtelMoneyService();
                $result = $airtelMoneyService->initiatePayment($user, $stream, [
                    [
                        'type' => 'Stream Access',
                        'price' => $stream->price,
                        'quantity' => 1,
                    ]
                ], $request->phone_number, $promoCode);
                break;
                
            case 'mtn':
                $mtnMoneyService = new MTNMoneyService();
                $result = $mtnMoneyService->initiatePayment($user, $stream, [
                    [
                        'type' => 'Stream Access',
                        'price' => $stream->price,
                        'quantity' => 1,
                    ]
                ], $request->phone_number, $promoCode);
                break;
                
            default:
                return redirect()->route('streams.purchase', $stream->id)
                    ->with('error', 'Invalid payment method selected.');
        }
        
        if (!$result) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => "Failed to initiate payment with the selected method. Please try again."
                ], 500);
            }
            
            return redirect()->route('streams.purchase', $stream->id)
                ->with('error', 'Failed to initiate payment. Please try again or choose a different payment method.');
        }
        
        // Store payment ID in session for later reference
        session(['payment_id' => $result['payment']->id]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $result['message'] ?? 'Payment initiated successfully.',
                'payment_id' => $result['payment']->id,
            ]);
        }
        
        return redirect()->route('payment.status', $result['payment']->id)
            ->with('info', $result['message'] ?? 'Payment initiated successfully. Please complete the payment on your mobile device.');
    }

    /**
     * Handle the payment callback from any provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handlePaymentCallback(Request $request)
    {
        $paymentId = session('payment_id');
        $paymentType = $request->input('type', 'unknown');
        
        if (!$paymentId) {
            return redirect()->route('streams.index')
                ->with('error', 'Payment reference not found. Please contact support if you completed a payment.');
        }
        
        $payment = Payment::findOrFail($paymentId);
        
        // Process based on payment method
        $success = false;
        
        switch ($payment->payment_method) {
            case Payment::METHOD_PAYPAL:
                $orderId = $request->input('token');
                $paypalService = new PayPalService();
                $result = $paypalService->capturePayment($orderId);
                $success = $result && isset($result['success']) && $result['success'];
                break;
                
            case Payment::METHOD_STRIPE:
                // For Stripe, payment is handled via webhook
                // Just check the current status
                $success = $payment->isCompleted();
                break;
                
            case Payment::METHOD_PESAPAL:
            case Payment::METHOD_AIRTEL_MONEY:
            case Payment::METHOD_MTN_MONEY:
                // These are handled by their respective controllers
                // Just check the current status
                $success = $payment->isCompleted();
                break;
        }
        
        // Redirect to appropriate page based on outcome
        if ($success) {
            if ($payment->payment_type === Payment::TYPE_STREAM) {
                $streamId = $payment->details['stream_id'] ?? null;
                
                if ($streamId) {
                    return redirect()->route('streams.show', $streamId)
                        ->with('success', 'Payment successful! You now have access to the stream.');
                }
            }
            
            return redirect()->route('streams.index')
                ->with('success', 'Payment successful!');
        }
        
        return redirect()->route('payment.status', $payment->id)
            ->with('info', 'Your payment is being processed. Please wait for confirmation.');
    }

    /**
     * List user's stream purchases.
     *
     * @return \Illuminate\View\View
     */
    public function myPurchases()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $purchases = StreamPurchase::where('user_id', $user->id)
            ->with('stream')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('streams.my-purchases', compact('purchases'));
    }

    /**
     * Admin: List all streams.
     *
     * @return \Illuminate\View\View
     */
    public function adminIndex()
    {
        $this->authorizeAdmin();
        
        $streams = Stream::with('event')
            ->orderBy('scheduled_start', 'desc')
            ->paginate(15);
            
        return view('admin.streams.index', compact('streams'));
    }

    /**
     * Admin: Show stream form.
     *
     * @param  \App\Models\Stream|null  $stream
     * @return \Illuminate\View\View
     */
    public function adminCreate(Stream $stream = null)
    {
        $this->authorizeAdmin();
        
        $events = Event::orderBy('date', 'desc')->get();
        $editing = isset($stream);
        
        return view('admin.streams.form', compact('stream', 'events', 'editing'));
    }

    /**
     * Admin: Store a new stream.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminStore(Request $request)
    {
        $this->authorizeAdmin();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_id' => 'nullable|exists:events,id',
            'scheduled_start' => 'required|date',
            'scheduled_end' => 'required|date|after:scheduled_start',
            'access_level' => 'required|in:free,paid,subscription',
            'price' => 'required_if:access_level,paid|nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ]);
        
        $stream = new Stream();
        $stream->fill($request->except('thumbnail'));
        
        // Generate stream key
        $stream->stream_key = Str::random(32);
        
        // Set status to scheduled
        $stream->status = Stream::STATUS_SCHEDULED;
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('stream-thumbnails', 'public');
            $stream->thumbnail_url = Storage::url($path);
        }
        
        $stream->save();
        
        // Generate the playback URL
        $stream->playback_url = route('streams.playback', ['key' => $stream->stream_key]);
        $stream->save();
        
        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream created successfully');
    }

    /**
     * Admin: Update a stream.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminUpdate(Request $request, Stream $stream)
    {
        $this->authorizeAdmin();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_id' => 'nullable|exists:events,id',
            'scheduled_start' => 'required|date',
            'scheduled_end' => 'required|date|after:scheduled_start',
            'access_level' => 'required|in:free,paid,subscription',
            'price' => 'required_if:access_level,paid|nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
            'status' => 'required|in:scheduled,live,ended,cancelled',
        ]);
        
        $stream->fill($request->except('thumbnail'));
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('stream-thumbnails', 'public');
            $stream->thumbnail_url = Storage::url($path);
        }
        
        // If stream was made live, record actual start time
        if ($request->status === Stream::STATUS_LIVE && $stream->status !== Stream::STATUS_LIVE) {
            $stream->actual_start = now();
        }
        
        // If stream was ended, record actual end time
        if ($request->status === Stream::STATUS_ENDED && $stream->status !== Stream::STATUS_ENDED) {
            $stream->actual_end = now();
        }
        
        $stream->save();
        
        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream updated successfully');
    }

    /**
     * Admin: Start a stream.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminStartStream(Stream $stream)
    {
        $this->authorizeAdmin();
        
        if ($stream->isLive()) {
            return redirect()->route('admin.streams.index')
                ->with('warning', 'Stream is already live');
        }
        
        $stream->startStream();
        
        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream started successfully');
    }

    /**
     * Admin: End a stream.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminEndStream(Stream $stream)
    {
        $this->authorizeAdmin();
        
        if (!$stream->isLive()) {
            return redirect()->route('admin.streams.index')
                ->with('warning', 'Stream is not currently live');
        }
        
        $stream->endStream();
        
        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream ended successfully');
    }

    /**
     * Admin: Delete a stream.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminDestroy(Stream $stream)
    {
        $this->authorizeAdmin();
        
        // Check if stream has associated purchases
        $hasPurchases = $stream->purchases()->exists();
        
        if ($hasPurchases) {
            return redirect()->route('admin.streams.index')
                ->with('error', 'Cannot delete stream with associated purchases');
        }
        
        $stream->delete();
        
        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream deleted successfully');
    }

    /**
     * Check if current user is admin.
     *
     * @return void
     */
    protected function authorizeAdmin()
    {
        $user = Auth::user();
        
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
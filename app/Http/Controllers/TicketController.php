<?php

namespace App\Http\Controllers;

use App\Models\BoxingEvent;
use App\Models\EventTicket;
use App\Models\TicketPurchase;
use App\Services\TicketGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    protected $ticketGenerationService;

    public function __construct(TicketGenerationService $ticketGenerationService)
    {
        $this->ticketGenerationService = $ticketGenerationService;
    }

    /**
     * Show tickets for an event
     */
    public function index(BoxingEvent $event)
    {
        $tickets = $event->tickets()
            ->active()
            ->onSale()
            ->orderBy('price')
            ->get();

        return view('tickets.index', compact('event', 'tickets'));
    }

    /**
     * Show ticket purchase form
     */
    public function purchase(EventTicket $ticket)
    {
        if (!$ticket->is_available) {
            return redirect()->back()->with('error', 'This ticket is no longer available.');
        }

        return view('tickets.purchase', compact('ticket'));
    }

    /**
     * Process ticket purchase
     */
    public function processPurchase(Request $request, EventTicket $ticket)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . min($ticket->max_per_purchase, $ticket->remaining_quantity),
            'ticket_holder_name' => 'required|string|max:255',
            'ticket_holder_email' => 'required|email|max:255',
            'ticket_holder_phone' => 'nullable|string|max:20',
        ]);

        $quantity = $request->quantity;
        $unitPrice = $ticket->price;
        $totalPrice = $unitPrice * $quantity;
        $tax = $totalPrice * 0.16; // 16% VAT
        $fee = $totalPrice * 0.025; // 2.5% processing fee
        $grandTotal = $totalPrice + $tax + $fee;

        // Create ticket purchase record
        $purchase = TicketPurchase::create([
            'user_id' => Auth::id(),
            'event_ticket_id' => $ticket->id,
            'order_number' => $this->generateOrderNumber(),
            'ticket_holder_name' => $request->ticket_holder_name,
            'ticket_holder_email' => $request->ticket_holder_email,
            'ticket_holder_phone' => $request->ticket_holder_phone,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'tax' => $tax,
            'fee' => $fee,
            'grand_total' => $grandTotal,
            'status' => 'pending',
            'payment_method' => 'pesapal',
        ]);

        // Redirect to payment gateway
        return $this->initiatePayment($purchase);
    }

    /**
     * Initiate payment with Pesapal
     */
    protected function initiatePayment(TicketPurchase $purchase)
    {
        // This is a placeholder for Pesapal integration
        // In a real implementation, you would:
        // 1. Configure Pesapal API credentials
        // 2. Create payment request
        // 3. Redirect to Pesapal gateway

        $paymentData = [
            'amount' => $purchase->grand_total,
            'currency' => 'KES',
            'description' => "Ticket purchase for {$purchase->ticket->event->name}",
            'callback_url' => route('tickets.payment.callback'),
            'reference' => $purchase->order_number,
        ];

        // For now, simulate successful payment for testing
        return redirect()->route('tickets.payment.success', $purchase->order_number);
    }

    /**
     * Handle payment callback from Pesapal
     */
    public function paymentCallback(Request $request)
    {
        $orderNumber = $request->get('reference');
        $status = $request->get('status');

        $purchase = TicketPurchase::where('order_number', $orderNumber)->first();

        if (!$purchase) {
            return redirect()->route('home')->with('error', 'Invalid payment reference.');
        }

        if ($status === 'COMPLETED') {
            return $this->handleSuccessfulPayment($purchase);
        } else {
            return $this->handleFailedPayment($purchase);
        }
    }

    /**
     * Handle successful payment
     */
    protected function handleSuccessfulPayment(TicketPurchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            // Update purchase status
            $purchase->update([
                'status' => 'completed',
                'payment_status' => 'completed',
                'paid_at' => now(),
            ]);

            // Update ticket sold quantity
            $purchase->ticket->increment('quantity_sold', $purchase->quantity);

            // Generate ticket
            $this->ticketGenerationService->generateTicket($purchase);
        });

        return redirect()->route('tickets.success', $purchase->order_number);
    }

    /**
     * Handle failed payment
     */
    protected function handleFailedPayment(TicketPurchase $purchase)
    {
        $purchase->update([
            'status' => 'failed',
            'payment_status' => 'failed',
        ]);

        return redirect()->route('tickets.failed', $purchase->order_number);
    }

    /**
     * Show payment success page
     */
    public function paymentSuccess($orderNumber)
    {
        $purchase = TicketPurchase::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->first();

        if (!$purchase) {
            abort(404);
        }

        return view('tickets.success', compact('purchase'));
    }

    /**
     * Show payment failed page
     */
    public function paymentFailed($orderNumber)
    {
        $purchase = TicketPurchase::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->first();

        if (!$purchase) {
            abort(404);
        }

        return view('tickets.failed', compact('purchase'));
    }

    /**
     * Verify ticket by QR code or order number
     */
    public function verify($orderNumber)
    {
        $purchase = TicketPurchase::where('order_number', $orderNumber)
            ->with(['ticket.event', 'user'])
            ->first();

        if (!$purchase) {
            return response()->json(['valid' => false, 'message' => 'Ticket not found']);
        }

        $isValid = $purchase->status === 'completed' && $purchase->payment_status === 'completed';

        return response()->json([
            'valid' => $isValid,
            'ticket' => $isValid ? [
                'order_number' => $purchase->order_number,
                'event_name' => $purchase->ticket->event->name,
                'ticket_type' => $purchase->ticket->name,
                'holder_name' => $purchase->ticket_holder_name,
                'quantity' => $purchase->quantity,
                'is_checked_in' => $purchase->is_checked_in,
            ] : null,
        ]);
    }

    /**
     * Check in ticket
     */
    public function checkIn(Request $request, $orderNumber)
    {
        $purchase = TicketPurchase::where('order_number', $orderNumber)->first();

        if (!$purchase || $purchase->status !== 'completed') {
            return response()->json(['success' => false, 'message' => 'Invalid ticket']);
        }

        if ($purchase->is_checked_in) {
            return response()->json(['success' => false, 'message' => 'Ticket already checked in']);
        }

        $purchase->update([
            'is_checked_in' => true,
            'checked_in_at' => now(),
            'checked_in_by' => $request->user()->name ?? 'System',
        ]);

        return response()->json(['success' => true, 'message' => 'Ticket checked in successfully']);
    }

    /**
     * Download ticket
     */
    public function download($orderNumber)
    {
        $purchase = TicketPurchase::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->first();

        if (!$purchase || !$purchase->ticket_pdf_path) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $purchase->ticket_pdf_path);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, "ticket-{$orderNumber}.png");
    }

    /**
     * Check streaming access for user
     */
    public function checkStreamAccess(BoxingEvent $event)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['access' => false, 'message' => 'Authentication required']);
        }

        $hasAccess = $event->userHasStreamAccess($user);

        return response()->json([
            'access' => $hasAccess,
            'message' => $hasAccess ? 'Access granted' : 'Ticket purchase required for stream access',
            'stream_url' => $hasAccess ? $event->stream_url : null,
        ]);
    }

    /**
     * User's tickets dashboard
     */
    public function myTickets()
    {
        $user = Auth::user();
        
        $tickets = TicketPurchase::where('user_id', $user->id)
            ->with(['ticket.event'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tickets.my-tickets', compact('tickets'));
    }

    /**
     * Generate unique order number
     */
    protected function generateOrderNumber()
    {
        do {
            $orderNumber = 'TKT-' . strtoupper(Str::random(8));
        } while (TicketPurchase::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}

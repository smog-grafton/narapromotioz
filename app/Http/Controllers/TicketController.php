<?php

namespace App\Http\Controllers;

use App\Models\BoxingEvent;
use App\Models\EventTicket;
use App\Models\TicketPurchase;
use App\Models\TicketTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
    public function purchase(Request $request, BoxingEvent $event)
    {
        $request->validate([
            'ticket_template_id' => 'required|exists:ticket_templates,id',
            'quantity' => 'required|integer|min:1|max:10',
            'attendee_name' => 'required|string|max:255',
            'attendee_email' => 'required|email|max:255',
            'attendee_phone' => 'nullable|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            $template = TicketTemplate::findOrFail($request->ticket_template_id);
            $quantity = $request->quantity;

            // Check availability
            $availableTickets = EventTicket::where('ticket_template_id', $template->id)
                ->where('status', 'available')
                ->count();

            if ($availableTickets < $quantity) {
                throw new \Exception('Not enough tickets available. Only ' . $availableTickets . ' tickets left.');
            }

            // Calculate total amount
            $totalAmount = $template->price * $quantity;

            // Get available tickets
            $tickets = EventTicket::where('ticket_template_id', $template->id)
                ->where('status', 'available')
                ->limit($quantity)
                ->get();

            // Create purchase record
            $purchase = TicketPurchase::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'ticket_template_id' => $template->id,
                'quantity' => $quantity,
                'total_amount' => $totalAmount,
                'purchase_reference' => 'TKT-' . strtoupper(Str::random(8)),
                'attendee_name' => $request->attendee_name,
                'attendee_email' => $request->attendee_email,
                'attendee_phone' => $request->attendee_phone,
                'status' => 'completed', // For now, we'll mark as completed
                'payment_method' => 'cash', // Default payment method
                'payment_status' => 'completed',
            ]);

            // Update ticket statuses and link to purchase
            foreach ($tickets as $ticket) {
                $ticket->update([
                    'status' => 'sold',
                    'ticket_purchase_id' => $purchase->id,
                    'sold_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('tickets.my-tickets')
                ->with('success', 'Tickets purchased successfully! Reference: ' . $purchase->purchase_reference);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ticket purchase failed: ' . $e->getMessage());
            
            return back()->with('error', 'Purchase failed: ' . $e->getMessage());
        }
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

            // TODO: Implement ticket generation service
            // $this->ticketGenerationService->generateTicket($purchase);
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
    public function downloadTicket(TicketPurchase $purchase)
    {
        // Verify the purchase belongs to the authenticated user
        if ($purchase->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to ticket.');
        }

        // Load the purchase with its related data
        $purchase->load(['event', 'ticketTemplate', 'tickets']);

        // For now, we'll return a simple view
        // In the future, you could generate a PDF here
        return view('tickets.download', compact('purchase'));
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
     * Display user's tickets
     */
    public function myTickets()
    {
        $user = Auth::user();
        
        // Load tickets with proper relationships based on actual DB structure
        $userTickets = TicketPurchase::where('user_id', $user->id)
            ->with(['ticket.event']) // EventTicket has event() relationship to BoxingEvent
            ->orderBy('created_at', 'desc')
            ->get(); // Changed from paginate for now to simplify debugging

        return view('tickets.my-tickets', compact('userTickets'));
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

    public function show(BoxingEvent $event)
    {
        // Load event with its tickets and templates
        $event->load(['tickets.template']);
        
        // Group tickets by template for easier display
        $ticketGroups = $event->tickets->groupBy('ticket_template_id');
        
        return view('tickets.show', compact('event', 'ticketGroups'));
    }
}

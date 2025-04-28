<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Payment;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the user's tickets.
     */
    public function index()
    {
        $tickets = auth()->user()->tickets()->with('event')->get();
        
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for purchasing a ticket.
     */
    public function create(Event $event)
    {
        // Check if the event is in the past
        if ($event->isPast) {
            return redirect()->route('events.show', $event)
                           ->with('error', 'Cannot purchase tickets for past events.');
        }
        
        return view('tickets.create', compact('event'));
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request, Event $event)
    {
        // Validate request
        $request->validate([
            'payment_method' => 'required|in:pesapal,airtel_money,mtn_money',
        ]);
        
        // Generate a unique ticket number
        $ticketNumber = Ticket::generateTicketNumber();
        
        // Create the ticket with pending payment status
        $ticket = Ticket::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'ticket_number' => $ticketNumber,
            'amount_paid' => $event->ticket_price,
            'payment_status' => 'pending',
            'payment_method' => $request->payment_method,
        ]);
        
        // Create a payment record
        $payment = Payment::create([
            'user_id' => auth()->id(),
            'payment_gateway' => $request->payment_method,
            'amount' => $event->ticket_price,
            'status' => 'pending',
            'transaction_reference' => Payment::generateTransactionReference(),
            'transaction_date' => now(),
        ]);
        
        // Redirect to payment gateway (to be implemented)
        // For now, we'll just simulate a successful payment
        $ticket->update(['payment_status' => 'paid']);
        $payment->update(['status' => 'completed']);
        
        return redirect()->route('tickets.confirmation', $ticket);
    }

    /**
     * Display the ticket confirmation page.
     */
    public function confirmation(Ticket $ticket)
    {
        // Ensure the ticket belongs to the authenticated user
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('tickets.confirmation', compact('ticket'));
    }
}
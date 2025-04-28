<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\StreamAccess;
use App\Models\Payment;
use Illuminate\Http\Request;

class StreamAccessController extends Controller
{
    /**
     * Purchase access to a stream
     */
    public function purchase(Request $request, Event $event)
    {
        // Validate request
        $request->validate([
            'payment_method' => 'required|in:pesapal,airtel_money,mtn_money',
        ]);
        
        // Check if user already has access to this stream
        if (auth()->user()->hasStreamAccess($event)) {
            return redirect()->route('events.stream', $event)
                           ->with('info', 'You already have access to this stream.');
        }
        
        // Create a payment record
        $transaction = Payment::generateTransactionReference();
        $payment = Payment::create([
            'user_id' => auth()->id(),
            'payment_gateway' => $request->payment_method,
            'amount' => 9.99, // Fixed stream price - could be stored in Event model
            'status' => 'pending',
            'transaction_reference' => $transaction,
            'transaction_date' => now(),
        ]);
        
        // Process payment with gateway (to be implemented)
        // For now, we'll simulate a successful payment
        $payment->update(['status' => 'completed']);
        
        // Grant stream access
        StreamAccess::updateOrCreate(
            [
                'event_id' => $event->id,
                'user_id' => auth()->id(),
            ],
            [
                'has_access' => true,
                'payment_reference' => $transaction,
            ]
        );
        
        // Redirect to stream page
        return redirect()->route('events.stream', $event)
                       ->with('success', 'Payment successful. You now have access to the stream.');
    }
}
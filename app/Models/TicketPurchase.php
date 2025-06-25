<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TicketPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_ticket_id',
        'user_id',
        'transaction_id',
        'quantity',
        'unit_price',
        'total_price',
        'currency',
        'payment_method',
        'payment_status',
        'reference_number',
        'qr_code',
        'ticket_data',
        'status',
        'checked_in_at',
        'payment_details',
        'billing_details',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'ticket_data' => 'array',
        'payment_details' => 'json',
        'billing_details' => 'json',
        'checked_in_at' => 'datetime',
    ];

    /**
     * Get the user who purchased the ticket
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ticket that was purchased
     */
    public function ticket()
    {
        return $this->belongsTo(EventTicket::class, 'event_ticket_id');
    }

    /**
     * Get the event associated with this purchase (through ticket)
     */
    public function event()
    {
        return $this->hasOneThrough(BoxingEvent::class, EventTicket::class, 'id', 'id', 'event_ticket_id', 'boxing_event_id');
    }

    /**
     * Get the boxing event directly through the ticket relationship
     */
    public function boxingEvent()
    {
        return $this->hasOneThrough(BoxingEvent::class, EventTicket::class, 'id', 'id', 'event_ticket_id', 'boxing_event_id');
    }

    /**
     * Get the formatted total price
     */
    public function getFormattedTotalPriceAttribute()
    {
        $currencySymbols = [
            'USD' => '$',
            'KES' => 'KSh',
            'GBP' => '£',
            'EUR' => '€',
        ];

        $symbol = $currencySymbols[$this->currency] ?? $this->currency;
        return $symbol . ' ' . number_format($this->total_price, 2);
    }

    /**
     * Get the QR code URL
     */
    public function getQrCodeUrlAttribute()
    {
        return $this->qr_code ? asset("storage/{$this->qr_code}") : null;
    }

    /**
     * Check if the ticket has been checked in
     */
    public function getIsCheckedInAttribute()
    {
        return $this->checked_in_at !== null;
    }

    /**
     * Generate a unique reference number
     */
    public static function generateReferenceNumber()
    {
        return 'TKT-' . strtoupper(Str::random(8));
    }

    /**
     * Mark the ticket as checked in
     */
    public function checkIn()
    {
        if (!$this->is_checked_in) {
            $this->update(['checked_in_at' => now()]);
        }

        return $this;
    }

    /**
     * Scope a query to only include successful purchases.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('payment_status', 'completed');
    }

    /**
     * Scope a query to only include pending purchases.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope a query to only include failed purchases.
     */
    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    /**
     * Scope a query to only include checked-in tickets.
     */
    public function scopeCheckedIn($query)
    {
        return $query->whereNotNull('checked_in_at');
    }

    /**
     * Scope a query to only include tickets for a specific event.
     */
    public function scopeForEvent($query, $eventId)
    {
        return $query->whereHas('ticket', function ($q) use ($eventId) {
            $q->where('boxing_event_id', $eventId);
        });
    }

    /**
     * Scope a query to only include tickets for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Generate QR code for the ticket
     */
    public function generateQrCode()
    {
        // Implementation will depend on QR code generation library
        // This is a placeholder for the actual implementation
        $qrData = [
            'reference' => $this->reference_number,
            'event' => $this->ticket->event->name,
            'ticket' => $this->ticket->name,
            'quantity' => $this->quantity,
            'user' => $this->user ? $this->user->name : 'Guest',
        ];
        
        // In a real implementation, you would generate a QR code image
        // and save it to storage, then update the qr_code field
        
        return $qrData;
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_id',
        'user_id',
        'ticket_number',
        'amount_paid',
        'payment_status',
        'payment_method',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount_paid' => 'decimal:2',
    ];

    /**
     * Get the event for this ticket
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who owns this ticket
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the ticket is paid
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if the ticket is for a past event
     */
    public function isForPastEvent(): bool
    {
        return $this->event->isPast;
    }

    /**
     * Generate a unique ticket number
     */
    public static function generateTicketNumber(): string
    {
        return 'TKT-' . strtoupper(substr(uniqid(), -6)) . '-' . random_int(1000, 9999);
    }
}
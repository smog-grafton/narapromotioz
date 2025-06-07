<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'boxing_event_id',
        'ticket_template_id',
        'name',
        'description',
        'price',
        'currency',
        'quantity_available',
        'quantity_sold',
        'max_per_purchase',
        'sale_start_date',
        'sale_end_date',
        'status',
        'ticket_type',
        'seating_area',
        'ticket_features',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity_available' => 'integer',
        'quantity_sold' => 'integer',
        'max_per_purchase' => 'integer',
        'sale_start_date' => 'datetime',
        'sale_end_date' => 'datetime',
        'ticket_features' => 'array',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the boxing event that the ticket belongs to
     */
    public function event()
    {
        return $this->belongsTo(BoxingEvent::class, 'boxing_event_id');
    }

    /**
     * Get the ticket template associated with this ticket
     */
    public function template()
    {
        return $this->belongsTo(TicketTemplate::class, 'ticket_template_id');
    }

    /**
     * Get the purchases for this ticket
     */
    public function purchases()
    {
        return $this->hasMany(TicketPurchase::class);
    }

    /**
     * Get the formatted price
     */
    public function getFormattedPriceAttribute()
    {
        $currencySymbols = [
            'USD' => '$',
            'KES' => 'KSh',
            'GBP' => '£',
            'EUR' => '€',
        ];

        $symbol = $currencySymbols[$this->currency] ?? $this->currency;
        return $symbol . ' ' . number_format($this->price, 2);
    }

    /**
     * Get the remaining quantity
     */
    public function getRemainingQuantityAttribute()
    {
        return $this->quantity_available - $this->quantity_sold;
    }

    /**
     * Check if the ticket is available for sale
     */
    public function getIsAvailableAttribute()
    {
        $now = now();
        return $this->status === 'active' && 
               $this->remainingQuantity > 0 &&
               ($this->sale_start_date === null || $this->sale_start_date <= $now) &&
               ($this->sale_end_date === null || $this->sale_end_date >= $now);
    }

    /**
     * Check if ticket sales have started
     */
    public function getSalesStartedAttribute()
    {
        return $this->sale_start_date === null || $this->sale_start_date <= now();
    }

    /**
     * Check if ticket sales have ended
     */
    public function getSalesEndedAttribute()
    {
        return $this->sale_end_date !== null && $this->sale_end_date < now();
    }

    /**
     * Calculate percentage of tickets sold
     */
    public function getPercentageSoldAttribute()
    {
        if ($this->quantity_available <= 0) {
            return 0;
        }
        
        return min(100, round(($this->quantity_sold / $this->quantity_available) * 100));
    }

    /**
     * Scope a query to only include active tickets.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include tickets that are currently on sale.
     */
    public function scopeOnSale($query)
    {
        $now = now();
        return $query->where('status', 'active')
                     ->where(function($q) use ($now) {
                         $q->whereNull('sale_start_date')
                           ->orWhere('sale_start_date', '<=', $now);
                     })
                     ->where(function($q) use ($now) {
                         $q->whereNull('sale_end_date')
                           ->orWhere('sale_end_date', '>=', $now);
                     })
                     ->whereRaw('quantity_available > quantity_sold');
    }

    /**
     * Scope a query to only include featured tickets.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include tickets for a specific event.
     */
    public function scopeForEvent($query, $eventId)
    {
        return $query->where('boxing_event_id', $eventId);
    }

    /**
     * Scope a query to only include tickets of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('ticket_type', $type);
    }

    /**
     * Process a purchase of this ticket
     */
    public function purchase($quantity = 1)
    {
        if ($this->remainingQuantity < $quantity) {
            throw new \Exception('Not enough tickets available');
        }

        $this->increment('quantity_sold', $quantity);
        return $this;
    }

    /**
     * Get the maximum tickets per purchase (default to 10 if not set)
     */
    public function getMaxPerPurchaseAttribute($value)
    {
        return $value ?? 10;
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'event_date',
        'location',
        'event_banner',
        'ticket_price',
        'description',
        'live_stream_url',
        'is_live',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'event_date' => 'datetime',
        'is_live' => 'boolean',
        'ticket_price' => 'decimal:2',
    ];

    /**
     * Get the URL friendly slug for the event
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get all fights for this event
     */
    public function fights(): HasMany
    {
        return $this->hasMany(Fight::class)->orderBy('fight_order');
    }

    /**
     * Get all tickets for this event
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get all stream access for this event
     */
    public function streamAccess(): HasMany
    {
        return $this->hasMany(StreamAccess::class);
    }
    
    /**
     * Check if the event is in the past
     */
    public function getIsPastAttribute()
    {
        return $this->event_date->isPast();
    }
    
    /**
     * Check if the event is upcoming
     */
    public function getIsUpcomingAttribute()
    {
        return $this->event_date->isFuture();
    }
    
    /**
     * Get formatted date for display
     */
    public function getFormattedDateAttribute()
    {
        return $this->event_date->format('F j, Y, g:i a');
    }
}
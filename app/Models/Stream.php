<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Stream extends Model
{
    use HasFactory;
    
    // Stream status constants
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_LIVE = 'live';
    const STATUS_ENDED = 'ended';
    const STATUS_CANCELLED = 'cancelled';
    
    // Stream access level constants
    const ACCESS_LEVEL_FREE = 'free';
    const ACCESS_LEVEL_PAID = 'paid';
    const ACCESS_LEVEL_SUBSCRIPTION = 'subscription';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'event_id',
        'scheduled_start',
        'scheduled_end',
        'actual_start',
        'actual_end',
        'status',
        'access_level',
        'price',
        'thumbnail_url',
        'ingest_server',
        'stream_key',
        'playback_url',
        'is_featured',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'scheduled_start' => 'datetime',
        'scheduled_end' => 'datetime',
        'actual_start' => 'datetime',
        'actual_end' => 'datetime',
        'price' => 'decimal:2',
        'stream_meta' => 'json',
        'is_featured' => 'boolean',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'stream_key',
    ];
    
    /**
     * Get the event associated with the stream.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
    /**
     * Get the purchases for the stream.
     */
    public function purchases()
    {
        return $this->hasMany(StreamPurchase::class);
    }
    
    /**
     * Get the chat messages for the stream.
     */
    public function chatMessages()
    {
        return $this->hasMany(StreamChatMessage::class);
    }
    
    /**
     * Get the viewers for the stream.
     */
    public function viewers()
    {
        return $this->hasMany(StreamViewer::class);
    }
    
    /**
     * Check if the stream is free.
     *
     * @return bool
     */
    public function isFree()
    {
        return $this->access_level === self::ACCESS_LEVEL_FREE;
    }
    
    /**
     * Check if the stream is paid.
     *
     * @return bool
     */
    public function isPaid()
    {
        return $this->access_level === self::ACCESS_LEVEL_PAID;
    }
    
    /**
     * Check if the stream requires a subscription.
     *
     * @return bool
     */
    public function isSubscription()
    {
        return $this->access_level === self::ACCESS_LEVEL_SUBSCRIPTION;
    }
    
    /**
     * Check if the stream is scheduled.
     *
     * @return bool
     */
    public function isScheduled()
    {
        return $this->status === self::STATUS_SCHEDULED;
    }
    
    /**
     * Check if the stream is live.
     *
     * @return bool
     */
    public function isLive()
    {
        return $this->status === self::STATUS_LIVE;
    }
    
    /**
     * Check if the stream has ended.
     *
     * @return bool
     */
    public function hasEnded()
    {
        return $this->status === self::STATUS_ENDED;
    }
    
    /**
     * Check if the stream is cancelled.
     *
     * @return bool
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }
    
    /**
     * Check if the stream is accessible.
     *
     * @return bool
     */
    public function isAccessible()
    {
        return in_array($this->status, [self::STATUS_SCHEDULED, self::STATUS_LIVE]);
    }
    
    /**
     * Start the stream.
     *
     * @return bool
     */
    public function startStream()
    {
        if ($this->status !== self::STATUS_SCHEDULED) {
            return false;
        }
        
        $this->status = self::STATUS_LIVE;
        $this->actual_start = now();
        
        return $this->save();
    }
    
    /**
     * End the stream.
     *
     * @return bool
     */
    public function endStream()
    {
        if ($this->status !== self::STATUS_LIVE) {
            return false;
        }
        
        $this->status = self::STATUS_ENDED;
        $this->actual_end = now();
        
        return $this->save();
    }
    
    /**
     * Cancel the stream.
     *
     * @return bool
     */
    public function cancelStream()
    {
        if (!in_array($this->status, [self::STATUS_SCHEDULED, self::STATUS_LIVE])) {
            return false;
        }
        
        $this->status = self::STATUS_CANCELLED;
        
        if ($this->status === self::STATUS_LIVE) {
            $this->actual_end = now();
        }
        
        return $this->save();
    }
    
    /**
     * Get the formatted price.
     *
     * @return string
     */
    public function getFormattedPriceAttribute()
    {
        if ($this->isFree()) {
            return 'Free';
        }
        
        if ($this->isSubscription()) {
            return 'Subscription';
        }
        
        return '$' . number_format($this->price, 2);
    }
    
    /**
     * Get the formatted duration.
     *
     * @return string
     */
    public function getFormattedDurationAttribute()
    {
        if ($this->actual_start && $this->actual_end) {
            $duration = $this->actual_start->diffInMinutes($this->actual_end);
        } else {
            $duration = $this->scheduled_start->diffInMinutes($this->scheduled_end);
        }
        
        $hours = floor($duration / 60);
        $minutes = $duration % 60;
        
        return ($hours > 0 ? $hours . 'h ' : '') . $minutes . 'm';
    }
    
    /**
     * Get the status badge HTML.
     *
     * @return string
     */
    public function getStatusBadgeAttribute()
    {
        $classes = [
            self::STATUS_SCHEDULED => 'bg-blue-100 text-blue-800',
            self::STATUS_LIVE => 'bg-green-100 text-green-800',
            self::STATUS_ENDED => 'bg-gray-100 text-gray-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
        ];
        
        $labels = [
            self::STATUS_SCHEDULED => 'Scheduled',
            self::STATUS_LIVE => 'Live',
            self::STATUS_ENDED => 'Ended',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
        
        $class = $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
        $label = $labels[$this->status] ?? 'Unknown';
        
        return '<span class="px-2 py-1 rounded-full text-xs font-medium ' . $class . '">' . $label . '</span>';
    }
    
    /**
     * Get the access level badge HTML.
     *
     * @return string
     */
    public function getAccessLevelBadgeAttribute()
    {
        $classes = [
            self::ACCESS_LEVEL_FREE => 'bg-green-100 text-green-800',
            self::ACCESS_LEVEL_PAID => 'bg-yellow-100 text-yellow-800',
            self::ACCESS_LEVEL_SUBSCRIPTION => 'bg-purple-100 text-purple-800',
        ];
        
        $labels = [
            self::ACCESS_LEVEL_FREE => 'Free',
            self::ACCESS_LEVEL_PAID => 'Paid',
            self::ACCESS_LEVEL_SUBSCRIPTION => 'Subscription',
        ];
        
        $class = $classes[$this->access_level] ?? 'bg-gray-100 text-gray-800';
        $label = $labels[$this->access_level] ?? 'Unknown';
        
        return '<span class="px-2 py-1 rounded-full text-xs font-medium ' . $class . '">' . $label . '</span>';
    }
    
    /**
     * Get the countdown to stream start.
     *
     * @return string
     */
    public function getCountdownAttribute()
    {
        if (!$this->isScheduled()) {
            return null;
        }
        
        $now = Carbon::now();
        
        if ($now->gt($this->scheduled_start)) {
            return 'Starting soon';
        }
        
        $diff = $now->diff($this->scheduled_start);
        
        if ($diff->days > 0) {
            return $diff->days . ' day' . ($diff->days > 1 ? 's' : '') . ' ' . 
                   $diff->h . ' hour' . ($diff->h > 1 ? 's' : '');
        }
        
        if ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ' . 
                   $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
        }
        
        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ' . 
               $diff->s . ' second' . ($diff->s > 1 ? 's' : '');
    }
    
    /**
     * Scope a query to only include featured streams.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    /**
     * Scope a query to only include live streams.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLive($query)
    {
        return $query->where('status', self::STATUS_LIVE);
    }
    
    /**
     * Scope a query to only include scheduled streams.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }
    
    /**
     * Scope a query to only include upcoming streams.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED)
                    ->where('scheduled_start', '>', now())
                    ->orderBy('scheduled_start', 'asc');
    }
    
    /**
     * Scope a query to only include accessible streams.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAccessible($query)
    {
        return $query->whereIn('status', [self::STATUS_SCHEDULED, self::STATUS_LIVE]);
    }
    
    /**
     * Scope a query to only include free streams.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFree($query)
    {
        return $query->where('access_level', self::ACCESS_LEVEL_FREE);
    }
    
    /**
     * Scope a query to only include paid streams.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaid($query)
    {
        return $query->where('access_level', self::ACCESS_LEVEL_PAID);
    }
}
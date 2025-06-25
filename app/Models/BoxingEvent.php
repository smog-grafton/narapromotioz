<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BoxingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tagline',
        'slug',
        'event_date',
        'event_time',
        'venue',
        'city',
        'country',
        'address',
        'location',
        'network',
        'broadcast_type',
        'status',
        'event_type',
        'poster_image',
        'promo_images',
        'promo_video_url',
        'description',
        'full_description',
        'image_path',
        'banner_path',
        'photos',
        'broadcast_network',
        'ppv_price',
        'stream_price',
        'promoter',
        'organizer',
        'sanctioning_body',
        'is_featured',
        'is_ppv',
        'is_free',
        'tickets_available',
        'live_gate_open',
        'min_ticket_price',
        'max_ticket_price',
        'ticket_purchase_url',
        'main_event_boxer_1_id',
        'main_event_boxer_2_id',
        'weight_class',
        'title',
        'rounds',
        'has_stream',
        'stream_url',
        'stream_backup_url',
        'youtube_stream_id',
        'stream_password',
        'stream_starts_at',
        'stream_ends_at',
        'early_access_stream',
        'require_ticket_for_stream',
        'weigh_in_photos',
        'press_conference_photos',
        'behind_scenes_photos',
        'highlight_videos',
        'gallery_videos',
        'sponsors',
        'meta_data',
        'views_count',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'event_time' => 'datetime',
        'stream_starts_at' => 'datetime',
        'stream_ends_at' => 'datetime',
        'promo_images' => 'array',
        'photos' => 'array',
        'weigh_in_photos' => 'array',
        'press_conference_photos' => 'array',
        'behind_scenes_photos' => 'array',
        'highlight_videos' => 'array',
        'gallery_videos' => 'array',
        'sponsors' => 'array',
        'meta_data' => 'array',
        'is_featured' => 'boolean',
        'is_ppv' => 'boolean',
        'is_free' => 'boolean',
        'tickets_available' => 'boolean',
        'live_gate_open' => 'boolean',
        'has_stream' => 'boolean',
        'early_access_stream' => 'boolean',
        'require_ticket_for_stream' => 'boolean',
        'rounds' => 'integer',
        'views_count' => 'integer',
        'ppv_price' => 'decimal:2',
        'stream_price' => 'decimal:2',
        'min_ticket_price' => 'decimal:2',
        'max_ticket_price' => 'decimal:2',
    ];
    
    protected $appends = [
        'formatted_date',
        'thumbnail',
        'main_event_title',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->name);
            }
        });

        static::updating(function ($event) {
            if ($event->isDirty('name') && empty($event->slug)) {
                $event->slug = Str::slug($event->name);
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the event's thumbnail path
     */
    public function getThumbnailAttribute()
    {
        if (Str::startsWith($this->poster_image, ['http://', 'https://', 'assets/'])) {
            return $this->poster_image;
        }

        return $this->poster_image ? asset("storage/{$this->poster_image}") : asset('assets/images/events/default.jpg');
    }
    
    /**
     * Get formatted event date
     */
    public function getFormattedDateAttribute()
    {
        if (!$this->event_date) {
            return 'TBA';
        }
        
        return $this->event_date->format('F j, Y');
    }
    
    /**
     * Get event status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        $statusClasses = [
            'upcoming' => 'badge-success',
            'ongoing' => 'badge-warning',
            'completed' => 'badge-info',
            'cancelled' => 'badge-danger',
            'postponed' => 'badge-secondary',
        ];
        
        return $statusClasses[$this->status] ?? 'badge-dark';
    }
    
    /**
     * Get event type badge class
     */
    public function getEventTypeBadgeClassAttribute()
    {
        $typeClasses = [
            'championship' => 'badge-gold',
            'title_defense' => 'badge-silver',
            'exhibition' => 'badge-bronze',
            'tournament' => 'badge-purple',
            'regular' => 'badge-primary',
        ];
        
        return $typeClasses[$this->event_type] ?? 'badge-dark';
    }
    
    /**
     * Check if the event is upcoming
     */
    public function getIsUpcomingAttribute()
    {
        return $this->event_date > now();
    }

    /**
     * Check if the event is past
     */
    public function getIsPastAttribute()
    {
        return $this->event_date < now();
    }

    /**
     * Check if the event is live/ongoing
     */
    public function getIsLiveAttribute()
    {
        $now = now();
        return $this->event_date->isSameDay($now) && 
               $this->status === 'ongoing';
    }

    /**
     * Check if streaming is currently available
     */
    public function getIsStreamingLiveAttribute()
    {
        if (!$this->has_stream) {
            return false;
        }

        $now = now();
        
        // If no specific stream times are set, use event date
        if (!$this->stream_starts_at && !$this->stream_ends_at) {
            return $this->event_date->isSameDay($now);
        }

        return $this->stream_starts_at <= $now && 
               ($this->stream_ends_at === null || $this->stream_ends_at >= $now);
    }

    /**
     * Check if user has access to stream
     */
    public function userHasStreamAccess($user = null)
    {
        if (!$this->has_stream) {
            return false;
        }

        // If no ticket required, allow access
        if (!$this->require_ticket_for_stream) {
            return true;
        }

        // If user is not authenticated, no access
        if (!$user) {
            return false;
        }

        // Check if user has purchased tickets for this event
        return $this->ticketPurchases()
                    ->where('user_id', $user->id)
                    ->where('payment_status', 'completed')
                    ->exists();
    }

    /**
     * Get all media photos combined
     */
    public function getAllPhotosAttribute()
    {
        $allPhotos = [];
        
        if ($this->photos) {
            $allPhotos = array_merge($allPhotos, $this->photos);
        }
        
        if ($this->weigh_in_photos) {
            $allPhotos = array_merge($allPhotos, $this->weigh_in_photos);
        }
        
        if ($this->press_conference_photos) {
            $allPhotos = array_merge($allPhotos, $this->press_conference_photos);
        }
        
        if ($this->behind_scenes_photos) {
            $allPhotos = array_merge($allPhotos, $this->behind_scenes_photos);
        }
        
        return $allPhotos;
    }

    /**
     * Get all videos combined
     */
    public function getAllVideosAttribute()
    {
        $allVideos = [];
        
        if ($this->highlight_videos) {
            $allVideos = array_merge($allVideos, $this->highlight_videos);
        }
        
        if ($this->gallery_videos) {
            $allVideos = array_merge($allVideos, $this->gallery_videos);
        }
        
        return $allVideos;
    }

    /**
     * Get ticket purchases for this event
     */
    public function ticketPurchases()
    {
        return $this->hasManyThrough(
            TicketPurchase::class,
            EventTicket::class,
            'boxing_event_id',
            'event_ticket_id'
        );
    }

    /**
     * Get fight results for this event
     */
    public function fightResults()
    {
        return $this->hasMany(FightRecord::class, 'boxing_event_id');
    }

    /**
     * Check if event has results
     */
    public function getHasResultsAttribute()
    {
        return $this->fightResults()->exists();
    }
    
    /**
     * Get the event's featured image URL
     */
    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->poster_image) {
            return asset('assets/images/events/default-banner.jpg');
        }
        
        if (Str::startsWith($this->poster_image, ['http://', 'https://', 'assets/'])) {
            return $this->poster_image;
        }
        
        return asset("storage/{$this->poster_image}");
    }
    
    /**
     * Get the event's banner image URL
     */
    public function getBannerImageUrlAttribute()
    {
        if (!$this->banner_path) {
            return asset('assets/images/events/default-banner.jpg');
        }
        
        if (Str::startsWith($this->banner_path, ['http://', 'https://', 'assets/'])) {
            return $this->banner_path;
        }
        
        return asset("storage/{$this->banner_path}");
    }

    /**
     * Get the main event title (e.g. "Fighter 1 vs Fighter 2")
     */
    public function getMainEventTitleAttribute()
    {
        $boxer1 = $this->mainEventBoxer1;
        $boxer2 = $this->mainEventBoxer2;

        if (!$boxer1 || !$boxer2) {
            return null;
        }

        return "{$boxer1->first_name} {$boxer1->last_name} vs. {$boxer2->first_name} {$boxer2->last_name}";
    }

    /**
     * Scope a query to only include upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>', Carbon::now())
                     ->where('status', 'upcoming');
    }

    /**
     * Scope a query to only include past events.
     */
    public function scopePast($query)
    {
        return $query->where('event_date', '<', Carbon::now())
                     ->where('status', 'completed');
    }

    /**
     * Scope a query to only include featured events.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include events of a specific type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope a query to order by date (ascending).
     */
    public function scopeChronological($query)
    {
        return $query->orderBy('event_date', 'asc');
    }

    /**
     * Scope a query to order by date (descending).
     */
    public function scopeReverseChronological($query)
    {
        return $query->orderBy('event_date', 'desc');
    }

    /**
     * Get the boxers participating in this event
     */
    public function boxers()
    {
        return $this->belongsToMany(Boxer::class, 'boxer_boxing_event')
                    ->withPivot('role', 'is_attending')
                    ->withTimestamps();
    }

    /**
     * Get the videos associated with this event
     */
    public function videos()
    {
        return $this->hasMany(BoxingVideo::class, 'event_id');
    }

    /**
     * Get the news articles associated with this event
     */
    public function news()
    {
        return $this->belongsToMany(NewsArticle::class, 'boxing_event_news_article')
                    ->withTimestamps();
    }

    /**
     * Get the tickets for this event
     */
    public function tickets()
    {
        return $this->hasMany(EventTicket::class, 'boxing_event_id');
    }

    /**
     * Get the fights that took place at this event
     */
    public function fights()
    {
        return $this->hasMany(FightRecord::class, 'boxing_event_id');
    }

    /**
     * Get main event boxers
     */
    public function mainEventBoxers()
    {
        return $this->boxers()->wherePivot('role', 'main event');
    }

    /**
     * Get co-main event boxers
     */
    public function coMainEventBoxers()
    {
        return $this->boxers()->wherePivot('role', 'co-main');
    }

    /**
     * Get undercard boxers
     */
    public function undercardBoxers()
    {
        return $this->boxers()->wherePivot('role', 'undercard');
    }

    /**
     * Check if the event has tickets available
     */
    public function hasAvailableTickets()
    {
        if (!$this->tickets_available) {
            return false;
        }

        return $this->tickets()->where('is_active', true)
                    ->whereRaw('quantity_available > quantity_sold')
                    ->exists();
    }

    /**
     * Get the available tickets count
     */
    public function getAvailableTicketsCountAttribute()
    {
        if (!$this->tickets_available) {
            return 0;
        }

        return $this->tickets()->sum('quantity_available');
    }

    /**
     * Get the lowest ticket price
     */
    public function getLowestTicketPriceAttribute()
    {
        if (!$this->tickets_available) {
            return null;
        }

        return $this->tickets()->min('price');
    }

    /**
     * Increment view count
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Get the boxer 1 of the main event
     */
    public function mainEventBoxer1()
    {
        return $this->belongsTo(Boxer::class, 'main_event_boxer_1_id');
    }

    /**
     * Get the boxer 2 of the main event
     */
    public function mainEventBoxer2()
    {
        return $this->belongsTo(Boxer::class, 'main_event_boxer_2_id');
    }
    
    /**
     * Check if user has purchased ticket for this event
     */
    public function hasUserPurchasedTicket($userId)
    {
        if (!$userId) {
            return false;
        }
        
        return TicketPurchase::whereHas('ticket', function($query) {
                $query->where('boxing_event_id', $this->id);
            })
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->exists();
    }
    
    /**
     * Get event stream status
     */
    public function getStreamStatusAttribute()
    {
        if (!$this->has_stream) {
            return 'unavailable';
        }
        
        $now = Carbon::now();
        $startTime = $this->event_date->copy()->setTimeFromTimeString($this->event_time->format('H:i:s'));
        $endTime = $startTime->copy()->addHours(4); // Assuming events last 4 hours
        
        if ($now->lt($startTime)) {
            return 'upcoming';
        } elseif ($now->gt($endTime)) {
            return 'ended';
        } else {
            return 'live';
        }
    }
} 
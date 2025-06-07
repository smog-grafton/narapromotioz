<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BoxingVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'video_type',
        'video_id',
        'video_url',
        'video_path',
        'duration',
        'is_premium',
        'is_featured',
        'status',
        'views_count',
        'likes_count',
        'published_at',
        'tags',
        'meta_data',
        'category',
        'source_type',
        'video_file',
        'publish_date',
        'boxer_id',
        'event_id',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'published_at' => 'datetime',
        'tags' => 'array',
        'meta_data' => 'json',
        'publish_date' => 'date',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($video) {
            if (empty($video->slug)) {
                $video->slug = Str::slug($video->title);
            }

            if (empty($video->published_at)) {
                $video->published_at = now();
            }
        });

        static::updating(function ($video) {
            if ($video->isDirty('title') && empty($video->slug)) {
                $video->slug = Str::slug($video->title);
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
     * Get the video's thumbnail path
     */
    public function getThumbnailPathAttribute()
    {
        // First check for thumbnail_path
        if (!empty($this->attributes['thumbnail_path'])) {
            if (Str::startsWith($this->attributes['thumbnail_path'], ['http://', 'https://', 'assets/'])) {
                return $this->attributes['thumbnail_path'];
            }
            return $this->attributes['thumbnail_path'];
        }
        
        // Fall back to thumbnail field
        if (!empty($this->attributes['thumbnail'])) {
            if (Str::startsWith($this->attributes['thumbnail'], ['http://', 'https://', 'assets/'])) {
                return $this->attributes['thumbnail'];
            }
            return "storage/{$this->attributes['thumbnail']}";
        }

        // Default thumbnail
        return 'assets/images/videos/default-thumbnail.jpg';
    }

    /**
     * Get the video URL based on the video type
     */
    public function getVideoUrlAttribute($value)
    {
        if ($value) {
            return $value;
        }

        switch ($this->video_type) {
            case 'youtube':
                return "https://www.youtube.com/embed/{$this->video_id}";
            case 'vimeo':
                return "https://player.vimeo.com/video/{$this->video_id}";
            case 'twitch':
                return "https://player.twitch.tv/?video={$this->video_id}&parent=" . config('app.url');
            case 'uploaded':
                return asset("storage/{$this->video_path}");
            default:
                return null;
        }
    }

    /**
     * Get formatted published date
     */
    public function getFormattedPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('M j, Y') : null;
    }

    /**
     * Scope a query to only include published videos.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include featured videos.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include premium videos.
     */
    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    /**
     * Scope a query to only include free videos.
     */
    public function scopeFree($query)
    {
        return $query->where('is_premium', false);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to filter by tag.
     */
    public function scopeByTag($query, $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    /**
     * Get the boxers associated with this video
     */
    public function boxers()
    {
        return $this->belongsToMany(Boxer::class, 'boxer_boxing_video')
                    ->withTimestamps();
    }

    /**
     * Get the events associated with this video
     */
    public function events()
    {
        return $this->belongsToMany(BoxingEvent::class, 'boxing_event_boxing_video')
                    ->withTimestamps();
    }

    /**
     * Check if a user can view this video
     */
    public function canBeViewedBy(?User $user)
    {
        if (!$this->is_premium) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $user->hasPermissionTo('view premium videos') || 
               $user->subscribed('premium');
    }

    /**
     * Increment the view count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Increment the like count
     */
    public function incrementLikes()
    {
        $this->increment('likes_count');
    }

    /**
     * Get the boxer associated with the video
     */
    public function boxer()
    {
        return $this->belongsTo(Boxer::class);
    }

    /**
     * Get the event associated with the video
     */
    public function event()
    {
        return $this->belongsTo(BoxingEvent::class, 'event_id');
    }

    /**
     * Get the fight record associated with the video
     */
    public function fightRecord()
    {
        return $this->hasOne(FightRecord::class, 'video_id');
    }

    /**
     * Get the thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        if (!$this->thumbnail) {
            return null;
        }

        if (str_starts_with($this->thumbnail, 'http://') || 
            str_starts_with($this->thumbnail, 'https://') || 
            str_starts_with($this->thumbnail, 'assets/')) {
            return $this->thumbnail;
        }

        return asset("storage/{$this->thumbnail}");
    }

    /**
     * Get the video URL based on source type
     */
    public function getVideoSourceUrlAttribute()
    {
        if ($this->source_type === 'uploaded' && $this->video_file) {
            return asset("storage/{$this->video_file}");
        }

        return $this->video_url;
    }

    /**
     * Get the embedded video code
     */
    public function getEmbedCodeAttribute()
    {
        if ($this->source_type === 'youtube') {
            $videoId = $this->getYoutubeId();
            return '<iframe width="100%" height="100%" src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        } elseif ($this->source_type === 'vimeo') {
            $videoId = $this->getVimeoId();
            return '<iframe width="100%" height="100%" src="https://player.vimeo.com/video/' . $videoId . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
        } elseif ($this->source_type === 'uploaded') {
            return '<video width="100%" height="100%" controls><source src="' . $this->video_source_url . '" type="video/mp4"></video>';
        } elseif ($this->source_type === 'external') {
            return '<iframe width="100%" height="100%" src="' . $this->video_url . '" frameborder="0" allowfullscreen></iframe>';
        }

        return null;
    }

    /**
     * Extract YouTube video ID from URL
     */
    protected function getYoutubeId()
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $this->video_url, $matches);
        
        return $matches[1] ?? null;
    }

    /**
     * Extract Vimeo video ID from URL
     */
    protected function getVimeoId()
    {
        $pattern = '/(?:vimeo\.com\/(?:video\/)?)(\d+)/';
        preg_match($pattern, $this->video_url, $matches);
        
        return $matches[1] ?? null;
    }

    /**
     * Get the formatted publish date
     */
    public function getFormattedPublishDateAttribute()
    {
        return $this->publish_date->format('F j, Y');
    }

    /**
     * Get related videos based on tags and boxer
     */
    public function getRelatedVideosAttribute()
    {
        $query = self::where('id', '!=', $this->id);

        // First priority: same boxer and similar tags
        if ($this->boxer_id) {
            $query->where('boxer_id', $this->boxer_id);
        }

        // Second priority: similar tags
        if (!empty($this->tags)) {
            $query->where(function ($q) {
                foreach ($this->tags as $tag) {
                    $q->orWhereJsonContains('tags', $tag);
                }
            });
        }

        // Third priority: same video type
        $query->orWhere('video_type', $this->video_type);

        return $query->limit(4)->get();
    }

    /**
     * Scope a query to only include videos of a specific type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('video_type', $type);
    }

    /**
     * Scope a query to only include videos by a specific boxer.
     */
    public function scopeByBoxer($query, $boxerId)
    {
        return $query->where('boxer_id', $boxerId);
    }

    /**
     * Scope a query to only include videos from a specific event.
     */
    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    /**
     * Scope a query to order by publish date (descending).
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('publish_date', 'desc');
    }

    /**
     * Scope a query to filter by tags.
     */
    public function scopeWithTag($query, $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }
} 
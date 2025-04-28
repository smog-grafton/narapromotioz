<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class NewsArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'author_id',
        'excerpt',
        'content',
        'featured_image',
        'gallery',
        'video_url',
        'tags',
        'published_at',
        'is_featured',
        'is_breaking',
        'views',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'social_title',
        'social_description',
        'social_image',
        'auto_post_social',
        'social_platforms',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_breaking' => 'boolean',
        'views' => 'integer',
        'gallery' => 'array',
        'tags' => 'array',
        'meta_keywords' => 'array',
        'social_platforms' => 'array',
        'auto_post_social' => 'boolean',
    ];

    protected $appends = [
        'reading_time',
        'featured_image_url',
        'social_image_url',
    ];

    /**
     * Get the author of the article
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get fighters mentioned in the article
     */
    public function fighters()
    {
        return $this->belongsToMany(Fighter::class, 'news_article_fighter');
    }

    /**
     * Get events mentioned in the article
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'news_article_event');
    }

    /**
     * Get related articles
     */
    public function relatedArticles()
    {
        return $this->belongsToMany(
            NewsArticle::class,
            'news_article_related',
            'news_article_id',
            'related_article_id'
        );
    }

    /**
     * Get the URL for featured image
     */
    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->featured_image) {
            return asset('images/news-placeholder.jpg');
        }
        
        return Storage::url($this->featured_image);
    }

    /**
     * Get the URL for social image
     */
    public function getSocialImageUrlAttribute()
    {
        if (!$this->social_image) {
            return $this->featured_image_url;
        }
        
        return Storage::url($this->social_image);
    }

    /**
     * Calculate estimated reading time
     */
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = ceil($wordCount / 200); // Assuming 200 words per minute reading speed
        
        return $readingTime;
    }

    /**
     * Scope published articles
     */
    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now())
            ->whereNotNull('published_at');
    }

    /**
     * Scope unpublished/draft articles
     */
    public function scopeDraft($query)
    {
        return $query->whereNull('published_at')
            ->orWhere('published_at', '>', now());
    }

    /**
     * Scope featured articles
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope breaking news
     */
    public function scopeBreaking($query)
    {
        return $query->where('is_breaking', true);
    }

    /**
     * Scope articles by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope articles with a specific tag
     */
    public function scopeWithTag($query, $tag)
    {
        return $query->where('tags', 'like', "%$tag%");
    }

    /**
     * Scope popular articles
     */
    public function scopePopular($query, $limit = null)
    {
        $query = $query->orderBy('views', 'desc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query;
    }

    /**
     * Scope recent articles
     */
    public function scopeRecent($query, $limit = null)
    {
        $query = $query->orderBy('published_at', 'desc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query;
    }

    /**
     * Scope articles related to a fighter
     */
    public function scopeRelatedToFighter($query, $fighterId)
    {
        return $query->whereHas('fighters', function ($query) use ($fighterId) {
            $query->where('fighter_id', $fighterId);
        });
    }

    /**
     * Scope articles related to an event
     */
    public function scopeRelatedToEvent($query, $eventId)
    {
        return $query->whereHas('events', function ($query) use ($eventId) {
            $query->where('event_id', $eventId);
        });
    }

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('views');
        return $this->views;
    }

    /**
     * Get formatted publication date
     */
    public function getFormattedDateAttribute()
    {
        return $this->published_at->format('F j, Y');
    }

    /**
     * Check if article is published
     */
    public function isPublished()
    {
        return $this->published_at && $this->published_at <= now();
    }

    /**
     * Check if article is scheduled for future publication
     */
    public function isScheduled()
    {
        return $this->published_at && $this->published_at > now();
    }

    /**
     * Get article URL
     */
    public function getUrl()
    {
        return url("/news/{$this->slug}");
    }

    /**
     * Find similar articles based on tags
     */
    public function findSimilarArticles($limit = 5)
    {
        if (empty($this->tags)) {
            return NewsArticle::published()
                ->where('id', '!=', $this->id)
                ->where('category', $this->category)
                ->limit($limit)
                ->latest('published_at')
                ->get();
        }
        
        return NewsArticle::published()
            ->where('id', '!=', $this->id)
            ->where(function ($query) {
                foreach ($this->tags as $tag) {
                    $query->orWhere('tags', 'like', "%$tag%");
                }
            })
            ->limit($limit)
            ->latest('published_at')
            ->get();
    }
}
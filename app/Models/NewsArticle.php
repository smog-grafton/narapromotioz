<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NewsArticle extends Model
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
        'thumbnail_image',
        'content',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the URL friendly slug for the news article
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Scope for published articles.
     */
    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at')
              ->where('published_at', '<=', now())
              ->orderBy('published_at', 'desc');
    }

    /**
     * Get a summary of the article content.
     */
    public function getSummaryAttribute()
    {
        return str()->limit(strip_tags($this->content), 150);
    }

    /**
     * Get the formatted published date.
     */
    public function getFormattedPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('F j, Y') : null;
    }

    /**
     * Get the time to read the article in minutes.
     */
    public function getReadTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        return max(1, ceil($wordCount / 200)); // Assuming average reading speed of 200 words per minute
    }
}
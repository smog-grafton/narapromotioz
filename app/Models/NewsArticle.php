<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'user_id',
        'status',
        'published_at',
        'is_featured',
        'is_main_article',
        'allow_comments',
        'views_count',
        'comments_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'reading_time',
        'seo_data',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_main_article' => 'boolean',
        'allow_comments' => 'boolean',
        'views_count' => 'integer',
        'comments_count' => 'integer',
        'reading_time' => 'integer',
        'seo_data' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            if (empty($article->reading_time)) {
                $article->reading_time = self::calculateReadingTime($article->content);
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title') && empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            if ($article->isDirty('content')) {
                $article->reading_time = self::calculateReadingTime($article->content);
            }
            
            // Handle main article setting
            if ($article->isDirty('is_main_article') && $article->is_main_article) {
                // Set all other articles to not be main
                static::where('id', '!=', $article->id)
                    ->where('is_main_article', true)
                    ->update(['is_main_article' => false]);
                
                // Ensure the article is also featured
                if (!$article->is_featured) {
                    $article->is_featured = true;
                }
            }
        });
        
        static::saved(function ($article) {
            // If this is a newly created article and it's set as main
            if ($article->is_main_article) {
                // Double-check to ensure no other articles are set as main
                static::where('id', '!=', $article->id)
                    ->where('is_main_article', true)
                    ->update(['is_main_article' => false]);
            }
        });
    }

    protected static function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        return ceil($wordCount / 200); // Assuming 200 words per minute
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(NewsCategory::class, 'news_article_category');
    }

    public function tags()
    {
        return $this->belongsToMany(NewsTag::class, 'news_article_tag');
    }

    public function comments()
    {
        return $this->hasMany(NewsComment::class, 'news_id')->where('status', 'approved')->whereNull('parent_id');
    }

    public function allComments()
    {
        return $this->hasMany(NewsComment::class, 'news_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views_count', 'desc');
    }

    public function scopeMainArticle($query)
    {
        return $query->where('is_main_article', true);
    }

    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }
        return Str::limit(strip_tags($this->content), 160);
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function updateCommentsCount()
    {
        $this->update([
            'comments_count' => $this->allComments()->where('status', 'approved')->count()
        ]);
    }

    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('F j, Y') : null;
    }

    public function getReadingTimeTextAttribute()
    {
        return $this->reading_time . ' min read';
    }
}

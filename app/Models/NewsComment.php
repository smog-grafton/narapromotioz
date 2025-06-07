<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'parent_id',
        'name',
        'email',
        'website',
        'comment',
        'status',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($comment) {
            if ($comment->status === 'approved') {
                $comment->article->updateCommentsCount();
            }
        });

        static::updated(function ($comment) {
            if ($comment->isDirty('status')) {
                $comment->article->updateCommentsCount();
            }
        });

        static::deleted(function ($comment) {
            $comment->article->updateCommentsCount();
        });
    }

    public function article()
    {
        return $this->belongsTo(NewsArticle::class, 'news_id');
    }

    public function parent()
    {
        return $this->belongsTo(NewsComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(NewsComment::class, 'parent_id')->where('status', 'approved');
    }

    public function allReplies()
    {
        return $this->hasMany(NewsComment::class, 'parent_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }

    public function markAsSpam()
    {
        $this->update(['status' => 'spam']);
    }
}

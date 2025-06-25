<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsletterSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'status',
        'source',
        'subscribed_at',
        'unsubscribed_at',
        'unsubscribe_token',
        'metadata'
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'metadata' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscription) {
            if (empty($subscription->unsubscribe_token)) {
                $subscription->unsubscribe_token = Str::random(32);
            }
        });
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUnsubscribed($query)
    {
        return $query->where('status', 'unsubscribed');
    }

    public function scopeBounced($query)
    {
        return $query->where('status', 'bounced');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('subscribed_at', 'desc');
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    public function getFormattedSubscribedAtAttribute()
    {
        return $this->subscribed_at->format('M d, Y \a\t h:i A');
    }

    // Methods
    public function unsubscribe()
    {
        $this->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now()
        ]);
    }

    public function resubscribe()
    {
        $this->update([
            'status' => 'active',
            'unsubscribed_at' => null,
            'subscribed_at' => now()
        ]);
    }

    public function generateUnsubscribeToken()
    {
        $token = Str::random(64);
        $this->update(['unsubscribe_token' => $token]);
        return $token;
    }

    // Static methods for stats
    public static function getActiveCount()
    {
        return static::active()->count();
    }

    public static function getTodayCount()
    {
        return static::whereDate('subscribed_at', today())->count();
    }

    public static function getThisWeekCount()
    {
        return static::whereBetween('subscribed_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
    }

    public static function getThisMonthCount()
    {
        return static::whereMonth('subscribed_at', now()->month)
                    ->whereYear('subscribed_at', now()->year)
                    ->count();
    }
}

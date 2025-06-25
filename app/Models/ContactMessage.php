<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_notes',
        'read_at',
        'replied_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getIsUnreadAttribute()
    {
        return $this->status === 'unread';
    }

    public function getIsReadAttribute()
    {
        return $this->status === 'read';
    }

    public function getIsRepliedAttribute()
    {
        return $this->status === 'replied';
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('M d, Y \a\t h:i A');
    }

    // Mutators
    public function markAsRead()
    {
        $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    public function markAsReplied()
    {
        $this->update([
            'status' => 'replied',
            'replied_at' => now()
        ]);
    }

    // Static methods for dashboard stats
    public static function getUnreadCount()
    {
        return static::unread()->count();
    }

    public static function getTodayCount()
    {
        return static::whereDate('created_at', today())->count();
    }

    public static function getThisWeekCount()
    {
        return static::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
    }
}

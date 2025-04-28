<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreamViewer extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stream_id',
        'user_id',
        'session_id',
        'first_joined_at',
        'last_active_at',
        'total_time_seconds',
        'device_info',
        'is_active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'first_joined_at' => 'datetime',
        'last_active_at' => 'datetime',
        'total_time_seconds' => 'integer',
        'device_info' => 'json',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the stream that is being viewed.
     */
    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }
    
    /**
     * Get the user that is viewing the stream.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope a query to only include active viewers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope a query to only include viewers active within the last X minutes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $minutes
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecentlyActive($query, $minutes = 5)
    {
        return $query->where('last_active_at', '>=', now()->subMinutes($minutes));
    }
    
    /**
     * Mark the viewer as active.
     *
     * @param  array  $deviceInfo
     * @return bool
     */
    public function markAsActive($deviceInfo = null)
    {
        $now = now();
        
        // Calculate time since last activity
        if ($this->is_active && $this->last_active_at) {
            $secondsSinceLastActivity = $now->diffInSeconds($this->last_active_at);
            
            // If less than 1 hour gap, add to total time
            if ($secondsSinceLastActivity < 3600) {
                $this->total_time_seconds += $secondsSinceLastActivity;
            }
        }
        
        $this->last_active_at = $now;
        $this->is_active = true;
        
        if ($deviceInfo && is_array($deviceInfo)) {
            $this->device_info = $deviceInfo;
        }
        
        return $this->save();
    }
    
    /**
     * Mark the viewer as inactive.
     *
     * @return bool
     */
    public function markAsInactive()
    {
        if (!$this->is_active) {
            return true;
        }
        
        // Calculate time since last activity
        if ($this->last_active_at) {
            $secondsSinceLastActivity = now()->diffInSeconds($this->last_active_at);
            
            // If less than 1 hour gap, add to total time
            if ($secondsSinceLastActivity < 3600) {
                $this->total_time_seconds += $secondsSinceLastActivity;
            }
        }
        
        $this->is_active = false;
        
        return $this->save();
    }
    
    /**
     * Format total time as human readable.
     *
     * @return string
     */
    public function getFormattedTotalTimeAttribute()
    {
        $totalSeconds = $this->total_time_seconds;
        
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m {$seconds}s";
        } elseif ($minutes > 0) {
            return "{$minutes}m {$seconds}s";
        } else {
            return "{$seconds}s";
        }
    }
    
    /**
     * Get time since last activity as human readable.
     *
     * @return string|null
     */
    public function getTimeSinceLastActivityAttribute()
    {
        if (!$this->last_active_at) {
            return null;
        }
        
        return $this->last_active_at->diffForHumans();
    }
    
    /**
     * Check if the viewer is considered active now.
     *
     * @param  int  $thresholdMinutes
     * @return bool
     */
    public function isCurrentlyActive($thresholdMinutes = 5)
    {
        if (!$this->is_active || !$this->last_active_at) {
            return false;
        }
        
        return $this->last_active_at->diffInMinutes() <= $thresholdMinutes;
    }
}
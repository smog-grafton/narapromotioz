<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StreamChatMessage extends Model
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
        'message',
        'is_pinned',
        'is_hidden',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_pinned' => 'boolean',
        'is_hidden' => 'boolean',
    ];
    
    /**
     * Get the stream that owns the message.
     */
    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }
    
    /**
     * Get the user that sent the message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope a query to only include visible messages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }
    
    /**
     * Scope a query to only include pinned messages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }
    
    /**
     * Scope a query to only include hidden messages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHidden($query)
    {
        return $query->where('is_hidden', true);
    }
    
    /**
     * Hide the message.
     *
     * @return bool
     */
    public function hide()
    {
        if ($this->is_hidden) {
            return true;
        }
        
        $this->is_hidden = true;
        
        // If message is pinned, unpin it
        if ($this->is_pinned) {
            $this->is_pinned = false;
        }
        
        return $this->save();
    }
    
    /**
     * Unhide the message.
     *
     * @return bool
     */
    public function unhide()
    {
        if (!$this->is_hidden) {
            return true;
        }
        
        $this->is_hidden = false;
        return $this->save();
    }
    
    /**
     * Pin the message.
     *
     * @return bool
     */
    public function pin()
    {
        if ($this->is_pinned || $this->is_hidden) {
            return false;
        }
        
        $this->is_pinned = true;
        return $this->save();
    }
    
    /**
     * Unpin the message.
     *
     * @return bool
     */
    public function unpin()
    {
        if (!$this->is_pinned) {
            return true;
        }
        
        $this->is_pinned = false;
        return $this->save();
    }
    
    /**
     * Check if the message can be moderated by the current user.
     *
     * @return bool
     */
    public function canBeModerated()
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        // Admins and staff can moderate all messages
        if ($user->hasRole(['admin', 'staff'])) {
            return true;
        }
        
        // Users can moderate their own messages
        return $user->id === $this->user_id;
    }
}
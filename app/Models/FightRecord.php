<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FightRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'boxer_id',
        'opponent_id',
        'boxing_event_id',
        'fight_date',
        'result',
        'method',
        'rounds',
        'round_time',
        'location',
        'venue',
        'notes',
        'title_fight',
        'weight_class',
        'is_main_event',
        'order',
        'referee',
        'judges',
        'scorecards',
        'video_id',
        'image_path',
    ];

    protected $casts = [
        'fight_date' => 'date',
        'rounds' => 'integer',
        'is_main_event' => 'boolean',
        'judges' => 'json',
        'scorecards' => 'json',
    ];

    /**
     * Get the boxer who this fight record belongs to
     */
    public function boxer()
    {
        return $this->belongsTo(Boxer::class);
    }

    /**
     * Get the opponent in this fight
     */
    public function opponent()
    {
        return $this->belongsTo(Boxer::class, 'opponent_id');
    }

    /**
     * Alias for boxer - used in fight card displays
     */
    public function boxer1()
    {
        return $this->belongsTo(Boxer::class, 'boxer_id');
    }

    /**
     * Alias for opponent - used in fight card displays
     */
    public function boxer2()
    {
        return $this->belongsTo(Boxer::class, 'opponent_id');
    }

    /**
     * Get the event this fight was part of
     */
    public function event()
    {
        return $this->belongsTo(BoxingEvent::class, 'boxing_event_id');
    }

    /**
     * Get the video of this fight if available
     */
    public function video()
    {
        return $this->belongsTo(BoxingVideo::class, 'video_id');
    }

    /**
     * Get formatted fight date
     */
    public function getFormattedFightDateAttribute()
    {
        return $this->fight_date ? $this->fight_date->format('M j, Y') : null;
    }

    /**
     * Get the fight result with method
     */
    public function getResultWithMethodAttribute()
    {
        if (!$this->method) {
            return $this->result;
        }

        return "{$this->result} ({$this->method})";
    }

    /**
     * Get rounds display
     */
    public function getRoundsDisplayAttribute()
    {
        if (!$this->rounds) {
            return '';
        }

        return $this->round_time ? "Rd {$this->rounds}, {$this->round_time}" : "Rd {$this->rounds}";
    }

    /**
     * Get age at time of fight
     */
    public function getAgeAtFightAttribute()
    {
        $birthdate = $this->boxer->birth_date;
        if (!$birthdate || !$this->fight_date) {
            return null;
        }

        return Carbon::parse($birthdate)->diffInYears($this->fight_date);
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http://') || 
            str_starts_with($this->image_path, 'https://') || 
            str_starts_with($this->image_path, 'assets/')) {
            return $this->image_path;
        }

        return asset("storage/{$this->image_path}");
    }

    /**
     * Scope a query to only include wins.
     */
    public function scopeWins($query)
    {
        return $query->where('result', 'win');
    }

    /**
     * Scope a query to only include losses.
     */
    public function scopeLosses($query)
    {
        return $query->where('result', 'loss');
    }

    /**
     * Scope a query to only include draws.
     */
    public function scopeDraws($query)
    {
        return $query->where('result', 'draw');
    }

    /**
     * Scope a query to only include title fights.
     */
    public function scopeTitleFights($query)
    {
        return $query->whereNotNull('title_fight');
    }

    /**
     * Scope a query to only include main events.
     */
    public function scopeMainEvents($query)
    {
        return $query->where('is_main_event', true);
    }

    /**
     * Scope a query to filter by weight class.
     */
    public function scopeByWeightClass($query, $weightClass)
    {
        return $query->where('weight_class', $weightClass);
    }

    /**
     * Scope a query to only include knockouts.
     */
    public function scopeKnockouts($query)
    {
        return $query->where('result', 'win')
                     ->whereIn('method', ['KO', 'TKO']);
    }

    /**
     * Scope a query to include fights before a certain date.
     */
    public function scopeBefore($query, $date)
    {
        return $query->where('fight_date', '<', $date);
    }

    /**
     * Scope a query to include fights after a certain date.
     */
    public function scopeAfter($query, $date)
    {
        return $query->where('fight_date', '>', $date);
    }

    /**
     * Scope a query to order by fight date.
     */
    public function scopeChronological($query)
    {
        return $query->orderBy('fight_date', 'asc');
    }

    /**
     * Scope a query to order by fight date in reverse.
     */
    public function scopeReverseChronological($query)
    {
        return $query->orderBy('fight_date', 'desc');
    }
} 
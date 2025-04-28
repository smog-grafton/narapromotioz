<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fight extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_id',
        'fighter_one_id',
        'fighter_two_id',
        'result',
        'fight_order',
    ];

    /**
     * Get the event for this fight
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get fighter one
     */
    public function fighterOne(): BelongsTo
    {
        return $this->belongsTo(Fighter::class, 'fighter_one_id');
    }

    /**
     * Get fighter two
     */
    public function fighterTwo(): BelongsTo
    {
        return $this->belongsTo(Fighter::class, 'fighter_two_id');
    }

    /**
     * Check if fight is completed
     */
    public function getIsCompletedAttribute()
    {
        return !is_null($this->result);
    }

    /**
     * Check if fight is featured (main event - fight_order = 1)
     */
    public function getIsMainEventAttribute()
    {
        return $this->fight_order === 1;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ranking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fighter_id',
        'weight_class',
        'position',
        'points',
        'last_updated',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'position' => 'integer',
        'points' => 'decimal:2',
        'last_updated' => 'datetime',
    ];

    /**
     * Get the fighter who owns this ranking
     */
    public function fighter(): BelongsTo
    {
        return $this->belongsTo(Fighter::class);
    }

    /**
     * Scope for specific weight class
     */
    public function scopeByWeightClass($query, $weightClass)
    {
        return $query->where('weight_class', $weightClass)
                    ->orderBy('position', 'asc');
    }

    /**
     * Check if the fighter is in the top 5
     */
    public function isTopFive(): bool
    {
        return $this->position <= 5;
    }

    /**
     * Check if the fighter is the champion (position 1)
     */
    public function isChampion(): bool
    {
        return $this->position === 1;
    }
}
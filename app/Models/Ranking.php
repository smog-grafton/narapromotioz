<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ranking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization',
        'weight_class',
        'date',
        'notes',
        'champion_id',
        'fighter1_id',
        'fighter1_previous',
        'fighter2_id',
        'fighter2_previous',
        'fighter3_id',
        'fighter3_previous',
        'fighter4_id',
        'fighter4_previous',
        'fighter5_id',
        'fighter5_previous',
        'fighter6_id',
        'fighter6_previous',
        'fighter7_id',
        'fighter7_previous',
        'fighter8_id',
        'fighter8_previous',
        'fighter9_id',
        'fighter9_previous',
        'fighter10_id',
        'fighter10_previous',
    ];

    protected $casts = [
        'date' => 'date',
        'fighter1_previous' => 'integer',
        'fighter2_previous' => 'integer',
        'fighter3_previous' => 'integer',
        'fighter4_previous' => 'integer',
        'fighter5_previous' => 'integer',
        'fighter6_previous' => 'integer',
        'fighter7_previous' => 'integer',
        'fighter8_previous' => 'integer',
        'fighter9_previous' => 'integer',
        'fighter10_previous' => 'integer',
    ];

    /**
     * Get the champion fighter
     */
    public function champion()
    {
        return $this->belongsTo(Fighter::class, 'champion_id');
    }

    /**
     * Get the fighter at position 1
     */
    public function fighter1()
    {
        return $this->belongsTo(Fighter::class, 'fighter1_id');
    }

    /**
     * Get the fighter at position 2
     */
    public function fighter2()
    {
        return $this->belongsTo(Fighter::class, 'fighter2_id');
    }

    /**
     * Get the fighter at position 3
     */
    public function fighter3()
    {
        return $this->belongsTo(Fighter::class, 'fighter3_id');
    }

    /**
     * Get the fighter at position 4
     */
    public function fighter4()
    {
        return $this->belongsTo(Fighter::class, 'fighter4_id');
    }

    /**
     * Get the fighter at position 5
     */
    public function fighter5()
    {
        return $this->belongsTo(Fighter::class, 'fighter5_id');
    }

    /**
     * Get the fighter at position 6
     */
    public function fighter6()
    {
        return $this->belongsTo(Fighter::class, 'fighter6_id');
    }

    /**
     * Get the fighter at position 7
     */
    public function fighter7()
    {
        return $this->belongsTo(Fighter::class, 'fighter7_id');
    }

    /**
     * Get the fighter at position 8
     */
    public function fighter8()
    {
        return $this->belongsTo(Fighter::class, 'fighter8_id');
    }

    /**
     * Get the fighter at position 9
     */
    public function fighter9()
    {
        return $this->belongsTo(Fighter::class, 'fighter9_id');
    }

    /**
     * Get the fighter at position 10
     */
    public function fighter10()
    {
        return $this->belongsTo(Fighter::class, 'fighter10_id');
    }

    /**
     * Scope rankings by organization
     */
    public function scopeByOrganization($query, $organization)
    {
        return $query->where('organization', $organization);
    }

    /**
     * Scope rankings by weight class
     */
    public function scopeByWeightClass($query, $weightClass)
    {
        return $query->where('weight_class', $weightClass);
    }

    /**
     * Scope rankings with champion
     */
    public function scopeWithChampion($query)
    {
        return $query->whereNotNull('champion_id');
    }

    /**
     * Scope latest rankings
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('date', 'desc');
    }

    /**
     * Get all fighters in the ranking
     */
    public function fighters()
    {
        $fighters = [];
        
        for ($i = 1; $i <= 10; $i++) {
            $fighterId = "fighter{$i}_id";
            $previousPosition = "fighter{$i}_previous";
            
            if ($this->$fighterId) {
                $fighters[] = [
                    'position' => $i,
                    'fighter_id' => $this->$fighterId,
                    'fighter' => $this->{"fighter{$i}"},
                    'previous_position' => $this->$previousPosition,
                    'movement' => $this->$previousPosition ? $this->$previousPosition - $i : 'new',
                ];
            }
        }
        
        return $fighters;
    }

    /**
     * Check if a fighter is in this ranking
     */
    public function hasFighter($fighterId)
    {
        for ($i = 1; $i <= 10; $i++) {
            $currentFighterId = "fighter{$i}_id";
            
            if ($this->$currentFighterId == $fighterId) {
                return true;
            }
        }
        
        return $this->champion_id == $fighterId;
    }

    /**
     * Get a fighter's position in this ranking
     */
    public function getFighterPosition($fighterId)
    {
        for ($i = 1; $i <= 10; $i++) {
            $currentFighterId = "fighter{$i}_id";
            
            if ($this->$currentFighterId == $fighterId) {
                return $i;
            }
        }
        
        if ($this->champion_id == $fighterId) {
            return 'champion';
        }
        
        return null;
    }
}
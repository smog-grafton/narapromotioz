<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Fighter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'nickname',
        'date_of_birth',
        'nationality',
        'height_cm',
        'weight_kg',
        'weight_class',
        'boxing_style',
        'wins',
        'losses',
        'draws',
        'ko_wins',
        'profile_image',
        'bio',
        'slug',
    ];

    /**
     * Get the URL friendly slug for the fighter
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get fights where this fighter is fighter one
     */
    public function fightsAsOne(): HasMany
    {
        return $this->hasMany(Fight::class, 'fighter_one_id');
    }

    /**
     * Get fights where this fighter is fighter two
     */
    public function fightsAsTwo(): HasMany
    {
        return $this->hasMany(Fight::class, 'fighter_two_id');
    }

    /**
     * Get all fights for this fighter
     */
    public function fights()
    {
        return $this->fightsAsOne()->union($this->fightsAsTwo()->toBase());
    }

    /**
     * Get the ranking for this fighter
     */
    public function ranking(): HasOne
    {
        return $this->hasOne(Ranking::class);
    }

    /**
     * Calculate fighter's age based on date of birth
     */
    public function getAgeAttribute()
    {
        return \Carbon\Carbon::parse($this->date_of_birth)->age;
    }

    /**
     * Get total fights count
     */
    public function getTotalFightsAttribute()
    {
        return $this->wins + $this->losses + $this->draws;
    }
}
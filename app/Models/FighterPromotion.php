<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FighterPromotion extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The status options
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fighter_id',
        'event_id',
        'fight_id',
        'promo_code',
        'tickets_sold',
        'commission_earned',
        'commission_rate',
        'status',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tickets_sold' => 'integer',
        'commission_earned' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the fighter that owns the promotion.
     */
    public function fighter()
    {
        return $this->belongsTo(Fighter::class);
    }

    /**
     * Get the event associated with the promotion.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the fight associated with the promotion.
     */
    public function fight()
    {
        return $this->belongsTo(Fight::class);
    }

    /**
     * Get the tickets sold with this promotion code.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'promo_code', 'promo_code');
    }

    /**
     * Check if the promotion is active.
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE && 
               ($this->expires_at === null || $this->expires_at > now());
    }

    /**
     * Add a sale to the promotion.
     */
    public function addSale($ticketPrice)
    {
        $this->tickets_sold += 1;
        $commission = $ticketPrice * ($this->commission_rate / 100);
        $this->commission_earned += $commission;
        $this->save();

        // Add commission to the fighter's account
        $this->fighter->addCommission($commission);

        return $this->tickets_sold;
    }

    /**
     * Scope active promotions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->where(function ($query) {
                        $query->whereNull('expires_at')
                              ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope expired promotions.
     */
    public function scopeExpired($query)
    {
        return $query->where(function ($query) {
                        $query->where('status', self::STATUS_EXPIRED)
                              ->orWhere(function ($q) {
                                  $q->where('status', self::STATUS_ACTIVE)
                                    ->where('expires_at', '<=', now());
                              });
                    });
    }
}
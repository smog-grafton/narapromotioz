<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Fighter extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The profile verification status options.
     */
    const VERIFICATION_STATUS_PENDING = 'pending';
    const VERIFICATION_STATUS_VERIFIED = 'verified';
    const VERIFICATION_STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'first_name',
        'last_name',
        'nickname',
        'date_of_birth',
        'country',
        'weight_class',
        'height',
        'reach',
        'stance',
        'profile_image',
        'banner_image',
        'wins',
        'losses',
        'draws',
        'ko_wins',
        'decision_wins',
        'total_rounds',
        'knockout_percentage',
        'is_champion',
        'championship_title',
        'short_bio',
        'biography',
        'video_highlight_url',
        'gallery',
        'manager',
        'promoter',
        'trainer',
        'gym',
        'pro_debut_date',
        'amateur_record',
        'olympic_medals',
        'contract_status',
        'instagram_handle',
        'twitter_handle',
        'facebook_url',
        'youtube_channel',
        'website',
        'gender',
        'user_id',
        'verification_status',
        'verification_documents',
        'promo_code',
        'commission_rate',
        'commission_earned',
        'commission_withdrawn',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'pro_debut_date' => 'date',
        'gallery' => 'array',
        'verification_documents' => 'array',
        'is_champion' => 'boolean',
        'height' => 'decimal:2',
        'reach' => 'decimal:2',
        'wins' => 'integer',
        'losses' => 'integer',
        'draws' => 'integer',
        'ko_wins' => 'integer',
        'decision_wins' => 'integer',
        'total_rounds' => 'integer',
        'knockout_percentage' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_earned' => 'decimal:2',
        'commission_withdrawn' => 'decimal:2',
    ];

    protected $appends = [
        'full_name',
        'age',
        'record',
        'profile_image_url',
        'banner_image_url',
    ];

    /**
     * Get fighter's full name
     */
    public function getFullNameAttribute()
    {
        $name = "{$this->first_name} {$this->last_name}";
        
        if ($this->nickname) {
            $name = "{$this->first_name} \"{$this->nickname}\" {$this->last_name}";
        }
        
        return $name;
    }

    /**
     * Get fighter's age
     */
    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        
        return Carbon::parse($this->date_of_birth)->age;
    }

    /**
     * Get fighter's record as a string
     */
    public function getRecordAttribute()
    {
        return "{$this->wins}-{$this->losses}-{$this->draws}";
    }

    /**
     * Get profile image URL
     */
    public function getProfileImageUrlAttribute()
    {
        if (!$this->profile_image) {
            return asset('images/fighter-placeholder.jpg');
        }
        
        return Storage::url($this->profile_image);
    }

    /**
     * Get banner image URL
     */
    public function getBannerImageUrlAttribute()
    {
        if (!$this->banner_image) {
            return asset('images/fighter-banner-placeholder.jpg');
        }
        
        return Storage::url($this->banner_image);
    }

    /**
     * Get fights where fighter is fighter1
     */
    public function fightsAsFighter1()
    {
        return $this->hasMany(Fight::class, 'fighter1_id');
    }

    /**
     * Get fights where fighter is fighter2
     */
    public function fightsAsFighter2()
    {
        return $this->hasMany(Fight::class, 'fighter2_id');
    }

    /**
     * Get all fights for the fighter
     */
    public function fights()
    {
        return $this->fightsAsFighter1()->union($this->fightsAsFighter2());
    }

    /**
     * Get rankings for the fighter
     */
    public function rankings()
    {
        return $this->hasMany(FighterRanking::class);
    }

    /**
     * Get news articles that mention the fighter
     */
    public function newsArticles()
    {
        return $this->belongsToMany(NewsArticle::class, 'news_article_fighter');
    }

    /**
     * Get championships
     */
    public function titles()
    {
        return $this->hasMany(FighterTitle::class);
    }

    /**
     * Scope fighters by weight class
     */
    public function scopeByWeightClass($query, $weightClass)
    {
        return $query->where('weight_class', $weightClass);
    }

    /**
     * Scope champions only
     */
    public function scopeChampions($query)
    {
        return $query->where('is_champion', true);
    }

    /**
     * Scope undefeated fighters
     */
    public function scopeUndefeated($query)
    {
        return $query->where('losses', 0)->where('wins', '>', 0);
    }

    /**
     * Scope fighters by country
     */
    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Get current ranking for a specific organization and weight class
     */
    public function getCurrentRanking($organization, $weightClass = null)
    {
        $query = $this->rankings()
            ->where('organization', $organization)
            ->orderBy('date', 'desc');
            
        if ($weightClass) {
            $query->where('weight_class', $weightClass);
        }
        
        return $query->first();
    }
    
    /**
     * Get the user that owns this fighter profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get promotions by this fighter
     */
    public function promotions()
    {
        return $this->hasMany(FighterPromotion::class);
    }
    
    /**
     * Get withdrawal requests
     */
    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class);
    }
    
    /**
     * Generate a unique promo code for the fighter
     */
    public function generatePromoCode()
    {
        if (!$this->promo_code) {
            $baseCode = $this->nickname ? Str::slug($this->nickname) : Str::slug($this->first_name . $this->last_name);
            $baseCode = Str::limit($baseCode, 10, '');
            $randomStr = Str::random(4);
            $this->promo_code = strtoupper($baseCode . $randomStr);
            $this->save();
        }
        
        return $this->promo_code;
    }
    
    /**
     * Calculate available commission balance
     */
    public function getAvailableCommissionAttribute()
    {
        return $this->commission_earned - $this->commission_withdrawn;
    }
    
    /**
     * Scope fighters with verified accounts
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', self::VERIFICATION_STATUS_VERIFIED);
    }
    
    /**
     * Scope fighters with pending verification
     */
    public function scopePendingVerification($query)
    {
        return $query->where('verification_status', self::VERIFICATION_STATUS_PENDING);
    }
    
    /**
     * Scope fighters with user accounts
     */
    public function scopeWithUserAccounts($query)
    {
        return $query->whereNotNull('user_id');
    }
    
    /**
     * Add commission to the fighter's account
     */
    public function addCommission($amount)
    {
        $this->commission_earned += $amount;
        $this->save();
        
        return $this->commission_earned;
    }
    
    /**
     * Get upcoming events for the fighter
     */
    public function getUpcomingEvents()
    {
        $now = now();
        
        $fightsAsF1 = $this->fightsAsFighter1()
            ->whereHas('event', function ($query) use ($now) {
                $query->where('date', '>=', $now);
            })
            ->with('event')
            ->get();
            
        $fightsAsF2 = $this->fightsAsFighter2()
            ->whereHas('event', function ($query) use ($now) {
                $query->where('date', '>=', $now);
            })
            ->with('event')
            ->get();
            
        return $fightsAsF1->merge($fightsAsF2)->sortBy('event.date');
    }
}
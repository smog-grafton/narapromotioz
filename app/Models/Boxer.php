<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Boxer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'nickname',
        'weight_class',
        'wins',
        'losses',
        'draws',
        'knockouts',
        'kos_lost',
        'age',
        'height',
        'reach',
        'stance',
        'hometown',
        'country',
        'bio',
        'full_bio',
        'image_path',
        'titles',
        'years_pro',
        'status',
        'global_ranking',
        'total_fighters_in_division',
        'career_start',
        'career_end',
        'debut_date',
        'knockout_rate',
        'win_rate',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'debut_date' => 'date',
        'career_start' => 'integer',
        'career_end' => 'integer',
        'knockout_rate' => 'integer',
        'win_rate' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($boxer) {
            if (empty($boxer->slug)) {
                $boxer->slug = Str::slug($boxer->name);
            }
            
            // Calculate rates if not set
            if (!isset($boxer->knockout_rate)) {
                $boxer->knockout_rate = $boxer->calculateKnockoutRate();
            }
            
            if (!isset($boxer->win_rate)) {
                $boxer->win_rate = $boxer->calculateWinRate();
            }
            
            // Default the image path if not set
            if (empty($boxer->image_path)) {
                $boxer->image_path = 'assets/images/boxers/default.jpg';
            }
        });

        static::updating(function ($boxer) {
            if ($boxer->isDirty('name') && empty($boxer->slug)) {
                $boxer->slug = Str::slug($boxer->name);
            }
            
            // Update rates if related fields changed
            if ($boxer->isDirty('wins') || $boxer->isDirty('knockouts')) {
                $boxer->knockout_rate = $boxer->calculateKnockoutRate();
            }
            
            if ($boxer->isDirty('wins') || $boxer->isDirty('losses') || $boxer->isDirty('draws')) {
                $boxer->win_rate = $boxer->calculateWinRate();
            }
        });
    }

    /**
     * Calculate knockout rate
     */
    public function calculateKnockoutRate()
    {
        if ($this->wins == 0) return 0;
        return round(($this->knockouts / $this->wins) * 100);
    }

    /**
     * Calculate win rate
     */
    public function calculateWinRate()
    {
        $totalFights = $this->wins + $this->losses + $this->draws;
        if ($totalFights == 0) return 0;
        return round(($this->wins / $totalFights) * 100);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the boxer's record display string
     */
    public function getRecordAttribute()
    {
        return "{$this->wins}-{$this->losses}-{$this->draws}";
    }

    /**
     * Get the boxer's win rate as a percentage
     */
    public function getWinRateAttribute()
    {
        $totalFights = $this->wins + $this->losses + $this->draws;
        if ($totalFights == 0) return 0;
        return round(($this->wins / $totalFights) * 100, 1);
    }

    /**
     * Get the boxer's knockout rate as a percentage
     */
    public function getKoRateAttribute()
    {
        if ($this->wins == 0) return 0;
        return round(($this->knockouts / $this->wins) * 100, 1);
    }

    /**
     * Get the boxer's thumbnail path
     */
    public function getThumbnailAttribute()
    {
        if (Str::startsWith($this->image_path, ['http://', 'https://', 'assets/'])) {
            return $this->image_path;
        }

        return $this->image_path ? "storage/{$this->image_path}" : 'assets/images/boxers/default.jpg';
    }

    /**
     * Scope a query to only include active boxers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured boxers.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to filter by weight class.
     */
    public function scopeByWeightClass($query, $weightClass)
    {
        return $query->where('weight_class', $weightClass);
    }

    /**
     * Get the boxer's fight records
     */
    public function fights()
    {
        return $this->hasMany(FightRecord::class);
    }

    /**
     * Get the boxer's events
     */
    public function events()
    {
        return $this->belongsToMany(BoxingEvent::class, 'boxer_boxing_event')
                    ->withPivot('role', 'is_attending')
                    ->withTimestamps();
    }

    /**
     * Get the boxer's videos
     */
    public function videos()
    {
        return $this->belongsToMany(BoxingVideo::class, 'boxer_boxing_video')
                    ->withTimestamps();
    }

    /**
     * Get the boxer's news articles
     */
    public function news()
    {
        return $this->belongsToMany(NewsArticle::class, 'boxer_news_article')
                    ->withTimestamps();
    }

    /**
     * Get the wins count including total and method breakdown
     */
    public function getWinsBreakdownAttribute()
    {
        $wins = $this->fights()->where('result', 'win')->get();
        
        return [
            'total' => $wins->count(),
            'ko' => $wins->where('method', 'KO')->count(),
            'tko' => $wins->where('method', 'TKO')->count(),
            'ud' => $wins->where('method', 'UD')->count(),
            'sd' => $wins->where('method', 'SD')->count(),
            'md' => $wins->where('method', 'MD')->count(),
            'other' => $wins->whereNotIn('method', ['KO', 'TKO', 'UD', 'SD', 'MD'])->count(),
        ];
    }

    /**
     * Get the losses count including total and method breakdown
     */
    public function getLossesBreakdownAttribute()
    {
        $losses = $this->fights()->where('result', 'loss')->get();
        
        return [
            'total' => $losses->count(),
            'ko' => $losses->where('method', 'KO')->count(),
            'tko' => $losses->where('method', 'TKO')->count(),
            'ud' => $losses->where('method', 'UD')->count(),
            'sd' => $losses->where('method', 'SD')->count(),
            'md' => $losses->where('method', 'MD')->count(),
            'other' => $losses->whereNotIn('method', ['KO', 'TKO', 'UD', 'SD', 'MD'])->count(),
        ];
    }

    /**
     * Get upcoming events for the boxer
     */
    public function upcomingEvents()
    {
        return $this->events()
                    ->where('event_date', '>=', now())
                    ->where('status', 'upcoming')
                    ->orderBy('event_date', 'asc');
    }

    /**
     * Get past events for the boxer
     */
    public function pastEvents()
    {
        return $this->events()
                    ->where('status', 'completed')
                    ->orderBy('event_date', 'desc');
    }

    /**
     * Get published videos featuring the boxer
     */
    public function publishedVideos()
    {
        return $this->videos()
                    ->where('status', 'published')
                    ->orderBy('published_at', 'desc');
    }

    /**
     * Get fight records in reverse chronological order
     */
    public function fightRecords()
    {
        return $this->fights()
                    ->orderBy('fight_date', 'desc');
    }

    /**
     * Get a relationship of boxers by weight class
     */
    public function similarBoxers()
    {
        return self::where('weight_class', $this->weight_class)
                   ->where('id', '!=', $this->id)
                   ->where('is_active', true)
                   ->orderBy('global_ranking', 'asc')
                   ->limit(4);
    }

    /**
     * Get mock data for development
     */
    public static function getMockData($id)
    {
        $boxers = [
            1 => [
                'id' => 1,
                'name' => 'Sulaiman Mussaalo',
                'weight_class' => 'Heavyweight',
                'wins' => 28,
                'losses' => 1,
                'draws' => 1,
                'knockouts' => 18,
                'kos_lost' => 1,
                'age' => 29,
                'height' => '6\'4"',
                'reach' => '78"',
                'stance' => 'Orthodox',
                'hometown' => 'Kampala',
                'country' => 'Uganda',
                'bio' => 'Sulaiman Mussaalo is a rising star in the heavyweight division, known for his explosive power and technical prowess. Born and raised in Kampala, Uganda, he has quickly established himself as one of the most promising fighters in the division.',
                'image_path' => 'assets/images/boxers/boxer-1.jpg',
                'is_active' => true,
                'titles' => ['WBC Heavyweight Champion', 'Uganda National Champion'],
                'years_pro' => 7,
                'status' => 'Professional',
                'global_ranking' => 15,
                'total_fighters_in_division' => 1847,
                'career_start' => 2017,
                'career_end' => null,
                'debut_date' => '2017-03-15'
            ],
            2 => [
                'id' => 2,
                'name' => 'Mike Johnson',
                'weight_class' => 'Middleweight',
                'wins' => 22,
                'losses' => 3,
                'draws' => 0,
                'knockouts' => 15,
                'kos_lost' => 0,
                'age' => 26,
                'height' => '6\'1"',
                'reach' => '74"',
                'stance' => 'Southpaw',
                'hometown' => 'Chicago',
                'country' => 'United States',
                'bio' => 'Mike Johnson is a technically gifted middleweight from Chicago with lightning-fast combinations and exceptional footwork. His southpaw stance and aggressive fighting style have made him a fan favorite and a formidable opponent in the ring.',
                'image_path' => 'assets/images/boxers/boxer-detail-2.png',
                'is_active' => true,
                'titles' => ['WBA Interim Middleweight Champion'],
                'years_pro' => 6,
                'status' => 'Professional',
                'global_ranking' => 323,
                'total_fighters_in_division' => 2248,
                'career_start' => 2018,
                'career_end' => null,
                'debut_date' => '2018-01-14'
            ],
            3 => [
                'id' => 3,
                'name' => 'Carlos Rodriguez',
                'weight_class' => 'Welterweight',
                'wins' => 31,
                'losses' => 4,
                'draws' => 2,
                'knockouts' => 19,
                'kos_lost' => 2,
                'age' => 32,
                'height' => '5\'10"',
                'reach' => '72"',
                'stance' => 'Orthodox',
                'hometown' => 'Mexico City',
                'country' => 'Mexico',
                'bio' => 'Carlos Rodriguez is a seasoned veteran of the welterweight division, bringing years of experience and a relentless fighting spirit to every bout. His combination of power and endurance has earned him respect throughout the boxing world.',
                'image_path' => 'assets/images/boxers/boxer-3.jpg',
                'is_active' => true,
                'titles' => ['WBO Welterweight Champion', 'Mexican National Champion'],
                'years_pro' => 12,
                'status' => 'Professional',
                'global_ranking' => 78,
                'total_fighters_in_division' => 3156,
                'career_start' => 2012,
                'career_end' => null,
                'debut_date' => '2012-08-22'
            ]
        ];

        $defaultBoxer = [
            'id' => $id,
            'name' => 'Unknown Fighter',
            'weight_class' => 'Heavyweight',
            'wins' => 15,
            'losses' => 2,
            'draws' => 1,
            'knockouts' => 8,
            'kos_lost' => 1,
            'age' => 28,
            'height' => '6\'2"',
            'reach' => '76"',
            'stance' => 'Orthodox',
            'hometown' => 'Unknown',
            'country' => 'Unknown',
            'bio' => 'This fighter is making their mark in the boxing world with determination and skill.',
            'image_path' => 'assets/images/boxers/default-boxer.jpg',
            'is_active' => true,
            'titles' => [],
            'years_pro' => 5,
            'status' => 'Professional',
            'global_ranking' => 500,
            'total_fighters_in_division' => 1500,
            'career_start' => 2019,
            'career_end' => null,
            'debut_date' => '2019-06-10'
        ];

        $boxer = $boxers[$id] ?? $defaultBoxer;
        
        // Calculate rates
        $totalFights = $boxer['wins'] + $boxer['losses'] + $boxer['draws'];
        $boxer['win_rate'] = $totalFights > 0 ? round(($boxer['wins'] / $totalFights) * 100) : 0;
        $boxer['knockout_rate'] = $boxer['wins'] > 0 ? round(($boxer['knockouts'] / $boxer['wins']) * 100) : 0;
        
        return (object) $boxer;
    }

    /**
     * Get upcoming events relationship 
     */
    public function upcoming_events()
    {
        return $this->events()
                    ->where('event_date', '>=', now())
                    ->where('status', 'upcoming')
                    ->orderBy('event_date', 'asc');
    }

    /**
     * Get the boxer's titles as an array
     */
    public function getTitlesAttribute($value)
    {
        // If value is null or empty, return empty array
        if (empty($value)) {
            return [];
        }
        
        // If value is already an array, return it
        if (is_array($value)) {
            return $value;
        }
        
        // If value is a string, try to decode it
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }
        
        // Fallback to empty array for any other case
        return [];
    }

    /**
     * Set the boxer's titles as JSON
     */
    public function setTitlesAttribute($value)
    {
        if (is_array($value)) {
            // If it's an array, encode to JSON
            $this->attributes['titles'] = json_encode($value);
        } elseif (is_string($value)) {
            // If it's a string, check if it's empty or valid JSON
            if (empty($value)) {
                // Empty string should be stored as empty JSON array
                $this->attributes['titles'] = json_encode([]);
            } else {
                // Try to decode and re-encode to ensure valid JSON
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->attributes['titles'] = $value;
                } else {
                    // If not valid JSON, treat as empty
                    $this->attributes['titles'] = json_encode([]);
                }
            }
        } else {
            // For any other type (null, etc.), store as empty JSON array
            $this->attributes['titles'] = json_encode([]);
        }
    }
} 
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_fighter',
        'is_admin',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_fighter' => 'boolean',
        'is_admin' => 'boolean',
    ];

    /**
     * Get the fighter profile associated with the user.
     */
    public function fighter()
    {
        return $this->hasOne(Fighter::class);
    }

    /**
     * Get the tickets purchased by the user.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the payments made by the user.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the news articles written by the user.
     */
    public function newsArticles()
    {
        return $this->hasMany(NewsArticle::class, 'author_id');
    }

    /**
     * Check if the user is a fighter.
     */
    public function isFighter()
    {
        return $this->is_fighter;
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Check if the user has a fighter profile.
     */
    public function hasFighterProfile()
    {
        return $this->fighter()->exists();
    }

    /**
     * Check if the user has a verified fighter profile.
     */
    public function hasVerifiedFighterProfile()
    {
        return $this->hasFighterProfile() && 
               $this->fighter->verification_status === Fighter::VERIFICATION_STATUS_VERIFIED;
    }
}
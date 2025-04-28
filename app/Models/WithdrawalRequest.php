<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithdrawalRequest extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The status options for withdrawal requests
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PROCESSED = 'processed';

    /**
     * The payment method options
     */
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_MOBILE_MONEY = 'mobile_money';
    const METHOD_PAYPAL = 'paypal';
    const METHOD_STRIPE = 'stripe';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fighter_id',
        'amount',
        'status',
        'payment_method',
        'payment_details',
        'notes',
        'processed_at',
        'processed_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the fighter that owns the withdrawal request.
     */
    public function fighter()
    {
        return $this->belongsTo(Fighter::class);
    }

    /**
     * Get the admin user who processed the request.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Check if the request is pending.
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if the request is approved.
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if the request is rejected.
     */
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if the request is processed.
     */
    public function isProcessed()
    {
        return $this->status === self::STATUS_PROCESSED;
    }

    /**
     * Mark the request as approved.
     */
    public function approve(User $admin, $notes = null)
    {
        $this->status = self::STATUS_APPROVED;
        $this->processed_by = $admin->id;
        
        if ($notes) {
            $this->notes = $notes;
        }
        
        $this->save();
        
        return $this;
    }

    /**
     * Mark the request as rejected.
     */
    public function reject(User $admin, $notes = null)
    {
        $this->status = self::STATUS_REJECTED;
        $this->processed_by = $admin->id;
        
        if ($notes) {
            $this->notes = $notes;
        }
        
        $this->save();
        
        return $this;
    }

    /**
     * Mark the request as processed.
     */
    public function markAsProcessed(User $admin, $notes = null)
    {
        $this->status = self::STATUS_PROCESSED;
        $this->processed_by = $admin->id;
        $this->processed_at = now();
        
        if ($notes) {
            $this->notes = $notes;
        }
        
        $this->save();
        
        // Update the fighter's commission withdrawn amount
        $this->fighter->commission_withdrawn += $this->amount;
        $this->fighter->save();
        
        return $this;
    }

    /**
     * Scope pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope approved requests.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope processed requests.
     */
    public function scopeProcessed($query)
    {
        return $query->where('status', self::STATUS_PROCESSED);
    }

    /**
     * Scope rejected requests.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }
}
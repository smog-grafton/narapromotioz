<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreamPurchase extends Model
{
    use HasFactory;
    
    // Purchase status constants
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';
    
    // Payment method constants
    const PAYMENT_METHOD_STRIPE = 'stripe';
    const PAYMENT_METHOD_PAYPAL = 'paypal';
    const PAYMENT_METHOD_PESAPAL = 'pesapal';
    const PAYMENT_METHOD_AIRTEL = 'airtel';
    const PAYMENT_METHOD_MTN = 'mtn';
    const PAYMENT_METHOD_MANUAL = 'manual';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stream_id',
        'user_id',
        'transaction_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'payment_details',
        'promo_code_id',
        'fighter_commission_amount',
        'fighter_promotion_id',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'fighter_commission_amount' => 'decimal:2',
        'payment_details' => 'json',
    ];
    
    /**
     * Get the stream associated with the purchase.
     */
    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }
    
    /**
     * Get the user that made the purchase.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the fighter promotion associated with the purchase.
     */
    public function fighterPromotion()
    {
        return $this->belongsTo(FighterPromotion::class);
    }
    
    /**
     * Get the promo code associated with the purchase.
     */
    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }
    
    /**
     * Scope a query to only include completed purchases.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
    
    /**
     * Scope a query to only include pending purchases.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    /**
     * Scope a query to only include failed purchases.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }
    
    /**
     * Scope a query to only include refunded purchases.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRefunded($query)
    {
        return $query->where('status', self::STATUS_REFUNDED);
    }
    
    /**
     * Check if the purchase is completed.
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }
    
    /**
     * Check if the purchase is pending.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }
    
    /**
     * Check if the purchase has failed.
     *
     * @return bool
     */
    public function hasFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }
    
    /**
     * Check if the purchase has been refunded.
     *
     * @return bool
     */
    public function isRefunded()
    {
        return $this->status === self::STATUS_REFUNDED;
    }
    
    /**
     * Mark the purchase as completed.
     *
     * @param  string|null  $transactionId
     * @param  array|null  $paymentDetails
     * @return bool
     */
    public function markAsCompleted($transactionId = null, $paymentDetails = null)
    {
        if ($this->isCompleted()) {
            return true;
        }
        
        if ($transactionId) {
            $this->transaction_id = $transactionId;
        }
        
        if ($paymentDetails) {
            $this->payment_details = $paymentDetails;
        }
        
        $this->status = self::STATUS_COMPLETED;
        
        // If this purchase has a fighter promotion, add the commission to the fighter's earnings
        if ($this->fighter_promotion_id && $this->fighter_commission_amount > 0) {
            $fighterPromotion = $this->fighterPromotion;
            
            if ($fighterPromotion) {
                $fighterPromotion->recordEarnings(
                    $this->fighter_commission_amount,
                    $this->currency,
                    "Stream purchase commission for {$this->stream->title}",
                    $this
                );
            }
        }
        
        return $this->save();
    }
    
    /**
     * Mark the purchase as failed.
     *
     * @param  array|null  $paymentDetails
     * @return bool
     */
    public function markAsFailed($paymentDetails = null)
    {
        if ($this->hasFailed()) {
            return true;
        }
        
        if ($paymentDetails) {
            $this->payment_details = $paymentDetails;
        }
        
        $this->status = self::STATUS_FAILED;
        
        return $this->save();
    }
    
    /**
     * Mark the purchase as refunded.
     *
     * @param  array|null  $paymentDetails
     * @return bool
     */
    public function markAsRefunded($paymentDetails = null)
    {
        if ($this->isRefunded()) {
            return true;
        }
        
        if ($paymentDetails) {
            $this->payment_details = $paymentDetails;
        }
        
        $this->status = self::STATUS_REFUNDED;
        
        // If this purchase has a fighter promotion, reduce the fighter's earnings
        if ($this->fighter_promotion_id && $this->fighter_commission_amount > 0) {
            $fighterPromotion = $this->fighterPromotion;
            
            if ($fighterPromotion) {
                $fighterPromotion->recordEarningsReduction(
                    $this->fighter_commission_amount,
                    $this->currency,
                    "Refund for stream purchase commission for {$this->stream->title}",
                    $this
                );
            }
        }
        
        return $this->save();
    }
    
    /**
     * Get the status badge HTML.
     *
     * @return string
     */
    public function getStatusBadgeAttribute()
    {
        $classes = [
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800',
            self::STATUS_FAILED => 'bg-red-100 text-red-800',
            self::STATUS_REFUNDED => 'bg-purple-100 text-purple-800',
        ];
        
        $labels = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_REFUNDED => 'Refunded',
        ];
        
        $class = $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
        $label = $labels[$this->status] ?? 'Unknown';
        
        return '<span class="px-2 py-1 rounded-full text-xs font-medium ' . $class . '">' . $label . '</span>';
    }
    
    /**
     * Get the payment method badge HTML.
     *
     * @return string
     */
    public function getPaymentMethodBadgeAttribute()
    {
        $classes = [
            self::PAYMENT_METHOD_STRIPE => 'bg-blue-100 text-blue-800',
            self::PAYMENT_METHOD_PAYPAL => 'bg-indigo-100 text-indigo-800',
            self::PAYMENT_METHOD_PESAPAL => 'bg-green-100 text-green-800',
            self::PAYMENT_METHOD_AIRTEL => 'bg-red-100 text-red-800',
            self::PAYMENT_METHOD_MTN => 'bg-yellow-100 text-yellow-800',
            self::PAYMENT_METHOD_MANUAL => 'bg-gray-100 text-gray-800',
        ];
        
        $labels = [
            self::PAYMENT_METHOD_STRIPE => 'Stripe',
            self::PAYMENT_METHOD_PAYPAL => 'PayPal',
            self::PAYMENT_METHOD_PESAPAL => 'Pesapal',
            self::PAYMENT_METHOD_AIRTEL => 'Airtel Money',
            self::PAYMENT_METHOD_MTN => 'MTN Money',
            self::PAYMENT_METHOD_MANUAL => 'Manual',
        ];
        
        $class = $classes[$this->payment_method] ?? 'bg-gray-100 text-gray-800';
        $label = $labels[$this->payment_method] ?? 'Unknown';
        
        return '<span class="px-2 py-1 rounded-full text-xs font-medium ' . $class . '">' . $label . '</span>';
    }
    
    /**
     * Get the formatted amount.
     *
     * @return string
     */
    public function getFormattedAmountAttribute()
    {
        return $this->currency . ' ' . number_format($this->amount, 2);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The payment method options
     */
    const METHOD_STRIPE = 'stripe';
    const METHOD_PAYPAL = 'paypal';
    const METHOD_PESAPAL = 'pesapal';
    const METHOD_AIRTEL_MONEY = 'airtel_money';
    const METHOD_MTN_MONEY = 'mtn_money';
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_CASH = 'cash';

    /**
     * The payment status options
     */
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_PARTIALLY_REFUNDED = 'partially_refunded';

    /**
     * The payment type options
     */
    const TYPE_TICKET = 'ticket';
    const TYPE_STREAM = 'stream';
    const TYPE_SUBSCRIPTION = 'subscription';
    const TYPE_MERCHANDISE = 'merchandise';
    const TYPE_DONATION = 'donation';
    const TYPE_OTHER = 'other';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'payment_type',
        'transaction_id',
        'reference_id',
        'provider_payment_id',
        'status',
        'currency',
        'details',
        'billing_address',
        'refunded_amount',
        'refunded_at',
        'refund_reason',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'details' => 'array',
        'billing_address' => 'array',
        'metadata' => 'array',
        'refunded_at' => 'datetime',
    ];

    /**
     * Get the user who made the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tickets purchased with this payment.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the stream purchases made with this payment.
     */
    public function streamPurchases()
    {
        return $this->hasMany(StreamPurchase::class);
    }

    /**
     * Check if payment is completed.
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if payment is pending.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if payment has failed.
     *
     * @return bool
     */
    public function hasFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Check if payment is cancelled.
     *
     * @return bool
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Check if payment is refunded.
     *
     * @return bool
     */
    public function isRefunded()
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    /**
     * Check if payment is partially refunded.
     *
     * @return bool
     */
    public function isPartiallyRefunded()
    {
        return $this->status === self::STATUS_PARTIALLY_REFUNDED;
    }

    /**
     * Mark payment as completed.
     *
     * @param string|null $providerPaymentId
     * @return $this
     */
    public function markAsCompleted(?string $providerPaymentId = null)
    {
        $this->status = self::STATUS_COMPLETED;
        
        if ($providerPaymentId) {
            $this->provider_payment_id = $providerPaymentId;
        }
        
        $this->save();
        
        return $this;
    }

    /**
     * Mark payment as failed.
     *
     * @param string|null $reason
     * @return $this
     */
    public function markAsFailed(?string $reason = null)
    {
        $this->status = self::STATUS_FAILED;
        
        if ($reason) {
            $metadata = $this->metadata ?: [];
            $metadata['failure_reason'] = $reason;
            $this->metadata = $metadata;
        }
        
        $this->save();
        
        return $this;
    }

    /**
     * Mark payment as cancelled.
     *
     * @param string|null $reason
     * @return $this
     */
    public function markAsCancelled(?string $reason = null)
    {
        $this->status = self::STATUS_CANCELLED;
        
        if ($reason) {
            $metadata = $this->metadata ?: [];
            $metadata['cancellation_reason'] = $reason;
            $this->metadata = $metadata;
        }
        
        $this->save();
        
        return $this;
    }

    /**
     * Process a refund of the payment.
     *
     * @param float $amount
     * @param string|null $reason
     * @return $this
     */
    public function refund($amount, ?string $reason = null)
    {
        // Can only refund completed payments
        if (!$this->isCompleted()) {
            return $this;
        }
        
        // Calculate refunded amount
        $this->refunded_amount = $this->refunded_amount + $amount;
        $this->refunded_at = now();
        $this->refund_reason = $reason;
        
        // Set status based on refund amount
        if ($this->refunded_amount >= $this->amount) {
            $this->status = self::STATUS_REFUNDED;
        } else {
            $this->status = self::STATUS_PARTIALLY_REFUNDED;
        }
        
        $this->save();
        
        return $this;
    }

    /**
     * Get the readable payment method name.
     *
     * @return string
     */
    public function getPaymentMethodName()
    {
        $methods = [
            self::METHOD_STRIPE => 'Credit/Debit Card (Stripe)',
            self::METHOD_PAYPAL => 'PayPal',
            self::METHOD_PESAPAL => 'Pesapal',
            self::METHOD_AIRTEL_MONEY => 'Airtel Money',
            self::METHOD_MTN_MONEY => 'MTN Mobile Money',
            self::METHOD_BANK_TRANSFER => 'Bank Transfer',
            self::METHOD_CASH => 'Cash Payment',
        ];
        
        return $methods[$this->payment_method] ?? ucfirst(str_replace('_', ' ', $this->payment_method));
    }

    /**
     * Get the readable payment type.
     *
     * @return string
     */
    public function getPaymentTypeName()
    {
        $types = [
            self::TYPE_TICKET => 'Event Ticket',
            self::TYPE_STREAM => 'Live Stream',
            self::TYPE_SUBSCRIPTION => 'Subscription',
            self::TYPE_MERCHANDISE => 'Merchandise',
            self::TYPE_DONATION => 'Donation',
            self::TYPE_OTHER => 'Other',
        ];
        
        return $types[$this->payment_type] ?? ucfirst($this->payment_type);
    }

    /**
     * Get the readable payment status.
     *
     * @return string
     */
    public function getStatusName()
    {
        $statuses = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_REFUNDED => 'Refunded',
            self::STATUS_PARTIALLY_REFUNDED => 'Partially Refunded',
        ];
        
        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get the colorClass for the payment status.
     *
     * @return string
     */
    public function getStatusColorClass()
    {
        $colors = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_FAILED => 'danger',
            self::STATUS_CANCELLED => 'secondary',
            self::STATUS_REFUNDED => 'info',
            self::STATUS_PARTIALLY_REFUNDED => 'primary',
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Get pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Get failed payments.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Get ticket payments.
     */
    public function scopeTicketPayments($query)
    {
        return $query->where('payment_type', self::TYPE_TICKET);
    }

    /**
     * Get stream payments.
     */
    public function scopeStreamPayments($query)
    {
        return $query->where('payment_type', self::TYPE_STREAM);
    }
}
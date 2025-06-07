<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TicketTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'width',
        'height',
        'qr_code_position',
        'text_fields',
        'ticket_type',
        'is_active',
        'is_default',
        'user_id',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'qr_code_position' => 'json',
        'text_fields' => 'json',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Get the user who created the template
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tickets that use this template
     */
    public function tickets()
    {
        return $this->hasMany(EventTicket::class);
    }

    /**
     * Get the template image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset("storage/{$this->image_path}") : null;
    }

    /**
     * Get the QR code position as an object with proper attributes
     */
    public function getQrPositionAttribute()
    {
        $position = $this->qr_code_position;
        return (object) [
            'x' => $position['x'] ?? 0,
            'y' => $position['y'] ?? 0,
            'width' => $position['width'] ?? 150,
            'height' => $position['height'] ?? 150,
        ];
    }

    /**
     * Scope a query to only include active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include default templates.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope a query to filter by ticket type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('ticket_type', $type);
    }

    /**
     * Set a template as the default for its type
     */
    public function setAsDefault()
    {
        // Remove default status from other templates of the same type
        self::where('ticket_type', $this->ticket_type)
            ->where('id', '!=', $this->id)
            ->where('is_default', true)
            ->update(['is_default' => false]);

        // Set this template as default
        $this->update(['is_default' => true]);

        return $this;
    }

    /**
     * Generate a ticket image with dynamic data
     */
    public function generateTicketImage($data)
    {
        // In a real implementation, this method would use a library like Intervention Image
        // to load the template image and add dynamic text and QR code to it
        
        // This is a placeholder for the actual implementation
        $ticketData = [
            'template' => $this->name,
            'dimensions' => "{$this->width}x{$this->height}",
            'qr_position' => $this->qr_position,
            'data' => $data,
        ];
        
        return $ticketData;
    }

    /**
     * Get the default template for a ticket type
     */
    public static function getDefaultForType($type)
    {
        return self::where('ticket_type', $type)
                   ->where('is_default', true)
                   ->where('is_active', true)
                   ->first();
    }
} 
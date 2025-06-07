<?php

namespace App\Services;

use App\Models\TicketPurchase;
use App\Models\TicketTemplate;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;
use Illuminate\Support\Str;

class TicketGenerationService
{
    protected $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Generate a ticket for a purchase
     */
    public function generateTicket(TicketPurchase $purchase)
    {
        $ticketPath = $this->createTicketImage($purchase);
        
        $purchase->update([
            'ticket_pdf_path' => $ticketPath,
            'ticket_generated_at' => now(),
        ]);

        return $ticketPath;
    }

    /**
     * Create ticket image with QR code
     */
    protected function createTicketImage(TicketPurchase $purchase)
    {
        // Create base ticket image (800x400 pixels)
        $image = $this->imageManager->create(800, 400);
        
        // Fill with gradient background
        $image->fill('#1a1a2e');
        
        // Add decorative elements
        $this->addTicketDesign($image);
        
        // Add event information
        $this->addEventInfo($image, $purchase);
        
        // Add ticket holder information
        $this->addTicketHolderInfo($image, $purchase);
        
        // Generate and add QR code
        $this->addQRCode($image, $purchase);
        
        // Add ticket number and branding
        $this->addTicketDetails($image, $purchase);
        
        // Save the ticket
        $filename = 'tickets/' . $purchase->order_number . '.png';
        $fullPath = storage_path('app/public/' . $filename);
        
        // Ensure directory exists
        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $image->save($fullPath);
        
        return $filename;
    }

    /**
     * Add decorative design elements to ticket
     */
    protected function addTicketDesign($image)
    {
        // Add header bar
        $image->drawRectangle(0, 0, function ($draw) {
            $draw->size(800, 80);
            $draw->background('#e74c3c');
        });
        
        // Add side accent
        $image->drawRectangle(0, 0, function ($draw) {
            $draw->size(20, 400);
            $draw->background('#f39c12');
        });
        
        // Add bottom accent
        $image->drawRectangle(0, 320, function ($draw) {
            $draw->size(800, 80);
            $draw->background('#2c3e50');
        });
    }

    /**
     * Add event information to ticket
     */
    protected function addEventInfo($image, TicketPurchase $purchase)
    {
        $event = $purchase->ticket->event;
        
        // Event name
        $image->text($event->name, 40, 30, function ($font) {
            $font->size(24);
            $font->color('#ffffff');
        });
        
        // Event date and time
        $eventDateTime = $event->event_date->format('M d, Y') . ' at ' . $event->event_time->format('g:i A');
        $image->text($eventDateTime, 40, 100, function ($font) {
            $font->size(16);
            $font->color('#333333');
        });
        
        // Venue
        if ($event->venue) {
            $image->text($event->venue, 40, 125, function ($font) {
                $font->size(14);
                $font->color('#666666');
            });
        }
        
        // Ticket type
        $image->text($purchase->ticket->name, 40, 160, function ($font) {
            $font->size(18);
            $font->color('#e74c3c');
        });
    }

    /**
     * Add ticket holder information
     */
    protected function addTicketHolderInfo($image, TicketPurchase $purchase)
    {
        // Ticket holder name
        $image->text('Ticket Holder:', 40, 200, function ($font) {
            $font->size(12);
            $font->color('#666666');
        });
        
        $image->text($purchase->ticket_holder_name, 40, 220, function ($font) {
            $font->size(16);
            $font->color('#333333');
        });
        
        // Quantity
        if ($purchase->quantity > 1) {
            $image->text("Quantity: {$purchase->quantity}", 40, 245, function ($font) {
                $font->size(14);
                $font->color('#666666');
            });
        }
    }

    /**
     * Generate and add QR code to ticket
     */
    protected function addQRCode($image, TicketPurchase $purchase)
    {
        // Generate QR code data
        $qrData = json_encode([
            'order_number' => $purchase->order_number,
            'event_id' => $purchase->ticket->event->id,
            'ticket_id' => $purchase->ticket->id,
            'holder_name' => $purchase->ticket_holder_name,
            'quantity' => $purchase->quantity,
            'verification_url' => route('tickets.verify', $purchase->order_number),
        ]);
        
        // Generate QR code using endroid/qr-code Builder
        $builder = new Builder(
            writer: new PngWriter(),
            data: $qrData,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 120,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        );
        
        $result = $builder->build();
        
        // Save QR code temporarily
        $qrPath = storage_path('app/temp/qr_' . Str::random(10) . '.png');
        
        // Ensure temp directory exists
        $tempDir = dirname($qrPath);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        file_put_contents($qrPath, $result->getString());
        
        // Add QR code to ticket
        $qrImage = $this->imageManager->read($qrPath);
        $image->place($qrImage, 'top-right', 650, 90);
        
        // Clean up temporary file
        unlink($qrPath);
        
        // Add QR code label
        $image->text('Scan to verify', 650, 220, function ($font) {
            $font->size(10);
            $font->color('#666666');
        });
    }

    /**
     * Add ticket details and branding
     */
    protected function addTicketDetails($image, TicketPurchase $purchase)
    {
        // Order number
        $image->text('Order #: ' . $purchase->order_number, 40, 340, function ($font) {
            $font->size(12);
            $font->color('#ffffff');
        });
        
        // Price
        $image->text('Price: KES ' . number_format($purchase->grand_total, 2), 300, 340, function ($font) {
            $font->size(12);
            $font->color('#ffffff');
        });
        
        // Branding
        $image->text('NARA PROMOTIONZ', 650, 340, function ($font) {
            $font->size(14);
            $font->color('#f39c12');
        });
        
        // Purchase date
        $image->text('Purchased: ' . $purchase->created_at->format('M d, Y'), 40, 365, function ($font) {
            $font->size(10);
            $font->color('#cccccc');
        });
    }

    /**
     * Generate QR code for ticket verification
     */
    public function generateQRCode(TicketPurchase $purchase)
    {
        $qrData = json_encode([
            'order_number' => $purchase->order_number,
            'verification_url' => route('tickets.verify', $purchase->order_number),
        ]);

        $builder = new Builder(
            writer: new PngWriter(),
            data: $qrData,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 200,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        );
        
        $result = $builder->build();
        
        return $result->getString();
    }

    /**
     * Create ticket template for bulk generation
     */
    public function createTicketTemplate($eventId)
    {
        // This method can be used to create reusable templates
        // for events with consistent branding
        return null;
    }

    /**
     * Generate tickets for multiple purchases
     */
    public function generateBulkTickets(array $purchaseIds)
    {
        $results = [];
        
        foreach ($purchaseIds as $purchaseId) {
            $purchase = TicketPurchase::find($purchaseId);
            
            if ($purchase) {
                try {
                    $ticketPath = $this->generateTicket($purchase);
                    $results[$purchaseId] = [
                        'success' => true,
                        'ticket_path' => $ticketPath,
                    ];
                } catch (\Exception $e) {
                    $results[$purchaseId] = [
                        'success' => false,
                        'error' => $e->getMessage(),
                    ];
                }
            }
        }
        
        return $results;
    }

    /**
     * Regenerate ticket (useful for template updates)
     */
    public function regenerateTicket(TicketPurchase $purchase)
    {
        // Delete old files if they exist
        if ($purchase->qr_code) {
            Storage::disk('public')->delete($purchase->qr_code);
        }
        
        if ($purchase->ticket_pdf_path) {
            Storage::disk('public')->delete($purchase->ticket_pdf_path);
        }

        // Generate new ticket
        return $this->generateTicket($purchase);
    }
} 
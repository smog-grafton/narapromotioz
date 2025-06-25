@extends('layouts.app')

@section('title', 'Download Ticket')

@section('content')
<!-- Page Banner -->
<x-page-banner 
    title="Download Ticket" 
    subtitle="Your ticket is ready for download and printing"
    :breadcrumbs="[
        ['title' => 'Dashboard', 'url' => route('dashboard')],
        ['title' => 'My Tickets', 'url' => route('tickets.my-tickets')],
        ['title' => 'Download']
    ]" />

<div class="tickets-container">
    <div class="container">
        <div class="ticket-download">
            <!-- Download Header -->
            <div class="download-header">
                <div class="header-navigation">
                    <a href="{{ route('tickets.my-tickets') }}" class="nav-link">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to My Tickets
                    </a>
                </div>
                <div class="header-info">
                    <h1 class="download-title">Ticket Information</h1>
                    <p class="download-subtitle">Print or save your ticket for the event</p>
                </div>
            </div>

            <!-- Ticket Preview Card -->
            <div class="download-card">
                <div class="download-card-header">
                    <div class="card-status">
                        <span class="status-badge {{ strtolower($purchase->status) }}">
                            {{ ucfirst($purchase->status) }}
                        </span>
                    </div>
                    <div class="card-reference">
                        <span class="reference-label">Reference:</span>
                        <span class="reference-value">{{ $purchase->reference_number }}</span>
                    </div>
                </div>

                <div class="download-card-content">
                    <!-- Event Information -->
                    <div class="event-section">
                        <div class="event-image-container">
                            @if($purchase->boxingEvent && $purchase->boxingEvent->image)
                                <img src="{{ asset('storage/' . $purchase->boxingEvent->image) }}" 
                                     alt="{{ $purchase->boxingEvent->title }}" 
                                     class="event-image">
                            @else
                                <div class="event-image placeholder">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="event-details">
                            <h2 class="event-title">{{ $purchase->boxingEvent->title ?? 'Unknown Event' }}</h2>
                            
                            <div class="event-meta">
                                <div class="meta-item">
                                    <svg class="meta-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                                    </svg>
                                    <span class="meta-label">Date & Time:</span>
                                    <span class="meta-value">
                                        @if($purchase->boxingEvent)
                                            {{ $purchase->boxingEvent->event_date->format('l, M j, Y \a\t g:i A') }}
                                        @else
                                            TBD
                                        @endif
                                    </span>
                                </div>

                                <div class="meta-item">
                                    <svg class="meta-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                    </svg>
                                    <span class="meta-label">Venue:</span>
                                    <span class="meta-value">{{ $purchase->boxingEvent->location ?? $purchase->boxingEvent->city . ', ' . $purchase->boxingEvent->country }}</span>
                                </div>

                                <div class="meta-item">
                                    <svg class="meta-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M20,8H4V6H20M20,18H4V12H20V18Z"/>
                                    </svg>
                                    <span class="meta-label">Quantity:</span>
                                    <span class="meta-value">{{ $purchase->quantity }} ticket(s)</span>
                                </div>

                                <div class="meta-item">
                                    <svg class="meta-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                                    </svg>
                                    <span class="meta-label">Total Paid:</span>
                                    <span class="meta-value">${{ number_format($purchase->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Holder Information -->
                    <div class="holder-section">
                        <h3 class="section-title">Ticket Holder Information</h3>
                        <div class="holder-grid">
                            <div class="holder-item">
                                <span class="holder-label">Name:</span>
                                <span class="holder-value">{{ $purchase->user->name }}</span>
                            </div>
                            <div class="holder-item">
                                <span class="holder-label">Email:</span>
                                <span class="holder-value">{{ $purchase->user->email }}</span>
                            </div>
                            <div class="holder-item">
                                <span class="holder-label">Purchase Date:</span>
                                <span class="holder-value">{{ $purchase->created_at->format('M j, Y g:i A') }}</span>
                            </div>
                            <div class="holder-item">
                                <span class="holder-label">Payment Status:</span>
                                <span class="holder-value">
                                    <span class="payment-badge {{ strtolower($purchase->payment_status) }}">
                                        {{ ucfirst($purchase->payment_status) }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Section (if available) -->
                    @if(isset($qrCode))
                    <div class="qr-section">
                        <h3 class="section-title">Digital Ticket</h3>
                        <div class="qr-container">
                            <div class="qr-code">
                                {!! $qrCode !!}
                            </div>
                            <div class="qr-info">
                                <p class="qr-text">
                                    Present this QR code at the venue entrance for quick check-in.
                                </p>
                                <p class="qr-reference">
                                    Reference: <strong>{{ $purchase->reference_number }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Important Notice -->
                    <div class="notice-section">
                        <div class="notice-card">
                            <div class="notice-header">
                                <svg class="notice-icon" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                                </svg>
                                <h4 class="notice-title">Important Information</h4>
                            </div>
                            <div class="notice-content">
                                <ul class="notice-list">
                                    <li>Please arrive at the venue at least 30 minutes before the event starts</li>
                                    <li>Bring a valid photo ID that matches the ticket holder's name</li>
                                    <li>Present either the printed ticket or the QR code on your mobile device</li>
                                    <li>Tickets are non-refundable and non-transferable</li>
                                    <li>For any queries, contact our support team with your reference number</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Download Actions -->
                    <div class="download-actions">
                        <button onclick="window.print()" class="btn btn-primary">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
                            </svg>
                            Print Ticket
                        </button>

                        @if(Route::has('tickets.download.pdf'))
                        <a href="{{ route('tickets.download.pdf', $purchase) }}" class="btn btn-success">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M5 20h14v-2H5v2zM19 9h-4V3H9v6H5l7 7 7-7z"/>
                            </svg>
                            Download PDF
                        </a>
                        @endif

                        <button onclick="shareTicket()" class="btn btn-secondary">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92-1.31-2.92-2.92-2.92z"/>
                            </svg>
                            Share
                        </button>

                        <a href="{{ route('tickets.index') }}" class="btn btn-outline">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20,8H4V6H20M20,18H4V12H20V18Z"/>
                            </svg>
                            View All Tickets
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function shareTicket() {
    if (navigator.share) {
        navigator.share({
            title: 'Boxing Event Ticket - {{ $purchase->boxingEvent->title ?? "Unknown Event" }}',
            text: 'Check out my ticket for this amazing boxing event!',
            url: window.location.href
        }).catch(err => console.log('Error sharing:', err));
    } else {
        // Fallback for browsers that don't support Web Share API
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            alert('Ticket link copied to clipboard!');
        }).catch(err => {
            console.log('Error copying to clipboard:', err);
            // Fallback: show the URL in a prompt
            prompt('Copy this link to share your ticket:', url);
        });
    }
}

// Print-specific styles
const printStyles = `
    <style>
        @media print {
            .download-header .header-navigation,
            .download-actions {
                display: none !important;
            }
            
            .download-card {
                box-shadow: none !important;
                border: 2px solid #333 !important;
                margin: 0 !important;
            }
            
            .tickets-container {
                background: white !important;
                color: black !important;
            }
            
            .event-title {
                color: black !important;
            }
            
            .meta-value, .holder-value {
                color: black !important;
            }
            
            body {
                background: white !important;
            }
        }
    </style>
`;

document.head.insertAdjacentHTML('beforeend', printStyles);
</script>

@push('styles')
<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    .download-header .header-navigation,
    .download-actions {
        display: none !important;
    }
    
    .tickets-container {
        background: white !important;
        color: black !important;
        padding: 20px !important;
    }
    
    .download-card {
        box-shadow: none !important;
        border: 2px solid #333 !important;
        break-inside: avoid !important;
    }
    
    .event-title,
    .section-title,
    .meta-value,
    .holder-value {
        color: black !important;
    }
    
    .qr-code svg {
        max-width: 150px !important;
        max-height: 150px !important;
    }
}
</style>
@endpush
@endsection

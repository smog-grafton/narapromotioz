@extends('layouts.app')

@section('title', 'Ticket Confirmation')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h1 class="border-start border-success border-5 ps-3">TICKET CONFIRMATION</h1>
            <p class="text-muted">Your ticket details for the event are below.</p>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Success Message -->
            <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                <i class="fas fa-check-circle fa-2x me-3"></i>
                <div>
                    <strong>Payment Successful!</strong> Your ticket purchase has been confirmed.
                </div>
            </div>
            
            <!-- Ticket Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-ticket-alt me-2"></i> Ticket Information
                    </h4>
                </div>
                <div class="card-body p-4">
                    <!-- QR Code Section -->
                    <div class="text-center mb-4">
                        <div class="mb-3" style="max-width: 200px; margin: 0 auto;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($ticket->ticket_number) }}" 
                                 class="img-fluid border" alt="QR Code">
                        </div>
                        <h5 class="mb-1">{{ $ticket->ticket_number }}</h5>
                        <p class="text-muted small">Scan this QR code at the venue entrance</p>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Event Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-uppercase mb-3">Event Details</h5>
                            <p class="mb-1"><strong>{{ $ticket->event->title }}</strong></p>
                            <p class="mb-1">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                {{ $ticket->event->event_date->format('F j, Y') }}
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-clock me-2 text-primary"></i>
                                {{ $ticket->event->event_date->format('g:i A') }}
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                {{ $ticket->event->location }}
                            </p>
                        </div>
                        
                        <div class="col-md-6 mt-4 mt-md-0">
                            <h5 class="text-uppercase mb-3">Ticket Details</h5>
                            <p class="mb-1">
                                <strong>Ticket Type:</strong> {{ $ticket->ticket_type ?? 'Standard' }}
                            </p>
                            <p class="mb-1">
                                <strong>Price:</strong> ${{ number_format($ticket->amount_paid, 2) }}
                            </p>
                            <p class="mb-1">
                                <strong>Purchase Date:</strong> {{ $ticket->created_at->format('M d, Y') }}
                            </p>
                            <p class="mb-0">
                                <strong>Status:</strong> 
                                <span class="badge bg-success">PAID</span>
                            </p>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Important Notes -->
                    <div class="alert alert-info p-3 mb-0">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i> Important Information:
                        </h6>
                        <ul class="mb-0 ps-3">
                            <li>Please arrive at least 30 minutes before the event starts.</li>
                            <li>Bring a valid ID that matches the ticket purchaser's name.</li>
                            <li>This ticket is non-transferable and non-refundable.</li>
                            <li>No re-entry is allowed once you exit the venue.</li>
                            <li>Follow all venue rules and regulations.</li>
                        </ul>
                    </div>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between">
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i> Print Ticket
                    </button>
                    <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to My Tickets
                    </a>
                </div>
            </div>
            
            <!-- Add to Calendar Section -->
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Don't Miss the Event!</h5>
                </div>
                <div class="card-body p-4">
                    <p>Add this event to your calendar so you don't miss it!</p>
                    
                    <div class="d-flex flex-wrap gap-2">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fab fa-google me-2"></i> Google Calendar
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fab fa-apple me-2"></i> Apple Calendar
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-calendar-alt me-2"></i> Outlook
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
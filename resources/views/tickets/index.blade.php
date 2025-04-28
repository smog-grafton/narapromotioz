@extends('layouts.app')

@section('title', 'My Tickets')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h1 class="border-start border-primary border-5 ps-3">MY TICKETS</h1>
            <p class="text-muted">Manage your purchased tickets for upcoming boxing events.</p>
        </div>
    </div>
    
    <!-- Tickets List Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    @if($tickets->isEmpty())
                        <div class="p-5 text-center">
                            <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                            <h4>No Tickets Found</h4>
                            <p class="text-muted">You haven't purchased any tickets yet.</p>
                            <a href="{{ route('events.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-calendar-alt me-2"></i> Browse Events
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Ticket #</th>
                                        <th>Event</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td>{{ $ticket->ticket_number }}</td>
                                            <td>
                                                <a href="{{ route('events.show', $ticket->event) }}" class="text-decoration-none">
                                                    {{ $ticket->event->title }}
                                                </a>
                                            </td>
                                            <td>{{ $ticket->event->event_date->format('M d, Y - g:i A') }}</td>
                                            <td>${{ number_format($ticket->amount_paid, 2) }}</td>
                                            <td>
                                                @if($ticket->payment_status === 'paid')
                                                    <span class="badge bg-success">PAID</span>
                                                @elseif($ticket->payment_status === 'pending')
                                                    <span class="badge bg-warning text-dark">PENDING</span>
                                                @else
                                                    <span class="badge bg-danger">FAILED</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @if($ticket->payment_status === 'paid')
                                                        <!-- View Ticket Button -->
                                                        <a href="{{ route('tickets.confirmation', $ticket) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        
                                                        <!-- Download Ticket Button -->
                                                        <button type="button" class="btn btn-sm btn-outline-secondary">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Live Events Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="border-start border-danger border-5 ps-3 mb-4">LIVE STREAMING ACCESS</h2>
            
            @php
                $streamAccess = auth()->user()->streamAccess()->with('event')->get();
            @endphp
            
            @if($streamAccess->isEmpty())
                <div class="card shadow-sm">
                    <div class="card-body p-5 text-center">
                        <i class="fas fa-tv fa-3x text-muted mb-3"></i>
                        <h4>No Streaming Access</h4>
                        <p class="text-muted">You haven't purchased access to any live streams yet.</p>
                        <a href="{{ route('events.index') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-calendar-alt me-2"></i> Browse Events
                        </a>
                    </div>
                </div>
            @else
                <div class="row">
                    @foreach($streamAccess as $access)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                @if($access->event->event_banner)
                                    <img src="{{ $access->event->event_banner }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $access->event->title }}">
                                @else
                                    <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 180px;">
                                        <h5>{{ $access->event->title }}</h5>
                                    </div>
                                @endif
                                
                                <div class="card-body">
                                    <h5 class="card-title">{{ $access->event->title }}</h5>
                                    <p class="text-muted">
                                        <i class="fas fa-calendar-alt me-2"></i> {{ $access->event->event_date->format('M d, Y - g:i A') }}
                                    </p>
                                    
                                    @if($access->event->is_live)
                                        <div class="alert alert-success d-flex align-items-center" role="alert">
                                            <span class="live-badge me-2"></span> 
                                            <div>This event is currently live!</div>
                                        </div>
                                    @elseif($access->event->event_date->isPast())
                                        <div class="alert alert-secondary" role="alert">
                                            This event has ended.
                                        </div>
                                    @else
                                        <div class="alert alert-info" role="alert">
                                            Stream will be available on {{ $access->event->event_date->format('M d, Y') }}
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="card-footer bg-white border-top-0">
                                    @if($access->event->is_live)
                                        <a href="{{ route('events.stream', $access->event) }}" class="btn btn-danger w-100">
                                            <i class="fas fa-play-circle me-2"></i> WATCH STREAM
                                        </a>
                                    @elseif(!$access->event->event_date->isPast())
                                        <a href="{{ route('events.show', $access->event) }}" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-info-circle me-2"></i> EVENT DETAILS
                                        </a>
                                    @else
                                        <button class="btn btn-outline-secondary w-100" disabled>
                                            <i class="fas fa-history me-2"></i> EVENT ENDED
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
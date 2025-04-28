@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="container py-5">
    <!-- Event Header -->
    <div class="mb-5">
        <div class="card shadow position-relative overflow-hidden">
            <div class="event-banner-container">
                @if($event->event_banner)
                    <div class="event-banner" style="background-image: url('{{ $event->event_banner }}')"></div>
                @else
                    <div class="event-banner-placeholder d-flex align-items-center justify-content-center">
                        <h2 class="text-white">{{ $event->title }}</h2>
                    </div>
                @endif
                
                <div class="event-banner-overlay"></div>
                
                <div class="event-header-content">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-5 text-white mb-2">{{ $event->title }}</h1>
                            <div class="d-flex flex-wrap text-white mb-3">
                                <div class="me-4 mb-2">
                                    <i class="fas fa-calendar-alt me-2"></i> 
                                    {{ $event->event_date->format('F j, Y') }}
                                </div>
                                <div class="me-4 mb-2">
                                    <i class="fas fa-clock me-2"></i> 
                                    {{ $event->event_date->format('g:i A') }}
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-map-marker-alt me-2"></i> 
                                    {{ $event->location }}
                                </div>
                            </div>
                            
                            @if($event->is_live)
                                <div class="alert alert-danger d-inline-block px-3 py-2">
                                    <span class="live-indicator me-2"></span> LIVE NOW
                                </div>
                            @elseif($event->event_date->isPast())
                                <div class="alert alert-secondary d-inline-block px-3 py-2">
                                    EVENT COMPLETED
                                </div>
                            @else
                                <div class="alert alert-light d-inline-block px-3 py-2">
                                    <i class="fas fa-clock me-2"></i>
                                    {{ now()->diffForHumans($event->event_date, ['parts' => 2]) }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-4 mt-4 mt-md-0">
                            <div class="card p-3 shadow-sm">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Ticket Price:</span>
                                        <span class="fw-bold">${{ number_format($event->ticket_price, 2) }}</span>
                                    </div>
                                    
                                    @if($event->event_date->isFuture())
                                        <div class="d-flex justify-content-between">
                                            <span>Available Tickets:</span>
                                            <span class="fw-bold">{{ $event->available_tickets }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($event->is_live)
                                    <a href="{{ route('events.stream', $event) }}" class="btn btn-danger">
                                        <i class="fas fa-play-circle me-2"></i> WATCH LIVE STREAM
                                    </a>
                                @elseif($event->event_date->isFuture())
                                    <a href="{{ route('tickets.create', $event) }}" class="btn btn-primary">
                                        <i class="fas fa-ticket-alt me-2"></i> BUY TICKETS
                                    </a>
                                @else
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-history me-2"></i> EVENT ENDED
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Event Details -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <!-- Event Description -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Event Description</h5>
                </div>
                <div class="card-body">
                    <div class="event-description">
                        {!! $event->description !!}
                    </div>
                </div>
            </div>
            
            <!-- Fight Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Fight Card</h5>
                </div>
                
                <div class="card-body p-0">
                    @if($event->fights->isEmpty())
                        <div class="p-4 text-center">
                            <p class="text-muted mb-0">Fight card will be announced soon.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($event->fights->sortBy('fight_order') as $fight)
                                <div class="list-group-item p-3 {{ $fight->is_main_event ? 'main-event' : '' }}">
                                    @if($fight->is_main_event)
                                        <div class="text-end">
                                            <span class="badge bg-danger px-3 py-2 mb-2">MAIN EVENT</span>
                                        </div>
                                    @endif
                                    
                                    <div class="row align-items-center">
                                        <!-- Fighter One -->
                                        <div class="col-5 text-center">
                                            <div class="mb-2">
                                                @if($fight->fighterOne->profile_image)
                                                    <img src="{{ $fight->fighterOne->profile_image }}" 
                                                        class="fighter-thumbnail rounded-circle" 
                                                        alt="{{ $fight->fighterOne->full_name }}">
                                                @else
                                                    <div class="fighter-thumbnail-placeholder rounded-circle mx-auto">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <h5 class="mb-1">
                                                <a href="{{ route('fighters.show', $fight->fighterOne) }}" class="text-decoration-none">
                                                    {{ $fight->fighterOne->full_name }}
                                                </a>
                                            </h5>
                                            <div class="text-muted small">
                                                <span class="text-success">{{ $fight->fighterOne->wins }}W</span> - 
                                                <span class="text-danger">{{ $fight->fighterOne->losses }}L</span> - 
                                                <span class="text-primary">{{ $fight->fighterOne->draws }}D</span>
                                            </div>
                                            <div class="mt-1">
                                                <span class="badge bg-light text-dark">
                                                    {{ $fight->fighterOne->weight_class }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- VS -->
                                        <div class="col-2 text-center">
                                            <div class="vs-circle">VS</div>
                                            
                                            @if($fight->championship_title)
                                                <div class="mt-2">
                                                    <span class="badge bg-warning text-dark">{{ $fight->championship_title }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($fight->rounds)
                                                <div class="mt-2 small text-muted">
                                                    {{ $fight->rounds }} Rounds
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Fighter Two -->
                                        <div class="col-5 text-center">
                                            <div class="mb-2">
                                                @if($fight->fighterTwo->profile_image)
                                                    <img src="{{ $fight->fighterTwo->profile_image }}" 
                                                        class="fighter-thumbnail rounded-circle" 
                                                        alt="{{ $fight->fighterTwo->full_name }}">
                                                @else
                                                    <div class="fighter-thumbnail-placeholder rounded-circle mx-auto">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <h5 class="mb-1">
                                                <a href="{{ route('fighters.show', $fight->fighterTwo) }}" class="text-decoration-none">
                                                    {{ $fight->fighterTwo->full_name }}
                                                </a>
                                            </h5>
                                            <div class="text-muted small">
                                                <span class="text-success">{{ $fight->fighterTwo->wins }}W</span> - 
                                                <span class="text-danger">{{ $fight->fighterTwo->losses }}L</span> - 
                                                <span class="text-primary">{{ $fight->fighterTwo->draws }}D</span>
                                            </div>
                                            <div class="mt-1">
                                                <span class="badge bg-light text-dark">
                                                    {{ $fight->fighterTwo->weight_class }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($event->event_date->isPast() && $fight->result_method)
                                        <div class="fight-result mt-3 p-2 text-center">
                                            <div class="mb-1">
                                                <span class="badge {{ $fight->winner_id ? 'bg-success' : 'bg-primary' }} px-3 py-2">
                                                    @if($fight->winner_id)
                                                        {{ $fight->winner->full_name }} WINS
                                                    @else
                                                        DRAW
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="text-muted">
                                                {{ $fight->result_method }} 
                                                @if($fight->result_round)
                                                    in Round {{ $fight->result_round }}
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Event Gallery (if any) -->
            @if($event->images && count($event->images) > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Event Gallery</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($event->images as $image)
                                <div class="col-md-4 col-6">
                                    <a href="{{ $image }}" class="gallery-item" data-lightbox="event-gallery">
                                        <img src="{{ $image }}" class="img-fluid rounded" alt="Event Image">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-lg-4 mt-4 mt-lg-0">
            <!-- Event Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Event Details</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex">
                            <div class="me-3">
                                <i class="fas fa-calendar-alt fa-lg text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Date</div>
                                <div>{{ $event->event_date->format('F j, Y') }}</div>
                            </div>
                        </li>
                        <li class="list-group-item d-flex">
                            <div class="me-3">
                                <i class="fas fa-clock fa-lg text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Time</div>
                                <div>{{ $event->event_date->format('g:i A') }}</div>
                            </div>
                        </li>
                        <li class="list-group-item d-flex">
                            <div class="me-3">
                                <i class="fas fa-map-marker-alt fa-lg text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Venue</div>
                                <div>{{ $event->location }}</div>
                            </div>
                        </li>
                        <li class="list-group-item d-flex">
                            <div class="me-3">
                                <i class="fas fa-ticket-alt fa-lg text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Ticket Price</div>
                                <div>${{ number_format($event->ticket_price, 2) }}</div>
                            </div>
                        </li>
                        @if($event->promoter)
                            <li class="list-group-item d-flex">
                                <div class="me-3">
                                    <i class="fas fa-user-tie fa-lg text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Promoter</div>
                                    <div>{{ $event->promoter }}</div>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
                
                @if($event->event_date->isFuture())
                    <div class="card-footer bg-light">
                        <a href="#" class="text-decoration-none" onclick="addToCalendar(event)">
                            <i class="fas fa-calendar-plus me-2"></i> Add to Calendar
                        </a>
                    </div>
                @endif
            </div>
            
            <!-- Venue Map -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Venue Location</h5>
                </div>
                <div class="card-body p-0">
                    <div class="venue-map">
                        <!-- Map placeholder - You would integrate Google Maps or another map provider here -->
                        <div class="text-center py-5">
                            <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                            <h6>{{ $event->location }}</h6>
                            <p class="text-muted small mb-0">Map loading...</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="https://maps.google.com/?q={{ urlencode($event->location) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-directions me-2"></i> GET DIRECTIONS
                    </a>
                </div>
            </div>
            
            <!-- Live Stream Access -->
            @if($event->is_streamed && $event->stream_price > 0)
                <div class="card shadow-sm mb-4 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-tv me-2"></i> Live Stream Access
                        </h5>
                    </div>
                    <div class="card-body">
                        <p>Can't attend in person? Watch the event live from anywhere!</p>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Stream Price:</span>
                            <span class="fw-bold">${{ number_format($event->stream_price, 2) }}</span>
                        </div>
                        
                        @if($event->is_live)
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <span class="live-indicator me-2"></span>
                                <div>The event is streaming LIVE right now!</div>
                            </div>
                            
                            <a href="{{ route('events.stream', $event) }}" class="btn btn-danger w-100">
                                <i class="fas fa-play-circle me-2"></i> WATCH STREAM
                            </a>
                        @elseif($event->event_date->isFuture())
                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle me-2"></i> 
                                Stream will be available on {{ $event->event_date->format('M d, Y') }}
                            </div>
                            
                            <a href="{{ route('streams.purchase', $event) }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-shopping-cart me-2"></i> BUY STREAM ACCESS
                            </a>
                        @else
                            <div class="alert alert-secondary" role="alert">
                                <i class="fas fa-calendar-times me-2"></i> This event has ended.
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- Share Event -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Share This Event</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-success">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="btn btn-outline-secondary">
                            <i class="fas fa-envelope"></i>
                        </a>
                        <button class="btn btn-outline-dark" onclick="copyEventLink()">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Similar Events -->
    <div class="row">
        <div class="col-12">
            <h3 class="border-start border-primary border-5 ps-3 mb-4">More Events</h3>
            
            @php
                $similarEvents = \App\Models\Event::where('id', '!=', $event->id)
                    ->where('event_date', '>', now())
                    ->take(3)
                    ->get();
            @endphp
            
            @if($similarEvents->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No upcoming events scheduled at this time.
                </div>
            @else
                <div class="row">
                    @foreach($similarEvents as $similarEvent)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm event-card">
                                <div class="position-relative">
                                    @if($similarEvent->event_banner)
                                        <img src="{{ $similarEvent->event_banner }}" class="card-img-top event-image" alt="{{ $similarEvent->title }}">
                                    @else
                                        <div class="event-image-placeholder bg-light d-flex justify-content-center align-items-center">
                                            <h5 class="text-muted">{{ $similarEvent->title }}</h5>
                                        </div>
                                    @endif
                                    
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <div class="event-date-badge-sm shadow-sm">
                                            <div class="event-date-month-sm">
                                                {{ $similarEvent->event_date->format('M') }}
                                            </div>
                                            <div class="event-date-day-sm">
                                                {{ $similarEvent->event_date->format('d') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <h5 class="card-title">{{ $similarEvent->title }}</h5>
                                    
                                    <div class="small text-muted mb-3">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            <span>{{ $similarEvent->event_date->format('F j, Y') }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            <span>{{ $similarEvent->location }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-white border-top-0">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('events.show', $similarEvent) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-info-circle me-1"></i> DETAILS
                                        </a>
                                        <a href="{{ route('tickets.create', $similarEvent) }}" class="btn btn-primary">
                                            <i class="fas fa-ticket-alt me-1"></i> TICKETS
                                        </a>
                                    </div>
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

@section('styles')
<style>
    .event-banner-container {
        position: relative;
    }
    
    .event-banner {
        height: 400px;
        background-size: cover;
        background-position: center;
    }
    
    .event-banner-placeholder {
        height: 400px;
        background: linear-gradient(135deg, #1d3557, #457b9d);
    }
    
    .event-banner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.8));
    }
    
    .event-header-content {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 2rem;
    }
    
    .fighter-thumbnail {
        width: 80px;
        height: 80px;
        object-fit: cover;
    }
    
    .fighter-thumbnail-placeholder {
        width: 80px;
        height: 80px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }
    
    .vs-circle {
        display: inline-block;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e63946;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin: 0 auto;
    }
    
    .main-event {
        border-left: 5px solid #e63946;
        background-color: rgba(230, 57, 70, 0.05);
    }
    
    .event-card {
        transition: transform 0.2s;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .event-card:hover {
        transform: translateY(-5px);
    }
    
    .event-image {
        height: 200px;
        object-fit: cover;
    }
    
    .event-image-placeholder {
        height: 200px;
    }
    
    .gallery-item {
        display: block;
        transition: transform 0.2s;
    }
    
    .gallery-item:hover {
        transform: scale(1.03);
    }
    
    .event-date-badge-sm {
        background-color: white;
        border-radius: 6px;
        overflow: hidden;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        width: 50px;
    }
    
    .event-date-month-sm {
        background-color: #e63946;
        color: white;
        padding: 3px;
        font-weight: bold;
        font-size: 0.8rem;
        text-transform: uppercase;
    }
    
    .event-date-day-sm {
        color: #1d3557;
        font-size: 1.2rem;
        font-weight: bold;
        padding: 3px;
    }
    
    .fight-result {
        background-color: rgba(0,0,0,0.05);
        border-radius: 5px;
    }
    
    .live-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        background-color: #ff0000;
        border-radius: 50%;
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% {
            opacity: 1;
        }
        50% {
            opacity: 0.4;
        }
        100% {
            opacity: 1;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    function copyEventLink() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            alert('Event link copied to clipboard!');
        });
    }
    
    function addToCalendar(e) {
        e.preventDefault();
        
        const eventTitle = "{{ $event->title }}";
        const eventLocation = "{{ $event->location }}";
        const eventStart = "{{ $event->event_date->format('Y-m-d\\TH:i:s') }}";
        const eventEnd = "{{ $event->event_date->addHours(3)->format('Y-m-d\\TH:i:s') }}";
        const eventDescription = "{{ strip_tags($event->description) }}";
        
        // Google Calendar
        const googleCalendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(eventTitle)}&location=${encodeURIComponent(eventLocation)}&dates=${encodeURIComponent(eventStart.replace(/[-:]/g, ''))}.000Z/${encodeURIComponent(eventEnd.replace(/[-:]/g, ''))}.000Z&details=${encodeURIComponent(eventDescription)}`;
        
        window.open(googleCalendarUrl, '_blank');
    }
</script>
@endsection
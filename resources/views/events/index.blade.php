@extends('layouts.app')

@section('title', 'Boxing Events')

@section('content')
<div class="container py-5">
    <!-- Hero Banner -->
    <div class="position-relative mb-5">
        <div class="bg-dark rounded" style="height: 300px; background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.8)), url('https://images.unsplash.com/photo-1579269097383-7fe7212c2c92') center/cover no-repeat;">
        </div>
        <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-4">
            <h1 class="display-4 fw-bold mb-3">BOXING EVENTS</h1>
            <p class="lead">Experience the thrill of live boxing with Nara Promotionz.</p>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-md-8 mb-3 mb-md-0">
            <form action="{{ route('events.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search events..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        
        <div class="col-md-4">
            <select name="filter" class="form-select" onchange="window.location = this.value;">
                <option value="{{ route('events.index') }}" {{ request()->is('events') && !request('filter') ? 'selected' : '' }}>
                    All Events
                </option>
                <option value="{{ route('events.index', ['filter' => 'upcoming']) }}" {{ request('filter') == 'upcoming' ? 'selected' : '' }}>
                    Upcoming Events
                </option>
                <option value="{{ route('events.index', ['filter' => 'past']) }}" {{ request('filter') == 'past' ? 'selected' : '' }}>
                    Past Events
                </option>
            </select>
        </div>
    </div>
    
    <!-- Live Event Alert -->
    @php
        $liveEvent = \App\Models\Event::where('is_live', true)->first();
    @endphp
    
    @if($liveEvent)
        <div class="alert alert-danger mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <span class="live-indicator"></span>
                </div>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">LIVE NOW: {{ $liveEvent->title }}</h5>
                    <p class="mb-0">{{ $liveEvent->description }}</p>
                </div>
                <div>
                    <a href="{{ route('events.stream', $liveEvent) }}" class="btn btn-danger">
                        <i class="fas fa-play-circle me-2"></i> WATCH NOW
                    </a>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Featured/Upcoming Event -->
    @php
        $featuredEvent = \App\Models\Event::where('event_date', '>', now())
            ->where('is_featured', true)
            ->orderBy('event_date', 'asc')
            ->first() ?? \App\Models\Event::where('event_date', '>', now())
            ->orderBy('event_date', 'asc')
            ->first();
    @endphp
    
    @if($featuredEvent && request('filter') != 'past')
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow position-relative">
                    <div class="row g-0">
                        <div class="col-md-6">
                            @if($featuredEvent->event_banner)
                                <div class="h-100" style="min-height: 350px; background: url('{{ $featuredEvent->event_banner }}') center/cover no-repeat;">
                                </div>
                            @else
                                <div class="bg-dark d-flex align-items-center justify-content-center h-100" style="min-height: 350px;">
                                    <h3 class="text-white">{{ $featuredEvent->title }}</h3>
                                </div>
                            @endif
                            
                            @if($featuredEvent->is_featured)
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="fas fa-star me-1"></i> FEATURED EVENT
                                    </span>
                                </div>
                            @endif
                            
                            <div class="position-absolute top-0 end-0 m-3">
                                <div class="event-date-badge shadow-sm">
                                    <div class="event-date-month">
                                        {{ $featuredEvent->event_date->format('M') }}
                                    </div>
                                    <div class="event-date-day">
                                        {{ $featuredEvent->event_date->format('d') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card-body p-4">
                                <h2 class="card-title mb-3">{{ $featuredEvent->title }}</h2>
                                <p class="card-text mb-3">{{ $featuredEvent->description }}</p>
                                
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                        <span>{{ $featuredEvent->event_date->format('F j, Y - g:i A') }}</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        <span>{{ $featuredEvent->location }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-ticket-alt text-primary me-2"></i>
                                        <span>${{ number_format($featuredEvent->ticket_price, 2) }}</span>
                                    </div>
                                </div>
                                
                                <!-- Main Event Fight -->
                                @if($featuredEvent->fights->isNotEmpty())
                                    <?php $mainFight = $featuredEvent->fights->where('is_main_event', true)->first() ?? $featuredEvent->fights->sortBy('fight_order')->first(); ?>
                                    
                                    <div class="card mb-4">
                                        <div class="card-header bg-danger text-white">
                                            <h6 class="mb-0">MAIN EVENT</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-center">
                                                    <p class="mb-1 fw-bold">{{ $mainFight->fighterOne->full_name }}</p>
                                                    <span class="badge bg-success">{{ $mainFight->fighterOne->wins }}-{{ $mainFight->fighterOne->losses }}</span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="vs-badge">VS</span>
                                                </div>
                                                <div class="text-center">
                                                    <p class="mb-1 fw-bold">{{ $mainFight->fighterTwo->full_name }}</p>
                                                    <span class="badge bg-success">{{ $mainFight->fighterTwo->wins }}-{{ $mainFight->fighterTwo->losses }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('events.show', $featuredEvent) }}" class="btn btn-primary">
                                        <i class="fas fa-info-circle me-2"></i> EVENT DETAILS
                                    </a>
                                    <a href="{{ route('tickets.create', $featuredEvent) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-ticket-alt me-2"></i> BUY TICKETS
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Events List -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="border-start border-primary border-5 ps-3 mb-4">
                {{ request('filter') == 'past' ? 'PAST EVENTS' : 'UPCOMING EVENTS' }}
            </h2>
        </div>
    </div>
    
    <div class="row">
        @forelse($events as $event)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm event-card">
                    <div class="position-relative">
                        @if($event->event_banner)
                            <img src="{{ $event->event_banner }}" class="card-img-top event-image" alt="{{ $event->title }}">
                        @else
                            <div class="event-image-placeholder bg-light d-flex justify-content-center align-items-center">
                                <h5 class="text-muted">{{ $event->title }}</h5>
                            </div>
                        @endif
                        
                        <div class="position-absolute top-0 end-0 m-2">
                            <div class="event-date-badge-sm shadow-sm">
                                <div class="event-date-month-sm">
                                    {{ $event->event_date->format('M') }}
                                </div>
                                <div class="event-date-day-sm">
                                    {{ $event->event_date->format('d') }}
                                </div>
                            </div>
                        </div>
                        
                        @if($event->is_live)
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-danger">
                                    <span class="live-indicator-sm me-1"></span> LIVE NOW
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        
                        <div class="small text-muted mb-3">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <span>{{ $event->event_date->format('F j, Y - g:i A') }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                        </div>
                        
                        <p class="card-text">
                            {{ \Illuminate\Support\Str::limit($event->description, 100) }}
                        </p>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('events.show', $event) }}" class="btn btn-outline-primary">
                                <i class="fas fa-info-circle me-1"></i> DETAILS
                            </a>
                            
                            @if($event->event_date->isFuture())
                                <a href="{{ route('tickets.create', $event) }}" class="btn btn-primary">
                                    <i class="fas fa-ticket-alt me-1"></i> BUY TICKETS
                                </a>
                            @elseif($event->is_live)
                                <a href="{{ route('events.stream', $event) }}" class="btn btn-danger">
                                    <i class="fas fa-play-circle me-1"></i> WATCH
                                </a>
                            @else
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-history me-1"></i> ENDED
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No events found matching your criteria.
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $events->links() }}
    </div>
</div>
@endsection

@section('styles')
<style>
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
    
    .vs-badge {
        display: inline-block;
        background-color: #e63946;
        color: white;
        padding: 6px 12px;
        border-radius: 50%;
        font-weight: bold;
    }
    
    .event-date-badge {
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        width: 70px;
    }
    
    .event-date-month {
        background-color: #e63946;
        color: white;
        padding: 5px;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .event-date-day {
        color: #1d3557;
        font-size: 1.5rem;
        font-weight: bold;
        padding: 5px;
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
    
    .live-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        background-color: #ff0000;
        border-radius: 50%;
        animation: pulse 1.5s infinite;
    }
    
    .live-indicator-sm {
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #ffffff;
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
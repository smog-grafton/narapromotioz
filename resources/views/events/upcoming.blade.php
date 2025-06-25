@extends('layouts.app')

@section('title', 'Upcoming Boxing Events')

@section('content')
<!-- Banner Style One Start -->
<section class="banner-style-one">
    <div class="parallax" style="background-image: url({{ asset('assets/images/banner/events_page_banner.jpg') }});"></div>
    <div class="container">
        <div class="row">
            <div class="banner-details">
                <h2>Upcoming Boxing Events</h2>
                <p>Don't miss out on the most exciting upcoming boxing matches and events from Uganda's premier boxing promotion company.</p>
            </div>
        </div>
    </div>
    <div class="breadcrums">
        <div class="container">
            <div class="row">
                <ul>
                    <li>
                        <a href="{{ route('home') }}">
                            <i class="fa-solid fa-house"></i>
                           
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('events.index') }}">
                            <p>Events</p>
                        </a>
                    </li>
                    <li class="current">
                        <p>Upcoming Events</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- Banner Style One End -->

<div class="events-listing-section">
    <div class="bg-text">UPCOMING</div>
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <h1 class="section-title">UPCOMING BOXING EVENTS</h1>
                <p class="section-subtitle">Get ready for the next big fights</p>
                
                <div class="event-filter-tabs mt-4">
                    <a href="{{ route('events.index') }}" class="event-tab">ALL EVENTS</a>
                    <a href="{{ route('events.upcoming') }}" class="event-tab active">UPCOMING</a>
                    <a href="{{ route('events.past') }}" class="event-tab">PAST EVENTS</a>
                </div>
            </div>
        </div>

        @if($upcomingEvents->isNotEmpty())
            <div class="row">
                @foreach($upcomingEvents as $event)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="event-card upcoming-event">
                            <div class="event-status">
                                <span class="status-badge upcoming">
                                    <i class="fas fa-calendar-plus"></i> UPCOMING
                                </span>
                            </div>
                            
                            <div class="event-card-image">
                                <a href="{{ route('events.show', $event->slug) }}">
                                    <img src="{{ $event->thumbnail }}" alt="{{ $event->name }}" class="img-fluid">
                                </a>
                                <div class="event-date">
                                    <span class="event-day">{{ $event->event_date->format('d') }}</span>
                                    <span class="event-month">{{ $event->event_date->format('M') }}</span>
                                    <span class="event-year">{{ $event->event_date->format('Y') }}</span>
                                </div>
                                
                                @if($event->event_date->diffInDays(now()) <= 7)
                                    <div class="countdown-badge">
                                        <i class="fas fa-clock"></i>
                                        {{ $event->event_date->diffForHumans() }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="event-card-content">
                                <h3 class="event-title">
                                    <a href="{{ route('events.show', $event->slug) }}">{{ $event->name }}</a>
                                </h3>
                                
                                @if($event->tagline)
                                    <p class="event-tagline">{{ $event->tagline }}</p>
                                @endif
                                
                                <div class="event-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $event->venue }}, {{ $event->city }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $event->event_time ? $event->event_time->format('g:i A') : 'Time TBA' }}</span>
                                    </div>
                                    @if($event->event_type)
                                        <div class="meta-item">
                                            <i class="fas fa-tag"></i>
                                            <span>{{ ucfirst(str_replace('_', ' ', $event->event_type)) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($event->main_event_title)
                                    <div class="main-event">
                                        <h4><i class="fas fa-star"></i> Main Event</h4>
                                        <p>{{ $event->main_event_title }}</p>
                                    </div>
                                @endif
                                
                                <div class="event-features">
                                    @if($event->tickets_available)
                                        <span class="feature-badge tickets">
                                            <i class="fas fa-ticket-alt"></i> Tickets Available
                                        </span>
                                    @endif
                                    @if($event->has_stream)
                                        <span class="feature-badge stream">
                                            <i class="fas fa-video"></i> Live Stream
                                        </span>
                                    @endif
                                    @if($event->is_ppv)
                                        <span class="feature-badge ppv">
                                            <i class="fas fa-tv"></i> Pay-Per-View
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="event-footer">
                                    <div class="event-actions">
                                        @if($event->tickets_available)
                                            <a href="{{ route('events.show', $event->slug) }}#tickets" class="btn btn-primary">
                                                <i class="fas fa-ticket-alt"></i> Get Tickets
                                            </a>
                                        @endif
                                        <a href="{{ route('events.show', $event->slug) }}" class="btn btn-outline-light">
                                            <i class="fas fa-info-circle"></i> View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($upcomingEvents->hasPages())
                <div class="row">
                    <div class="col-12">
                        <div class="gym-pagination">
                            {{ $upcomingEvents->links() }}
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <h3 class="empty-title">No Upcoming Events</h3>
                <p class="empty-description">There are currently no upcoming boxing events scheduled. Check back soon for exciting new announcements!</p>
                <div class="empty-actions mt-4">
                    <a href="{{ route('events.past') }}" class="btn btn-outline-light">
                        <i class="fas fa-history"></i> View Past Events
                    </a>
                    <a href="{{ route('events.index') }}" class="btn btn-primary">
                        <i class="fas fa-calendar"></i> All Events
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-wrapper">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="newsletter-title">Never Miss a Fight</h2>
                    <p class="newsletter-description">Subscribe to get notified about upcoming events, ticket sales, and exclusive content.</p>
                </div>
                <div class="col-lg-6">
                    <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="email" name="email" class="form-control" placeholder="Your email address" required>
                            <button type="submit" class="btn btn-primary">SUBSCRIBE</button>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="event_notifications" id="eventNotifications" checked>
                            <label class="form-check-label" for="eventNotifications">
                                Send me notifications about upcoming events
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .upcoming-event {
        border: 2px solid rgba(40, 167, 69, 0.3);
    }
    
    .upcoming-event:hover {
        border-color: rgba(40, 167, 69, 0.6);
        box-shadow: 0 15px 35px rgba(40, 167, 69, 0.1);
    }
    
    .countdown-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255, 193, 7, 0.9);
        color: #000;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 4px;
    }
    
    .feature-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 4px;
        margin: 0.25rem 0.25rem 0.25rem 0;
        
        &.tickets {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        
        &.stream {
            background: rgba(23, 162, 184, 0.2);
            color: #17a2b8;
            border: 1px solid rgba(23, 162, 184, 0.3);
        }
        
        &.ppv {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }
    }
    
    .event-year {
        font-size: 0.75rem;
        opacity: 0.8;
    }
</style>
@endpush 
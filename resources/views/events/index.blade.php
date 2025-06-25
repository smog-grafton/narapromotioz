@extends('layouts.app')

@section('title', 'Boxing Events Calendar')

@section('content')
<!-- Banner Style One Start -->
<section class="banner-style-one">
    <div class="parallax" style="background-image: url({{ asset('assets/images/banner/events_page_banner.jpg') }});"></div>
    <div class="container">
        <div class="row">
            <div class="banner-details">
                <h2>Boxing Events Calendar</h2>
                <p>Discover upcoming and past boxing events from Uganda's premier boxing promotion company - professionally managed, high-octane events showcasing the best talent.</p>
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
                           
                    </li>
                    <li class="current">
                        <p>Boxing Events</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- Banner Style One End -->

<div class="events-listing-section">
    <div class="bg-text">EVENTS</div>
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <h1 class="section-title">BOXING EVENTS</h1>
                <p class="section-subtitle">Find upcoming and past boxing events</p>
                
                <div class="event-tabs mt-4">
                    <a href="#upcoming" class="event-tab active" data-tab="upcoming">UPCOMING</a>
                    <a href="#past" class="event-tab" data-tab="past">PAST</a>
                </div>
            </div>
        </div>

        <!-- Upcoming Events Tab Content -->
        <div id="upcoming" class="event-tab-content active">
            @if($upcomingEvents->isNotEmpty())
                <div class="row">
                    @foreach($upcomingEvents as $event)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="event-card">
                                <div class="event-card-image">
                                    <a href="{{ route('events.show', $event->slug) }}">
                                        <img src="{{ $event->thumbnail }}" alt="{{ $event->name }}" class="img-fluid">
                                    </a>
                                    <div class="event-date">
                                        <span class="event-day">{{ $event->event_date->format('d') }}</span>
                                        <span class="event-month">{{ $event->event_date->format('M') }}</span>
                                    </div>
                                </div>
                                <div class="event-card-content">
                                    <h3 class="event-title">
                                        <a href="{{ route('events.show', $event->slug) }}">{{ $event->name }}</a>
                                    </h3>
                                    <div class="event-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $event->venue }}, {{ $event->city }}</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $event->event_time ? $event->event_time->format('g:i A') : 'TBA' }}</span>
                                        </div>
                                    </div>
                                    @if($event->main_event_title)
                                        <div class="main-event">
                                            <h4>Main Event</h4>
                                            <p>{{ $event->main_event_title }}</p>
                                        </div>
                                    @endif
                                    <div class="event-footer">
                                        @if($event->tickets_available)
                                            <span class="ticket-badge">
                                                <i class="fas fa-ticket-alt"></i> Tickets Available
                                            </span>
                                        @endif
                                        @if($event->has_stream)
                                            <span class="stream-badge">
                                                <i class="fas fa-video"></i> Live Stream
                                            </span>
                                        @endif
                                        <a href="{{ route('events.show', $event->slug) }}" class="btn-view-event">VIEW EVENT</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('events.upcoming') }}" class="btn btn-outline-light btn-view-all">VIEW ALL UPCOMING EVENTS</a>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="empty-title">No Upcoming Events</h3>
                    <p class="empty-description">There are currently no upcoming events scheduled. Please check back later.</p>
                </div>
            @endif
        </div>
        
        <!-- Past Events Tab Content -->
        <div id="past" class="event-tab-content">
            @if($pastEvents->isNotEmpty())
                <div class="row">
                    @foreach($pastEvents->take(6) as $event)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="event-card past-event">
                                <div class="event-card-image">
                                    <a href="{{ route('events.show', $event->slug) }}">
                                        <img src="{{ $event->thumbnail }}" alt="{{ $event->name }}" class="img-fluid">
                                    </a>
                                    <div class="event-date">
                                        <span class="event-day">{{ $event->event_date->format('d') }}</span>
                                        <span class="event-month">{{ $event->event_date->format('M') }}</span>
                                    </div>
                                    
                                    @if($event->videos->isNotEmpty())
                                        <div class="event-highlight-badge">
                                            <i class="fas fa-play"></i> Highlights
                                        </div>
                                    @endif
                                </div>
                                <div class="event-card-content">
                                    <h3 class="event-title">
                                        <a href="{{ route('events.show', $event->slug) }}">{{ $event->name }}</a>
                                    </h3>
                                    <div class="event-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $event->venue }}, {{ $event->city }}</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>{{ $event->event_date->format('F j, Y') }}</span>
                                        </div>
                                    </div>
                                    @if($event->main_event_title)
                                        <div class="main-event">
                                            <h4>Main Event</h4>
                                            <p>{{ $event->main_event_title }}</p>
                                        </div>
                                    @endif
                                    <div class="event-footer">
                                        @if($event->hasResults)
                                            <span class="results-badge">
                                                <i class="fas fa-trophy"></i> Results Available
                                            </span>
                                        @endif
                                        <a href="{{ route('events.show', $event->slug) }}" class="btn-view-event">VIEW EVENT</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('events.past') }}" class="btn btn-outline-light btn-view-all">VIEW ALL PAST EVENTS</a>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="empty-title">No Past Events</h3>
                    <p class="empty-description">There are no past events in our records yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Events Listing Section */
    .events-listing-section {
        position: relative;
        padding: 5rem 0;
        background-color: $dark-bg;
        color: $text-light;
        overflow: hidden;
    }
    
    .bg-text {
        position: absolute;
        top: 0;
        left: 0;
        font-size: 20vw;
        font-family: $font-family-heading;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.03);
        text-transform: uppercase;
        line-height: 1;
        z-index: 0;
        pointer-events: none;
    }
    
    .section-title {
        font-family: $font-family-heading;
        font-size: 3rem;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }
    
    .section-subtitle {
        font-size: 1.1rem;
        color: $text-gray;
        margin-bottom: 2rem;
        position: relative;
        z-index: 1;
    }
    
    /* Event Tabs */
    .event-tabs {
        display: flex;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 2rem;
        position: relative;
        z-index: 1;
    }
    
    .event-tab {
        padding: 1rem 2rem;
        font-family: $font-family-heading;
        font-weight: 700;
        text-transform: uppercase;
        color: $text-gray;
        text-decoration: none;
        position: relative;
        transition: $transition-base;
        
        &:hover {
            color: $text-light;
        }
        
        &.active {
            color: $theme-red;
            
            &:after {
                content: '';
                position: absolute;
                bottom: -1px;
                left: 0;
                width: 100%;
                height: 3px;
                background-color: $theme-red;
            }
        }
    }
    
    /* Event Tab Content */
    .event-tab-content {
        display: none;
        
        &.active {
            display: block;
        }
    }
    
    /* Event Card */
    .event-card {
        background: rgba(30, 30, 30, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 2rem;
        position: relative;
        z-index: 1;
        
        &:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            
            .event-card-image img {
                transform: scale(1.05);
            }
        }
        
        &.past-event {
            .event-date {
                background-color: rgba(30, 30, 30, 0.8);
            }
        }
    }
    
    .event-card-image {
        position: relative;
        overflow: hidden;
        height: 200px;
        
        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
    }
    
    .event-date {
        position: absolute;
        top: 15px;
        left: 15px;
        background-color: $theme-red;
        color: $text-light;
        padding: 0.5rem;
        border-radius: 4px;
        text-align: center;
        min-width: 60px;
        
        .event-day {
            display: block;
            font-family: $font-family-heading;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
        }
        
        .event-month {
            display: block;
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: 700;
        }
    }
    
    .event-highlight-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background-color: rgba(0, 0, 0, 0.7);
        color: $text-light;
        padding: 0.5rem 0.75rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        
        i {
            color: $theme-red;
            margin-right: 0.3rem;
        }
    }
    
    .event-card-content {
        padding: 1.5rem;
    }
    
    .event-title {
        font-family: $font-family-heading;
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 1rem;
        line-height: 1.3;
        
        a {
            color: $text-light;
            text-decoration: none;
            transition: $transition-base;
            
            &:hover {
                color: $theme-red;
            }
        }
    }
    
    .event-meta {
        margin-bottom: 1rem;
        
        .meta-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            
            i {
                color: $theme-red;
                margin-right: 0.5rem;
                width: 16px;
                text-align: center;
            }
            
            span {
                color: $text-gray;
                font-size: 0.9rem;
            }
        }
    }
    
    .main-event {
        margin-bottom: 1.25rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        
        h4 {
            font-size: 0.9rem;
            text-transform: uppercase;
            color: $text-gray;
            margin-bottom: 0.3rem;
        }
        
        p {
            font-family: $font-family-heading;
            font-weight: 600;
            margin-bottom: 0;
        }
    }
    
    .event-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        
        .ticket-badge, .stream-badge, .results-badge {
            font-size: 0.8rem;
            color: $text-gray;
            margin-right: 0.5rem;
            
            i {
                color: $theme-green;
                margin-right: 0.3rem;
            }
        }
        
        .results-badge i {
            color: $theme-gold;
        }
        
        .btn-view-event {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: rgba(255, 255, 255, 0.1);
            color: $text-light;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 4px;
            transition: $transition-base;
            
            &:hover {
                background-color: $theme-red;
            }
        }
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 0;
    }
    
    .empty-icon {
        font-size: 3rem;
        color: rgba(255, 255, 255, 0.1);
        margin-bottom: 1rem;
    }
    
    .empty-title {
        font-family: $font-family-heading;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .empty-description {
        color: $text-gray;
        max-width: 500px;
        margin: 0 auto;
    }
    
    /* View All Button */
    .btn-view-all {
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: $transition-base;
        
        &:hover {
            background-color: $theme-red;
            border-color: $theme-red;
        }
    }
    
    /* Responsive Styles */
    @media (max-width: 992px) {
        .section-title {
            font-size: 2.5rem;
        }
        
        .event-tabs {
            justify-content: center;
        }
    }
    
    @media (max-width: 768px) {
        .section-title {
            font-size: 2rem;
        }
        
        .event-tabs {
            display: flex;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            
            .event-tab {
                padding: 0.75rem 1.25rem;
                white-space: nowrap;
            }
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.event-tab');
        const tabContents = document.querySelectorAll('.event-tab-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Get the tab target
                const target = this.getAttribute('data-tab');
                
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Hide all tab contents
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Show the target content
                document.getElementById(target).classList.add('active');
                
                // Update URL hash
                window.location.hash = target;
            });
        });
        
        // Check for hash in URL
        if (window.location.hash) {
            const hash = window.location.hash.substring(1);
            const tab = document.querySelector(`.event-tab[data-tab="${hash}"]`);
            
            if (tab) {
                tab.click();
            }
        }
    });
</script>
@endpush 
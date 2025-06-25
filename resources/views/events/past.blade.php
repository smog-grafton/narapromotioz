@extends('layouts.app')

@section('title', 'Past Boxing Events')

@section('content')
<!-- Banner Style One Start -->
<section class="banner-style-one">
    <div class="parallax" style="background-image: url({{ asset('assets/images/banner/events_page_banner.jpg') }});"></div>
    <div class="container">
        <div class="row">
            <div class="banner-details">
                <h2>Past Boxing Events</h2>
                <p>Relive the excitement of our previous boxing events with highlights, results, and memorable moments.</p>
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
                        <p>Past Events</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- Banner Style One End -->

<div class="events-listing-section">
    <div class="bg-text">HISTORY</div>
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <h1 class="section-title">PAST BOXING EVENTS</h1>
                <p class="section-subtitle">Relive the greatest moments in boxing history</p>
                
                <div class="event-filter-tabs mt-4">
                    <a href="{{ route('events.index') }}" class="event-tab">ALL EVENTS</a>
                    <a href="{{ route('events.upcoming') }}" class="event-tab">UPCOMING</a>
                    <a href="{{ route('events.past') }}" class="event-tab active">PAST EVENTS</a>
                </div>
            </div>
        </div>

        @if($pastEvents->isNotEmpty())
            <div class="row">
                @foreach($pastEvents as $event)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="event-card past-event">
                            <div class="event-status">
                                <span class="status-badge completed">
                                    <i class="fas fa-check-circle"></i> COMPLETED
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
                                
                                @if($event->videos && count($event->videos) > 0)
                                    <div class="highlight-badge">
                                        <i class="fas fa-play"></i> Highlights Available
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
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>{{ $event->event_date->format('F j, Y') }}</span>
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
                                    @if($event->hasResults)
                                        <span class="feature-badge results">
                                            <i class="fas fa-trophy"></i> Results Available
                                        </span>
                                    @endif
                                    @if($event->videos && count($event->videos) > 0)
                                        <span class="feature-badge highlights">
                                            <i class="fas fa-video"></i> Highlights
                                        </span>
                                    @endif
                                    @if($event->photos && count($event->photos) > 0)
                                        <span class="feature-badge photos">
                                            <i class="fas fa-images"></i> Photos
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="event-footer">
                                    <div class="event-actions">
                                        @if($event->hasResults)
                                            <a href="{{ route('events.show', $event->slug) }}#results" class="btn btn-primary">
                                                <i class="fas fa-trophy"></i> View Results
                                            </a>
                                        @endif
                                        @if($event->videos && count($event->videos) > 0)
                                            <a href="{{ route('events.show', $event->slug) }}#highlights" class="btn btn-outline-light">
                                                <i class="fas fa-play"></i> Watch Highlights
                                            </a>
                                        @else
                                            <a href="{{ route('events.show', $event->slug) }}" class="btn btn-outline-light">
                                                <i class="fas fa-info-circle"></i> View Details
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($pastEvents->hasPages())
                <div class="row">
                    <div class="col-12">
                        <div class="gym-pagination">
                            {{ $pastEvents->links() }}
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-history"></i>
                </div>
                <h3 class="empty-title">No Past Events</h3>
                <p class="empty-description">There are no past events in our records yet. Check back after our first event!</p>
                <div class="empty-actions mt-4">
                    <a href="{{ route('events.upcoming') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i> View Upcoming Events
                    </a>
                    <a href="{{ route('events.index') }}" class="btn btn-outline-light">
                        <i class="fas fa-calendar"></i> All Events
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Event Statistics Section -->
@if($pastEvents->isNotEmpty())
<section class="event-stats-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="section-title">EVENT STATISTICS</h2>
                <p class="section-subtitle">Our boxing legacy in numbers</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-number">{{ $pastEvents->total() }}</div>
                    <div class="stat-label">Events Completed</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-fist-raised"></i>
                    </div>
                    <div class="stat-number">{{ $pastEvents->sum(function($event) { return count($event->fights ?? []); }) }}</div>
                    <div class="stat-label">Total Fights</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stat-number">{{ number_format($pastEvents->sum('views_count')) }}</div>
                    <div class="stat-label">Total Views</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="stat-number">{{ $pastEvents->filter(function($event) { return $event->videos && count($event->videos) > 0; })->count() }}</div>
                    <div class="stat-label">Events with Highlights</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endsection

@push('styles')
<style>
    .past-event {
        border: 2px solid rgba(108, 117, 125, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .past-event:hover {
        border-color: rgba(108, 117, 125, 0.6);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }
    
    .past-event::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, transparent 0%, rgba(108, 117, 125, 0.05) 100%);
        pointer-events: none;
    }
    
    .highlight-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(220, 53, 69, 0.9);
        color: #fff;
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
        
        &.results {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }
        
        &.highlights {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }
        
        &.photos {
            background: rgba(23, 162, 184, 0.2);
            color: #17a2b8;
            border: 1px solid rgba(23, 162, 184, 0.3);
        }
    }
    
    .event-year {
        font-size: 0.75rem;
        opacity: 0.8;
    }
    
    .event-stats-section {
        padding: 5rem 0;
        background: #1e1e1e;
        
        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            
            &:hover {
                transform: translateY(-5px);
                background: rgba(255, 255, 255, 0.1);
            }
            
            .stat-icon {
                font-size: 2.5rem;
                color: #dc3545;
                margin-bottom: 1rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
                font-weight: 700;
                color: #fff;
                margin-bottom: 0.5rem;
            }
            
            .stat-label {
                color: #adb5bd;
                font-weight: 500;
                text-transform: uppercase;
                letter-spacing: 1px;
            }
        }
    }
</style>
@endpush 
<!-- Event Hero Section -->
<section class="event-hero-section" style="background-image: url({{ asset($event->banner_path ?: 'assets/images/page-title-bg.jpg') }});">
    <div class="hero-background-overlay"></div>
    <div class="container">
        <div class="row g-4 align-items-center">
            <!-- Event Content -->
            <div class="col-lg-7">
                <div class="event-hero-content">
                    <div class="event-date">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}
                        @if($event->event_time)
                            &bull; {{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}
                        @endif
                    </div>
                    
                    <h1 class="event-title">{{ $event->name }}</h1>
                    
                    @if($event->tagline)
                        <div class="event-tagline">{{ $event->tagline }}</div>
                    @endif
                    
                    <div class="event-meta">
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="meta-content">
                                <span class="meta-label">Venue</span>
                                <span class="meta-value">{{ $event->venue }}</span>
                            </div>
                        </div>
                        
                        <div class="meta-item">
                            <i class="fas fa-map-pin"></i>
                            <div class="meta-content">
                                <span class="meta-label">Location</span>
                                <span class="meta-value">{{ $event->city }}, {{ $event->country }}</span>
                            </div>
                        </div>
                        
                        @if($event->network)
                            <div class="meta-item">
                                <i class="fas fa-tv"></i>
                                <div class="meta-content">
                                    <span class="meta-label">Broadcast</span>
                                    <span class="meta-value">{{ $event->network }}</span>
                                </div>
                            </div>
                        @endif
                        
                        @if($event->promoter)
                            <div class="meta-item">
                                <i class="fas fa-user-tie"></i>
                                <div class="meta-content">
                                    <span class="meta-label">Promoter</span>
                                    <span class="meta-value">{{ $event->promoter }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="event-actions">
                        @if($isPastEvent)
                            <a href="#results" class="btn btn-primary">
                                <i class="fas fa-trophy"></i> View Results
                            </a>
                            <a href="#highlights" class="btn btn-outline-light">
                                <i class="fas fa-play"></i> Watch Highlights
                            </a>
                        @else
                            @if($event->tickets_available)
                                <a href="#tickets" class="btn btn-primary">
                                    <i class="fas fa-ticket-alt"></i> Get Tickets
                                </a>
                            @endif
                            
                            @if($event->isOngoing && !$event->is_free)
                                <a href="#stream" class="btn btn-primary">
                                    <i class="fas fa-play-circle"></i> Watch Live
                                </a>
                            @endif
                            
                            <a href="#fight-card" class="btn btn-outline-light">
                                <i class="fas fa-list"></i> View Fight Card
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Event Poster -->
            <div class="col-lg-5">
                <div class="event-poster">
                    <img src="{{ $event->image_path ? asset('storage/' . $event->image_path) : asset('assets/images/events/default-poster.jpg') }}" alt="{{ $event->name }} Poster">
                </div>
            </div>
        </div>
    </div>
</section> 
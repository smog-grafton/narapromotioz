@extends('layouts.app')

@section('title', 'Premier Boxing Promotions')

@section('content')
    <!-- Hero Section with Video Background -->
    <section class="hero-section">
        <div class="container-fluid px-0">
            <div class="position-relative">
                <!-- Hero Background with Video Option -->
                <div class="bg-dark" style="height: 80vh; background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.8)), url('https://images.unsplash.com/photo-1622467827417-bbe3e975464c') center/cover no-repeat;">
                    <!-- Optional Video Background -->
                    <div class="video-container d-none d-lg-block">
                        <video autoplay muted loop class="hero-video">
                            <source src="{{ asset('videos/boxing-hero.mp4') }}" type="video/mp4">
                        </video>
                    </div>
                </div>
                
                <!-- Hero Content -->
                <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-4">
                    <h1 class="display-2 fw-bold mb-3 hero-title">THE ULTIMATE BOXING EXPERIENCE</h1>
                    <p class="lead mb-4 hero-subtitle">World-class fights, legendary fighters, and unforgettable moments.</p>
                    
                    <!-- Countdown Timer for Next Event -->
                    @if(isset($nextEvent) && $nextEvent)
                        <div class="countdown-container mb-4">
                            <div class="countdown-header">NEXT BIG EVENT IN:</div>
                            <div class="countdown-timer" data-event-date="{{ $nextEvent->event_date->format('Y-m-d H:i:s') }}">
                                <div class="countdown-item">
                                    <span class="countdown-value days">00</span>
                                    <span class="countdown-label">Days</span>
                                </div>
                                <div class="countdown-item">
                                    <span class="countdown-value hours">00</span>
                                    <span class="countdown-label">Hours</span>
                                </div>
                                <div class="countdown-item">
                                    <span class="countdown-value minutes">00</span>
                                    <span class="countdown-label">Minutes</span>
                                </div>
                                <div class="countdown-item">
                                    <span class="countdown-value seconds">00</span>
                                    <span class="countdown-label">Seconds</span>
                                </div>
                            </div>
                            <div class="countdown-event-name">{{ $nextEvent->title ?? 'Championship Fight Night' }}</div>
                        </div>
                    @endif
                    
                    <!-- Call to Action Buttons -->
                    <div class="d-flex justify-content-center gap-3 mt-4 hero-cta">
                        <a href="{{ route('events.index') }}" class="btn btn-primary btn-lg shadow-sm">UPCOMING EVENTS</a>
                        <a href="{{ route('fighters.index') }}" class="btn btn-outline-light btn-lg">FIGHTERS</a>
                    </div>
                    
                    <!-- Live Event Badge (if any) -->
                    @if(isset($liveEvent) && $liveEvent)
                    <div class="mt-5 live-event-badge animate__animated animate__pulse animate__infinite">
                        <span class="live-badge me-2">LIVE NOW</span>
                        <a href="{{ route('streams.show', $liveEvent) }}" class="text-white text-decoration-none fw-bold">
                            <strong>{{ $liveEvent->title }} - Watch Now!</strong>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    
    <!-- News Ticker -->
    <div class="news-ticker py-2 bg-dark-navy">
        <div class="container">
            <div class="ticker-wrapper">
                <div class="ticker-label bg-action-red">BREAKING NEWS</div>
                <div class="ticker-content">
                    @if(isset($latestNews) && $latestNews->count() > 0)
                        @foreach($latestNews as $news)
                            <span class="ticker-item">{{ $news->title }} &bull; </span>
                        @endforeach
                    @else
                        <span class="ticker-item">Welcome to Nara Promotionz - The Home of Boxing Excellence &bull; </span>
                        <span class="ticker-item">Championship Fight Night set for July 15th at Madison Square Garden &bull; </span>
                        <span class="ticker-item">Carlos Rodriguez defends heavyweight title against Victor King &bull; </span>
                        <span class="ticker-item">Tickets now available for Summer Showdown &bull; </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Counter Section -->
    <section class="py-4 bg-primary text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-6 col-md-3 mb-3 mb-md-0">
                    <div class="counter-item">
                        <h2 class="counter-number mb-0" data-count="{{ $totalEvents ?? 150 }}">0+</h2>
                        <p class="counter-text mb-0">Events Hosted</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3 mb-md-0">
                    <div class="counter-item">
                        <h2 class="counter-number mb-0" data-count="{{ $totalFighters ?? 500 }}">0+</h2>
                        <p class="counter-text mb-0">Pro Fighters</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="counter-item">
                        <h2 class="counter-number mb-0" data-count="{{ $totalFights ?? 750 }}">0+</h2>
                        <p class="counter-text mb-0">Championship Fights</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="counter-item">
                        <h2 class="counter-number mb-0" data-count="{{ $totalFans ?? 250000 }}">0+</h2>
                        <p class="counter-text mb-0">Boxing Fans</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Upcoming Events Section -->
    <section class="py-5 upcoming-events-section">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="section-title border-start border-primary border-5 ps-3">UPCOMING EVENTS</h2>
                </div>
            </div>
            
            <div class="row">
                @forelse($upcomingEvents ?? [] as $event)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm event-card">
                            <div class="position-relative">
                                @if(isset($event->event_banner) && $event->event_banner)
                                    <img src="{{ $event->event_banner }}" class="card-img-top" alt="{{ $event->title }}">
                                @else
                                    <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 200px;">
                                        <h5>{{ $event->title ?? 'Boxing Event' }}</h5>
                                    </div>
                                @endif
                                
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-primary">{{ isset($event->event_date) ? $event->event_date->format('M d, Y') : 'Upcoming' }}</span>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <h5 class="card-title">{{ $event->title ?? 'Boxing Event Title' }}</h5>
                                <p class="card-text text-muted">{{ $event->location ?? 'Event Location' }}</p>
                                
                                <!-- Main Event Fighters -->
                                @if(isset($event->fights) && $event->fights->isNotEmpty())
                                    <?php $mainEvent = $event->fights->sortBy('fight_order')->first(); ?>
                                    <div class="d-flex justify-content-between align-items-center mt-3 event-fighters">
                                        <div class="text-center">
                                            <p class="mb-0 fw-bold">{{ $mainEvent->fighterOne->full_name }}</p>
                                            <small>{{ $mainEvent->fighterOne->wins }}-{{ $mainEvent->fighterOne->losses }}</small>
                                        </div>
                                        <div class="text-center vs-badge">
                                            <span class="text-danger">VS</span>
                                        </div>
                                        <div class="text-center">
                                            <p class="mb-0 fw-bold">{{ $mainEvent->fighterTwo->full_name }}</p>
                                            <small>{{ $mainEvent->fighterTwo->wins }}-{{ $mainEvent->fighterTwo->losses }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="card-footer bg-white border-top-0">
                                <a href="{{ isset($event) ? route('events.show', $event) : '#' }}" class="btn btn-outline-primary w-100">VIEW DETAILS</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Placeholder cards for design when no events are present -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm event-card">
                            <div class="position-relative">
                                <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 200px;">
                                    <h5>Championship Fight Night</h5>
                                </div>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-primary">July 15, 2023</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Championship Fight Night</h5>
                                <p class="card-text text-muted">Madison Square Garden, New York</p>
                                <div class="d-flex justify-content-between align-items-center mt-3 event-fighters">
                                    <div class="text-center">
                                        <p class="mb-0 fw-bold">James Wilson</p>
                                        <small>22-1</small>
                                    </div>
                                    <div class="text-center vs-badge">
                                        <span class="text-danger">VS</span>
                                    </div>
                                    <div class="text-center">
                                        <p class="mb-0 fw-bold">Mike Johnson</p>
                                        <small>20-2</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="#" class="btn btn-outline-primary w-100">VIEW DETAILS</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm event-card">
                            <div class="position-relative">
                                <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 200px;">
                                    <h5>Summer Showdown</h5>
                                </div>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-primary">August 22, 2023</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Summer Showdown</h5>
                                <p class="card-text text-muted">T-Mobile Arena, Las Vegas</p>
                                <div class="d-flex justify-content-between align-items-center mt-3 event-fighters">
                                    <div class="text-center">
                                        <p class="mb-0 fw-bold">David Thomas</p>
                                        <small>25-0</small>
                                    </div>
                                    <div class="text-center vs-badge">
                                        <span class="text-danger">VS</span>
                                    </div>
                                    <div class="text-center">
                                        <p class="mb-0 fw-bold">Carlos Rodriguez</p>
                                        <small>23-1</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="#" class="btn btn-outline-primary w-100">VIEW DETAILS</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm event-card">
                            <div class="position-relative">
                                <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 200px;">
                                    <h5>Heavyweight Clash</h5>
                                </div>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-primary">September 8, 2023</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Heavyweight Clash</h5>
                                <p class="card-text text-muted">Wembley Stadium, London</p>
                                <div class="d-flex justify-content-between align-items-center mt-3 event-fighters">
                                    <div class="text-center">
                                        <p class="mb-0 fw-bold">Alexander Smith</p>
                                        <small>18-0</small>
                                    </div>
                                    <div class="text-center vs-badge">
                                        <span class="text-danger">VS</span>
                                    </div>
                                    <div class="text-center">
                                        <p class="mb-0 fw-bold">Victor King</p>
                                        <small>15-2</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="#" class="btn btn-outline-primary w-100">VIEW DETAILS</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="{{ route('events.index') }}" class="btn btn-outline-dark">VIEW ALL EVENTS</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Streaming Promo Section -->
    <section class="py-5 text-white streaming-promo-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="streaming-content pe-lg-4">
                        <h2 class="mb-4">WATCH LIVE BOXING ANYWHERE</h2>
                        <p class="lead mb-4">Subscribe to our premium streaming service and never miss a moment of action. Watch championship fights live or on demand from any device.</p>
                        <ul class="list-unstyled mb-4 streaming-features">
                            <li><i class="fas fa-check-circle text-success me-2"></i> Full HD & 4K quality streams</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Multiple camera angles</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Expert commentary</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Replay and on-demand access</li>
                        </ul>
                        <a href="{{ route('streams.index') }}" class="btn btn-danger">EXPLORE STREAMS</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="streaming-mockup text-center">
                        <img src="{{ asset('images/streaming-mockup.png') }}" alt="Streaming on devices" class="img-fluid rounded shadow">
                        <div class="live-indicator">
                            <span class="live-badge">LIVE</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Upcoming Tickets Section -->
    <section class="py-5 ticket-section bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2 class="section-title border-bottom border-primary border-3 d-inline-block pb-2 mb-1">GET YOUR TICKETS</h2>
                    <p class="text-muted mb-5">Secure your seats for the most anticipated boxing events of the year</p>
                </div>
            </div>
            
            <div class="row">
                @if(isset($ticketEvents) && $ticketEvents->count() > 0)
                    @foreach($ticketEvents as $event)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 ticket-card shadow">
                                <div class="position-relative">
                                    @if(isset($event->event_banner) && $event->event_banner)
                                        <img src="{{ $event->event_banner }}" class="card-img-top" alt="{{ $event->title }}">
                                    @else
                                        <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 150px;">
                                            <h5>{{ $event->title }}</h5>
                                        </div>
                                    @endif
                                    <div class="position-absolute bottom-0 start-0 m-3 ticket-date-badge">
                                        <span class="date-month">{{ isset($event->event_date) ? $event->event_date->format('M') : 'APR' }}</span>
                                        <span class="date-day">{{ isset($event->event_date) ? $event->event_date->format('d') : '28' }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $event->title ?? 'Championship Boxing Event' }}</h5>
                                    <p class="card-text text-muted mb-1">
                                        <i class="fas fa-map-marker-alt me-2"></i> {{ $event->location ?? 'Madison Square Garden, New York' }}
                                    </p>
                                    <p class="card-text text-muted">
                                        <i class="fas fa-clock me-2"></i> {{ isset($event->event_date) ? $event->event_date->format('g:i A') : '7:00 PM' }}
                                    </p>
                                    
                                    <div class="ticket-price mt-4">
                                        <span class="from-text">From</span>
                                        <span class="price">${{ isset($event->min_ticket_price) ? number_format($event->min_ticket_price, 2) : '79.99' }}</span>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0 pt-0">
                                    <a href="{{ isset($event) ? route('events.tickets', $event) : '#' }}" class="btn btn-primary w-100">
                                        <i class="fas fa-ticket-alt me-2"></i> BUY TICKETS
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Placeholder ticket cards for design when no events are present -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 ticket-card shadow">
                            <div class="position-relative">
                                <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 150px;">
                                    <h5>Championship Fight Night</h5>
                                </div>
                                <div class="position-absolute bottom-0 start-0 m-3 ticket-date-badge">
                                    <span class="date-month">JUL</span>
                                    <span class="date-day">15</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Championship Fight Night</h5>
                                <p class="card-text text-muted mb-1">
                                    <i class="fas fa-map-marker-alt me-2"></i> Madison Square Garden, New York
                                </p>
                                <p class="card-text text-muted">
                                    <i class="fas fa-clock me-2"></i> 7:00 PM
                                </p>
                                
                                <div class="ticket-price mt-4">
                                    <span class="from-text">From</span>
                                    <span class="price">$99.99</span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 pt-0">
                                <a href="#" class="btn btn-primary w-100">
                                    <i class="fas fa-ticket-alt me-2"></i> BUY TICKETS
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 ticket-card shadow">
                            <div class="position-relative">
                                <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 150px;">
                                    <h5>Summer Showdown</h5>
                                </div>
                                <div class="position-absolute bottom-0 start-0 m-3 ticket-date-badge">
                                    <span class="date-month">AUG</span>
                                    <span class="date-day">22</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Summer Showdown</h5>
                                <p class="card-text text-muted mb-1">
                                    <i class="fas fa-map-marker-alt me-2"></i> T-Mobile Arena, Las Vegas
                                </p>
                                <p class="card-text text-muted">
                                    <i class="fas fa-clock me-2"></i> 8:00 PM
                                </p>
                                
                                <div class="ticket-price mt-4">
                                    <span class="from-text">From</span>
                                    <span class="price">$149.99</span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 pt-0">
                                <a href="#" class="btn btn-primary w-100">
                                    <i class="fas fa-ticket-alt me-2"></i> BUY TICKETS
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 ticket-card shadow">
                            <div class="position-relative">
                                <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 150px;">
                                    <h5>Heavyweight Clash</h5>
                                </div>
                                <div class="position-absolute bottom-0 start-0 m-3 ticket-date-badge">
                                    <span class="date-month">SEP</span>
                                    <span class="date-day">08</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Heavyweight Clash</h5>
                                <p class="card-text text-muted mb-1">
                                    <i class="fas fa-map-marker-alt me-2"></i> Wembley Stadium, London
                                </p>
                                <p class="card-text text-muted">
                                    <i class="fas fa-clock me-2"></i> 6:30 PM
                                </p>
                                
                                <div class="ticket-price mt-4">
                                    <span class="from-text">From</span>
                                    <span class="price">$124.99</span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 pt-0">
                                <a href="#" class="btn btn-primary w-100">
                                    <i class="fas fa-ticket-alt me-2"></i> BUY TICKETS
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    
    <!-- Latest News Section -->
    <section class="py-5 news-section">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="section-title border-start border-danger border-5 ps-3">LATEST NEWS</h2>
                        <a href="{{ route('news.index') }}" class="btn btn-link text-dark view-all-link">VIEW ALL <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="row">
                @if(isset($latestNewsArticles) && $latestNewsArticles->count() > 0)
                    @foreach($latestNewsArticles as $article)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 news-card">
                                <!-- Article image -->
                                <div class="position-relative">
                                    @if(isset($article->featured_image) && $article->featured_image)
                                        <img src="{{ $article->featured_image }}" class="card-img-top" alt="{{ $article->title }}">
                                    @else
                                        <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 200px;">
                                            <i class="fas fa-newspaper fa-3x"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Category badge -->
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <span class="badge bg-primary">{{ $article->category->name ?? 'Boxing News' }}</span>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <div class="news-date mb-2 text-muted">
                                        <i class="far fa-calendar-alt me-1"></i> {{ isset($article->published_at) ? $article->published_at->format('M d, Y') : now()->format('M d, Y') }}
                                    </div>
                                    <h5 class="card-title">{{ $article->title ?? 'Boxing News Article Title' }}</h5>
                                    <p class="card-text text-muted">
                                        {{ $article->excerpt ?? Str::limit('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 100) }}
                                    </p>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="news-author">
                                            <i class="far fa-user me-1"></i> {{ $article->author->name ?? 'Nara Editorial Team' }}
                                        </div>
                                        <a href="{{ isset($article) ? route('news.show', $article) : '#' }}" class="read-more-link">
                                            Read More <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Placeholder news cards for design when no news present -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 news-card">
                            <div class="position-relative">
                                <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 200px;">
                                    <i class="fas fa-newspaper fa-3x"></i>
                                </div>
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-primary">Fight News</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="news-date mb-2 text-muted">
                                    <i class="far fa-calendar-alt me-1"></i> Apr 25, 2023
                                </div>
                                <h5 class="card-title">Championship Belt on the Line for Wilson vs. Johnson</h5>
                                <p class="card-text text-muted">
                                    The highly anticipated bout between James Wilson and Mike Johnson will now be for the WBC Continental title...
                                </p>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="news-author">
                                        <i class="far fa-user me-1"></i> Michael Brooks
                                    </div>
                                    <a href="#" class="read-more-link">
                                        Read More <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 news-card">
                            <div class="position-relative">
                                <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 200px;">
                                    <i class="fas fa-newspaper fa-3x"></i>
                                </div>
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-primary">Interviews</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="news-date mb-2 text-muted">
                                    <i class="far fa-calendar-alt me-1"></i> Apr 22, 2023
                                </div>
                                <h5 class="card-title">Exclusive Interview with Rising Star David Thomas</h5>
                                <p class="card-text text-muted">
                                    We sat down with undefeated boxer David Thomas to discuss his training regimen and his upcoming fight...
                                </p>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="news-author">
                                        <i class="far fa-user me-1"></i> Sarah Johnson
                                    </div>
                                    <a href="#" class="read-more-link">
                                        Read More <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 news-card">
                            <div class="position-relative">
                                <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 200px;">
                                    <i class="fas fa-newspaper fa-3x"></i>
                                </div>
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-primary">Analysis</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="news-date mb-2 text-muted">
                                    <i class="far fa-calendar-alt me-1"></i> Apr 20, 2023
                                </div>
                                <h5 class="card-title">Breaking Down the Smith vs. King Heavyweight Matchup</h5>
                                <p class="card-text text-muted">
                                    Our expert analysts break down the tactics and strategies both fighters will likely employ in the upcoming...
                                </p>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="news-author">
                                        <i class="far fa-user me-1"></i> Robert Williams
                                    </div>
                                    <a href="#" class="read-more-link">
                                        Read More <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    
    <!-- Featured Fighters Section -->
    <section class="py-5 featured-fighters-section bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="section-title border-start border-dark-navy border-5 ps-3">FEATURED FIGHTERS</h2>
                        <a href="{{ route('fighters.index') }}" class="btn btn-link text-dark view-all-link">VIEW ALL <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="row">
                @if(isset($featuredFighters) && $featuredFighters->count() > 0)
                    @foreach($featuredFighters as $fighter)
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card h-100 fighter-card shadow-sm">
                                <div class="position-relative">
                                    @if(isset($fighter->profile_image) && $fighter->profile_image)
                                        <img src="{{ $fighter->profile_image }}" class="card-img-top" alt="{{ $fighter->full_name }}">
                                    @else
                                        <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 280px;">
                                            <i class="fas fa-user-alt fa-3x"></i>
                                        </div>
                                    @endif
                                    
                                    @if(isset($fighter->is_champion) && $fighter->is_champion)
                                        <div class="position-absolute top-0 start-0 m-2">
                                            <span class="badge champion-badge"><i class="fas fa-trophy me-1"></i> CHAMPION</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="card-body text-center">
                                    <h5 class="card-title mb-1">{{ $fighter->full_name ?? 'Fighter Name' }}</h5>
                                    <p class="text-muted mb-2">{{ $fighter->weight_class ?? 'Heavyweight' }}</p>
                                    
                                    <div class="fighter-stats d-flex justify-content-center mt-3">
                                        <div class="stat-item text-center px-2">
                                            <span class="stat-value d-block fw-bold text-success">{{ $fighter->wins ?? '0' }}</span>
                                            <span class="stat-label small text-muted">WINS</span>
                                        </div>
                                        <div class="stat-item text-center px-2">
                                            <span class="stat-value d-block fw-bold text-danger">{{ $fighter->losses ?? '0' }}</span>
                                            <span class="stat-label small text-muted">LOSSES</span>
                                        </div>
                                        <div class="stat-item text-center px-2">
                                            <span class="stat-value d-block fw-bold">{{ $fighter->draws ?? '0' }}</span>
                                            <span class="stat-label small text-muted">DRAWS</span>
                                        </div>
                                        <div class="stat-item text-center px-2">
                                            <span class="stat-value d-block fw-bold text-primary">{{ $fighter->ko_percent ?? '75%' }}</span>
                                            <span class="stat-label small text-muted">KO %</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-white border-top-0 text-center">
                                    <a href="{{ isset($fighter) ? route('fighters.show', $fighter) : '#' }}" class="btn btn-outline-primary">VIEW PROFILE</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Placeholder fighter cards -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100 fighter-card shadow-sm">
                            <div class="position-relative">
                                <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 280px;">
                                    <i class="fas fa-user-alt fa-3x"></i>
                                </div>
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge champion-badge"><i class="fas fa-trophy me-1"></i> CHAMPION</span>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-1">James Wilson</h5>
                                <p class="text-muted mb-2">Middleweight</p>
                                
                                <div class="fighter-stats d-flex justify-content-center mt-3">
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold text-success">22</span>
                                        <span class="stat-label small text-muted">WINS</span>
                                    </div>
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold text-danger">1</span>
                                        <span class="stat-label small text-muted">LOSSES</span>
                                    </div>
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold">0</span>
                                        <span class="stat-label small text-muted">DRAWS</span>
                                    </div>
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold text-primary">82%</span>
                                        <span class="stat-label small text-muted">KO %</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 text-center">
                                <a href="#" class="btn btn-outline-primary">VIEW PROFILE</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100 fighter-card shadow-sm">
                            <div class="position-relative">
                                <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 280px;">
                                    <i class="fas fa-user-alt fa-3x"></i>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-1">Mike Johnson</h5>
                                <p class="text-muted mb-2">Middleweight</p>
                                
                                <div class="fighter-stats d-flex justify-content-center mt-3">
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold text-success">20</span>
                                        <span class="stat-label small text-muted">WINS</span>
                                    </div>
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold text-danger">2</span>
                                        <span class="stat-label small text-muted">LOSSES</span>
                                    </div>
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold">1</span>
                                        <span class="stat-label small text-muted">DRAWS</span>
                                    </div>
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold text-primary">75%</span>
                                        <span class="stat-label small text-muted">KO %</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 text-center">
                                <a href="#" class="btn btn-outline-primary">VIEW PROFILE</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100 fighter-card shadow-sm">
                            <div class="position-relative">
                                <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 280px;">
                                    <i class="fas fa-user-alt fa-3x"></i>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-1">David Thomas</h5>
                                <p class="text-muted mb-2">Lightweight</p>
                                
                                <div class="fighter-stats d-flex justify-content-center mt-3">
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold text-success">25</span>
                                        <span class="stat-label small text-muted">WINS</span>
                                    </div>
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold text-danger">0</span>
                                        <span class="stat-label small text-muted">LOSSES</span>
                                    </div>
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold">0</span>
                                        <span class="stat-label small text-muted">DRAWS</span>
                                    </div>
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold text-primary">88%</span>
                                        <span class="stat-label small text-muted">KO %</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 text-center">
                                <a href="#" class="btn btn-outline-primary">VIEW PROFILE</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100 fighter-card shadow-sm">
                            <div class="position-relative">
                                <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 280px;">
                                    <i class="fas fa-user-alt fa-3x"></i>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-1">Carlos Rodriguez</h5>
                                <p class="text-muted mb-2">Heavyweight</p>
                                
                                <div class="fighter-stats d-flex justify-content-center mt-3">
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold text-success">23</span>
                                        <span class="stat-label small text-muted">WINS</span>
                                    </div>
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold text-danger">1</span>
                                        <span class="stat-label small text-muted">LOSSES</span>
                                    </div>
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold">0</span>
                                        <span class="stat-label small text-muted">DRAWS</span>
                                    </div>
                                    <div class="stat-item text-center px-2">
                                        <span class="stat-value d-block fw-bold text-primary">91%</span>
                                        <span class="stat-label small text-muted">KO %</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 text-center">
                                <a href="#" class="btn btn-outline-primary">VIEW PROFILE</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    <section class="py-5 newsletter-section text-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="mb-3">JOIN OUR BOXING COMMUNITY</h2>
                    <p class="lead mb-4">Subscribe to our newsletter for exclusive fight news, special ticket offers, fighter interviews, and insider analysis.</p>
                    
                    <form action="#" method="POST" class="newsletter-form">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email" class="form-control form-control-lg" placeholder="Your email address" required>
                            <button class="btn btn-danger" type="submit">SUBSCRIBE</button>
                        </div>
                        <div class="form-text text-white-50">We respect your privacy. Unsubscribe at any time.</div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5 bg-light featured-fighters-section">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="section-title border-start border-danger border-5 ps-3">FEATURED FIGHTERS</h2>
                </div>
            </div>
            
            <div class="row">
                @forelse($featuredFighters ?? [] as $fighter)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm fighter-card">
                            @if(isset($fighter->profile_image) && $fighter->profile_image)
                                <img src="{{ $fighter->profile_image }}" class="card-img-top" alt="{{ $fighter->full_name }}">
                            @else
                                <div class="bg-secondary text-white d-flex justify-content-center align-items-center" style="height: 250px;">
                                    <i class="fas fa-user fa-3x"></i>
                                </div>
                            @endif
                            
                            <div class="card-body text-center">
                                <h5 class="card-title mb-0">{{ $fighter->full_name ?? 'Fighter Name' }}</h5>
                                @if(isset($fighter->nickname) && $fighter->nickname)
                                    <p class="text-muted mb-2">"{{ $fighter->nickname }}"</p>
                                @endif
                                
                                <div class="d-flex justify-content-center mt-2 fighter-stats">
                                    <span class="badge bg-success me-1">{{ $fighter->wins ?? '0' }} W</span>
                                    <span class="badge bg-danger me-1">{{ $fighter->losses ?? '0' }} L</span>
                                    <span class="badge bg-secondary">{{ $fighter->draws ?? '0' }} D</span>
                                </div>
                                
                                <p class="mt-2 mb-0">{{ $fighter->weight_class ?? 'Weight Class' }}</p>
                                
                                @if(isset($fighter->ranking) && $fighter->ranking && $fighter->ranking->isChampion())
                                    <div class="mt-2">
                                        <span class="badge bg-warning text-dark champion-badge">CHAMPION</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="card-footer bg-white border-top-0">
                                <a href="{{ isset($fighter) ? route('fighters.show', $fighter) : '#' }}" class="btn btn-sm btn-outline-primary w-100">VIEW PROFILE</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Placeholder fighters when no data is present -->
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm fighter-card">
                            <div class="bg-secondary position-relative" style="height: 250px;">
                                <img src="{{ asset('images/fighter1.jpg') }}" class="card-img-top h-100 object-fit-cover" alt="Fighter">
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-warning text-dark">CHAMPION</span>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-0">Michael "Iron" Stevens</h5>
                                <p class="text-muted mb-2">"The Destroyer"</p>
                                <div class="d-flex justify-content-center mt-2 fighter-stats">
                                    <span class="badge bg-success me-1">28 W</span>
                                    <span class="badge bg-danger me-1">0 L</span>
                                    <span class="badge bg-secondary">2 D</span>
                                </div>
                                <p class="mt-2 mb-0">Heavyweight</p>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="#" class="btn btn-sm btn-outline-primary w-100">VIEW PROFILE</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm fighter-card">
                            <div class="bg-secondary position-relative" style="height: 250px;">
                                <img src="{{ asset('images/fighter2.jpg') }}" class="card-img-top h-100 object-fit-cover" alt="Fighter">
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-0">Sarah Johnson</h5>
                                <p class="text-muted mb-2">"The Hurricane"</p>
                                <div class="d-flex justify-content-center mt-2 fighter-stats">
                                    <span class="badge bg-success me-1">22 W</span>
                                    <span class="badge bg-danger me-1">1 L</span>
                                    <span class="badge bg-secondary">0 D</span>
                                </div>
                                <p class="mt-2 mb-0">Lightweight</p>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="#" class="btn btn-sm btn-outline-primary w-100">VIEW PROFILE</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm fighter-card">
                            <div class="bg-secondary position-relative" style="height: 250px;">
                                <img src="{{ asset('images/fighter3.jpg') }}" class="card-img-top h-100 object-fit-cover" alt="Fighter">
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-0">Carlos Mendez</h5>
                                <p class="text-muted mb-2">"El Toro"</p>
                                <div class="d-flex justify-content-center mt-2 fighter-stats">
                                    <span class="badge bg-success me-1">25 W</span>
                                    <span class="badge bg-danger me-1">3 L</span>
                                    <span class="badge bg-secondary">1 D</span>
                                </div>
                                <p class="mt-2 mb-0">Middleweight</p>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="#" class="btn btn-sm btn-outline-primary w-100">VIEW PROFILE</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm fighter-card">
                            <div class="bg-secondary position-relative" style="height: 250px;">
                                <img src="{{ asset('images/fighter4.jpg') }}" class="card-img-top h-100 object-fit-cover" alt="Fighter">
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-0">Alex "Thunder" Williams</h5>
                                <p class="text-muted mb-2">"Swift"</p>
                                <div class="d-flex justify-content-center mt-2 fighter-stats">
                                    <span class="badge bg-success me-1">18 W</span>
                                    <span class="badge bg-danger me-1">2 L</span>
                                    <span class="badge bg-secondary">0 D</span>
                                </div>
                                <p class="mt-2 mb-0">Welterweight</p>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="#" class="btn btn-sm btn-outline-primary w-100">VIEW PROFILE</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="{{ route('fighters.index') }}" class="btn btn-outline-dark">VIEW ALL FIGHTERS</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Latest News Section -->
    <section class="py-5 latest-news-section">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="section-title border-start border-primary border-5 ps-3">LATEST NEWS</h2>
                </div>
            </div>
            
            <div class="row">
                @forelse($latestNews ?? [] as $article)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm news-card">
                            @if(isset($article->thumbnail_image) && $article->thumbnail_image)
                                <img src="{{ $article->thumbnail_image }}" class="card-img-top" alt="{{ $article->title }}">
                            @else
                                <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 200px;">
                                    <i class="fas fa-newspaper fa-3x"></i>
                                </div>
                            @endif
                            
                            <div class="card-body">
                                <h5 class="card-title">{{ $article->title ?? 'News Article Title' }}</h5>
                                <p class="text-muted small mb-2">
                                    <i class="far fa-calendar-alt me-1"></i> {{ isset($article->formattedPublishedDate) ? $article->formattedPublishedDate : date('M d, Y') }}
                                    <span class="ms-2"><i class="far fa-clock me-1"></i> {{ $article->readTime ?? '5' }} min read</span>
                                </p>
                                <p class="card-text">{{ $article->summary ?? 'Article summary goes here. This is a short preview of the news article content.' }}</p>
                            </div>
                            
                            <div class="card-footer bg-white border-top-0">
                                <a href="{{ isset($article) ? route('news.show', $article) : '#' }}" class="btn btn-sm btn-outline-primary w-100">READ MORE</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Placeholder news cards when no data is present -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm news-card">
                            <div class="bg-dark position-relative" style="height: 200px;">
                                <img src="{{ asset('images/news1.jpg') }}" class="card-img-top h-100 object-fit-cover" alt="News">
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-danger">BREAKING</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Championship Fight Announced for Summer</h5>
                                <p class="text-muted small mb-2">
                                    <i class="far fa-calendar-alt me-1"></i> May 15, 2023
                                    <span class="ms-2"><i class="far fa-clock me-1"></i> 5 min read</span>
                                </p>
                                <p class="card-text">The much-anticipated championship bout between Michael Stevens and Victor King has been confirmed for this summer at Madison Square Garden.</p>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="#" class="btn btn-sm btn-outline-primary w-100">READ MORE</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm news-card">
                            <div class="bg-dark position-relative" style="height: 200px;">
                                <img src="{{ asset('images/news2.jpg') }}" class="card-img-top h-100 object-fit-cover" alt="News">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Rising Star Sarah Johnson Signs with Nara Promotionz</h5>
                                <p class="text-muted small mb-2">
                                    <i class="far fa-calendar-alt me-1"></i> May 12, 2023
                                    <span class="ms-2"><i class="far fa-clock me-1"></i> 4 min read</span>
                                </p>
                                <p class="card-text">Undefeated lightweight sensation Sarah "The Hurricane" Johnson has officially signed a multi-year contract with Nara Promotionz.</p>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="#" class="btn btn-sm btn-outline-primary w-100">READ MORE</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm news-card">
                            <div class="bg-dark position-relative" style="height: 200px;">
                                <img src="{{ asset('images/news3.jpg') }}" class="card-img-top h-100 object-fit-cover" alt="News">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">New International Venue Partnerships Announced</h5>
                                <p class="text-muted small mb-2">
                                    <i class="far fa-calendar-alt me-1"></i> May 10, 2023
                                    <span class="ms-2"><i class="far fa-clock me-1"></i> 6 min read</span>
                                </p>
                                <p class="card-text">Nara Promotionz expands its global reach with new partnerships with iconic venues in London, Tokyo, and Mexico City.</p>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="#" class="btn btn-sm btn-outline-primary w-100">READ MORE</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="{{ route('news.index') }}" class="btn btn-outline-dark">VIEW ALL NEWS</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Rankings Teaser Section -->
    <section class="py-5 bg-light rankings-teaser-section">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="section-title border-start border-primary border-5 ps-3">BOXING RANKINGS</h2>
                    <p class="lead">See where your favorite fighters stand in our official Nara Promotionz rankings</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100 ranking-card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Heavyweight Division</h5>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning text-dark me-2">C</span>
                                        <span>Michael Stevens</span>
                                    </div>
                                    <span class="text-muted">28-0-2</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="me-3">1</span>
                                        <span>Victor King</span>
                                    </div>
                                    <span class="text-muted">25-2-0</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="me-3">2</span>
                                        <span>Alexander Williams</span>
                                    </div>
                                    <span class="text-muted">22-1-1</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="me-3">3</span>
                                        <span>James Thompson</span>
                                    </div>
                                    <span class="text-muted">20-2-0</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="me-3">4</span>
                                        <span>Daniel Morrison</span>
                                    </div>
                                    <span class="text-muted">19-3-1</span>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer bg-white text-center">
                            <a href="{{ route('rankings.index') }}" class="btn btn-sm btn-outline-primary">VIEW FULL RANKINGS</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100 ranking-card">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">Welterweight Division</h5>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning text-dark me-2">C</span>
                                        <span>Carlos Mendez</span>
                                    </div>
                                    <span class="text-muted">25-3-1</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="me-3">1</span>
                                        <span>David Rodriguez</span>
                                    </div>
                                    <span class="text-muted">22-1-0</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="me-3">2</span>
                                        <span>Tony Parker</span>
                                    </div>
                                    <span class="text-muted">21-2-0</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="me-3">3</span>
                                        <span>Kevin Nelson</span>
                                    </div>
                                    <span class="text-muted">19-3-1</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="me-3">4</span>
                                        <span>Mark Johnson</span>
                                    </div>
                                    <span class="text-muted">18-2-2</span>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer bg-white text-center">
                            <a href="{{ route('rankings.index') }}" class="btn btn-sm btn-outline-primary">VIEW FULL RANKINGS</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section class="py-5 testimonials-section">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2 class="section-title mb-3">WHAT PEOPLE SAY</h2>
                    <p class="lead mb-5">Fans, fighters, and media praise Nara Promotionz for our world-class events</p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="testimonial-carousel">
                        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="testimonial-item text-center">
                                        <div class="testimonial-image mb-4">
                                            <img src="{{ asset('images/testimonial1.jpg') }}" alt="Testimonial" class="rounded-circle" width="100">
                                        </div>
                                        <p class="testimonial-text mb-3">"Nara Promotionz puts on the best boxing events I've ever attended. The production quality, fighter matchups, and overall experience are second to none."</p>
                                        <h5 class="testimonial-name mb-1">John Davis</h5>
                                        <p class="testimonial-position text-muted">Boxing Fan</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="testimonial-item text-center">
                                        <div class="testimonial-image mb-4">
                                            <img src="{{ asset('images/testimonial2.jpg') }}" alt="Testimonial" class="rounded-circle" width="100">
                                        </div>
                                        <p class="testimonial-text mb-3">"As a professional fighter, working with Nara Promotionz has been the highlight of my career. They truly care about the fighters and create incredible opportunities."</p>
                                        <h5 class="testimonial-name mb-1">Sarah Johnson</h5>
                                        <p class="testimonial-position text-muted">Professional Boxer</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="testimonial-item text-center">
                                        <div class="testimonial-image mb-4">
                                            <img src="{{ asset('images/testimonial3.jpg') }}" alt="Testimonial" class="rounded-circle" width="100">
                                        </div>
                                        <p class="testimonial-text mb-3">"The streaming quality and production value of Nara Promotionz events are outstanding. Their commitment to delivering premium boxing content is unmatched in the industry."</p>
                                        <h5 class="testimonial-name mb-1">Robert Thompson</h5>
                                        <p class="testimonial-position text-muted">Sports Journalist</p>
                                    </div>
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    <section class="py-5 bg-primary text-white newsletter-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="mb-3">STAY IN THE LOOP</h2>
                    <p class="lead mb-4">Subscribe to our newsletter for exclusive updates, fighter interviews, and special offers.</p>
                    
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email" class="form-control form-control-lg" placeholder="Your email address" required>
                            <button class="btn btn-danger" type="submit">SUBSCRIBE</button>
                        </div>
                        <div class="form-check text-start">
                            <input class="form-check-input" type="checkbox" id="acceptTerms" required>
                            <label class="form-check-label" for="acceptTerms">
                                I agree to receive email updates from Nara Promotionz
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-5 bg-dark text-white cta-section">
        <div class="container text-center">
            <h2 class="mb-4">JOIN THE BOXING REVOLUTION</h2>
            <p class="lead mb-4">Create an account to purchase tickets, access live streams, and stay updated with the latest boxing news.</p>
            
            @guest
                <a href="{{ route('register') }}" class="btn btn-danger btn-lg">SIGN UP NOW</a>
            @else
                <a href="{{ route('events.index') }}" class="btn btn-danger btn-lg">EXPLORE EVENTS</a>
            @endguest
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Animation for counters
    $(document).ready(function() {
        $('.counter-number').each(function() {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text().replace('+', '')
            }, {
                duration: 2000,
                easing: 'swing',
                step: function(now) {
                    $(this).text(Math.ceil(now) + '+');
                }
            });
        });
        
        // Initialize carousels with autoplay and indicators
        $('.carousel').carousel({
            interval: 5000,
            pause: 'hover'
        });
    });
</script>
@endsection
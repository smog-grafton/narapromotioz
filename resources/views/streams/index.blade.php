@extends('layouts.app')

@section('title', 'Live Streams')

@section('styles')
<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
<style>
    .stream-card {
        transition: all 0.3s ease;
        border-radius: var(--border-radius-md);
        overflow: hidden;
    }
    
    .stream-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg) !important;
    }
    
    .stream-thumbnail {
        position: relative;
        height: 200px;
        overflow: hidden;
    }
    
    .stream-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .stream-card:hover .stream-thumbnail img {
        transform: scale(1.05);
    }
    
    .stream-status {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
    }
    
    .stream-time {
        position: absolute;
        bottom: 10px;
        left: 10px;
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 5px 10px;
        border-radius: var(--border-radius-sm);
        font-size: 0.8rem;
    }
    
    .stream-info {
        padding: 1.5rem;
    }
    
    .stream-title {
        font-weight: bold;
        margin-bottom: 0.5rem;
        font-family: 'Oswald', sans-serif;
    }
    
    .stream-description {
        color: #666;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .stream-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        font-size: 0.9rem;
    }
    
    .stream-price {
        font-weight: bold;
    }
    
    .stream-access {
        color: #666;
    }
    
    .stream-tabs {
        border-bottom: 2px solid var(--light-gray);
        margin-bottom: 2rem;
    }
    
    .stream-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        border-radius: 0;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        color: var(--text-color);
        transition: all 0.3s ease;
    }
    
    .stream-tabs .nav-link:hover {
        color: var(--sky-blue);
    }
    
    .stream-tabs .nav-link.active {
        background-color: transparent;
        border-bottom: 3px solid var(--sky-blue);
        color: var(--sky-blue);
    }
    
    .featured-stream {
        position: relative;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .featured-stream-content {
        position: relative;
        z-index: 1;
        padding: 3rem;
    }
    
    .featured-stream-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        filter: brightness(0.3);
        z-index: 0;
    }
    
    .featured-stream-title {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 1rem;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
    }
    
    .featured-stream-description {
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        max-width: 700px;
    }
    
    .countdown-wrapper {
        display: flex;
        margin-bottom: 1.5rem;
    }
    
    .countdown-item {
        background-color: rgba(0, 0, 0, 0.5);
        padding: 0.75rem 1rem;
        margin-right: 0.5rem;
        border-radius: var(--border-radius-sm);
        text-align: center;
        min-width: 70px;
    }
    
    .countdown-value {
        font-size: 1.5rem;
        font-weight: bold;
        display: block;
    }
    
    .countdown-label {
        font-size: 0.8rem;
        opacity: 0.8;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 0;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
    <!-- Stream Banner -->
    <div class="container-fluid bg-dark text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 mb-4">LIVE BOXING STREAMS</h1>
                    <p class="lead mb-4">Watch championship fights live from anywhere in the world. Premium quality streams with expert commentary.</p>
                    <p class="mb-4">Subscribe for access to all live and on-demand content, or purchase individual event passes.</p>
                    <a href="#subscription" class="btn btn-primary btn-lg me-3">SUBSCRIPTION PLANS</a>
                    <a href="#upcoming" class="btn btn-outline-light btn-lg">UPCOMING STREAMS</a>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="{{ asset('images/streaming-devices.png') }}" alt="Streaming on multiple devices" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stream Navigation -->
    <div class="container mt-5">
        <ul class="nav nav-tabs stream-tabs" id="streamTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="live-tab" data-bs-toggle="tab" data-bs-target="#live" type="button" role="tab" aria-controls="live" aria-selected="true">
                    LIVE NOW 
                    @if(isset($liveStreams) && $liveStreams->count() > 0)
                        <span class="badge bg-danger ms-2">{{ $liveStreams->count() }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="false">
                    UPCOMING
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab" aria-controls="past" aria-selected="false">
                    PAST STREAMS
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="streamTabsContent">
            <!-- Live Streams Tab -->
            <div class="tab-pane fade show active" id="live" role="tabpanel" aria-labelledby="live-tab">
                @if(isset($liveStreams) && $liveStreams->count() > 0)
                    <!-- Featured Live Stream -->
                    @php $featuredLive = $liveStreams->where('is_featured', true)->first() ?? $liveStreams->first(); @endphp
                    <div class="featured-stream text-white">
                        <div class="featured-stream-bg" style="background-image: url('{{ $featuredLive->thumbnail_url ?? asset('images/featured-stream-bg.jpg') }}');"></div>
                        <div class="featured-stream-content">
                            <span class="live-badge mb-3">LIVE NOW</span>
                            <h2 class="featured-stream-title">{{ $featuredLive->title ?? 'Championship Fight Night' }}</h2>
                            <p class="featured-stream-description">{{ $featuredLive->description ?? 'Witness the clash of titans in this premier boxing event featuring world-class fighters competing for glory and championship belts.' }}</p>
                            <div class="d-flex align-items-center mb-4">
                                <div class="me-4">
                                    <i class="fas fa-users me-2"></i> {{ $featuredLive->viewer_count ?? rand(500, 2000) }} watching now
                                </div>
                                <div>
                                    <i class="fas fa-clock me-2"></i> Started {{ $featuredLive->actual_start ? $featuredLive->actual_start->diffForHumans() : '45 minutes ago' }}
                                </div>
                            </div>
                            <a href="{{ isset($featuredLive) ? route('streams.show', $featuredLive) : '#' }}" class="btn btn-danger btn-lg">
                                <i class="fas fa-play-circle me-2"></i> WATCH NOW
                            </a>
                        </div>
                    </div>
                    
                    <!-- Other Live Streams -->
                    <h3 class="mt-5 mb-4">Also Live Now</h3>
                    <div class="row">
                        @foreach($liveStreams->where('id', '!=', optional($featuredLive)->id)->take(6) as $stream)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card stream-card h-100">
                                    <div class="stream-thumbnail">
                                        <img src="{{ $stream->thumbnail_url ?? asset('images/stream-' . ($loop->index % 3 + 1) . '.jpg') }}" alt="{{ $stream->title }}">
                                        <div class="stream-status">
                                            <span class="live-badge">LIVE</span>
                                        </div>
                                    </div>
                                    <div class="stream-info">
                                        <h4 class="stream-title">{{ $stream->title }}</h4>
                                        <div class="stream-description">{{ $stream->description }}</div>
                                        <div class="stream-meta">
                                            <div class="stream-access">
                                                @if($stream->access_level == 'free')
                                                    <span class="badge bg-success">FREE</span>
                                                @elseif($stream->access_level == 'subscription')
                                                    <span class="badge bg-primary">SUBSCRIPTION</span>
                                                @else
                                                    <span class="stream-price">${{ number_format($stream->price, 2) }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <i class="fas fa-users me-1"></i> {{ $stream->viewer_count ?? rand(100, 1000) }}
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('streams.show', $stream) }}" class="btn btn-primary w-100">WATCH NOW</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State for No Live Streams -->
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-video-slash"></i>
                        </div>
                        <h3 class="mb-3">No Live Streams Right Now</h3>
                        <p class="text-muted mb-4">Check out our upcoming streams or watch past events.</p>
                        <button class="btn btn-primary" id="showUpcomingButton">VIEW UPCOMING STREAMS</button>
                    </div>
                @endif
            </div>
            
            <!-- Upcoming Streams Tab -->
            <div class="tab-pane fade" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                @if(isset($upcomingStreams) && $upcomingStreams->count() > 0)
                    <!-- Featured Upcoming Stream -->
                    @php $featuredUpcoming = $upcomingStreams->where('is_featured', true)->first() ?? $upcomingStreams->first(); @endphp
                    <div class="featured-stream text-white">
                        <div class="featured-stream-bg" style="background-image: url('{{ $featuredUpcoming->thumbnail_url ?? asset('images/upcoming-stream-bg.jpg') }}');"></div>
                        <div class="featured-stream-content">
                            <span class="badge bg-primary mb-3">COMING SOON</span>
                            <h2 class="featured-stream-title">{{ $featuredUpcoming->title ?? 'Heavyweight Championship Bout' }}</h2>
                            <p class="featured-stream-description">{{ $featuredUpcoming->description ?? 'The most anticipated boxing match of the year. Two champions will face off for the unified heavyweight title in a battle that will go down in history.' }}</p>
                            <div class="countdown-wrapper" data-countdown="{{ $featuredUpcoming->scheduled_start ?? '2023-12-25T20:00:00' }}">
                                <div class="countdown-item">
                                    <span class="countdown-value">00</span>
                                    <span class="countdown-label">Days</span>
                                </div>
                                <div class="countdown-item">
                                    <span class="countdown-value">00</span>
                                    <span class="countdown-label">Hours</span>
                                </div>
                                <div class="countdown-item">
                                    <span class="countdown-value">00</span>
                                    <span class="countdown-label">Minutes</span>
                                </div>
                                <div class="countdown-item">
                                    <span class="countdown-value">00</span>
                                    <span class="countdown-label">Seconds</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-4">
                                <div class="me-4">
                                    <i class="fas fa-calendar me-2"></i> {{ $featuredUpcoming->scheduled_start ? $featuredUpcoming->scheduled_start->format('F j, Y - g:i A') : 'December 25, 2023 - 8:00 PM' }}
                                </div>
                            </div>
                            @if($featuredUpcoming->access_level == 'paid')
                                <a href="{{ isset($featuredUpcoming) ? route('streams.purchase', $featuredUpcoming) : '#' }}" class="btn btn-primary btn-lg me-3">
                                    <i class="fas fa-ticket-alt me-2"></i> PRE-ORDER (${{ number_format($featuredUpcoming->price, 2) }})
                                </a>
                            @else
                                <a href="{{ isset($featuredUpcoming) ? route('streams.show', $featuredUpcoming) : '#' }}" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-bell me-2"></i> SET REMINDER
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Other Upcoming Streams -->
                    <h3 class="mt-5 mb-4">More Upcoming Events</h3>
                    <div class="row">
                        @foreach($upcomingStreams->where('id', '!=', optional($featuredUpcoming)->id)->take(6) as $stream)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card stream-card h-100">
                                    <div class="stream-thumbnail">
                                        <img src="{{ $stream->thumbnail_url ?? asset('images/upcoming-' . ($loop->index % 3 + 1) . '.jpg') }}" alt="{{ $stream->title }}">
                                        <div class="stream-time">
                                            <i class="far fa-clock me-1"></i> {{ $stream->scheduled_start ? $stream->scheduled_start->format('M j, Y - g:i A') : 'Coming Soon' }}
                                        </div>
                                    </div>
                                    <div class="stream-info">
                                        <h4 class="stream-title">{{ $stream->title }}</h4>
                                        <div class="stream-description">{{ $stream->description }}</div>
                                        <div class="stream-meta">
                                            <div class="stream-access">
                                                @if($stream->access_level == 'free')
                                                    <span class="badge bg-success">FREE</span>
                                                @elseif($stream->access_level == 'subscription')
                                                    <span class="badge bg-primary">SUBSCRIPTION</span>
                                                @else
                                                    <span class="stream-price">${{ number_format($stream->price, 2) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            @if($stream->access_level == 'paid')
                                                <a href="{{ route('streams.purchase', $stream) }}" class="btn btn-primary w-100">PRE-ORDER</a>
                                            @else
                                                <a href="{{ route('streams.show', $stream) }}" class="btn btn-outline-primary w-100">DETAILS</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State for No Upcoming Streams -->
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="far fa-calendar"></i>
                        </div>
                        <h3 class="mb-3">No Upcoming Streams Scheduled</h3>
                        <p class="text-muted mb-4">We're working on our next schedule. Check back soon for updates.</p>
                        <a href="{{ route('events.index') }}" class="btn btn-primary">BROWSE EVENTS</a>
                    </div>
                @endif
            </div>
            
            <!-- Past Streams Tab -->
            <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                @if(isset($pastStreams) && $pastStreams->count() > 0)
                    <div class="row mt-4">
                        @foreach($pastStreams as $stream)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card stream-card h-100">
                                    <div class="stream-thumbnail">
                                        <img src="{{ $stream->thumbnail_url ?? asset('images/past-' . ($loop->index % 3 + 1) . '.jpg') }}" alt="{{ $stream->title }}">
                                        <div class="stream-status">
                                            <span class="badge bg-secondary">REPLAY</span>
                                        </div>
                                    </div>
                                    <div class="stream-info">
                                        <h4 class="stream-title">{{ $stream->title }}</h4>
                                        <div class="stream-description">{{ $stream->description }}</div>
                                        <div class="d-flex align-items-center text-muted mt-2 mb-3">
                                            <i class="far fa-calendar-alt me-2"></i> {{ $stream->actual_end ? $stream->actual_end->format('M j, Y') : 'Recently concluded' }}
                                            <span class="mx-2">|</span>
                                            <i class="far fa-clock me-2"></i> {{ $stream->formatted_duration ?? '1h 45m' }}
                                        </div>
                                        <div class="stream-meta">
                                            <div class="stream-access">
                                                @if($stream->access_level == 'free')
                                                    <span class="badge bg-success">FREE</span>
                                                @elseif($stream->access_level == 'subscription')
                                                    <span class="badge bg-primary">SUBSCRIPTION</span>
                                                @else
                                                    <span class="stream-price">${{ number_format($stream->price, 2) }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <i class="fas fa-eye me-1"></i> {{ $stream->view_count ?? rand(1000, 10000) }}
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('streams.show', $stream) }}" class="btn btn-primary w-100">WATCH REPLAY</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    @if(isset($pastStreams) && method_exists($pastStreams, 'links'))
                        <div class="d-flex justify-content-center mt-4">
                            {{ $pastStreams->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State for No Past Streams -->
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <h3 class="mb-3">No Past Streams Available</h3>
                        <p class="text-muted mb-4">We haven't archived any past streams yet. Check back after our upcoming events.</p>
                        <button class="btn btn-primary" id="showUpcomingButton2">VIEW UPCOMING STREAMS</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Subscription Plans -->
    <section id="subscription" class="py-5 mt-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="mb-3">SUBSCRIPTION PLANS</h2>
                <p class="lead">Choose the perfect plan for your boxing entertainment needs</p>
            </div>
            
            <div class="row">
                <!-- Basic Plan -->
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-dark text-white text-center py-4">
                            <h3 class="mb-0">BASIC</h3>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="card-price mt-3 mb-4">$9.99<span class="period">/month</span></h4>
                            <ul class="list-group list-group-flush mb-4">
                                <li class="list-group-item">Access to all free streams</li>
                                <li class="list-group-item">HD quality streaming</li>
                                <li class="list-group-item">24-hour replay access</li>
                                <li class="list-group-item">Limited chat access</li>
                                <li class="list-group-item text-muted">No premium events</li>
                                <li class="list-group-item text-muted">No offline downloads</li>
                            </ul>
                            <a href="#" class="btn btn-outline-primary w-100">GET STARTED</a>
                        </div>
                    </div>
                </div>
                
                <!-- Pro Plan -->
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="card h-100 shadow position-relative">
                        <div class="position-absolute top-0 start-50 translate-middle">
                            <span class="badge bg-danger px-3 py-2 rounded-pill">MOST POPULAR</span>
                        </div>
                        <div class="card-header bg-primary text-white text-center py-4">
                            <h3 class="mb-0">PRO</h3>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="card-price mt-3 mb-4">$19.99<span class="period">/month</span></h4>
                            <ul class="list-group list-group-flush mb-4">
                                <li class="list-group-item">Access to all free streams</li>
                                <li class="list-group-item">Full HD & 4K quality</li>
                                <li class="list-group-item">7-day replay access</li>
                                <li class="list-group-item">Full chat access</li>
                                <li class="list-group-item">Most premium events included</li>
                                <li class="list-group-item text-muted">No offline downloads</li>
                            </ul>
                            <a href="#" class="btn btn-primary w-100">SUBSCRIBE NOW</a>
                        </div>
                    </div>
                </div>
                
                <!-- Premium Plan -->
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-dark text-white text-center py-4">
                            <h3 class="mb-0">PREMIUM</h3>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="card-price mt-3 mb-4">$29.99<span class="period">/month</span></h4>
                            <ul class="list-group list-group-flush mb-4">
                                <li class="list-group-item">Access to all streams</li>
                                <li class="list-group-item">Full HD & 4K quality</li>
                                <li class="list-group-item">30-day replay access</li>
                                <li class="list-group-item">Full chat access</li>
                                <li class="list-group-item">All premium events included</li>
                                <li class="list-group-item">Offline downloads</li>
                            </ul>
                            <a href="#" class="btn btn-outline-primary w-100">SUBSCRIBE NOW</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="mb-3">FREQUENTLY ASKED QUESTIONS</h2>
                <p class="lead">Everything you need to know about our streaming service</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="streamingFAQ">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                    What devices can I watch streams on?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="heading1" data-bs-parent="#streamingFAQ">
                                <div class="accordion-body">
                                    You can watch our streams on any device with a modern web browser, including desktops, laptops, tablets, and mobile phones. We also offer dedicated apps for iOS and Android devices, as well as smart TVs and streaming devices like Roku and Apple TV.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                    What is the difference between subscription tiers?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2" data-bs-parent="#streamingFAQ">
                                <div class="accordion-body">
                                    The main differences between our subscription tiers are access to premium events, video quality, replay duration, and additional features like offline downloads. Higher tiers provide more premium content and features.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                    Can I cancel my subscription at any time?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3" data-bs-parent="#streamingFAQ">
                                <div class="accordion-body">
                                    Yes, you can cancel your subscription at any time. Your access will continue until the end of the current billing period, after which it will not renew. There are no cancellation fees or long-term commitments.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                    What internet speed do I need for HD streaming?
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4" data-bs-parent="#streamingFAQ">
                                <div class="accordion-body">
                                    For HD (720p) streaming, we recommend a minimum internet speed of 5 Mbps. For Full HD (1080p), at least 10 Mbps is recommended, and for 4K streaming, we recommend at least 25 Mbps. Our player will automatically adjust quality based on your connection speed.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                    How many devices can I stream on simultaneously?
                                </button>
                            </h2>
                            <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="heading5" data-bs-parent="#streamingFAQ">
                                <div class="accordion-body">
                                    Basic plan allows streaming on 1 device at a time, Pro plan allows 2 simultaneous devices, and Premium plan allows up to 4 devices to stream content simultaneously.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize countdown timers
        const countdownElement = document.querySelector('[data-countdown]');
        if (countdownElement) {
            const targetDate = new Date(countdownElement.dataset.countdown).getTime();
            
            const countdownInterval = setInterval(function() {
                const now = new Date().getTime();
                const distance = targetDate - now;
                
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    countdownElement.innerHTML = '<div class="alert alert-info">Event is starting soon!</div>';
                    return;
                }
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                const daysEl = countdownElement.querySelector('.countdown-item:nth-child(1) .countdown-value');
                const hoursEl = countdownElement.querySelector('.countdown-item:nth-child(2) .countdown-value');
                const minutesEl = countdownElement.querySelector('.countdown-item:nth-child(3) .countdown-value');
                const secondsEl = countdownElement.querySelector('.countdown-item:nth-child(4) .countdown-value');
                
                if (daysEl) daysEl.textContent = days.toString().padStart(2, '0');
                if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
                if (minutesEl) minutesEl.textContent = minutes.toString().padStart(2, '0');
                if (secondsEl) secondsEl.textContent = seconds.toString().padStart(2, '0');
            }, 1000);
        }
        
        // Switch to upcoming tab when empty state button is clicked
        const showUpcomingButtons = document.querySelectorAll('#showUpcomingButton, #showUpcomingButton2');
        showUpcomingButtons.forEach(button => {
            if (button) {
                button.addEventListener('click', function() {
                    const upcomingTab = document.querySelector('#upcoming-tab');
                    if (upcomingTab) {
                        upcomingTab.click();
                    }
                });
            }
        });
    });
</script>
@endsection
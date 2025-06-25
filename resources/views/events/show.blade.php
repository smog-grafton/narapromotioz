@extends('layouts.app')

@section('title', $event->name . ' - ' . config('app.name'))

@section('meta')
    <meta name="description" content="{{ $event->meta_description ?? 'Boxing event: ' . $event->name . ' - ' . $event->tagline }}">
    <meta property="og:title" content="{{ $event->name }} - {{ config('app.name') }}">
    <meta property="og:description" content="{{ $event->meta_description ?? 'Boxing event: ' . $event->name . ' - ' . $event->tagline }}">
    <meta property="og:image" content="{{ $event->featured_image ? asset('storage/' . $event->featured_image) : asset('assets/images/default-event.jpg') }}">
    <meta property="og:url" content="{{ route('events.show', $event->slug) }}">
    <meta name="twitter:card" content="summary_large_image">
@endsection

@section('content')
<!-- Banner Style One Start -->
<section class="banner-style-one">
    <div class="parallax" style="background-image: url({{ $event->banner_path ? asset($event->banner_path) : asset('assets/images/banner/event_banner.jpg') }});"></div>
    <div class="container">
        <div class="row">
            <div class="banner-details">
                <h2>{{ $event->name }}</h2>
                <p>{{ $event->tagline ?? 'Experience the excitement of professional boxing at its finest.' }}</p>
                
                <div class="event-meta-info">
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>{{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}
                        @if($event->event_time)
                            at {{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}
                        @endif
                        </span>
                    </div>
                    
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $event->venue }}, {{ $event->city }}, {{ $event->country }}</span>
                    </div>
                    
                    @if($event->network)
                        <div class="meta-item">
                            <i class="fas fa-tv"></i>
                            <span>{{ $event->network }}</span>
                        </div>
                    @endif
                </div>
                
                <div class="event-actions">
                    @if($isPastEvent)
                        <a href="#results" class="theme-btn">
                            <i class="fas fa-trophy"></i> View Results
                        </a>
                        <a href="#highlights" class="theme-btn theme-btn-outline">
                            <i class="fas fa-play"></i> Watch Highlights
                        </a>
                    @else
                        @if($event->tickets_available)
                            <a href="#tickets" class="theme-btn">
                                <i class="fas fa-ticket-alt"></i> Get Tickets
                            </a>
                        @endif
                        
                        @if($event->isOngoing && !$event->is_free)
                            <a href="#stream" class="theme-btn">
                                <i class="fas fa-play-circle"></i> Watch Live
                            </a>
                        @endif
                        
                        <a href="#fight-card" class="theme-btn theme-btn-outline">
                            <i class="fas fa-list"></i> View Fight Card
                        </a>
                    @endif
                </div>
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
                            <p>Home</p>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('events.index') }}">
                            <i class="fa-solid fa-calendar"></i>
                            <p>Events</p>
                        </a>
                    </li>
                    <li class="current">
                        <p>{{ $event->name }}</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- Banner Style One End -->

    <!-- Event Stream Section (If available) -->
    @if($event->has_stream)
        @include('events.partials._event_stream')
    @endif
    
    <!-- Tickets Section -->
    @if($event->tickets_available)
        @include('events.partials._tickets')
    @endif
    
    <!-- Fight Card Section -->
    @include('events.partials._fight_card')
    
    <!-- Event Results Section (For past events) -->
    @if(!$event->isUpcoming)
        @include('events.partials._event_results')
    @endif
    
    <!-- Event Media Section -->
    @include('events.partials._event_media')
    
    <!-- Related News Section -->
    @include('events.partials._related_news')

    <!-- Event Sponsors Section -->
    @if($event->sponsors && count($event->sponsors) > 0)
        <section class="sponsors-section">
            <div class="container">
                <div class="section-header text-center">
                    <h2 class="section-title">EVENT SPONSORS</h2>
                </div>
                
                <div class="sponsors-grid">
                    @foreach($event->sponsors as $sponsor)
                        <div class="sponsor-item">
                            <a href="{{ $sponsor['url'] ?? '#' }}" target="_blank" class="sponsor-link">
                                <img src="{{ asset('storage/' . $sponsor['logo']) }}" alt="{{ $sponsor['name'] }}" class="sponsor-img">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    
    <!-- Event Newsletter -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-wrapper">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h2 class="newsletter-title">Stay Updated</h2>
                        <p class="newsletter-description">Get the latest news, fight announcements, and exclusive content delivered straight to your inbox.</p>
                    </div>
                    <div class="col-lg-6">
                        <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="email" name="email" class="form-control" placeholder="Your email address" required>
                                <button type="submit" class="btn btn-primary">SUBSCRIBE</button>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="event_updates" id="eventUpdates" checked>
                                <label class="form-check-label" for="eventUpdates">
                                    Send me updates about {{ $event->name }}
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
    /* Event Banner Custom Styles */
    .banner-style-one .banner-details .event-meta-info {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin: 2rem 0;
        align-items: center;
    }
    
    .banner-style-one .banner-details .event-meta-info .meta-item {
        display: flex;
        align-items: center;
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
    }
    
    .banner-style-one .banner-details .event-meta-info .meta-item i {
        color: #dc3545;
        margin-right: 0.75rem;
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }
    
    .banner-style-one .banner-details .event-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .banner-style-one .banner-details .event-actions .theme-btn {
        display: inline-flex;
        align-items: center;
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        font-size: 0.875rem;
    }
    
    .banner-style-one .banner-details .event-actions .theme-btn i {
        margin-right: 0.5rem;
        font-size: 0.9em;
    }
    
    .banner-style-one .banner-details .event-actions .theme-btn-outline {
        background-color: transparent;
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.5);
    }
    
    .banner-style-one .banner-details .event-actions .theme-btn-outline:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .banner-style-one .banner-details .event-meta-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .banner-style-one .banner-details .event-actions {
            flex-direction: column;
            width: 100%;
        }
        
        .banner-style-one .banner-details .event-actions .theme-btn {
            justify-content: center;
            text-align: center;
        }
    }

    /* Sponsors Section Styles */
    .sponsors-section {
        padding: 4rem 0;
        background-color: #0a0a0a;
    }
    
    .sponsors-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 2rem;
        margin-top: 2rem;
    }
    
    .sponsor-item {
        padding: 1rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        height: 120px;
        width: 200px;
        transition: all 0.3s ease;
    }
    
    .sponsor-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        background: rgba(255, 255, 255, 0.1);
    }
    
    .sponsor-link {
        display: block;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .sponsor-img {
        max-width: 80%;
        max-height: 80%;
        filter: grayscale(100%);
        opacity: 0.7;
        transition: all 0.3s ease;
    }
    
    .sponsor-item:hover .sponsor-img {
        filter: grayscale(0);
        opacity: 1;
    }
    
    /* Newsletter Section Styles */
    .newsletter-section {
        padding: 0 0 5rem 0;
        background-color: #0a0a0a;
    }
    
    .newsletter-wrapper {
        background: linear-gradient(to right, #dc3545, #c82333);
        padding: 3rem;
        border-radius: 8px;
        color: #fff;
    }
    
    .newsletter-title {
        font-family: 'Anton', sans-serif;
        font-size: 2.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-transform: uppercase;
    }
    
    .newsletter-description {
        font-size: 1.1rem;
        margin-bottom: 0;
    }
    
    .newsletter-form .input-group .form-control {
        height: 50px;
        border: none;
        padding: 0 1.25rem;
        font-size: 1rem;
        background: #fff;
        color: #333;
    }
    
    .newsletter-form .input-group .form-control:focus {
        box-shadow: none;
    }
    
    .newsletter-form .input-group .btn {
        padding: 0 1.5rem;
        font-weight: 600;
        letter-spacing: 1px;
        border: none;
        background: #333;
        color: #fff;
    }
    
    .newsletter-form .input-group .btn:hover {
        background: #555;
    }
    
    .newsletter-form .form-check {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .newsletter-form .form-check .form-check-input {
        background-color: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.4);
    }
    
    .newsletter-form .form-check .form-check-input:checked {
        background-color: #fff;
        border-color: #fff;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Track event view
        fetch('{{ route("events.track-view", $event->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            credentials: 'same-origin'
        });
        
        // Social share buttons
        const shareButtons = document.querySelectorAll('.share-btn');
        
        shareButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const url = this.getAttribute('data-url');
                const platform = this.getAttribute('data-platform');
                
                let shareUrl;
                
                switch(platform) {
                    case 'facebook':
                        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                        break;
                    case 'twitter':
                        shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent('Check out this boxing event: {{ $event->name }}')}`;
                        break;
                    case 'whatsapp':
                        shareUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent('Check out this boxing event: {{ $event->name }} ' + url)}`;
                        break;
                    case 'telegram':
                        shareUrl = `https://t.me/share/url?url=${encodeURIComponent(url)}&text=${encodeURIComponent('Check out this boxing event: {{ $event->name }}')}`;
                        break;
                    default:
                        return;
                }
                
                window.open(shareUrl, '_blank', 'width=600,height=400');
            });
        });
    });
</script>
@endpush 
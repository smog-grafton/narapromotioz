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
    <!-- Event Hero Section -->
    @include('events.partials._event_hero')

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
    @if($event->sponsors && count(json_decode($event->sponsors, true)) > 0)
        <section class="sponsors-section">
            <div class="container">
                <div class="section-header text-center">
                    <h2 class="section-title">EVENT SPONSORS</h2>
                </div>
                
                <div class="sponsors-grid">
                    @foreach(json_decode($event->sponsors, true) as $sponsor)
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
        background: linear-gradient(to right, $theme-red, darken($theme-red, 20%));
        padding: 3rem;
        border-radius: 8px;
        color: $text-light;
    }
    
    .newsletter-title {
        font-family: $font-family-heading;
        font-size: 2.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-transform: uppercase;
    }
    
    .newsletter-description {
        font-size: 1.1rem;
        margin-bottom: 0;
    }
    
    .newsletter-form {
        .input-group {
            .form-control {
                height: 50px;
                border: none;
                padding: 0 1.25rem;
                font-size: 1rem;
                background: $white;
                color: $text-dark;
                
                &:focus {
                    box-shadow: none;
                }
            }
            
            .btn {
                padding: 0 1.5rem;
                font-weight: 600;
                letter-spacing: 1px;
                border: none;
                background: $text-dark;
                color: $white;
                
                &:hover {
                    background: lighten($text-dark, 10%);
                }
            }
        }
        
        .form-check {
            color: rgba(255, 255, 255, 0.9);
            
            .form-check-input {
                background-color: rgba(255, 255, 255, 0.2);
                border-color: rgba(255, 255, 255, 0.4);
                
                &:checked {
                    background-color: $white;
                    border-color: $white;
                }
            }
            
            .form-check-label {
                font-size: 0.9rem;
            }
        }
    }
    
    /* Responsive Styles */
    @media (max-width: 992px) {
        .newsletter-wrapper {
            padding: 2rem;
        }
        
        .newsletter-form {
            margin-top: 2rem;
        }
    }
    
    @media (max-width: 768px) {
        .sponsor-item {
            width: 160px;
            height: 100px;
        }
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
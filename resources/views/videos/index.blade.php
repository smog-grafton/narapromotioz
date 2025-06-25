@extends('layouts.app')

@section('title', 'Boxing Videos - NARA Promotionz')

@section('meta')
    <meta name="description" content="Watch professional boxing videos, highlights, training sessions, and exclusive content from top boxers and events.">
    <meta name="keywords" content="boxing videos, boxing highlights, boxing training, professional boxing, boxing matches">
    <meta property="og:title" content="Boxing Videos - Professional Boxing Content">
    <meta property="og:description" content="Watch professional boxing videos, highlights, training sessions, and exclusive content from top boxers and events.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
@endsection

@push('styles')
    <style>
        .premium-badge {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            animation: premium-glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes premium-glow {
            from { box-shadow: 0 0 5px rgba(255, 215, 0, 0.5); }
            to { box-shadow: 0 0 15px rgba(255, 215, 0, 0.8); }
        }
        
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
        
        .no-videos {
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            background: #f8f9fa;
            border-radius: 12px;
            border: 2px dashed #dee2e6;
        }
        
        .filter-active {
            background: #e3f2fd !important;
            border-color: #2196f3 !important;
        }
        
        .video-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .video-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        
        .video-card:active {
            transform: translateY(-2px);
        }
    </style>
@endpush

@section('content')
<!-- Banner Style One Start -->
<section class="banner-style-one">
    <div class="parallax" style="background-image: url({{ asset('assets/images/banner/videos_banner.jpg') }});"></div>
    <div class="container">
        <div class="row">
            <div class="banner-details">
                <h2>Boxing Videos</h2>
                <p>Watch the best boxing content from around the world - exclusive fights, training sessions, and behind-the-scenes footage.</p>
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
                    <li class="current">
                        <p>Videos</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- Banner Style One End -->

<div class="videos-page">
    <!-- Video Filters -->
    <div class="video-filters">
        <div class="container">
            <form method="GET" action="{{ route('videos.index') }}" class="filter-container">
                <!-- Search Box -->
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search videos, boxers, or descriptions..."
                           class="form-control">
                </div>

                <!-- Filter Dropdowns -->
                <div class="filter-dropdowns">
                    <select name="category" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ ucfirst($category) }}
                            </option>
                        @endforeach
                    </select>

                    <select name="type" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        @foreach($videoTypes as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>

                    <select name="boxer" onchange="this.form.submit()">
                        <option value="">All Boxers</option>
                        @foreach($boxers as $boxer)
                            <option value="{{ $boxer->id }}" {{ request('boxer') == $boxer->id ? 'selected' : '' }}>
                                {{ $boxer->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="premium" onchange="this.form.submit()">
                        <option value="">All Content</option>
                        <option value="free" {{ request('premium') == 'free' ? 'selected' : '' }}>Free</option>
                        <option value="premium" {{ request('premium') == 'premium' ? 'selected' : '' }}>Premium</option>
                    </select>

                    <select name="sort" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="liked" {{ request('sort') == 'liked' ? 'selected' : '' }}>Most Liked</option>
                        <option value="alphabetical" {{ request('sort') == 'alphabetical' ? 'selected' : '' }}>A-Z</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    </select>
                </div>

                <!-- Results Count -->
                <div class="results-count">
                    {{ $videos->total() }} videos found
                </div>
            </form>
        </div>
    </div>

    <!-- Videos Grid -->
    <div class="container">
        @if($videos->count() > 0)
            <div class="videos-grid">
                @foreach($videos as $video)
                    <div class="video-card" 
                         data-video-id="{{ $video->id }}"
                         data-video-slug="{{ $video->slug }}"
                         data-is-premium="{{ $video->is_premium ? 'true' : 'false' }}"
                         onclick="window.location.href='{{ route('videos.show', $video->slug) }}'">
                        
                        <!-- Video Thumbnail -->
                        <div class="video-thumbnail">
                            <img src="{{ asset($video->getThumbnailPathAttribute()) }}" 
                                 alt="{{ $video->title }}"
                                 loading="lazy">
                            
                            @if($video->is_premium)
                                <div class="premium-badge">
                                    <i class="fas fa-crown"></i> Premium
                                </div>
                            @endif
                            
                            <div class="video-duration">{{ $video->duration }}</div>
                            
                            <div class="play-overlay">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Video Info -->
                        <div class="video-info">
                            <h3 class="video-title">{{ $video->title }}</h3>
                            
                            <div class="video-meta">
                                <div class="video-stats">
                                    <div class="stat-item">
                                        <i class="fas fa-eye"></i>
                                        <span>{{ number_format($video->views_count) }}</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-heart"></i>
                                        <span>{{ number_format($video->likes_count) }}</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $video->published_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($video->description)
                                <div class="video-description">
                                    {{ Str::limit($video->description, 100) }}
                                </div>
                            @endif

                            @php
                                $videoTags = $video->tags;
                                if (is_string($videoTags)) {
                                    $videoTags = json_decode($videoTags, true) ?: [];
                                }
                                if (!is_array($videoTags)) {
                                    $videoTags = [];
                                }
                            @endphp

                            @if($videoTags && count($videoTags) > 0)
                                <div class="video-tags">
                                    @foreach(array_slice($videoTags, 0, 3) as $tag)
                                        <span class="tag">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif

                            @if($video->boxer)
                                <div class="boxer-info">
                                    <div class="boxer-avatar">
                                        @if($video->boxer->image)
                                            <img src="{{ asset('storage/' . $video->boxer->image) }}" 
                                                 alt="{{ $video->boxer->name }}">
                                        @else
                                            <i class="fas fa-user"></i>
                                        @endif
                                    </div>
                                    <span class="boxer-name">{{ $video->boxer->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="videos-pagination">
                {{ $videos->links('pagination.custom') }}
            </div>
        @else
            <div class="no-videos-found text-center py-5">
                <div class="empty-icon mb-4">
                    <i class="fas fa-video" style="font-size: 4rem; color: var(--bs-primary);"></i>
                </div>
                <h4 class="text-light mb-3">No videos found</h4>
                <p class="text-muted mb-4">
                    @if(request()->hasAny(['search', 'category', 'type', 'boxer', 'premium']))
                        Try adjusting your filters or search terms.
                    @else
                        Check back later for new video content.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'category', 'type', 'boxer', 'premium']))
                    <a href="{{ route('videos.index') }}" class="btn btn-primary">
                        <i class="fas fa-refresh me-2"></i>Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Videos page loaded');
    
    // Add hover effects for video cards
    const videoCards = document.querySelectorAll('.video-card');
    
    videoCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.3)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
        });
    });
});
</script>
@endpush
@endsection 
@extends('layouts.app')

@section('title', $video->title)

@section('content')
<!-- Banner Style One Start -->
<section class="banner-style-one">
    <div class="parallax" style="background-image: url({{ asset('assets/images/banner/videos_banner.jpg') }});"></div>
    <div class="container">
        <div class="row">
            <div class="banner-details">
                <h2>{{ Str::limit($video->title, 50) }}</h2>
                <p>{{ $video->description ? Str::limit($video->description, 120) : 'Watch this exclusive boxing video content from Uganda\'s premier boxing promotion company.' }}</p>
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
                        <a href="{{ route('videos.index') }}">
                            <p>Videos</p>
                        </a>
                    </li>
                    <li class="current">
                        <p>{{ Str::limit($video->title, 30) }}</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- Banner Style One End -->

<div class="video-player-page">
    <div class="container-fluid">
        <div class="row">
            <!-- Main Video Player Column -->
            <div class="col-lg-8 col-md-12">
                <div class="video-player-container">
                    <!-- Video Player -->
                    <div class="video-player" id="videoPlayer">
                        @if($video->video_url)
                            @if(str_contains($video->video_url, 'youtube.com') || str_contains($video->video_url, 'youtu.be'))
                                @php
                                    // Extract YouTube video ID from URL
                                    $videoId = '';
                                    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video->video_url, $matches)) {
                                        $videoId = $matches[1];
                                    }
                                @endphp
                                @if($videoId)
                                    <iframe 
                                        src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=0&rel=0&showinfo=0&modestbranding=1" 
                                        frameborder="0" 
                                        allowfullscreen
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        class="video-iframe">
                                    </iframe>
                                @endif
                            @elseif(str_contains($video->video_url, 'vimeo.com'))
                                @php
                                    // Extract Vimeo video ID from URL
                                    $videoId = '';
                                    if (preg_match('/vimeo\.com\/(\d+)/', $video->video_url, $matches)) {
                                        $videoId = $matches[1];
                                    }
                                @endphp
                                @if($videoId)
                                    <iframe 
                                        src="https://player.vimeo.com/video/{{ $videoId }}?autoplay=0&title=0&byline=0&portrait=0" 
                                        frameborder="0" 
                                        allowfullscreen
                                        class="video-iframe">
                                    </iframe>
                                @endif
                            @else
                                <!-- For other external URLs, use a direct iframe -->
                                <iframe 
                                    src="{{ $video->video_url }}" 
                                    frameborder="0" 
                                    allowfullscreen
                                    class="video-iframe">
                                </iframe>
                            @endif
                        @elseif($video->video_path)
                            <!-- For uploaded video files -->
                            <video controls class="video-element">
                                <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <div class="video-placeholder">
                                <i class="fas fa-play-circle"></i>
                                <p>Video not available</p>
                            </div>
                        @endif
                    </div>

                    <!-- Premium Overlay -->
                    @if($video->is_premium && !auth()->check())
                        <div class="premium-overlay">
                            <div class="premium-content">
                                <i class="fas fa-crown"></i>
                                <h3>Premium Content</h3>
                                <p>This video is available for premium members only.</p>
                                <a href="{{ route('login') }}" class="btn btn-primary">Login to Watch</a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Video Information -->
                <div class="video-info">
                    <div class="video-header">
                        <h1 class="video-title">{{ $video->title }}</h1>
                        <div class="video-actions">
                            <div class="video-stats">
                                <span class="views">{{ number_format($video->views_count) }} views</span>
                                <span class="date">{{ $video->published_at->format('M j, Y') }}</span>
                            </div>
                            <div class="action-buttons">
                                <button class="btn btn-action like-btn" onclick="likeVideo({{ $video->id }})">
                                    <i class="fas fa-thumbs-up"></i>
                                    <span>{{ number_format($video->likes_count) }}</span>
                                </button>
                                <button class="btn btn-action share-btn" onclick="shareVideo({{ $video->id }})">
                                    <i class="fas fa-share"></i>
                                    <span>Share</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Video Description -->
                    @if($video->description)
                        <div class="video-description">
                            <div class="description-content" id="descriptionContent">
                                {!! nl2br(e($video->description)) !!}
                            </div>
                            <button class="show-more-btn" id="showMoreBtn" onclick="toggleDescription()">
                                Show more
                            </button>
                        </div>
                    @endif

                    <!-- Video Tags -->
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
                            @foreach($videoTags as $tag)
                                <span class="tag">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Boxer & Event Info -->
                    <div class="video-metadata">
                        @if($video->boxer)
                            <div class="boxer-info">
                                <div class="boxer-avatar">
                                    @if($video->boxer->image)
                                        <img src="{{ asset('storage/' . $video->boxer->image) }}" alt="{{ $video->boxer->name }}">
                                    @else
                                        <div class="avatar-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="boxer-details">
                                    <h3>{{ $video->boxer->name }}</h3>
                                    @if($video->boxer->weight_class)
                                        <p class="weight-class">{{ $video->boxer->weight_class }}</p>
                                    @endif
                                    @if($video->boxer->nationality)
                                        <p class="nationality">{{ $video->boxer->nationality }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($video->event)
                            <div class="event-info">
                                <div class="event-details">
                                    <h4>{{ $video->event->title }}</h4>
                                    @if($video->event->event_date)
                                        <p class="event-date">{{ $video->event->event_date->format('F j, Y') }}</p>
                                    @endif
                                    @if($video->event->venue)
                                        <p class="venue">{{ $video->event->venue }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 col-md-12">
                <div class="video-sidebar">
                    <!-- Related Videos -->
                    @if($relatedVideos->count() > 0)
                        <div class="related-videos">
                            <h3>Related Videos</h3>
                            <div class="related-videos-list">
                                @foreach($relatedVideos as $relatedVideo)
                                    <div class="related-video-item">
                                        <a href="{{ route('videos.show', $relatedVideo->slug) }}" class="related-video-link">
                                            <div class="related-video-thumbnail">
                                                <img src="{{ asset($relatedVideo->getThumbnailPathAttribute()) }}" alt="{{ $relatedVideo->title }}">
                                                @if($relatedVideo->duration)
                                                    <span class="duration">{{ $relatedVideo->duration }}</span>
                                                @endif
                                                @if($relatedVideo->is_premium)
                                                    <span class="premium-badge">
                                                        <i class="fas fa-crown"></i>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="related-video-info">
                                                <h4>{{ $relatedVideo->title }}</h4>
                                                @if($relatedVideo->boxer)
                                                    <p class="boxer-name">{{ $relatedVideo->boxer->name }}</p>
                                                @endif
                                                <div class="video-meta">
                                                    <span class="views">{{ number_format($relatedVideo->views_count) }} views</span>
                                                    <span class="date">{{ $relatedVideo->published_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Featured Videos -->
                    @if($featuredVideos->count() > 0)
                        <div class="featured-videos">
                            <h3>Featured Videos</h3>
                            <div class="featured-videos-list">
                                @foreach($featuredVideos as $featuredVideo)
                                    <div class="featured-video-item">
                                        <a href="{{ route('videos.show', $featuredVideo->slug) }}" class="featured-video-link">
                                            <div class="featured-video-thumbnail">
                                                <img src="{{ asset($featuredVideo->getThumbnailPathAttribute()) }}" alt="{{ $featuredVideo->title }}">
                                                @if($featuredVideo->duration)
                                                    <span class="duration">{{ $featuredVideo->duration }}</span>
                                                @endif
                                                @if($featuredVideo->is_premium)
                                                    <span class="premium-badge">
                                                        <i class="fas fa-crown"></i>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="featured-video-info">
                                                <h4>{{ $featuredVideo->title }}</h4>
                                                @if($featuredVideo->boxer)
                                                    <p class="boxer-name">{{ $featuredVideo->boxer->name }}</p>
                                                @endif
                                                <div class="video-meta">
                                                    <span class="views">{{ number_format($featuredVideo->views_count) }} views</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function likeVideo(videoId) {
    fetch(`/videos/${videoId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update like count
            const likeBtn = document.querySelector('.like-btn span');
            if (likeBtn) {
                likeBtn.textContent = data.likes_count;
            }
            
            // Show feedback
            showNotification(data.message || 'Video liked!', 'success');
        } else {
            showNotification(data.message || 'Error liking video', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error liking video', 'error');
    });
}

function shareVideo(videoId) {
    const url = window.location.href;
    const title = document.querySelector('.video-title').textContent;
    
    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        }).catch(console.error);
    } else if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(() => {
            showNotification('Video URL copied to clipboard!', 'success');
        }).catch(() => {
            promptCopyUrl(url);
        });
    } else {
        promptCopyUrl(url);
    }
}

function promptCopyUrl(url) {
    const textarea = document.createElement('textarea');
    textarea.value = url;
    document.body.appendChild(textarea);
    textarea.select();
    try {
        document.execCommand('copy');
        showNotification('Video URL copied to clipboard!', 'success');
    } catch (err) {
        prompt('Copy this URL:', url);
    }
    document.body.removeChild(textarea);
}

function toggleDescription() {
    const content = document.getElementById('descriptionContent');
    const btn = document.getElementById('showMoreBtn');
    
    if (content.classList.contains('expanded')) {
        content.classList.remove('expanded');
        btn.textContent = 'Show more';
    } else {
        content.classList.add('expanded');
        btn.textContent = 'Show less';
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
</script>
@endsection
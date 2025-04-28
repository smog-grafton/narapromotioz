@extends('layouts.app')

@section('title', isset($stream) ? $stream->title : 'Live Stream')

@section('styles')
<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
<style>
    /* Stream Page Specific Styles */
    .stream-container {
        position: relative;
        background-color: #0a0a0a;
        border-radius: var(--border-radius-md);
        overflow: hidden;
    }
    
    .player-wrapper {
        position: relative;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
    }
    
    #videoPlayer {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    
    .stream-details {
        padding: 1.5rem;
        background-color: var(--white);
        border-radius: var(--border-radius-md);
        margin-top: 1.5rem;
        box-shadow: var(--shadow-sm);
    }
    
    .stream-title-container {
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--light-gray);
    }
    
    .stream-status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: bold;
        margin-right: 0.5rem;
    }
    
    .badge-live {
        background-color: var(--action-red);
        color: white;
        animation: pulse 2s infinite;
    }
    
    .badge-upcoming {
        background-color: var(--sky-blue);
        color: white;
    }
    
    .badge-ended {
        background-color: #6c757d;
        color: white;
    }
    
    .stream-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
        color: #666;
    }
    
    .stream-meta-item {
        display: flex;
        align-items: center;
    }
    
    .stream-meta-item i {
        margin-right: 0.5rem;
    }
    
    .stream-description {
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    
    .stream-options {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--light-gray);
    }
    
    /* Chat Styles */
    .chat-container {
        display: flex;
        flex-direction: column;
        height: 100%;
        max-height: 600px;
        border-radius: var(--border-radius-md);
        overflow: hidden;
        background-color: var(--white);
        box-shadow: var(--shadow-sm);
    }
    
    .chat-header {
        padding: 1rem;
        background-color: var(--dark-navy);
        color: var(--white);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .chat-header h3 {
        margin: 0;
        font-size: 1.2rem;
    }
    
    .chat-viewers {
        background-color: rgba(255, 255, 255, 0.2);
        padding: 0.3rem 0.7rem;
        border-radius: 50px;
        font-size: 0.9rem;
    }
    
    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .chat-message {
        display: flex;
        padding: 0.75rem;
        border-radius: var(--border-radius-sm);
        background-color: #f8f9fa;
        position: relative;
    }
    
    .chat-message.user-message {
        background-color: #e9f5ff;
    }
    
    .chat-message.pinned-message {
        background-color: rgba(255, 215, 0, 0.1);
        border-left: 3px solid var(--gold);
    }
    
    .chat-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }
    
    .chat-content {
        flex: 1;
    }
    
    .chat-user {
        font-weight: bold;
        margin-bottom: 0.25rem;
    }
    
    .chat-time {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .chat-text {
        word-break: break-word;
    }
    
    .chat-footer {
        padding: 1rem;
        border-top: 1px solid var(--light-gray);
    }
    
    .chat-form {
        display: flex;
        gap: 0.5rem;
    }
    
    .chat-input {
        flex: 1;
        border-radius: var(--border-radius-sm);
        border: 1px solid #ced4da;
        padding: 0.5rem 1rem;
        font-size: 1rem;
    }
    
    .chat-btn {
        border-radius: var(--border-radius-sm);
    }
    
    /* Related Streams */
    .related-streams {
        margin-top: 2rem;
    }
    
    .related-stream-card {
        display: flex;
        margin-bottom: 1rem;
        background-color: var(--white);
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
    }
    
    .related-stream-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    
    .related-stream-thumbnail {
        width: 120px;
        height: 70px;
        flex-shrink: 0;
        position: relative;
    }
    
    .related-stream-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .related-stream-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        font-size: 0.7rem;
        padding: 0.1rem 0.4rem;
        border-radius: var(--border-radius-sm);
    }
    
    .related-stream-info {
        padding: 0.5rem 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .related-stream-title {
        font-size: 0.9rem;
        font-weight: var(--font-medium);
        margin-bottom: 0.2rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .related-stream-meta {
        font-size: 0.75rem;
        color: #6c757d;
    }
    
    /* Purchase Panel Styles */
    .purchase-panel {
        background-color: var(--white);
        border-radius: var(--border-radius-md);
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
    }
    
    .purchase-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .purchase-price {
        font-size: 2rem;
        font-weight: bold;
        color: var(--dark-navy);
    }
    
    .purchase-features {
        margin-bottom: 1.5rem;
    }
    
    .purchase-feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
    }
    
    .purchase-feature-item i {
        margin-right: 0.75rem;
        color: var(--sky-blue);
    }
    
    .purchase-options {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    /* Countdown Styles */
    .countdown-container {
        display: flex;
        justify-content: center;
        margin: 2rem 0;
        gap: 1rem;
    }
    
    .countdown-block {
        background-color: var(--dark-navy);
        color: var(--white);
        padding: 1rem;
        min-width: 80px;
        border-radius: var(--border-radius-sm);
        text-align: center;
    }
    
    .countdown-value {
        font-size: 2rem;
        font-weight: bold;
        display: block;
    }
    
    .countdown-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        opacity: 0.8;
    }
    
    /* Mobile Optimizations */
    @media (max-width: 991.98px) {
        .chat-container {
            margin-top: 1.5rem;
            max-height: 400px;
        }
        
        .related-streams {
            margin-top: 1.5rem;
        }
    }
    
    @media (max-width: 767.98px) {
        .stream-options {
            flex-direction: column;
        }
        
        .purchase-panel {
            margin-top: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container mt-4 mb-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Video Player Container -->
            <div class="stream-container">
                @if(isset($stream) && ($stream->status == 'live' || $stream->status == 'ended') && (isset($hasAccess) && $hasAccess))
                    <div class="player-wrapper">
                        <video id="videoPlayer" crossorigin="anonymous" playsinline controls>
                            <source src="{{ $stream->playback_url ?? 'https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4' }}" type="video/mp4">
                            <track kind="captions" label="English" src="{{ asset('captions/en.vtt') }}" srclang="en" default>
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @elseif(isset($stream) && $stream->status == 'upcoming')
                    <!-- Upcoming Stream Placeholder -->
                    <div class="position-relative" style="padding-top: 56.25%;">
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center bg-dark text-white p-4">
                            <h3>This stream is scheduled to start soon</h3>
                            
                            <div class="countdown-container" data-countdown="{{ $stream->scheduled_start ?? '2023-12-25T20:00:00' }}">
                                <div class="countdown-block">
                                    <span class="countdown-value" id="days">00</span>
                                    <span class="countdown-label">Days</span>
                                </div>
                                <div class="countdown-block">
                                    <span class="countdown-value" id="hours">00</span>
                                    <span class="countdown-label">Hours</span>
                                </div>
                                <div class="countdown-block">
                                    <span class="countdown-value" id="minutes">00</span>
                                    <span class="countdown-label">Minutes</span>
                                </div>
                                <div class="countdown-block">
                                    <span class="countdown-value" id="seconds">00</span>
                                    <span class="countdown-label">Seconds</span>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button class="btn btn-primary btn-lg" id="reminderButton">
                                    <i class="fas fa-bell me-2"></i> Set Reminder
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Purchase Required Placeholder -->
                    <div class="position-relative" style="padding-top: 56.25%;">
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center bg-dark text-white p-4">
                            <i class="fas fa-lock fa-3x mb-4"></i>
                            <h3>Access Required</h3>
                            <p class="text-center mb-4">Purchase access to this stream or subscribe to our streaming service to watch.</p>
                            <a href="{{ route('streams.purchase', $stream ?? 1) }}" class="btn btn-primary btn-lg">Purchase Access</a>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Stream Details -->
            <div class="stream-details">
                <div class="stream-title-container">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            @if(isset($stream))
                                @if($stream->status == 'live')
                                    <span class="stream-status-badge badge-live">LIVE</span>
                                @elseif($stream->status == 'upcoming')
                                    <span class="stream-status-badge badge-upcoming">UPCOMING</span>
                                @else
                                    <span class="stream-status-badge badge-ended">ENDED</span>
                                @endif
                            @else
                                <span class="stream-status-badge badge-live">LIVE</span>
                            @endif
                            
                            <h1 class="h2 d-inline">{{ $stream->title ?? 'Championship Boxing Match' }}</h1>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <button class="btn btn-sm btn-outline-primary me-2" id="shareButton">
                                <i class="fas fa-share-alt me-1"></i> Share
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="streamOptionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="streamOptionsDropdown">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-flag me-2"></i> Report Issue</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-info-circle me-2"></i> Stream Info</a></li>
                                    @if(isset($hasAccess) && $hasAccess)
                                        <li><a class="dropdown-item" href="#" id="qualitySelector"><i class="fas fa-cog me-2"></i> Quality Settings</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="stream-meta">
                    <div class="stream-meta-item">
                        <i class="far fa-calendar-alt"></i>
                        <span>{{ isset($stream->scheduled_start) ? $stream->scheduled_start->format('F j, Y - g:i A') : 'December 20, 2023 - 8:00 PM' }}</span>
                    </div>
                    
                    @if(isset($stream) && $stream->status == 'live')
                        <div class="stream-meta-item">
                            <i class="fas fa-circle text-danger"></i>
                            <span>Started {{ isset($stream->actual_start) ? $stream->actual_start->diffForHumans() : '45 minutes ago' }}</span>
                        </div>
                    @elseif(isset($stream) && $stream->status == 'ended')
                        <div class="stream-meta-item">
                            <i class="fas fa-stopwatch"></i>
                            <span>Duration: {{ $stream->formatted_duration ?? '1 hour 45 minutes' }}</span>
                        </div>
                    @endif
                    
                    <div class="stream-meta-item">
                        <i class="fas fa-eye"></i>
                        <span>{{ $stream->view_count ?? rand(500, 5000) }} viewers</span>
                    </div>
                </div>
                
                <div class="stream-description">
                    <p>{{ $stream->description ?? 'Experience the ultimate championship boxing match live as two titans of the ring face off in an epic battle for the belt. This highly anticipated event features world-class athletes at the peak of their careers, promising an unforgettable night of elite boxing action.' }}</p>
                    
                    @if(isset($stream) && !empty($stream->fighters))
                        <div class="mt-4">
                            <h4>Featured Fighters</h4>
                            <div class="row mt-3">
                                @foreach($stream->fighters as $fighter)
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $fighter->profile_image ?? asset('images/fighter-placeholder.jpg') }}" alt="{{ $fighter->name }}" class="rounded-circle me-3" width="50" height="50">
                                            <div>
                                                <h5 class="mb-0">{{ $fighter->name }}</h5>
                                                <div>{{ $fighter->record }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="stream-options">
                    <button class="btn btn-outline-primary" id="fullscreenButton">
                        <i class="fas fa-expand me-2"></i> Fullscreen
                    </button>
                    
                    @if(isset($hasAccess) && $hasAccess)
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" id="qualityDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cog me-2"></i> Quality: <span id="currentQuality">Auto</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="qualityDropdown">
                                <li><a class="dropdown-item quality-option" data-quality="auto" href="#">Auto</a></li>
                                <li><a class="dropdown-item quality-option" data-quality="1080" href="#">1080p</a></li>
                                <li><a class="dropdown-item quality-option" data-quality="720" href="#">720p</a></li>
                                <li><a class="dropdown-item quality-option" data-quality="480" href="#">480p</a></li>
                                <li><a class="dropdown-item quality-option" data-quality="360" href="#">360p</a></li>
                            </ul>
                        </div>
                    @endif
                    
                    <button class="btn btn-outline-secondary ms-auto" id="reportButton">
                        <i class="fas fa-flag me-2"></i> Report Issue
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            @if(isset($hasAccess) && $hasAccess && isset($stream) && $stream->status == 'live')
                <!-- Live Chat -->
                <div class="chat-container">
                    <div class="chat-header">
                        <h3><i class="far fa-comments me-2"></i> Live Chat</h3>
                        <div class="chat-viewers">
                            <i class="fas fa-user me-1"></i> <span id="chatViewerCount">{{ $stream->chat_user_count ?? rand(50, 500) }}</span>
                        </div>
                    </div>
                    
                    <div class="chat-body" id="chatMessages">
                        @php
                            // Sample chat messages for demonstration
                            $userNames = ['John Smith', 'Sarah Johnson', 'Mike Williams', 'Emily Davis', 'Carlos Rodriguez', 'Lisa Wang'];
                            $messages = [
                                'Great fight so far!',
                                'That left hook was incredible!',
                                'Who do you think will win?',
                                'The champion is looking strong tonight',
                                'Amazing footwork from both fighters',
                                'That was a close call!',
                                'I\'m predicting a knockout in round 7',
                                'The defense is solid tonight',
                                'This is why boxing is the best sport',
                                'Can\'t believe that counter punch'
                            ];
                            $timestamps = [];
                            for ($i = 0; $i < 8; $i++) {
                                $timestamps[] = Carbon\Carbon::now()->subMinutes(rand(1, 30))->format('h:i A');
                            }
                        @endphp
                        
                        @for($i = 0; $i < 8; $i++)
                            <div class="chat-message {{ $i === 2 ? 'pinned-message' : ($i === 4 ? 'user-message' : '') }}">
                                <img src="{{ asset('images/avatar-' . (($i % 5) + 1) . '.jpg') }}" alt="User Avatar" class="chat-avatar">
                                <div class="chat-content">
                                    <div class="d-flex justify-content-between">
                                        <div class="chat-user">{{ $userNames[$i % count($userNames)] }}</div>
                                        <div class="chat-time">{{ $timestamps[$i] }}</div>
                                    </div>
                                    <div class="chat-text">{{ $messages[$i % count($messages)] }}</div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    
                    <div class="chat-footer">
                        <form class="chat-form" id="chatForm">
                            <input type="text" class="chat-input" id="chatInput" placeholder="Type a message..." {{ auth()->check() ? '' : 'disabled' }}>
                            <button type="submit" class="btn btn-primary chat-btn" {{ auth()->check() ? '' : 'disabled' }}>
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                        @if(!auth()->check())
                            <div class="text-center mt-2 text-muted">
                                <small>Please <a href="{{ route('login') }}">log in</a> to join the conversation</small>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif(isset($stream) && $stream->status != 'live' && (!isset($hasAccess) || !$hasAccess))
                <!-- Purchase Panel -->
                <div class="purchase-panel">
                    <div class="purchase-header">
                        <h3 class="mb-3">Stream Access</h3>
                        <div class="purchase-price">${{ $stream->price ?? '19.99' }}</div>
                        <p class="text-muted">One-time payment for full access</p>
                    </div>
                    
                    <div class="purchase-features">
                        <div class="purchase-feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ isset($stream) && $stream->status == 'ended' ? 'Full replay access' : 'Live and replay access' }}</span>
                        </div>
                        <div class="purchase-feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>HD & 4K quality</span>
                        </div>
                        <div class="purchase-feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Live chat during stream</span>
                        </div>
                        <div class="purchase-feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>30-day replay period</span>
                        </div>
                        <div class="purchase-feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>All devices supported</span>
                        </div>
                    </div>
                    
                    <div class="purchase-options">
                        <a href="{{ route('streams.purchase', $stream ?? 1) }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-credit-card me-2"></i> Purchase Now
                        </a>
                        
                        <div class="text-center mt-3">
                            <p class="mb-2">Or get access with a subscription</p>
                            <a href="#subscription" class="btn btn-outline-primary">
                                <i class="fas fa-crown me-2"></i> View Subscription Plans
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Related Streams -->
            <div class="related-streams">
                <h4 class="mb-3">Related Streams</h4>
                
                @if(isset($relatedStreams) && $relatedStreams->count() > 0)
                    @foreach($relatedStreams as $relatedStream)
                        <a href="{{ route('streams.show', $relatedStream) }}" class="text-decoration-none">
                            <div class="related-stream-card">
                                <div class="related-stream-thumbnail">
                                    <img src="{{ $relatedStream->thumbnail_url ?? asset('images/stream-thumbnail-' . ($loop->index % 3 + 1) . '.jpg') }}" alt="{{ $relatedStream->title }}">
                                    @if($relatedStream->status == 'live')
                                        <span class="related-stream-badge badge-live">LIVE</span>
                                    @elseif($relatedStream->status == 'upcoming')
                                        <span class="related-stream-badge badge-upcoming">SOON</span>
                                    @endif
                                </div>
                                <div class="related-stream-info">
                                    <div class="related-stream-title">{{ $relatedStream->title }}</div>
                                    <div class="related-stream-meta">
                                        @if($relatedStream->status == 'live')
                                            <i class="fas fa-circle text-danger me-1" style="font-size: 8px;"></i> Live Now
                                        @elseif($relatedStream->status == 'upcoming')
                                            <i class="far fa-clock me-1"></i> {{ $relatedStream->scheduled_start->format('M j, g:i A') }}
                                        @else
                                            <i class="fas fa-play-circle me-1"></i> {{ $relatedStream->view_count ?? rand(1000, 50000) }} views
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <!-- Sample Related Streams -->
                    @php
                        $sampleTitles = [
                            'Heavyweight Championship: Smith vs. Johnson',
                            'Welterweight Title Fight: Rodriguez vs. Thompson',
                            'Women\'s Boxing: Davis vs. Martinez',
                            'Rising Stars: Young vs. Phillips',
                            'Lightweight Classic: Garcia vs. Lewis'
                        ];
                        $sampleStatuses = ['live', 'upcoming', 'ended', 'upcoming', 'ended'];
                    @endphp
                    
                    @for($i = 0; $i < 5; $i++)
                        <a href="#" class="text-decoration-none">
                            <div class="related-stream-card">
                                <div class="related-stream-thumbnail">
                                    <img src="{{ asset('images/stream-thumbnail-' . (($i % 3) + 1) . '.jpg') }}" alt="{{ $sampleTitles[$i] }}">
                                    @if($sampleStatuses[$i] == 'live')
                                        <span class="related-stream-badge badge-live">LIVE</span>
                                    @elseif($sampleStatuses[$i] == 'upcoming')
                                        <span class="related-stream-badge badge-upcoming">SOON</span>
                                    @endif
                                </div>
                                <div class="related-stream-info">
                                    <div class="related-stream-title">{{ $sampleTitles[$i] }}</div>
                                    <div class="related-stream-meta">
                                        @if($sampleStatuses[$i] == 'live')
                                            <i class="fas fa-circle text-danger me-1" style="font-size: 8px;"></i> Live Now
                                        @elseif($sampleStatuses[$i] == 'upcoming')
                                            <i class="far fa-clock me-1"></i> {{ Carbon\Carbon::now()->addDays($i)->format('M j, g:i A') }}
                                        @else
                                            <i class="fas fa-play-circle me-1"></i> {{ rand(1000, 50000) }} views
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endfor
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Subscription Section -->
<section id="subscription" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-3">SUBSCRIPTION PLANS</h2>
            <p class="lead">Get unlimited access to all our live streams and replays</p>
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

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Share This Stream</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Share this boxing match with your friends and followers:</p>
                
                <div class="d-flex justify-content-center gap-3 mb-4">
                    <a href="#" class="btn btn-outline-primary" target="_blank">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="btn btn-outline-info" target="_blank">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="btn btn-outline-success" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="#" class="btn btn-outline-secondary" target="_blank">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
                
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="shareLink" value="{{ isset($stream) ? route('streams.show', $stream) : url()->current() }}" readonly>
                    <button class="btn btn-primary" type="button" id="copyLinkBtn">Copy</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Report an Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm">
                    <div class="mb-3">
                        <label for="issueType" class="form-label">Issue Type</label>
                        <select class="form-select" id="issueType" required>
                            <option value="" selected disabled>Select an issue type</option>
                            <option value="playback">Playback Problem</option>
                            <option value="quality">Video Quality</option>
                            <option value="audio">Audio Problem</option>
                            <option value="chat">Chat Issue</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="issueDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="issueDescription" rows="4" placeholder="Please describe the issue you're experiencing..." required></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="contactCheck">
                        <label class="form-check-label" for="contactCheck">Contact me about this issue</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitReportBtn">Submit Report</button>
            </div>
        </div>
    </div>
</div>

<!-- Reminder Modal -->
<div class="modal fade" id="reminderModal" tabindex="-1" aria-labelledby="reminderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reminderModalLabel">Set a Reminder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>We'll remind you when this stream is about to start. How would you like to be notified?</p>
                
                <form id="reminderForm">
                    <div class="mb-3">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="emailReminder" checked>
                            <label class="form-check-label" for="emailReminder">
                                Email notification
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="browserReminder" checked>
                            <label class="form-check-label" for="browserReminder">
                                Browser notification
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="calendarReminder">
                            <label class="form-check-label" for="calendarReminder">
                                Add to calendar
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">When to remind you:</label>
                        <select class="form-select" id="reminderTime">
                            <option value="5">5 minutes before</option>
                            <option value="15">15 minutes before</option>
                            <option value="30" selected>30 minutes before</option>
                            <option value="60">1 hour before</option>
                            <option value="1440">1 day before</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="setReminderBtn">Set Reminder</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize video player
        const player = new Plyr('#videoPlayer', {
            controls: [
                'play-large', 'play', 'progress', 'current-time', 'mute', 
                'volume', 'captions', 'settings', 'pip', 'airplay', 'fullscreen'
            ],
            settings: ['captions', 'quality', 'speed'],
            quality: {
                default: 720,
                options: [1080, 720, 480, 360]
            }
        });
        
        // Share functionality
        const shareButton = document.getElementById('shareButton');
        if (shareButton) {
            shareButton.addEventListener('click', function() {
                const shareModal = new bootstrap.Modal(document.getElementById('shareModal'));
                shareModal.show();
            });
        }
        
        // Copy link button
        const copyLinkBtn = document.getElementById('copyLinkBtn');
        if (copyLinkBtn) {
            copyLinkBtn.addEventListener('click', function() {
                const shareLink = document.getElementById('shareLink');
                shareLink.select();
                document.execCommand('copy');
                copyLinkBtn.textContent = 'Copied!';
                setTimeout(() => {
                    copyLinkBtn.textContent = 'Copy';
                }, 2000);
            });
        }
        
        // Report button
        const reportButton = document.getElementById('reportButton');
        if (reportButton) {
            reportButton.addEventListener('click', function() {
                const reportModal = new bootstrap.Modal(document.getElementById('reportModal'));
                reportModal.show();
            });
        }
        
        // Submit report button
        const submitReportBtn = document.getElementById('submitReportBtn');
        if (submitReportBtn) {
            submitReportBtn.addEventListener('click', function() {
                const form = document.getElementById('reportForm');
                if (form.checkValidity()) {
                    // Here you would normally send the report to the server
                    // For this example, we'll just show a success message
                    alert('Thank you for your report. We will investigate the issue.');
                    bootstrap.Modal.getInstance(document.getElementById('reportModal')).hide();
                } else {
                    form.reportValidity();
                }
            });
        }
        
        // Reminder button
        const reminderButton = document.getElementById('reminderButton');
        if (reminderButton) {
            reminderButton.addEventListener('click', function() {
                const reminderModal = new bootstrap.Modal(document.getElementById('reminderModal'));
                reminderModal.show();
            });
        }
        
        // Set reminder button
        const setReminderBtn = document.getElementById('setReminderBtn');
        if (setReminderBtn) {
            setReminderBtn.addEventListener('click', function() {
                // Here you would normally set the reminder in the server
                // For this example, we'll just show a success message
                alert('Reminder set! We\'ll notify you before the stream starts.');
                bootstrap.Modal.getInstance(document.getElementById('reminderModal')).hide();
            });
        }
        
        // Quality selector dropdown
        const qualityOptions = document.querySelectorAll('.quality-option');
        const currentQuality = document.getElementById('currentQuality');
        
        if (qualityOptions.length > 0 && currentQuality) {
            qualityOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    const quality = this.dataset.quality;
                    currentQuality.textContent = quality === 'auto' ? 'Auto' : quality + 'p';
                    
                    // Here you would normally change the video quality
                    // If using Plyr, you could do something like:
                    // player.quality = quality;
                });
            });
        }
        
        // Fullscreen button
        const fullscreenButton = document.getElementById('fullscreenButton');
        if (fullscreenButton && player) {
            fullscreenButton.addEventListener('click', function() {
                player.fullscreen.enter();
            });
        }
        
        // Chat functionality
        const chatForm = document.getElementById('chatForm');
        const chatInput = document.getElementById('chatInput');
        const chatMessages = document.getElementById('chatMessages');
        
        if (chatForm && chatInput && chatMessages) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const message = chatInput.value.trim();
                if (!message) return;
                
                // Create a new message element
                const messageEl = document.createElement('div');
                messageEl.className = 'chat-message user-message';
                
                const currentTime = new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
                
                messageEl.innerHTML = `
                    <img src="{{ auth()->check() && auth()->user()->profile_photo_url ? auth()->user()->profile_photo_url : asset('images/avatar-default.jpg') }}" alt="Your Avatar" class="chat-avatar">
                    <div class="chat-content">
                        <div class="d-flex justify-content-between">
                            <div class="chat-user">{{ auth()->check() ? auth()->user()->name : 'You' }}</div>
                            <div class="chat-time">${currentTime}</div>
                        </div>
                        <div class="chat-text">${message}</div>
                    </div>
                `;
                
                // Add to chat and scroll to bottom
                chatMessages.appendChild(messageEl);
                chatMessages.scrollTop = chatMessages.scrollHeight;
                
                // Clear input
                chatInput.value = '';
                
                // Here you would normally send the message to the server via WebSocket
                // For demonstration, we'll simulate a response
                setTimeout(() => {
                    // Sample response
                    const names = ['John', 'Sarah', 'Mike', 'Emily', 'David'];
                    const responses = [
                        'I agree with you!',
                        'That\'s a good point.',
                        'What do you think about the left hook?',
                        'The challenger is looking good in this round.',
                        'I think we\'ll see a knockout soon.'
                    ];
                    
                    const responseIndex = Math.floor(Math.random() * responses.length);
                    const nameIndex = Math.floor(Math.random() * names.length);
                    
                    const responseEl = document.createElement('div');
                    responseEl.className = 'chat-message';
                    responseEl.innerHTML = `
                        <img src="{{ asset('images/avatar-' . (rand(1, 5)) . '.jpg') }}" alt="User Avatar" class="chat-avatar">
                        <div class="chat-content">
                            <div class="d-flex justify-content-between">
                                <div class="chat-user">${names[nameIndex]}</div>
                                <div class="chat-time">${currentTime}</div>
                            </div>
                            <div class="chat-text">${responses[responseIndex]}</div>
                        </div>
                    `;
                    
                    chatMessages.appendChild(responseEl);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 2000);
            });
        }
        
        // Countdown timer for upcoming streams
        const countdownContainer = document.querySelector('[data-countdown]');
        if (countdownContainer) {
            const targetDate = new Date(countdownContainer.dataset.countdown).getTime();
            
            const countdownInterval = setInterval(function() {
                const now = new Date().getTime();
                const distance = targetDate - now;
                
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    document.getElementById('days').textContent = '00';
                    document.getElementById('hours').textContent = '00';
                    document.getElementById('minutes').textContent = '00';
                    document.getElementById('seconds').textContent = '00';
                    return;
                }
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById('days').textContent = days.toString().padStart(2, '0');
                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
            }, 1000);
        }
    });
</script>
@endsection
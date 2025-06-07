@extends('layouts.app')

@section('title', $boxer->name . ' - Professional Boxer Profile')

@section('content')
<div class="boxer-detail-page">
    <!-- Boxer Hero Section -->
    <section class="boxer-hero-section" style="background-image: url({{ asset('assets/images/page-title-bg.jpg') }});">
        <div class="hero-background-overlay"></div>
        <div class="container">
            <div class="row g-4 align-items-center min-vh-100">
                <!-- Boxer Image -->
                <div class="col-lg-5 col-md-6">
                    <div class="boxer-hero-image text-center">
                        <img src="{{ asset($boxer->image_path) }}" 
                             alt="{{ $boxer->name }}" 
                             class="img-fluid boxer-image">
                    </div>
                </div>

                <!-- Boxer Info -->
                <div class="col-lg-7 col-md-6">
                    <div class="boxer-hero-content">
                        <!-- Titles -->
                        <div class="boxer-titles mb-3">
                            @if($boxer->titles && is_array($boxer->titles) && count($boxer->titles) > 0)
                                @foreach($boxer->titles as $title)
                                    <span class="title-badge champion">{{ $title }}</span>
                                @endforeach
                            @endif
                        </div>

                        <!-- Name and Weight Class -->
                        <h1 class="boxer-name">{{ $boxer->name }}</h1>
                        <h2 class="boxer-weight-class">{{ $boxer->weight_class }} Division</h2>

                        <!-- Global Ranking with Professional Status -->
                        <div class="ranking-status-row mb-3">
                            <div class="boxer-ranking">
                                <i class="fas fa-globe me-2"></i>
                                <span class="ranking-text">
                                    #{{ number_format($boxer->global_ranking) }} / {{ number_format($boxer->total_fighters_in_division) }}
                                    <small class="text-muted">in {{ $boxer->weight_class }}</small>
                                </span>
                            </div>
                            <div class="boxer-status">
                                <span class="status-badge">{{ $boxer->status }}</span>
                            </div>
                        </div>

                        <!-- Record -->
                        <div class="boxer-record mb-4">
                            <div class="record-item">
                                <strong>{{ $boxer->wins }}</strong>
                                <span>Wins</span>
                            </div>
                            <div class="record-item">
                                <strong>{{ $boxer->losses }}</strong>
                                <span>Losses</span>
                            </div>
                            <div class="record-item">
                                <strong>{{ $boxer->draws }}</strong>
                                <span>Draws</span>
                            </div>
                            <div class="record-item">
                                <strong>{{ $boxer->knockouts }}</strong>
                                <span>KOs</span>
                            </div>
                            <div class="record-item">
                                <strong>{{ $boxer->kos_lost }}</strong>
                                <span>KOs Lost</span>
                            </div>
                        </div>

                        <!-- Fighter Details -->
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="boxer-details-list">
                                    <p><strong>Age:</strong> {{ $boxer->age }} years</p>
                                    <p><strong>Height:</strong> {{ $boxer->height }}</p>
                                    <p><strong>Reach:</strong> {{ $boxer->reach }}</p>
                                    <p><strong>Stance:</strong> {{ $boxer->stance }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="boxer-details-list">
                                    <p><strong>Hometown:</strong> {{ $boxer->hometown }}</p>
                                    <p><strong>Country:</strong> {{ $boxer->country }}</p>
                                    <p><strong>Debut:</strong> {{ \Carbon\Carbon::parse($boxer->debut_date)->format('M j, Y') }}</p>
                                    <p><strong>Career:</strong> 
                                        {{ $boxer->career_start }}{{ $boxer->career_end ? ' - ' . $boxer->career_end : ' - Present' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Performance Stats -->
                        <div class="boxer-performance-stats mt-4">
                            <div class="row g-4">
                                <div class="col-4">
                                    <div class="performance-stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-fist-raised"></i>
                                        </div>
                                        <div class="stat-content">
                                        <div class="stat-number">{{ $boxer->knockout_rate }}%</div>
                                        <div class="stat-label">KO Rate</div>
                                        </div>
                                        <div class="stat-progress">
                                            <div class="progress-bar" style="width: {{ $boxer->knockout_rate }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="performance-stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-trophy"></i>
                                        </div>
                                        <div class="stat-content">
                                        <div class="stat-number">{{ $boxer->win_rate }}%</div>
                                        <div class="stat-label">Win Rate</div>
                                        </div>
                                        <div class="stat-progress">
                                            <div class="progress-bar" style="width: {{ $boxer->win_rate }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="performance-stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div class="stat-content">
                                        <div class="stat-number">{{ $boxer->years_pro }}</div>
                                        <div class="stat-label">Years Pro</div>
                                        </div>
                                        <div class="stat-progress">
                                            <div class="progress-bar" style="width: {{ min(100, ($boxer->years_pro / 20) * 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Page Title Section -->
    <section class="page-title-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-content">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/') }}">
                                        <i class="fas fa-home me-1"></i>Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#" onclick="window.history.back()">Boxers</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $boxer->name }}
                                </li>
                            </ol>
                        </nav>
                        <h1 class="page-title">{{ $boxer->name }}</h1>
                        <p class="page-subtitle">Professional Boxer Profile</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Boxing Stats Section -->
    <section class="boxing-stats-section">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Boxing Statistics</h2>
                <p class="section-subtitle">Professional Career Performance</p>
            </div>
            
            <div class="boxing-stats-grid">
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6 col-4">
                        <div class="boxing-stat-card wins">
                            <div class="stat-icon-bg">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $boxer->wins }}</div>
                            <div class="stat-label">Total Wins</div>
                            <div class="stat-description">Professional victories</div>
                            </div>
                            <div class="stat-visual">
                                <div class="stat-ring">
                                    <svg class="ring-progress" viewBox="0 0 120 120">
                                        <circle cx="60" cy="60" r="54"></circle>
                                        <circle cx="60" cy="60" r="54"></circle>
                                    </svg>
                                    <div class="ring-center">{{ $boxer->win_rate }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 col-4">
                        <div class="boxing-stat-card knockouts">
                            <div class="stat-icon-bg">
                                <i class="fas fa-fist-raised"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $boxer->knockouts }}</div>
                            <div class="stat-label">Knockouts</div>
                            <div class="stat-description">KO victories</div>
                            </div>
                            <div class="stat-visual">
                                <div class="stat-ring">
                                    <svg class="ring-progress" viewBox="0 0 120 120">
                                        <circle cx="60" cy="60" r="54"></circle>
                                        <circle cx="60" cy="60" r="54"></circle>
                                    </svg>
                                    <div class="ring-center">{{ $boxer->knockout_rate }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 col-4">
                        <div class="boxing-stat-card losses">
                            <div class="stat-icon-bg">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $boxer->losses }}</div>
                            <div class="stat-label">Losses</div>
                            <div class="stat-description">Career defeats</div>
                            </div>
                            <div class="stat-visual">
                                <div class="stat-ring">
                                    <svg class="ring-progress" viewBox="0 0 120 120">
                                        <circle cx="60" cy="60" r="54"></circle>
                                        <circle cx="60" cy="60" r="54"></circle>
                                    </svg>
                                    <div class="ring-center">{{ $boxer->losses > 0 ? round(($boxer->losses / ($boxer->wins + $boxer->losses + $boxer->draws)) * 100) : 0 }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 col-4">
                        <div class="boxing-stat-card experience">
                            <div class="stat-icon-bg">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div class="stat-content">
                            <div class="stat-number">{{ $boxer->years_pro }}</div>
                            <div class="stat-label">Years Active</div>
                            <div class="stat-description">Professional career</div>
                            </div>
                            <div class="stat-visual">
                                <div class="stat-ring">
                                    <svg class="ring-progress" viewBox="0 0 120 120">
                                        <circle cx="60" cy="60" r="54"></circle>
                                        <circle cx="60" cy="60" r="54"></circle>
                                    </svg>
                                    <div class="ring-center">{{ $boxer->years_pro }}y</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Performance Metrics -->
            <div class="performance-metrics mt-5">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="metric-card">
                            <div class="metric-header">
                                <i class="fas fa-chart-line"></i>
                                <span>Fight Record</span>
                            </div>
                            <div class="metric-value">{{ $boxer->wins }}-{{ $boxer->losses }}-{{ $boxer->draws }}</div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="metric-card">
                            <div class="metric-header">
                                <i class="fas fa-bullseye"></i>
                                <span>Power Rating</span>
                            </div>
                            <div class="metric-value">{{ round(($boxer->knockouts / max(1, $boxer->wins)) * 10) }}/10</div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="metric-card">
                            <div class="metric-header">
                                <i class="fas fa-ranking-star"></i>
                                <span>Global Rank</span>
                            </div>
                            <div class="metric-value">#{{ $boxer->global_ranking }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Biography Section -->
    <section class="boxer-biography-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header text-center mb-5">
                    <h2 class="section-title">Biography</h2>
                        <p class="section-subtitle">{{ $boxer->name }}'s Boxing Journey</p>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="bio-content">
                        <div class="bio-text" id="bioText">
                            <p class="lead-paragraph">{{ $boxer->bio }}</p>
                            
                            <p class="bio-paragraph">Throughout {{ $boxer->name }}'s {{ $boxer->years_pro }}-year professional career, they have consistently demonstrated exceptional skill, athletic prowess, and mental fortitude. Their {{ strtolower($boxer->stance) }} stance and impressive {{ $boxer->reach }} reach have proven to be significant advantages in the ring, allowing them to control distance and deliver powerful combinations.</p>
                            
                            <div class="bio-expandable" style="display: none;">
                                <p class="bio-paragraph">{{ $boxer->name }}'s training regimen is legendary among boxing circles. They maintain peak physical condition through rigorous daily workouts, combining traditional boxing techniques with modern strength and conditioning methods. Their dedication to the sport extends beyond personal achievement, as they actively mentor young fighters in their hometown of {{ $boxer->hometown }}.</p>
                                
                                <p class="bio-paragraph">Born and raised in {{ $boxer->hometown }}, {{ $boxer->country }}, {{ $boxer->name }} discovered boxing at a young age. Their natural talent was evident from the beginning, but it was their unwavering dedication and relentless work ethic that truly set them apart from their peers. The journey to professional boxing was not without challenges, but each obstacle only served to strengthen their resolve.</p>
                                
                                <p class="bio-paragraph">The transition from amateur to professional boxing marked a new chapter in {{ $boxer->name }}'s career. With each fight, they continued to evolve and adapt, studying opponents meticulously and constantly refining their technique. Their ability to read the ring and make split-second tactical decisions has become one of their greatest assets.</p>
                                
                                <p class="bio-paragraph">Beyond the ring, {{ $boxer->name }} is known for their charitable work and community involvement. They regularly visit schools and youth centers, sharing their story and inspiring the next generation to pursue their dreams with the same passion and determination that has defined their own career.</p>
                            </div>
                            
                            <button class="load-more-btn" id="loadMoreBtn">
                                <span class="btn-text">Read More</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="bio-highlights-card">
                        <h4 class="highlights-title">Career Highlights</h4>
                        <div class="highlights-list">
                            @if($boxer->titles && is_array($boxer->titles) && count($boxer->titles) > 0)
                                <div class="highlight-section">
                                    <h5 class="highlight-subtitle">
                                        <i class="fas fa-trophy"></i>
                                        Championships
                                    </h5>
                                    <ul class="highlight-items">
                                        @foreach($boxer->titles as $title)
                                            <li>{{ $title }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <div class="highlight-section">
                                <h5 class="highlight-subtitle">
                                    <i class="fas fa-chart-bar"></i>
                                    Career Statistics
                                </h5>
                                <ul class="highlight-items">
                                    <li>{{ $boxer->wins }} Professional Wins</li>
                                    <li>{{ $boxer->knockouts }} Knockout Victories</li>
                                    <li>{{ $boxer->knockout_rate }}% Knockout Rate</li>
                                    <li>{{ $boxer->win_rate }}% Win Rate</li>
                                    <li>{{ $boxer->years_pro }} Years Professional</li>
                                </ul>
                            </div>
                            
                            <div class="highlight-section">
                                <h5 class="highlight-subtitle">
                                    <i class="fas fa-info-circle"></i>
                                    Fighter Details
                                </h5>
                                <ul class="highlight-items">
                                    <li>Height: {{ $boxer->height }}</li>
                                    <li>Reach: {{ $boxer->reach }}</li>
                                    <li>Stance: {{ $boxer->stance }}</li>
                                    <li>Age: {{ $boxer->age }} years old</li>
                                    <li>From: {{ $boxer->hometown }}, {{ $boxer->country }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="events-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="section-title">EVENTS</h2>
                        <a href="{{ route('events.index') }}" class="filter-link">VIEW ALL</a>
                    </div>
                </div>
            </div>

            <!-- Event Items List -->
            <div class="events-list">
                @if($boxer->upcoming_events && $boxer->upcoming_events->count() > 0)
                    <!-- Desktop Layout (md and up) -->
                    <div class="desktop-events d-none d-md-block">
                        @foreach($boxer->upcoming_events as $event)
                            <div class="event-item-row d-flex">
                                <div class="col-md-5 col-lg-4 event-image-container">
                                    <img src="{{ asset($event->featured_image_path) }}" alt="{{ $event->title }}" class="img-fluid event-image">
                                </div>
                                <div class="col-md-7 col-lg-8 event-details-container d-flex flex-column">
                                    <div class="event-info flex-grow-1">
                                        <p class="event-date">{{ \Carbon\Carbon::parse($event->date)->format('D, M j') }} / {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }} {{ $event->timezone }}</p>
                                        <h4 class="event-name">{{ strtoupper($event->title) }}</h4>
                                        <p class="event-venue"><i class="fas fa-map-marker-alt"></i>{{ $event->venue }} | {{ $event->location }}</p>
                                        <p class="event-network">{{ $event->broadcast_network }}</p>
                                    </div>
                                    <div class="event-actions d-flex flex-row gap-3 mt-3">
                                        @if($event->is_completed)
                                            <a href="{{ route('events.show', $event->slug) }}" class="btn btn-event-primary">
                                                <i class="fas fa-play"></i>WATCH HIGHLIGHTS
                                            </a>
                                            <a href="{{ route('events.show', $event->slug) }}#results" class="btn btn-event-secondary">
                                                RESULTS
                                            </a>
                                        @elseif($event->stream_available)
                                            <a href="{{ route('events.show', $event->slug) }}#stream" class="btn btn-event-primary">
                                                <i class="fas fa-play"></i>WATCH LIVE
                                            </a>
                                            <a href="{{ route('events.show', $event->slug) }}#tickets" class="btn btn-event-secondary">
                                                BUY TICKETS
                                            </a>
                                        @else
                                            <a href="{{ route('events.show', $event->slug) }}#tickets" class="btn btn-event-primary">
                                                BUY TICKETS
                                            </a>
                                            <a href="{{ route('events.show', $event->slug) }}" class="btn btn-event-secondary">
                                                EVENT DETAILS
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Mobile Layout (xs to sm) -->
                    <div class="mobile-events d-md-none">
                        @foreach($boxer->upcoming_events as $event)
                            <div class="event-card">
                                <div class="event-card-image">
                                    <img src="{{ asset($event->featured_image_path) }}" alt="{{ $event->title }}">
                                    <div class="event-card-overlay">
                                        <div class="event-card-content">
                                            <h4 class="event-name">{{ strtoupper($event->title) }}</h4>
                                            <p class="event-date">{{ \Carbon\Carbon::parse($event->date)->format('D, M j') }} / {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }} {{ $event->timezone }}</p>
                                            <p class="event-venue"><i class="fas fa-map-marker-alt"></i>{{ $event->venue }} | {{ $event->location }}</p>
                                            <p class="event-network">{{ $event->broadcast_network }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="event-actions">
                                    @if($event->is_completed)
                                        <a href="{{ route('events.show', $event->slug) }}" class="btn btn-event-primary">
                                            <i class="fas fa-play"></i>WATCH HIGHLIGHTS
                                        </a>
                                        <a href="{{ route('events.show', $event->slug) }}#results" class="btn btn-event-secondary">
                                            RESULTS
                                        </a>
                                    @elseif($event->stream_available)
                                        <a href="{{ route('events.show', $event->slug) }}#stream" class="btn btn-event-primary">
                                            <i class="fas fa-play"></i>WATCH LIVE
                                        </a>
                                        <a href="{{ route('events.show', $event->slug) }}#tickets" class="btn btn-event-secondary">
                                            BUY TICKETS
                                        </a>
                                    @else
                                        <a href="{{ route('events.show', $event->slug) }}#tickets" class="btn btn-event-primary">
                                            BUY TICKETS
                                        </a>
                                        <a href="{{ route('events.show', $event->slug) }}" class="btn btn-event-secondary">
                                            EVENT DETAILS
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        No upcoming events found for {{ $boxer->name }}.
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Videos Section -->
    <section class="videos-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">VIDEOS WITH {{ strtoupper($boxer->name) }}</h2>
                <div class="carousel-navigation">
                    <button class="nav-arrow prev-arrow" id="videoPrev">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="nav-arrow next-arrow" id="videoNext">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            
            <div class="video-carousel-container">
                @if($boxer->videos && $boxer->videos->count() > 0)
                    <div class="video-carousel-track" id="videoTrack">
                        @foreach($boxer->videos as $video)
                            <div class="video-card" data-video-id="{{ $video->id }}" data-video-url="{{ $video->video_url }}">
                                <div class="video-thumbnail">
                                    <img src="{{ asset($video->getThumbnailPathAttribute()) }}" alt="{{ $video->title }}">
                                    @if($video->is_premium)
                                        <div class="premium-badge">Premium</div>
                                    @endif
                                    <div class="video-duration">{{ $video->duration }}</div>
                                    <div class="play-overlay">
                                        <div class="play-button">
                                            <i class="fas fa-play"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="video-info">
                                    <p class="video-date">{{ \Carbon\Carbon::parse($video->published_at)->format('D, M j') }}</p>
                                    <h4 class="video-title">{{ $video->title }}</h4>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        No videos available for {{ $boxer->name }} at this time.
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Fight History Section -->
    <section class="fight-history-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title">FIGHT HISTORY</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @if($boxer->fights && $boxer->fights->count() > 0)
                        <div class="fight-history-table-wrapper">
                            <table class="table fight-history-table">
                                <thead>
                                    <tr>
                                        <th>Result</th>
                                        <th>Rec</th>
                                        <th>Opponent</th>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Round</th>
                                        <th>Time</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($boxer->fights as $fight)
                                        <tr>
                                            <td>
                                                <span class="result-badge {{ strtolower($fight->result) }}">
                                                    {{ strtoupper($fight->result) }}
                                                </span>
                                            </td>
                                            <td>{{ $fight->record_after }}</td>
                                            <td>{{ $fight->opponent_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($fight->fight_date)->format('M j, Y') }}</td>
                                            <td>{{ $fight->method }}</td>
                                            <td>{{ $fight->round }}</td>
                                            <td>{{ $fight->time }}</td>
                                            <td>{{ $fight->notes }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($boxer->fights->count() > 10)
                            <div class="show-more-container">
                                <button class="show-more-btn" id="showMoreFights">
                                    SHOW MORE
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            No fight records available for {{ $boxer->name }}.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>


    <!-- Video Modal -->
    <div class="video-modal" id="videoModal">
        <div class="video-modal-content">
            <button class="video-modal-close">&times;</button>
            <div class="video-container" id="videoContainer">
                <!-- Video will be loaded here -->
            </div>
            <div class="video-info-container">
                <h3 class="modal-video-title" id="modalVideoTitle"></h3>
                <div class="premium-message" id="premiumMessage" style="display: none;">
                    <div class="premium-icon">
                        <i class="fas fa-crown"></i>
                    </div>
                    <p>This video is available exclusively to premium members.</p>
                    <a href="/subscription" class="btn btn-primary">Upgrade to Premium</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Video carousel functionality
    const videoTrack = document.getElementById('videoTrack');
    const videoPrev = document.getElementById('videoPrev');
    const videoNext = document.getElementById('videoNext');
    const videoCards = document.querySelectorAll('.video-card');
    
    let currentVideoIndex = 0;
    let cardsPerView = calculateCardsPerView();
    
    // Calculate how many cards are visible based on screen width
    function calculateCardsPerView() {
        const viewportWidth = window.innerWidth;
        if (viewportWidth >= 1200) return 3;
        if (viewportWidth >= 768) return 2;
        return 1;
    }
    
    // Update carousel state
    function updateVideoCarousel() {
        if (!videoTrack) return;
        
        const cardWidth = videoCards[0]?.offsetWidth;
        if (!cardWidth) return;
        
        const cardGap = parseInt(window.getComputedStyle(videoTrack).columnGap) || 32; // Default gap
        
        // Calculate the translation amount (width + gap)
        const translateX = -currentVideoIndex * (cardWidth + cardGap);
        videoTrack.style.transform = `translateX(${translateX}px)`;
        
        // Update button states
        if (videoPrev) videoPrev.disabled = currentVideoIndex <= 0;
        if (videoNext) videoNext.disabled = currentVideoIndex >= videoCards.length - cardsPerView;
    }
    
    // Navigate to previous slide
    if (videoPrev) {
        videoPrev.addEventListener('click', function() {
            if (currentVideoIndex > 0) {
                currentVideoIndex--;
                updateVideoCarousel();
            }
        });
    }
    
    // Navigate to next slide
    if (videoNext) {
        videoNext.addEventListener('click', function() {
            if (currentVideoIndex < videoCards.length - cardsPerView) {
                currentVideoIndex++;
                updateVideoCarousel();
            }
        });
    }
    
    // Handle window resize
    window.addEventListener('resize', function() {
        cardsPerView = calculateCardsPerView();
        
        // Ensure currentIndex is valid after resize
        if (currentVideoIndex > videoCards.length - cardsPerView) {
            currentVideoIndex = Math.max(0, videoCards.length - cardsPerView);
        }
        
        updateVideoCarousel();
    });
    
    // Initialize carousel
    if (videoTrack && videoCards.length) {
        setTimeout(updateVideoCarousel, 100); // Short delay to ensure styles are applied
    }

    // Video modal functionality
    const videoModal = document.getElementById('videoModal');
    const modalClose = document.querySelector('.video-modal-close');
    const videoContainer = document.getElementById('videoContainer');
    const modalVideoTitle = document.getElementById('modalVideoTitle');
    const premiumMessage = document.getElementById('premiumMessage');

    if (videoCards && videoCards.length) {
        videoCards.forEach(card => {
            card.addEventListener('click', function() {
                const videoId = this.getAttribute('data-video-id');
                const videoUrl = this.getAttribute('data-video-url');
                const isPremium = this.querySelector('.premium-badge') !== null;
                const videoTitle = this.querySelector('.video-title').textContent;
                
                modalVideoTitle.textContent = videoTitle;
                
                if (isPremium) {
                    // Show premium message for premium videos
                    videoContainer.innerHTML = '';
                    premiumMessage.style.display = 'block';
                } else {
                    // Create embed based on video URL
                    premiumMessage.style.display = 'none';
                    
                    if (videoUrl.includes('youtube.com') || videoUrl.includes('youtu.be')) {
                        // YouTube embed
                        const youtubeId = videoUrl.includes('v=') 
                            ? videoUrl.split('v=')[1].split('&')[0] 
                            : videoUrl.split('/').pop();
                            
                        videoContainer.innerHTML = `
                            <iframe width="100%" height="315" 
                                src="https://www.youtube.com/embed/${youtubeId}?autoplay=1" 
                                frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                            </iframe>
                        `;
                    } else if (videoUrl.includes('vimeo.com')) {
                        // Vimeo embed
                        const vimeoId = videoUrl.split('/').pop();
                        videoContainer.innerHTML = `
                            <iframe src="https://player.vimeo.com/video/${vimeoId}?autoplay=1" 
                                width="100%" height="315" frameborder="0" allow="autoplay; fullscreen" 
                                allowfullscreen>
                            </iframe>
                        `;
                    } else {
                        // Direct video URL
                        videoContainer.innerHTML = `
                            <video controls autoplay width="100%">
                                <source src="${videoUrl}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        `;
                    }
                }
                
                videoModal.classList.add('active');
            });
        });
    }

    if (modalClose) {
        modalClose.addEventListener('click', function() {
            videoModal.classList.remove('active');
            videoContainer.innerHTML = ''; // Stop video playback
        });
    }

    if (videoModal) {
        window.addEventListener('click', function(e) {
            if (e.target === videoModal) {
                videoModal.classList.remove('active');
                videoContainer.innerHTML = ''; // Stop video playback
            }
        });
    }

    // Fight history show more functionality
    const showMoreBtn = document.getElementById('showMoreFights');
    if (showMoreBtn) {
        const fightRows = document.querySelectorAll('.fight-history-table tbody tr');
        const rowsToShow = 10;
        
        // Initially hide rows beyond the limit
        for (let i = rowsToShow; i < fightRows.length; i++) {
            fightRows[i].style.display = 'none';
        }
        
        showMoreBtn.addEventListener('click', function() {
            for (let i = 0; i < fightRows.length; i++) {
                fightRows[i].style.display = 'table-row';
            }
            this.style.display = 'none';
        });
    }

    // Load More Biography Functionality
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const bioExpandable = document.querySelector('.bio-expandable');
    let isExpanded = false;
    
    if (loadMoreBtn && bioExpandable) {
        loadMoreBtn.addEventListener('click', function() {
            isExpanded = !isExpanded;
            
            if (isExpanded) {
                bioExpandable.style.display = 'block';
                loadMoreBtn.classList.add('expanded');
                loadMoreBtn.querySelector('.btn-text').textContent = 'Read Less';
            } else {
                bioExpandable.style.display = 'none';
                loadMoreBtn.classList.remove('expanded');
                loadMoreBtn.querySelector('.btn-text').textContent = 'Read More';
            }
        });
    }
    
    // Circular Progress Animation
    function animateProgress() {
        const progressRings = document.querySelectorAll('.ring-progress circle:last-child');
        
        progressRings.forEach(circle => {
            const card = circle.closest('.boxing-stat-card');
            if (!card) return;
            
            let progress = 0;
            if (card.classList.contains('wins')) {
                progress = {{ $boxer->win_rate }};
            } else if (card.classList.contains('knockouts')) {
                progress = {{ $boxer->knockout_rate }};
            } else if (card.classList.contains('losses')) {
                progress = {{ $boxer->losses > 0 ? round(($boxer->losses / ($boxer->wins + $boxer->losses + $boxer->draws)) * 100) : 0 }};
            } else if (card.classList.contains('experience')) {
                progress = {{ min(100, ($boxer->years_pro / 20) * 100) }};
            }
            
            // Set the progress
            const circumference = 2 * Math.PI * 54; // r = 54
            const offset = circumference - (progress / 100) * circumference;
            
            circle.style.strokeDasharray = circumference;
            circle.style.strokeDashoffset = circumference;
            
            // Animate after a short delay
            setTimeout(() => {
                circle.style.transition = 'stroke-dashoffset 2s ease-in-out';
                circle.style.strokeDashoffset = offset;
            }, 500);
        });
    }
    
    // Intersection Observer for animation trigger
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateProgress();
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    const statsSection = document.querySelector('.boxing-stats-section');
    if (statsSection) {
        observer.observe(statsSection);
    }
});
</script>
@endpush 
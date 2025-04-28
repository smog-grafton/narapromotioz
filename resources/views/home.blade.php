@extends('layouts.app')

@section('title', 'Premier Boxing Promotions')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container-fluid px-0">
            <div class="position-relative">
                <!-- Hero Background -->
                <div class="bg-dark" style="height: 600px; background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.8)), url('https://images.unsplash.com/photo-1622467827417-bbe3e975464c') center/cover no-repeat;">
                </div>
                
                <!-- Hero Content -->
                <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-4">
                    <h1 class="display-3 fw-bold mb-3">THE ULTIMATE BOXING EXPERIENCE</h1>
                    <p class="lead mb-4">World-class fights, legendary fighters, and unforgettable moments.</p>
                    
                    <!-- Call to Action Buttons -->
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <a href="{{ route('events.index') }}" class="btn btn-primary btn-lg">UPCOMING EVENTS</a>
                        <a href="{{ route('fighters.index') }}" class="btn btn-outline-light btn-lg">FIGHTERS</a>
                    </div>
                    
                    <!-- Live Event Badge (if any) -->
                    @if(isset($liveEvent) && $liveEvent)
                    <div class="mt-5">
                        <span class="live-badge me-2">LIVE NOW</span>
                        <a href="{{ route('events.stream', $liveEvent) }}" class="text-white text-decoration-none">
                            <strong>{{ $liveEvent->title }} - Watch Now!</strong>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    
    <!-- Upcoming Events Section -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="border-start border-primary border-5 ps-3">UPCOMING EVENTS</h2>
                </div>
            </div>
            
            <div class="row">
                @forelse($upcomingEvents as $event)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="position-relative">
                                @if($event->event_banner)
                                    <img src="{{ $event->event_banner }}" class="card-img-top" alt="{{ $event->title }}">
                                @else
                                    <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 200px;">
                                        <h5>{{ $event->title }}</h5>
                                    </div>
                                @endif
                                
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-primary">{{ $event->event_date->format('M d, Y') }}</span>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <h5 class="card-title">{{ $event->title }}</h5>
                                <p class="card-text text-muted">{{ $event->location }}</p>
                                
                                <!-- Main Event Fighters -->
                                @if($event->fights->isNotEmpty())
                                    <?php $mainEvent = $event->fights->sortBy('fight_order')->first(); ?>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div class="text-center">
                                            <p class="mb-0 fw-bold">{{ $mainEvent->fighterOne->full_name }}</p>
                                            <small>{{ $mainEvent->fighterOne->wins }}-{{ $mainEvent->fighterOne->losses }}</small>
                                        </div>
                                        <div class="text-center">
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
                                <a href="{{ route('events.show', $event) }}" class="btn btn-outline-primary w-100">VIEW DETAILS</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            No upcoming events scheduled at this time. Check back soon!
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
    
    <!-- Featured Fighters Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="border-start border-danger border-5 ps-3">FEATURED FIGHTERS</h2>
                </div>
            </div>
            
            <div class="row">
                @forelse($featuredFighters as $fighter)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            @if($fighter->profile_image)
                                <img src="{{ $fighter->profile_image }}" class="card-img-top" alt="{{ $fighter->full_name }}">
                            @else
                                <div class="bg-secondary text-white d-flex justify-content-center align-items-center" style="height: 250px;">
                                    <i class="fas fa-user fa-3x"></i>
                                </div>
                            @endif
                            
                            <div class="card-body text-center">
                                <h5 class="card-title mb-0">{{ $fighter->full_name }}</h5>
                                @if($fighter->nickname)
                                    <p class="text-muted mb-2">"{{ $fighter->nickname }}"</p>
                                @endif
                                
                                <div class="d-flex justify-content-center mt-2">
                                    <span class="badge bg-success me-1">{{ $fighter->wins }} W</span>
                                    <span class="badge bg-danger me-1">{{ $fighter->losses }} L</span>
                                    <span class="badge bg-secondary">{{ $fighter->draws }} D</span>
                                </div>
                                
                                <p class="mt-2 mb-0">{{ $fighter->weight_class }}</p>
                                
                                @if($fighter->ranking && $fighter->ranking->isChampion())
                                    <div class="mt-2">
                                        <span class="badge bg-warning text-dark">CHAMPION</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="card-footer bg-white border-top-0">
                                <a href="{{ route('fighters.show', $fighter) }}" class="btn btn-sm btn-outline-primary w-100">VIEW PROFILE</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            No featured fighters at this time.
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
    <section class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="border-start border-primary border-5 ps-3">LATEST NEWS</h2>
                </div>
            </div>
            
            <div class="row">
                @forelse($latestNews as $article)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if($article->thumbnail_image)
                                <img src="{{ $article->thumbnail_image }}" class="card-img-top" alt="{{ $article->title }}">
                            @else
                                <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 200px;">
                                    <i class="fas fa-newspaper fa-3x"></i>
                                </div>
                            @endif
                            
                            <div class="card-body">
                                <h5 class="card-title">{{ $article->title }}</h5>
                                <p class="text-muted small mb-2">
                                    <i class="far fa-calendar-alt me-1"></i> {{ $article->formattedPublishedDate }}
                                    <span class="ms-2"><i class="far fa-clock me-1"></i> {{ $article->readTime }} min read</span>
                                </p>
                                <p class="card-text">{{ $article->summary }}</p>
                            </div>
                            
                            <div class="card-footer bg-white border-top-0">
                                <a href="{{ route('news.show', $article) }}" class="btn btn-sm btn-outline-primary w-100">READ MORE</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            No news articles available at this time.
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
    
    <!-- CTA Section -->
    <section class="py-5 bg-dark text-white">
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
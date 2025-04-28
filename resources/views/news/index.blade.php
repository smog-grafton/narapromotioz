@extends('layouts.app')

@section('title', 'Boxing News')

@section('content')
<div class="container py-5">
    <!-- Hero Banner -->
    <div class="position-relative mb-5">
        <div class="bg-dark rounded" style="height: 300px; background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.8)), url('https://images.unsplash.com/photo-1517466787929-bc90951d0974') center/cover no-repeat;">
        </div>
        <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-4">
            <h1 class="display-4 fw-bold mb-3">BOXING NEWS</h1>
            <p class="lead">Stay updated with the latest news, interviews, and events from the boxing world.</p>
        </div>
    </div>
    
    <!-- News Articles Grid -->
    <div class="row">
        @forelse($newsArticles as $article)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        @if($article->thumbnail_image)
                            <img src="{{ $article->thumbnail_image }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $article->title }}">
                        @else
                            <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 200px;">
                                <i class="fas fa-newspaper fa-3x"></i>
                            </div>
                        @endif
                        
                        <div class="position-absolute bottom-0 start-0 m-3">
                            <span class="badge bg-dark p-2">
                                <i class="far fa-calendar-alt me-1"></i> {{ $article->formattedPublishedDate }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $article->title }}</h5>
                        <p class="card-text text-muted small mb-2">
                            <i class="far fa-clock me-1"></i> {{ $article->readTime }} min read
                        </p>
                        <p class="card-text flex-grow-1">{{ $article->summary }}</p>
                        <a href="{{ route('news.show', $article) }}" class="btn btn-outline-primary mt-3">
                            READ FULL ARTICLE
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No news articles available at this time.
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            {{ $newsArticles->links() }}
        </div>
    </div>
    
    <!-- Newsletter Subscription -->
    <div class="row mt-5">
        <div class="col-12 bg-light p-4 rounded shadow-sm">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-3 mb-lg-0">
                    <h4>STAY UPDATED WITH BOXING NEWS</h4>
                    <p class="mb-0">Subscribe to our newsletter to receive the latest boxing news, event announcements, and exclusive content.</p>
                </div>
                <div class="col-lg-5">
                    <form action="#" method="POST" class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="Your email address" required>
                        <button type="submit" class="btn btn-primary">SUBSCRIBE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
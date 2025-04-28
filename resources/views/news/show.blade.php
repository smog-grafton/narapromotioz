@extends('layouts.app')

@section('title', $newsArticle->title)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Main Article Content -->
        <div class="col-lg-8">
            <!-- Article Header -->
            <div class="mb-4">
                <h1 class="mb-3">{{ $newsArticle->title }}</h1>
                <div class="d-flex flex-wrap align-items-center text-muted mb-4">
                    <div class="me-4">
                        <i class="far fa-calendar-alt me-1"></i> {{ $newsArticle->formattedPublishedDate }}
                    </div>
                    <div>
                        <i class="far fa-clock me-1"></i> {{ $newsArticle->readTime }} min read
                    </div>
                </div>
                
                <!-- Featured Image -->
                @if($newsArticle->thumbnail_image)
                    <div class="position-relative mb-4">
                        <img src="{{ $newsArticle->thumbnail_image }}" class="img-fluid rounded shadow-sm w-100" alt="{{ $newsArticle->title }}" style="max-height: 500px; object-fit: cover;">
                    </div>
                @endif
            </div>
            
            <!-- Article Content -->
            <div class="article-content mb-5">
                {!! $newsArticle->content !!}
            </div>
            
            <!-- Social Share -->
            <div class="mt-5 mb-4">
                <h5>Share this article</h5>
                <div class="d-flex">
                    <a href="#" class="btn btn-sm btn-outline-primary me-2">
                        <i class="fab fa-facebook-f me-1"></i> Facebook
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-info me-2">
                        <i class="fab fa-twitter me-1"></i> Twitter
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-success">
                        <i class="fab fa-whatsapp me-1"></i> WhatsApp
                    </a>
                </div>
            </div>
            
            <!-- Tags -->
            @if($newsArticle->seo_keywords)
                <div class="mb-5">
                    <h5>Tags</h5>
                    <div>
                        @foreach(explode(',', $newsArticle->seo_keywords) as $keyword)
                            <span class="badge bg-light text-dark border me-2 mb-2 p-2">{{ trim($keyword) }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Related News -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Related News</h5>
                </div>
                <div class="card-body">
                    @forelse($relatedNews as $article)
                        <div class="d-flex mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                            @if($article->thumbnail_image)
                                <img src="{{ $article->thumbnail_image }}" class="rounded me-3" style="width: 80px; height: 60px; object-fit: cover;" alt="{{ $article->title }}">
                            @else
                                <div class="bg-secondary text-white rounded d-flex justify-content-center align-items-center me-3" style="width: 80px; height: 60px;">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-1">
                                    <a href="{{ route('news.show', $article) }}" class="text-decoration-none">
                                        {{ $article->title }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i> {{ $article->formattedPublishedDate }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No related articles found.</p>
                    @endforelse
                </div>
            </div>
            
            <!-- Upcoming Events Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Upcoming Events</h5>
                </div>
                <div class="card-body">
                    @php
                        $upcomingEvents = \App\Models\Event::where('event_date', '>', now())
                                         ->orderBy('event_date', 'asc')
                                         ->take(3)
                                         ->get();
                    @endphp
                    
                    @forelse($upcomingEvents as $event)
                        <div class="d-flex mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                            <div class="text-center me-3" style="min-width: 60px;">
                                <div class="bg-danger text-white rounded p-1">
                                    <strong>{{ $event->event_date->format('M') }}</strong>
                                </div>
                                <div class="border border-danger rounded-bottom px-2 py-1">
                                    <strong>{{ $event->event_date->format('d') }}</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">
                                    <a href="{{ route('events.show', $event) }}" class="text-decoration-none">
                                        {{ $event->title }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i> {{ $event->location }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No upcoming events at this time.</p>
                    @endforelse
                    
                    <div class="mt-3">
                        <a href="{{ route('events.index') }}" class="btn btn-outline-primary btn-sm w-100">
                            VIEW ALL EVENTS
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Newsletter Subscription -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Newsletter</h5>
                </div>
                <div class="card-body">
                    <p>Subscribe to our newsletter to receive the latest boxing news and updates.</p>
                    <form action="#" method="POST">
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Your email address" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">SUBSCRIBE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add custom styling to article content
    document.addEventListener('DOMContentLoaded', function() {
        const articleContent = document.querySelector('.article-content');
        
        if (articleContent) {
            // Style paragraphs
            const paragraphs = articleContent.querySelectorAll('p');
            paragraphs.forEach(p => {
                p.classList.add('mb-4');
            });
            
            // Style images
            const images = articleContent.querySelectorAll('img');
            images.forEach(img => {
                img.classList.add('img-fluid', 'rounded', 'my-3');
            });
            
            // Style headings
            const headings = articleContent.querySelectorAll('h2, h3, h4');
            headings.forEach(heading => {
                heading.classList.add('mt-4', 'mb-3');
            });
        }
    });
</script>
@endsection
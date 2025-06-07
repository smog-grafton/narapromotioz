@extends('layouts.app')

@section('title', 'Home - ' . config('app.name'))

@section('content')
@include('partials._hero_slider')

@include('partials._featured_news')

@include('partials._event_section')

@include('partials._video_wall')

<!-- Blog Style Two Start -->
<section class="gap blog-style-two">
    <div class="container">
        <div class="heading">
            <figure>
                <img src="{{ asset('assets/images/heading-icon.png') }}" alt="heading-icon-22">
            </figure>
            <span>Blog & News</span>
            <h2>Recent Articles</h2>
        </div>
        <div class="blog-grid row">
            @forelse($recentArticles->take(3) as $article)
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="blog-item">
                        <figure>
                            @if($article->featured_image)
                                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}">
                            @else
                                <img src="https://via.placeholder.com/409x266" alt="{{ $article->title }}">
                            @endif
                            @if($article->categories->first())
                                <a href="{{ route('news.category', $article->categories->first()->slug) }}" class="category">{{ $article->categories->first()->name }}</a>
                            @else
                                <a href="#" class="category">General</a>
                            @endif
                        </figure>
                        <div class="blog-text">
                            <span class="blog-date">{{ $article->formatted_published_at }}</span>
                            <h2><a href="{{ route('news.show', $article->slug) }}">{{ $article->title }}</a></h2>
                            <p>{{ $article->excerpt }}</p> 
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback content if no articles are found -->
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="blog-item">
                        <figure>
                            <img src="https://via.placeholder.com/409x266" alt="blog-img-1">
                            <a href="#" class="category">workout</a>
                        </figure>
                        <div class="blog-text">
                            <span class="blog-date">January 9, 2025</span>
                            <h2><a href="#">10 tips how to prepare meals fast and easy weight loss</a></h2>
                            <p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit.</p> 
                        </div>
                    </div>
                </div> 
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="blog-item">
                        <figure>
                            <img src="https://via.placeholder.com/409x266" alt="blog-img-1">
                            <a href="#" class="category">workout</a>
                        </figure>
                        <div class="blog-text">
                            <span class="blog-date">January 9, 2025</span>
                            <h2><a href="#">Interval training, and focusing on compound exercises.</a></h2>
                            <p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit.</p> 
                        </div>
                    </div>
                </div> 
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="blog-item">
                        <figure>
                            <img src="https://via.placeholder.com/409x266" alt="blog-img-1">
                            <a href="#" class="category">workout</a>
                        </figure>
                        <div class="blog-text">
                            <span class="blog-date">January 9, 2025</span>
                            <h2><a href="#">How to Keep Your Body Healthy Over the Festive Season</a></h2>
                            <p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit.</p> 
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
<!-- Blog Style Two End -->

@endsection 
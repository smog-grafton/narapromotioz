@extends('layouts.app')

@section('title', 'Our Blog - ' . config('app.name'))

@section('content')
<!-- Banner Style One Start -->
<section class="banner-style-one">
    <div class="parallax" style="background-image: url({{ asset('assets/images/banner/news_oagebanner.jpg') }});"></div>
    <div class="container">
        <div class="row">
            <div class="banner-details">
                <h2>Boxing News & Updates</h2>
                <p>Stay updated with the latest boxing news, fight results, and exclusive content from Uganda's premier boxing promotion company.</p>
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
                        <p>Boxing News</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- Banner Style One End -->

<!-- Blog Style One Start -->
<section class="gap blog-style-one our-blog-one">
    <div class="container">
        <div class="row g-4">
            @forelse($articles as $article)
            <div class="col-lg-4">
                <div class="blog-post">
                    <div class="blog-image">
                        <figure>
                           <img src="{{ $article->featured_image ? Storage::url($article->featured_image) : 'https://via.placeholder. com/469x269' }}" alt="{{ $article->title }}">
                        </figure>
                        <a href="{{ route('news.show', $article->slug) }}">
                            <i class="fa-solid fa-angles-right"></i>
                        </a>
                    </div>
                    <div class="blog-data">
                        <span class="blog-date">{{ $article->created_at->format('F d, Y') }}</span>
                        <h2>
                            <a href="{{ route('news.show', $article->slug) }}">{{ $article->title }}</a>
                        </h2>
                        <div class="blog-author d-flex-all justify-content-start">
                            <div class="author-img">
                                <figure>
                                    <img src="{{ $article->user && $article->user->avatar ? Storage::url($article->user->avatar) : asset('assets/images/avatar.jpg') }}" alt="{{ $article->user ? $article->user->name : 'Author' }}">
                                </figure>
                            </div>
                            <div class="details">
                                <h3><span>by</span> {{ $article->user ? $article->user->name : 'Admin' }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center">
                    <h3>No articles found</h3>
                    <p>Check back later for new content.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
    
    @if($articles->hasPages())
    <div class="container">
        <div class="row">
            <div class="gym-pagination">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($articles->onFirstPage())
                            <li class="page-item disabled"><span class="page-link"><i class='fa-solid fa-arrow-left-long'></i></span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $articles->previousPageUrl() }}"><i class='fa-solid fa-arrow-left-long'></i></a></li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                            @if ($page == $articles->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @elseif ($page == 1 || $page == $articles->lastPage() || ($page >= $articles->currentPage() - 2 && $page <= $articles->currentPage() + 2))
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @elseif ($page == $articles->currentPage() - 3 || $page == $articles->currentPage() + 3)
                                <li class="page-item space"><span class="page-link">..........</span></li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($articles->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $articles->nextPageUrl() }}"><i class='fa-solid fa-arrow-right-long'></i></a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link"><i class='fa-solid fa-arrow-right-long'></i></span></li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    @endif
</section>
<!-- Blog Style One End -->
@endsection 
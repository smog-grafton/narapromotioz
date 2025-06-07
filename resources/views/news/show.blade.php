@extends('layouts.app')

@section('title', $article->title . ' - ' . config('app.name'))

@push('meta')
<meta name="description" content="{{ Str::limit(strip_tags($article->content), 160) }}">
<meta property="og:title" content="{{ $article->title }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($article->content), 160) }}">
<meta property="og:image" content="{{ $article->featured_image ? asset(Storage::url($article->featured_image)) : asset('assets/images/default-blog.jpg') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="twitter:card" content="summary_large_image">
@endpush

@section('content')
<!-- Banner Style One Start -->
<section class="banner-style-one">
    <div class="parallax" style="background-image: url({{ asset('assets/images/bg-banner.jpg') }});"></div>
    <div class="container">
        <div class="row">
            <div class="banner-details">
                <h2>Blog Detail</h2>
                <p>our values and vaulted us to the top of our industry.</p>
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
                            <p>Home</p>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('news.index') }}">
                            <p>Blog</p>
                        </a>
                    </li>
                    <li class="current">
                        <p>{{ Str::limit($article->title, 30) }}</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- Banner Style One End -->

<!-- Blog Style Three Start -->
<section class="gap blog-style-one blog-detail detail-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="blog-post">
                    <div class="blog-image">
                        <figure>
                            <img src="{{ $article->featured_image ? Storage::url($article->featured_image) : 'https://via.placeholder.com/833x484' }}" alt="{{ $article->title }}">
                        </figure>
                    </div>
                    <div class="blog-data">
                        <span class="blog-date">{{ $article->created_at->format('F d, Y') }}</span>
                        <h2>
                            <a href="javascript:void(0)">{{ $article->title }}</a>
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
                    
                    <div class="blog-content">
                        {!! $article->content !!}
                    </div>

                    <div class="category shape">
                        <p>Posted in 
                            @if($article->categories->count() > 0)
                                @foreach($article->categories as $category)
                                    <a href="{{ route('news.category', $category->slug) }}">{{ $category->name }}</a>@if(!$loop->last), @endif
                                @endforeach
                            @else
                                <a href="JavaScript:void(0)">Uncategorized</a>
                            @endif
                        </p>
                    </div>

                    @if($article->tags->count() > 0)
                    <div class="category shape tags">
                        <p>Tags: 
                            @foreach($article->tags as $tag)
                                <a href="{{ route('news.tag', $tag->slug) }}">{{ $tag->name }}</a>@if(!$loop->last), @endif
                            @endforeach
                        </p>
                    </div>
                    @endif

                    <div class="category shape social-medias">
                        <p>Share this:</p>
                        <ul>
                            <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank">Facebook</a></li>
                            <li><a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}" target="_blank">Twitter</a></li>
                            <li><a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank">LinkedIn</a></li>
                        </ul>
                    </div>

                    <div class="category shape comments">
                        <h3>Comments ({{ $article->comments->count() }})</h3>
                        @if($article->comments->count() > 0)
                        <ul>
                            @foreach($article->comments as $comment)
                            <li>
                                <div class="comment {{ $comment->parent_id ? 'reply' : '' }}">
                                    <div class="c-img">
                                        <figure>
                                            <img src="{{ asset('assets/images/avatar.jpg') }}" alt="{{ $comment->name }}">
                                        </figure>
                                    </div>
                                    <div class="c-data">
                                        <h4>{{ $comment->name }}</h4>
                                        <span>{{ $comment->created_at->format('F d, Y') }}</span>
                                        <p>{{ $comment->comment }}</p>
                                        <a class="c-r-btn" href="javascript:void(0)" onclick="replyToComment({{ $comment->id }})">Reply</a>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>

                    <div class="category shape form">
                        <h3>Leave a Comment</h3>
                        <p>Your email address will not be published.</p>
                        <form id="comment-form" action="{{ route('news.comment', $article->slug) }}" method="POST">
                            @csrf
                            <input type="hidden" name="parent_id" id="parent_id" value="">
                            <textarea name="comment" placeholder="Comment" required></textarea>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input type="text" name="name" placeholder="Complete Name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="email" name="email" placeholder="Email Address" required>
                                </div>
                            </div>
                            <div class="row align-items-center form-check">
                                <div class="col-lg-12 d-flex-all justify-content-start">
                                    <input type="checkbox" class="form-check-inputt" id="exampleCheck24">
                                    <label class="form-check-labell" for="exampleCheck24"> Save my name, email, and website in this browser for the next time I comment.</label>
                                </div>
                            </div>
                            <button type="submit" class="theme-btn">Post Comment</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <aside class="sidebar">
                    <div class="box recent-posts">
                        <h3>Recent Posts</h3>
                        <ul>
                            @foreach($recentPosts as $post)
                            <li>
                                <img src="{{ $post->featured_image ? Storage::url($post->featured_image) : 'https://via.placeholder.com/80x80' }}" alt="{{ $post->title }}">
                                <div>
                                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                                    <a href="{{ route('news.show', $post->slug) }}"><p>{{ Str::limit($post->title, 40) }}</p></a>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="box recent-cmnts">
                        <h3>Recent Comments</h3>
                        <ul>
                            @foreach($recentComments as $comment)
                            <li>
                                <h4>{{ $comment->name }}</h4>
                                <p>{{ Str::limit($comment->comment, 50) }}</p>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="box categories">
                        <h3>Categories</h3>
                        <ul>
                            @foreach($categories as $category)
                            <li>
                                <a href="{{ route('news.category', $category->slug) }}">
                                    <p>{{ $category->name }} ({{ $category->articles_count }})</p>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="box categories">
                        <h3>Archives</h3>
                        <ul>
                            @foreach($archives as $archive)
                            <li>
                                <a href="{{ route('news.archive', ['year' => $archive->year, 'month' => $archive->month]) }}">
                                    <p>{{ $archive->month_name }} {{ $archive->year }} ({{ $archive->count }})</p>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="box categories">
                        <h3>Meta</h3>
                        <ul>
                            @if (Route::has('login'))
                                @auth
                                    <li><a href="{{ url('/admin') }}"><p>Admin Panel</p></a></li>
                                @else
                                    <li><a href="{{ route('login') }}"><p>Log in</p></a></li>
                                @endauth
                            @endif
                            <li><a href="{{ route('news.index') }}"><p>Entries feed</p></a></li>
                            <li><a href="javascript:void(0)"><p>Comments feed</p></a></li>
                            <li><a href="{{ route('home') }}"><p>{{ config('app.name') }}</p></a></li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
<!-- Blog Style Three End -->

@push('scripts')
<script>
function replyToComment(commentId) {
    document.getElementById('parent_id').value = commentId;
    document.querySelector('#comment-form textarea').focus();
    document.querySelector('#comment-form h3').textContent = 'Reply to Comment';
}

// Handle comment form submission
document.getElementById('comment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error posting comment. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error posting comment. Please try again.');
    });
});
</script>
@endpush
@endsection 
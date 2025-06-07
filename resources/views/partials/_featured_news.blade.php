<section class="featured-news">
    
    <style>
        /* Emergency inline styles to fix image display issues */
        .featured-news {
            padding: 3rem 0 !important;
            background-color: #1a1a1a !important;
            color: #fff !important;
        }
        
        .featured-news .container-fluid {
            max-width: 1400px !important;
            margin: 0 auto !important;
        }
        
        .featured-news .news-item {
            position: relative !important;
            background: #222 !important;
            border-radius: 4px !important;
            overflow: hidden !important;
            transition: transform 0.3s ease !important;
            height: 100% !important;
            margin-bottom: 1rem !important;
        }
        
        .featured-news .news-item:hover {
            transform: translateY(-5px) !important;
        }
        
        .featured-news .news-item-link {
            display: block !important;
            height: 100% !important;
            color: inherit !important;
            text-decoration: none !important;
        }
        
        .featured-news .news-item-image {
            display: block !important;
            width: 100% !important;
            position: relative !important;
        }
        
        .featured-news .news-item-image img {
            display: block !important;
            width: 100% !important;
            object-fit: cover !important;
        }
        
        /* Main article layout fixes */
        .featured-news .news-item-large {
            display: flex !important;
            flex-direction: column !important;
        }
        
        .featured-news .news-item-large .news-item-image {
            height: 0 !important;
            padding-bottom: 62.5% !important; /* 16:10 aspect ratio */
            position: relative !important;
        }
        
        .featured-news .news-item-large .news-item-image img {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
        }
        
        .featured-news .news-item-content {
            padding: 1.25rem !important;
            position: relative !important;
            z-index: 5 !important;
            color: #fff !important;
            flex: 1 !important;
        }
        
        .featured-news .news-item-large .news-item-content {
            background-color: #222 !important;
        }
        
        .featured-news .news-item-title {
            margin-bottom: 0.75rem !important;
            font-weight: 600 !important;
        }
        
        .featured-news .news-item-title a {
            color: #fff !important;
            text-decoration: none !important;
        }
        
        .featured-news .news-item-date {
            display: block !important;
            font-size: 0.875rem !important;
            color: #ff3c3c !important;
            margin-bottom: 0.5rem !important;
        }
        
        .featured-news .news-item-medium .news-item-image {
            height: 0 !important;
            padding-bottom: 56.25% !important; /* 16:9 aspect ratio */
            position: relative !important;
        }
        
        .featured-news .news-item-medium .news-item-image img {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
        }
        
        /* Small articles */
        .featured-news .small-articles {
            display: flex !important;
            flex-direction: column !important;
        }
        
        .featured-news .news-item-small {
            display: flex !important;
            align-items: flex-start !important;
            padding: 1rem !important;
            margin-bottom: 0.5rem !important;
            background: #222 !important;
            border-radius: 4px !important;
        }
        
        .featured-news .news-item-image-small {
            flex: 0 0 80px !important;
            width: 80px !important;
            height: 80px !important;
            margin-right: 1rem !important;
            overflow: hidden !important;
        }
        
        .featured-news .news-item-image-small img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }
        
        .featured-news .news-item-content-small {
            flex: 1 !important;
        }
        
        /* Ensure right column is positioned correctly */
        .featured-news .right-column {
            display: flex !important;
            flex-direction: column !important;
        }
        
        /* Media query for mobile */
        @media (max-width: 991px) {
            .featured-news .container-fluid {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }
        }
    </style>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Main featured article (left column) -->
            <div class="col-lg-7 mb-4 mb-lg-0">
                @if($mainArticle)
                <div class="news-item news-item-large">
                    <div class="news-item-image">
                        <a href="{{ route('news.show', $mainArticle->slug) }}">
                            @if($mainArticle->featured_image)
                                <img src="{{ asset('storage/' . $mainArticle->featured_image) }}" alt="{{ $mainArticle->title }}">
                            @else
                                <img src="https://via.placeholder.com/800x500" alt="{{ $mainArticle->title }}">
                            @endif
                        </a>
                    </div>
                    <div class="news-item-content">
                        <span class="news-item-date">{{ $mainArticle->formatted_published_at }}</span>
                        <h3 class="news-item-title">
                            <a href="{{ route('news.show', $mainArticle->slug) }}">{{ $mainArticle->title }}</a>
                        </h3>
                        <p>{{ Str::limit($mainArticle->excerpt ?? strip_tags($mainArticle->content), 150) }}</p>
                    </div>
                </div>
                @else
                <!-- No main article found -->
                <div class="alert alert-warning">
                    No main article found. Set an article as the main featured article in the admin panel.
                </div>
                @endif
            </div>
            
            <!-- Secondary articles (right column) -->
            <div class="col-lg-5 right-column">
                <!-- Medium sized articles (top right) -->
                <div class="row medium-articles">
                    @for($i = 0; $i < 2 && $i < $featuredArticles->count(); $i++)
                        @php $mediumArticle = $featuredArticles->shift(); @endphp
                        <div class="col-md-6 col-lg-12 col-xl-6 mb-4">
                            <div class="news-item news-item-medium">
                                <div class="news-item-image">
                                    <a href="{{ route('news.show', $mediumArticle->slug) }}">
                                        @if($mediumArticle->featured_image)
                                            <img src="{{ asset('storage/' . $mediumArticle->featured_image) }}" alt="{{ $mediumArticle->title }}">
                                        @else
                                            <img src="https://via.placeholder.com/400x300" alt="{{ $mediumArticle->title }}">
                                        @endif
                                    </a>
                                </div>
                                <div class="news-item-content">
                                    <span class="news-item-date">{{ $mediumArticle->formatted_published_at }}</span>
                                    <h4 class="news-item-title">
                                        <a href="{{ route('news.show', $mediumArticle->slug) }}">{{ $mediumArticle->title }}</a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                
                <!-- Small articles with side images (bottom right) -->
                <div class="small-articles">
                    @foreach($featuredArticles as $smallArticle)
                        <div class="news-item news-item-small">
                            <div class="news-item-image-small">
                                <a href="{{ route('news.show', $smallArticle->slug) }}">
                                    @if($smallArticle->featured_image)
                                        <img src="{{ asset('storage/' . $smallArticle->featured_image) }}" alt="{{ $smallArticle->title }}">
                                    @else
                                        <img src="https://via.placeholder.com/80x80" alt="{{ $smallArticle->title }}">
                                    @endif
                                </a>
                            </div>
                            <div class="news-item-content-small">
                                <h5 class="news-item-title">
                                    <a href="{{ route('news.show', $smallArticle->slug) }}">{{ $smallArticle->title }}</a>
                                </h5>
                                <span class="news-item-date">{{ $smallArticle->formatted_published_at }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Equal height for medium articles
    document.addEventListener('DOMContentLoaded', function() {
        function equalizeHeights() {
            // Reset heights
            document.querySelectorAll('.news-item-medium').forEach(function(item) {
                item.style.height = 'auto';
            });
            
            // Only apply on desktop
            if (window.innerWidth >= 768) {
                // Get all medium article rows
                const mediumRows = document.querySelectorAll('.medium-articles .row');
                
                mediumRows.forEach(function(row) {
                    const articles = row.querySelectorAll('.news-item-medium');
                    let maxHeight = 0;
                    
                    // Find the tallest article
                    articles.forEach(function(article) {
                        const height = article.offsetHeight;
                        maxHeight = height > maxHeight ? height : maxHeight;
                    });
                    
                    // Set all to the same height
                    articles.forEach(function(article) {
                        article.style.height = maxHeight + 'px';
                    });
                });
            }
        }
        
        // Run on load and resize
        equalizeHeights();
        window.addEventListener('resize', equalizeHeights);
    });
</script>
@endpush 
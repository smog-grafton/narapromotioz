<!-- Related News Section -->
<section class="related-news-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">RELATED NEWS</h2>
            <a href="{{ route('news.index') }}" class="view-all-link">VIEW ALL NEWS</a>
        </div>
        
        @if($relatedNews && $relatedNews->isNotEmpty())
            <div class="news-grid">
                @foreach($relatedNews as $article)
                    <div class="event-card">
                        <div class="event-image">
                            <img src="{{ asset($article->image_path ?: 'assets/images/news/default.jpg') }}" alt="{{ $article->title }}">
                            <div class="event-overlay"></div>
                        </div>
                        <div class="event-content">
                            <div class="event-date">{{ $article->created_at->format('F j, Y') }}</div>
                            <h3 class="event-title">
                                <a href="{{ route('news.show', $article->slug) }}">{{ $article->title }}</a>
                            </h3>
                            <p>{{ Str::limit($article->excerpt ?: strip_tags($article->content), 100) }}</p>
                            
                            @if($article->categories->isNotEmpty())
                                <div class="article-categories mt-2">
                                    @foreach($article->categories as $category)
                                        <span class="article-category">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="event-footer">
                            <div class="author">
                                @if($article->author)
                                    <span>By {{ $article->author->name }}</span>
                                @endif
                            </div>
                            <div class="event-action">
                                <a href="{{ route('news.show', $article->slug) }}" class="btn btn-outline">READ MORE</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <h3 class="empty-title">No Related News</h3>
                <p class="empty-description">There are no news articles related to this event yet. Check back later for updates.</p>
            </div>
        @endif
    </div>
</section> 
@php
    $featuredVideos = App\Models\BoxingVideo::published()
        ->featured()
        ->with(['boxer', 'event'])
        ->orderBy('published_at', 'desc')
        ->take(8)
        ->get();
        
    // Split videos into rows (4 videos per row)
    $videoRows = $featuredVideos->chunk(4);
@endphp

<section class="video-wall-section">
    <div class="bg-text">VIDEOS</div>
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">FEATURED VIDEOS</h2>
            <div class="carousel-navigation">
                <button class="nav-arrow prev-arrow" id="videoPrev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="nav-arrow next-arrow" id="videoNext">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <div class="video-wall-container">
            <div class="video-wall-wrapper">
                @forelse ($videoRows as $index => $row)
                    <!-- Video Row {{ $index + 1 }} -->
                    <div class="video-row" id="videoRow{{ $index + 1 }}">
                        @foreach ($row as $video)
                            <!-- Video Item {{ $video->id }} ({{ $video->is_premium ? 'Premium' : 'Free' }}) -->
                            <div class="video-item"
                                data-video-id="{{ $video->id }}"
                                data-video-slug="{{ $video->slug }}"
                                data-is-premium="{{ $video->is_premium ? 'true' : 'false' }}"
                                onclick="window.location.href='{{ route('videos.show', $video->slug) }}'">
                                <div class="video-thumbnail">
                                    <img src="{{ asset($video->getThumbnailPathAttribute()) }}" alt="{{ $video->title }}">
                                    @if ($video->is_premium)
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
                                    <h4 class="video-title">{{ $video->title }}</h4>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <div class="alert alert-info">
                        No featured videos available at this time.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Video Wall Carousel Navigation
        const videoRows = document.querySelectorAll('.video-row');
        let currentVideoRowIndex = 0;

        if (videoRows.length > 0) {
            // Initially hide all rows except the first one
            videoRows.forEach((row, index) => {
                if (index !== 0) {
                    row.style.display = 'none';
                }
            });

            // Navigation buttons
            const prevBtn = document.getElementById('videoPrev');
            const nextBtn = document.getElementById('videoNext');

            if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                    videoRows[currentVideoRowIndex].style.display = 'none';
                    currentVideoRowIndex = (currentVideoRowIndex - 1 + videoRows.length) % videoRows.length;
                    videoRows[currentVideoRowIndex].style.display = 'flex';
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                    videoRows[currentVideoRowIndex].style.display = 'none';
                    currentVideoRowIndex = (currentVideoRowIndex + 1) % videoRows.length;
                    videoRows[currentVideoRowIndex].style.display = 'flex';
                });
            }
        }

        // Video Navigation - Direct to show page
        const videoItems = document.querySelectorAll('.video-item');
        videoItems.forEach(item => {
            item.addEventListener('click', function() {
                const videoSlug = this.dataset.videoSlug;
                if (videoSlug) {
                    window.location.href = `/videos/${videoSlug}`;
                }
            });
        });
    });
</script> 
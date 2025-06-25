<!-- Event Media Section -->
<section id="media" class="event-media-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">EVENT MEDIA</h2>
            <p class="section-subtitle">Photos and Videos from {{ $event->name }}</p>
        </div>
        
        <div class="media-tabs">
            <div class="media-tab active" data-tab="photos">PHOTOS</div>
            <div class="media-tab" data-tab="videos">VIDEOS</div>
        </div>
        
        <div class="media-content">
            <!-- Photos Tab Content -->
            <div id="photos-content" class="media-pane active">
                @if(isset($event->meta_data) && is_array($event->meta_data) && isset($event->meta_data['gallery']))
                    @php
                        $gallery = $event->meta_data['gallery'] ?? [];
                    @endphp
                    
                    @if(count($gallery) > 0)
                        <div class="photos-gallery">
                            @foreach($gallery as $photo)
                                <div class="gallery-item" data-src="{{ asset($photo['url']) }}">
                                    <img src="{{ asset($photo['url']) }}" alt="{{ $photo['caption'] ?? $event->name . ' Photo' }}">
                                    <div class="gallery-item-overlay">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-images"></i>
                            </div>
                            <h3 class="empty-title">No Photos Available</h3>
                            <p class="empty-description">No photos have been added for this event yet. Check back later for updates.</p>
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-images"></i>
                        </div>
                        <h3 class="empty-title">No Photos Available</h3>
                        <p class="empty-description">No photos have been added for this event yet. Check back later for updates.</p>
                    </div>
                @endif
            </div>
            
            <!-- Videos Tab Content -->
            <div id="videos-content" class="media-pane">
                @if($videos->isNotEmpty())
                    <div class="videos-grid">
                        @foreach($videos as $video)
                            <div class="video-card" data-video-url="{{ $video->video_url }}">
                                <div class="video-thumbnail">
                                    <img src="{{ asset($video->getThumbnailPathAttribute()) }}" alt="{{ $video->title }}">
                                    <div class="play-overlay">
                                        <div class="play-button">
                                            <i class="fas fa-play"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="video-content">
                                    <div class="video-date">{{ \Carbon\Carbon::parse($video->created_at)->format('F j, Y') }}</div>
                                    <h4 class="video-title">{{ $video->title }}</h4>
                                    <p class="video-description">{{ Str::limit($video->description, 100) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <h3 class="empty-title">No Videos Available</h3>
                        <p class="empty-description">No videos have been added for this event yet. Check back later for updates.</p>
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
        <div class="video-player-container">
            <iframe src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="video-modal" id="imageModal">
    <div class="video-modal-content">
        <button class="video-modal-close">&times;</button>
        <img src="" alt="Full-size image" class="img-fluid">
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Media tabs switching
        const mediaTabs = document.querySelectorAll('.media-tab');
        mediaTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                mediaTabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Get the tab id
                const tabId = this.getAttribute('data-tab');
                
                // Hide all content panes
                document.querySelectorAll('.media-pane').forEach(pane => pane.classList.remove('active'));
                
                // Show the selected content pane
                document.getElementById(tabId + '-content').classList.add('active');
            });
        });
        
        // Video card click - open modal and play video
        const videoCards = document.querySelectorAll('.video-card');
        const videoModal = document.getElementById('videoModal');
        const videoModalClose = videoModal.querySelector('.video-modal-close');
        const videoIframe = videoModal.querySelector('iframe');
        
        videoCards.forEach(card => {
            card.addEventListener('click', function() {
                const videoUrl = this.getAttribute('data-video-url');
                
                // Handle YouTube URLs
                if (videoUrl.includes('youtube.com') || videoUrl.includes('youtu.be')) {
                    let videoId = '';
                    
                    if (videoUrl.includes('youtube.com/watch')) {
                        videoId = new URL(videoUrl).searchParams.get('v');
                    } else if (videoUrl.includes('youtu.be')) {
                        videoId = videoUrl.split('/').pop();
                    }
                    
                    if (videoId) {
                        videoIframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
                        videoModal.classList.add('active');
                    }
                }
                // Handle Vimeo URLs
                else if (videoUrl.includes('vimeo.com')) {
                    const vimeoId = videoUrl.split('/').pop();
                    videoIframe.src = `https://player.vimeo.com/video/${vimeoId}?autoplay=1`;
                    videoModal.classList.add('active');
                }
                // Handle direct MP4 URLs
                else if (videoUrl.endsWith('.mp4')) {
                    videoIframe.src = videoUrl;
                    videoModal.classList.add('active');
                }
            });
        });
        
        videoModalClose.addEventListener('click', function() {
            videoModal.classList.remove('active');
            videoIframe.src = '';
        });
        
        // Image gallery click - open modal
        const galleryItems = document.querySelectorAll('.gallery-item');
        const imageModal = document.getElementById('imageModal');
        const imageModalClose = imageModal.querySelector('.video-modal-close');
        const modalImage = imageModal.querySelector('img');
        
        galleryItems.forEach(item => {
            item.addEventListener('click', function() {
                const imageSrc = this.getAttribute('data-src');
                modalImage.src = imageSrc;
                imageModal.classList.add('active');
            });
        });
        
        imageModalClose.addEventListener('click', function() {
            imageModal.classList.remove('active');
        });
        
        // Close modals when clicking outside content
        window.addEventListener('click', function(e) {
            if (e.target === videoModal) {
                videoModal.classList.remove('active');
                videoIframe.src = '';
            }
            if (e.target === imageModal) {
                imageModal.classList.remove('active');
            }
        });
    });
</script>
@endpush 
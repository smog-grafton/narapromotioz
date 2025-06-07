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
                                data-video-url="{{ $video->is_premium ? '' : $video->video_url }}"
                                data-title="{{ $video->title }}"
                                data-description="{{ $video->description }}"
                                data-thumbnail="{{ $video->getThumbnailPathAttribute() }}"
                                data-duration="{{ $video->duration }}"
                                data-is-premium="{{ $video->is_premium ? 'true' : 'false' }}"
                                data-tags="{{ is_array($video->tags) ? implode(',', $video->tags) : (is_string($video->tags) ? implode(',', json_decode($video->tags, true) ?? []) : '') }}">
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

<!-- Video Player Modal -->
<div class="modal fade video-player-modal" id="videoPlayerModal" tabindex="-1" aria-labelledby="videoPlayerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoPlayerModalLabel">Video Player</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="video-player-container">
                    <div class="video-player-main">
                        <!-- Video Player Area (will be populated dynamically) -->
                        <div id="videoPlayerArea">
                            <!-- Either video player or premium content message will be shown here -->
                        </div>
                        
                        <!-- Video Info Container -->
                        <div class="video-info-container">
                            <h2 class="video-title" id="modalVideoTitle"></h2>
                            
                            <!-- Tags for Premium Videos -->
                            <div class="video-meta" id="videoTagsContainer">
                                <!-- Tags will be added dynamically -->
                            </div>
                            
                            <!-- Video Description -->
                            <div class="video-description" id="videoDescription"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- Premium button will be added here conditionally -->
                <div id="premiumButtonContainer"></div>
            </div>
        </div>
    </div>
</div>

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

            prevBtn.addEventListener('click', function() {
                videoRows[currentVideoRowIndex].style.display = 'none';
                currentVideoRowIndex = (currentVideoRowIndex - 1 + videoRows.length) % videoRows.length;
                videoRows[currentVideoRowIndex].style.display = 'flex';
            });

            nextBtn.addEventListener('click', function() {
                videoRows[currentVideoRowIndex].style.display = 'none';
                currentVideoRowIndex = (currentVideoRowIndex + 1) % videoRows.length;
                videoRows[currentVideoRowIndex].style.display = 'flex';
            });
        }

        // Video Modal Functionality
        const videoItems = document.querySelectorAll('.video-item');
        const videoPlayerModal = document.getElementById('videoPlayerModal');
        const videoPlayerArea = document.getElementById('videoPlayerArea');
        const modalVideoTitle = document.getElementById('modalVideoTitle');
        const videoDescription = document.getElementById('videoDescription');
        const videoTagsContainer = document.getElementById('videoTagsContainer');
        const premiumButtonContainer = document.getElementById('premiumButtonContainer');

        videoItems.forEach(item => {
            item.addEventListener('click', function() {
                const videoId = this.dataset.videoId;
                const videoUrl = this.dataset.videoUrl;
                const videoTitle = this.dataset.title;
                const videoDesc = this.dataset.description;
                const isPremium = this.dataset.isPremium === 'true';
                const tags = this.dataset.tags ? this.dataset.tags.split(',') : [];

                // Set modal title
                modalVideoTitle.textContent = videoTitle;
                
                // Set video description
                videoDescription.textContent = videoDesc;
                
                // Clear and set tags
                videoTagsContainer.innerHTML = '';
                tags.forEach(tag => {
                    if (tag.trim()) {
                        const tagSpan = document.createElement('span');
                        tagSpan.className = 'video-tag';
                        tagSpan.textContent = tag.trim();
                        videoTagsContainer.appendChild(tagSpan);
                    }
                });
                
                // Clear premium button container
                premiumButtonContainer.innerHTML = '';

                // Set video player content based on premium status
                if (isPremium) {
                    // Show premium content message
                    videoPlayerArea.innerHTML = `
                        <div class="premium-content-message">
                            <div class="premium-icon">
                                <i class="fas fa-crown"></i>
                            </div>
                            <h3>Premium Content</h3>
                            <p>This video is available exclusively to premium members.</p>
                        </div>
                    `;
                    
                    // Add upgrade button
                    const upgradeButton = document.createElement('button');
                    upgradeButton.className = 'btn btn-primary';
                    upgradeButton.textContent = 'Upgrade to Premium';
                    upgradeButton.addEventListener('click', function() {
                        window.location.href = '/subscription';
                    });
                    premiumButtonContainer.appendChild(upgradeButton);
                } else {
                    // Show video player
                    videoPlayerArea.innerHTML = `
                        <div class="video-embed-container">
                            <iframe 
                                src="${videoUrl}" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    `;
                }
                
                // Show modal
                const modalInstance = new bootstrap.Modal(videoPlayerModal);
                modalInstance.show();
            });
        });
        
        // Stop video playback when modal is closed
        videoPlayerModal.addEventListener('hidden.bs.modal', function() {
            videoPlayerArea.innerHTML = '';
        });
    });
</script> 
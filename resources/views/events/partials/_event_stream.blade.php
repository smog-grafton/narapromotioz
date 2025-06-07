<!-- Event Stream Section -->
<section id="stream" class="event-stream-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">LIVE STREAM</h2>
            <p class="section-subtitle">Watch {{ $event->name }} Live</p>
        </div>
        
        <div class="stream-container">
            <div class="stream-status">
                @if($event->isOngoing)
                    <div class="status-badge live">
                        <span class="dot"></span>
                        LIVE NOW
                    </div>
                @elseif($event->isUpcoming)
                    <div class="status-badge upcoming">
                        <i class="fas fa-clock"></i>
                        UPCOMING
                    </div>
                @else
                    <div class="status-badge ended">
                        <i class="fas fa-calendar-check"></i>
                        ENDED
                    </div>
                @endif
            </div>
            
            <div class="video-player-container">
                @if($event->isOngoing)
                    @if($event->is_free || (auth()->check() && auth()->user()->hasPurchasedTicket($event->id)))
                        @if(isset($event->meta_data) && is_array(json_decode($event->meta_data, true)) && isset(json_decode($event->meta_data, true)['stream_url']))
                            @php
                                $streamUrl = json_decode($event->meta_data, true)['stream_url'];
                                $streamType = json_decode($event->meta_data, true)['stream_type'] ?? 'youtube';
                            @endphp
                            
                            @if($streamType === 'youtube')
                                <iframe 
                                    src="https://www.youtube.com/embed/{{ $streamUrl }}?autoplay=0&rel=0"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            @elseif($streamType === 'vimeo')
                                <iframe 
                                    src="https://player.vimeo.com/video/{{ $streamUrl }}?autoplay=0"
                                    frameborder="0" 
                                    allow="autoplay; fullscreen; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            @elseif($streamType === 'm3u8')
                                <video id="live-player" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto">
                                    <source src="{{ $streamUrl }}" type="application/x-mpegURL">
                                </video>
                                
                                @push('styles')
                                <link href="https://vjs.zencdn.net/7.15.4/video-js.css" rel="stylesheet">
                                @endpush
                                
                                @push('scripts')
                                <script src="https://vjs.zencdn.net/7.15.4/video.min.js"></script>
                                <script src="https://unpkg.com/@videojs/http-streaming/dist/videojs-http-streaming.js"></script>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var player = videojs('live-player', {
                                            fluid: true,
                                            liveui: true
                                        });
                                    });
                                </script>
                                @endpush
                            @elseif($streamType === 'embed')
                                {!! $streamUrl !!}
                            @endif
                        @else
                            <div class="player-overlay">
                                <div class="overlay-icon">
                                    <i class="fas fa-video"></i>
                                </div>
                                <h3 class="overlay-title">Stream Not Available</h3>
                                <p class="overlay-text">The live stream for this event has not been set up yet. Please check back later.</p>
                            </div>
                        @endif
                    @else
                        <div class="player-overlay">
                            <div class="overlay-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h3 class="overlay-title">Premium Content</h3>
                            <p class="overlay-text">This live stream is available to ticket holders only. Purchase a ticket to watch this event live.</p>
                            <a href="#tickets" class="btn">GET TICKETS</a>
                        </div>
                    @endif
                @elseif($event->isUpcoming)
                    <div class="player-overlay">
                        <div class="overlay-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="overlay-title">Stream Starts Soon</h3>
                        <p class="overlay-text">The live stream for this event will be available on {{ $event->event_date->format('F j, Y') }} at {{ $event->event_time ? $event->event_time->format('g:i A') : 'TBA' }}.</p>
                        
                        @if(!$event->is_free)
                            <a href="#tickets" class="btn">GET TICKETS</a>
                        @endif
                    </div>
                @else
                    <div class="player-overlay">
                        <div class="overlay-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="overlay-title">Event Ended</h3>
                        <p class="overlay-text">This event has concluded. Check out the highlights and results below.</p>
                        <a href="#highlights" class="btn">VIEW HIGHLIGHTS</a>
                    </div>
                @endif
            </div>
            
            <div class="stream-info">
                <div class="info-item">
                    <div class="info-label">Date</div>
                    <div class="info-value">{{ $event->event_date->format('F j, Y') }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Time</div>
                    <div class="info-value">{{ $event->event_time ? $event->event_time->format('g:i A') : 'TBA' }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Broadcast</div>
                    <div class="info-value">{{ $event->network ?: 'TBA' }}</div>
                </div>
                
                @if(!$event->is_free)
                    <div class="info-item">
                        <div class="info-label">Stream Price</div>
                        <div class="info-value">${{ number_format($event->min_ticket_price, 2) }}</div>
                    </div>
                @else
                    <div class="info-item">
                        <div class="info-label">Stream Type</div>
                        <div class="info-value">Free Stream</div>
                    </div>
                @endif
            </div>
            
            @if($event->isUpcoming || $event->isOngoing)
                @if(!$event->is_free && (!auth()->check() || !auth()->user()->hasPurchasedTicket($event->id)))
                    <div class="stream-cta">
                        <a href="#tickets" class="btn">
                            <i class="fas fa-ticket-alt"></i>
                            PURCHASE STREAM ACCESS
                        </a>
                        <div class="price-info">
                            Starting at ${{ number_format($event->min_ticket_price, 2) }} - Watch live and on-demand for 7 days after the event
                        </div>
                    </div>
                @elseif($event->is_free && $event->isUpcoming)
                    <div class="stream-cta">
                        @if(auth()->check())
                            <a href="#" class="btn btn-reminder" data-event-id="{{ $event->id }}">
                                <i class="fas fa-bell"></i>
                                SET REMINDER
                            </a>
                        @else
                            <a href="{{ route('login') }}?redirect={{ route('events.show', $event->slug) }}" class="btn">
                                <i class="fas fa-sign-in-alt"></i>
                                LOGIN TO SET REMINDER
                            </a>
                        @endif
                        <div class="price-info">
                            Free Stream - No purchase required
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set reminder functionality
        const reminderButtons = document.querySelectorAll('.btn-reminder');
        
        reminderButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const eventId = this.getAttribute('data-event-id');
                
                // You would implement an AJAX call to set a reminder
                // This is a placeholder for the actual implementation
                alert('Reminder set successfully! You will be notified before the event starts.');
                
                // Example AJAX call:
                /*
                fetch('/api/set-reminder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        event_id: eventId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Reminder set successfully! You will be notified before the event starts.');
                    } else {
                        alert('Failed to set reminder. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
                */
            });
        });
    });
</script>
@endpush 
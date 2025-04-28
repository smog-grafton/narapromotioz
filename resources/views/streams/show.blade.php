@extends('layouts.app')

@section('title', 'Live Stream - ' . $event->title)

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Video Player Section -->
        <div class="col-lg-9 stream-main-column">
            <div class="stream-container">
                <!-- Stream Status -->
                <div class="stream-status {{ $event->is_live ? 'live' : 'upcoming' }}">
                    @if($event->is_live)
                        <span class="live-indicator me-2"></span> LIVE
                    @else
                        <i class="fas fa-clock me-2"></i> STARTS IN {{ now()->diffForHumans($event->event_date, ['parts' => 2]) }}
                    @endif
                </div>
                
                <!-- Video Player -->
                <div class="video-container">
                    @if($event->is_live)
                        <div class="embed-responsive">
                            <!-- This is where the actual video player would be embedded -->
                            <!-- For example, an iframe with the stream source -->
                            @if($event->stream_url)
                                <iframe 
                                    src="{{ $event->stream_url }}" 
                                    class="embed-responsive-item" 
                                    allowfullscreen
                                    allow="autoplay; encrypted-media; picture-in-picture"
                                    frameborder="0">
                                </iframe>
                            @else
                                <!-- Placeholder for when stream_url is not yet available -->
                                <div class="stream-placeholder d-flex flex-column align-items-center justify-content-center">
                                    <div class="live-pulse-container mb-4">
                                        <div class="live-pulse"></div>
                                        <div class="live-pulse-center">
                                            <i class="fas fa-play-circle fa-3x"></i>
                                        </div>
                                    </div>
                                    <h3 class="mb-3">Live Stream Active</h3>
                                    <p class="text-center mx-auto" style="max-width: 600px;">
                                        The stream is active. If you're having trouble viewing the stream, 
                                        please refresh your browser or check your internet connection.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Stream Not Live Yet -->
                        <div class="stream-placeholder d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-clock fa-4x mb-4 text-primary"></i>
                            <h3 class="mb-3">Stream Starts Soon</h3>
                            <div class="stream-countdown mb-3">
                                <div class="countdown-item">
                                    <div class="countdown-value" id="countdown-days">--</div>
                                    <div class="countdown-label">Days</div>
                                </div>
                                <div class="countdown-item">
                                    <div class="countdown-value" id="countdown-hours">--</div>
                                    <div class="countdown-label">Hours</div>
                                </div>
                                <div class="countdown-item">
                                    <div class="countdown-value" id="countdown-minutes">--</div>
                                    <div class="countdown-label">Minutes</div>
                                </div>
                                <div class="countdown-item">
                                    <div class="countdown-value" id="countdown-seconds">--</div>
                                    <div class="countdown-label">Seconds</div>
                                </div>
                            </div>
                            <p class="text-center mx-auto" style="max-width: 600px;">
                                The stream for {{ $event->title }} will begin shortly. 
                                You don't need to refresh the page - it will automatically update when the stream begins.
                            </p>
                        </div>
                    @endif
                </div>
                
                <!-- Stream Info -->
                <div class="stream-info p-3">
                    <h3 class="stream-title mb-2">{{ $event->title }}</h3>
                    <div class="stream-meta mb-3">
                        <span class="me-3">
                            <i class="fas fa-calendar-alt me-1"></i> {{ $event->event_date->format('F j, Y') }}
                        </span>
                        <span class="me-3">
                            <i class="fas fa-clock me-1"></i> {{ $event->event_date->format('g:i A') }}
                        </span>
                        <span>
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $event->location }}
                        </span>
                    </div>
                    <p class="stream-description">
                        {{ $event->description }}
                    </p>
                </div>
                
                <!-- Stream Controls (Quality, Volume, etc) -->
                <div class="stream-controls p-3 border-top">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="dropdown me-3">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="qualityDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cog me-1"></i> Quality
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="qualityDropdown">
                                        <li><a class="dropdown-item" href="#">Auto</a></li>
                                        <li><a class="dropdown-item" href="#">1080p</a></li>
                                        <li><a class="dropdown-item" href="#">720p</a></li>
                                        <li><a class="dropdown-item" href="#">480p</a></li>
                                        <li><a class="dropdown-item" href="#">360p</a></li>
                                    </ul>
                                </div>
                                
                                <div class="form-check form-switch me-3">
                                    <input class="form-check-input" type="checkbox" id="chatToggle" checked>
                                    <label class="form-check-label" for="chatToggle">Chat</label>
                                </div>
                                
                                <button class="btn btn-outline-secondary" id="fullscreenBtn">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-6 d-flex justify-content-md-end align-items-center">
                            <div class="stream-viewers me-3">
                                <i class="fas fa-eye me-1"></i> <span id="viewerCount">1,234</span> viewers
                            </div>
                            
                            <button class="btn btn-outline-primary" id="shareBtn">
                                <i class="fas fa-share-alt me-1"></i> Share
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Fight Card -->
            <div class="card mt-4 mb-4 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Fight Card</h5>
                </div>
                
                <div class="card-body p-0">
                    @if($event->fights->isEmpty())
                        <div class="p-4 text-center">
                            <p class="text-muted mb-0">Fight card will be announced soon.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Order</th>
                                        <th>Fighter 1</th>
                                        <th>vs</th>
                                        <th>Fighter 2</th>
                                        <th>Weight Class</th>
                                        <th>Rounds</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($event->fights->sortBy('fight_order') as $fight)
                                        <tr class="{{ $fight->is_main_event ? 'table-primary' : '' }}">
                                            <td>
                                                @if($fight->is_main_event)
                                                    <span class="badge bg-primary">Main Event</span>
                                                @else
                                                    {{ $fight->fight_order }}
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('fighters.show', $fight->fighterOne) }}" class="text-decoration-none">
                                                    {{ $fight->fighterOne->full_name }}
                                                    <span class="text-muted">({{ $fight->fighterOne->wins }}-{{ $fight->fighterOne->losses }})</span>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-danger">VS</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('fighters.show', $fight->fighterTwo) }}" class="text-decoration-none">
                                                    {{ $fight->fighterTwo->full_name }}
                                                    <span class="text-muted">({{ $fight->fighterTwo->wins }}-{{ $fight->fighterTwo->losses }})</span>
                                                </a>
                                            </td>
                                            <td>{{ $fight->weight_class }}</td>
                                            <td>{{ $fight->rounds }}</td>
                                            <td>
                                                @if($fight->status === 'completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($fight->status === 'in_progress')
                                                    <span class="badge bg-warning text-dark">In Progress</span>
                                                @else
                                                    <span class="badge bg-secondary">Upcoming</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Live Chat Section -->
        <div class="col-lg-3 stream-chat-column" id="chatSection">
            <div class="stream-chat">
                <div class="chat-header p-3 border-bottom">
                    <h5 class="mb-0">Live Chat</h5>
                </div>
                
                <div class="chat-messages p-3" id="chatMessages">
                    <!-- This would be populated dynamically with messages -->
                    <div class="chat-message">
                        <div class="chat-message-header">
                            <span class="chat-username">BoxingFan123</span>
                            <span class="chat-time">2:34 PM</span>
                        </div>
                        <div class="chat-message-content">
                            Can't wait for the main event! 🥊
                        </div>
                    </div>
                    
                    <div class="chat-message">
                        <div class="chat-message-header">
                            <span class="chat-username">FightNight</span>
                            <span class="chat-time">2:35 PM</span>
                        </div>
                        <div class="chat-message-content">
                            Who do you all think will win tonight?
                        </div>
                    </div>
                    
                    <div class="chat-message">
                        <div class="chat-message-header">
                            <span class="chat-username">KnockoutKing</span>
                            <span class="chat-time">2:36 PM</span>
                        </div>
                        <div class="chat-message-content">
                            I'm predicting a knockout in round 5!
                        </div>
                    </div>
                    
                    <div class="chat-message system-message">
                        <div class="chat-message-content">
                            <i class="fas fa-info-circle me-1"></i> The next fight will begin in approximately 5 minutes.
                        </div>
                    </div>
                    
                    <div class="chat-message">
                        <div class="chat-message-header">
                            <span class="chat-username">RingMaster</span>
                            <span class="chat-time">2:38 PM</span>
                        </div>
                        <div class="chat-message-content">
                            The atmosphere here is electric! So glad I got to watch this live.
                        </div>
                    </div>
                </div>
                
                <div class="chat-input p-3 border-top">
                    <form id="chatForm">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Type your message..." id="messageInput">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="chat-rules p-3 border-top">
                    <h6>Chat Rules:</h6>
                    <ul class="small mb-0">
                        <li>Be respectful to all fighters and viewers</li>
                        <li>No spam or inappropriate language</li>
                        <li>No advertising or self-promotion</li>
                        <li>Moderators reserve the right to remove messages</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    body {
        background-color: #1a1a1a;
    }
    
    .stream-main-column {
        background-color: #1a1a1a;
        min-height: calc(100vh - 56px);
    }
    
    .stream-chat-column {
        background-color: #262626;
        min-height: calc(100vh - 56px);
    }
    
    .stream-container {
        background-color: #000;
        position: relative;
    }
    
    .stream-status {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 10;
        padding: 5px 12px;
        border-radius: 30px;
        font-weight: bold;
        font-size: 0.9rem;
    }
    
    .stream-status.live {
        background-color: rgba(220, 53, 69, 0.9);
        color: white;
    }
    
    .stream-status.upcoming {
        background-color: rgba(0, 123, 255, 0.9);
        color: white;
    }
    
    .video-container {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
        height: 0;
        overflow: hidden;
    }
    
    .video-container iframe,
    .stream-placeholder {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }
    
    .stream-placeholder {
        background-color: #111;
        color: white;
        padding: 20px;
    }
    
    .stream-info {
        background-color: #262626;
        color: white;
    }
    
    .stream-title {
        font-weight: bold;
    }
    
    .stream-meta {
        color: #aaa;
        font-size: 0.9rem;
    }
    
    .stream-description {
        color: #ddd;
        font-size: 0.95rem;
    }
    
    .stream-controls {
        background-color: #2d2d2d;
        color: white;
    }
    
    .stream-chat {
        background-color: #262626;
        color: white;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .chat-header {
        background-color: #2d2d2d;
    }
    
    .chat-messages {
        flex-grow: 1;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 15px;
        max-height: calc(100vh - 250px);
    }
    
    .chat-message {
        background-color: #333;
        border-radius: 10px;
        padding: 10px 15px;
    }
    
    .chat-message-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }
    
    .chat-username {
        font-weight: bold;
        color: #00ADEF;
    }
    
    .chat-time {
        color: #888;
        font-size: 0.8rem;
    }
    
    .chat-message-content {
        word-break: break-word;
    }
    
    .system-message {
        background-color: rgba(0, 123, 255, 0.3);
        border: 1px solid rgba(0, 123, 255, 0.5);
    }
    
    .chat-input {
        background-color: #2d2d2d;
    }
    
    .chat-rules {
        background-color: #323232;
        font-size: 0.85rem;
    }
    
    .chat-rules ul {
        padding-left: 20px;
    }
    
    .stream-countdown {
        display: flex;
        gap: 15px;
    }
    
    .countdown-item {
        text-align: center;
        min-width: 70px;
    }
    
    .countdown-value {
        font-size: 2rem;
        font-weight: bold;
        background-color: rgba(0, 123, 255, 0.2);
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 5px;
    }
    
    .countdown-label {
        font-size: 0.8rem;
        color: #aaa;
    }
    
    .live-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        background-color: #ff0000;
        border-radius: 50%;
        animation: pulse 1.5s infinite;
    }
    
    .live-pulse-container {
        position: relative;
        width: 100px;
        height: 100px;
    }
    
    .live-pulse {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: rgba(220, 53, 69, 0.5);
        animation: pulse-wave 2s infinite;
    }
    
    .live-pulse-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #dc3545;
    }
    
    @keyframes pulse {
        0% {
            opacity: 1;
        }
        50% {
            opacity: 0.4;
        }
        100% {
            opacity: 1;
        }
    }
    
    @keyframes pulse-wave {
        0% {
            transform: scale(0.7);
            opacity: 1;
        }
        70% {
            transform: scale(1.3);
            opacity: 0;
        }
        100% {
            transform: scale(0.7);
            opacity: 0;
        }
    }
    
    @media (max-width: 991.98px) {
        .stream-chat-column {
            height: 400px;
        }
        
        .chat-messages {
            max-height: 250px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle chat section
        const chatToggle = document.getElementById('chatToggle');
        const chatSection = document.getElementById('chatSection');
        
        if (chatToggle && chatSection) {
            chatToggle.addEventListener('change', function() {
                if (this.checked) {
                    chatSection.style.display = 'block';
                } else {
                    chatSection.style.display = 'none';
                }
            });
        }
        
        // Fullscreen button functionality
        const fullscreenBtn = document.getElementById('fullscreenBtn');
        const videoContainer = document.querySelector('.video-container');
        
        if (fullscreenBtn && videoContainer) {
            fullscreenBtn.addEventListener('click', function() {
                if (videoContainer.requestFullscreen) {
                    videoContainer.requestFullscreen();
                } else if (videoContainer.webkitRequestFullscreen) {
                    videoContainer.webkitRequestFullscreen();
                } else if (videoContainer.msRequestFullscreen) {
                    videoContainer.msRequestFullscreen();
                }
            });
        }
        
        // Chat form submission
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const chatMessages = document.getElementById('chatMessages');
        
        if (chatForm && messageInput && chatMessages) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const message = messageInput.value.trim();
                if (message) {
                    // In a real app, you would send this to a websocket server
                    // Here we just simulate adding the message to the chat
                    const timestamp = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    
                    const messageElement = document.createElement('div');
                    messageElement.className = 'chat-message';
                    messageElement.innerHTML = `
                        <div class="chat-message-header">
                            <span class="chat-username">You</span>
                            <span class="chat-time">${timestamp}</span>
                        </div>
                        <div class="chat-message-content">
                            ${message}
                        </div>
                    `;
                    
                    chatMessages.appendChild(messageElement);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                    messageInput.value = '';
                }
            });
        }
        
        // Countdown timer for upcoming streams
        const countdownDays = document.getElementById('countdown-days');
        const countdownHours = document.getElementById('countdown-hours');
        const countdownMinutes = document.getElementById('countdown-minutes');
        const countdownSeconds = document.getElementById('countdown-seconds');
        
        if (countdownDays && countdownHours && countdownMinutes && countdownSeconds) {
            @if(!$event->is_live)
                const eventDate = new Date('{{ $event->event_date->toISOString() }}');
                
                function updateCountdown() {
                    const now = new Date();
                    const diff = eventDate - now;
                    
                    if (diff <= 0) {
                        // Event has started
                        countdownDays.textContent = '00';
                        countdownHours.textContent = '00';
                        countdownMinutes.textContent = '00';
                        countdownSeconds.textContent = '00';
                        
                        // Potentially refresh the page to show the live stream
                        setTimeout(() => {
                            window.location.reload();
                        }, 5000);
                        
                        return;
                    }
                    
                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                    
                    countdownDays.textContent = days.toString().padStart(2, '0');
                    countdownHours.textContent = hours.toString().padStart(2, '0');
                    countdownMinutes.textContent = minutes.toString().padStart(2, '0');
                    countdownSeconds.textContent = seconds.toString().padStart(2, '0');
                }
                
                // Initial call and then set interval
                updateCountdown();
                setInterval(updateCountdown, 1000);
            @endif
        }
        
        // Share button functionality
        const shareBtn = document.getElementById('shareBtn');
        
        if (shareBtn) {
            shareBtn.addEventListener('click', function() {
                const url = window.location.href;
                
                if (navigator.share) {
                    navigator.share({
                        title: '{{ $event->title }} - Live Stream',
                        text: 'Check out this boxing live stream!',
                        url: url
                    });
                } else {
                    // Fallback for browsers that don't support the Web Share API
                    navigator.clipboard.writeText(url).then(() => {
                        alert('Stream link copied to clipboard!');
                    });
                }
            });
        }
    });
</script>
@endsection
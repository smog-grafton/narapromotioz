/**
 * Nara Promotionz - Boxing Promotions Website
 * Main JavaScript File
 */

// Import modules
import './bootstrap';

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initNavbarEffects();
    initCountdownTimers();
    initLazyLoading();
    initAnimations();
    initScrollToTop();
    
    // Show site after everything is loaded
    setTimeout(() => {
        document.body.classList.add('loaded');
    }, 500);
});

/**
 * Initialize Navbar Effects
 */
function initNavbarEffects() {
    const navbar = document.querySelector('.navbar');
    
    if (!navbar) return;
    
    // Shrink navbar on scroll
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    
    // Mobile menu background
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', () => {
            navbar.classList.toggle('menu-open');
        });
    }
}

/**
 * Initialize Countdown Timers
 */
function initCountdownTimers() {
    // Legacy countdowns
    const countdownElements = document.querySelectorAll('[data-countdown]');
    
    countdownElements.forEach(element => {
        const targetDate = new Date(element.dataset.countdown).getTime();
        
        // Update countdown every second
        const countdownInterval = setInterval(() => {
            const now = new Date().getTime();
            const distance = targetDate - now;
            
            if (distance < 0) {
                clearInterval(countdownInterval);
                element.innerHTML = element.dataset.expiredMessage || 'Event Started';
                return;
            }
            
            // Calculate time units
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Format and display countdown
            element.innerHTML = `
                <div class="countdown-item">
                    <span class="countdown-value">${days}</span>
                    <span class="countdown-label">Days</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-value">${hours}</span>
                    <span class="countdown-label">Hours</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-value">${minutes}</span>
                    <span class="countdown-label">Minutes</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-value">${seconds}</span>
                    <span class="countdown-label">Seconds</span>
                </div>
            `;
        }, 1000);
    });
    
    // New hero countdown timer
    const eventTimers = document.querySelectorAll('.countdown-timer[data-event-date]');
    
    eventTimers.forEach(timer => {
        const targetDate = new Date(timer.dataset.eventDate).getTime();
        const daysElem = timer.querySelector('.days');
        const hoursElem = timer.querySelector('.hours');
        const minutesElem = timer.querySelector('.minutes');
        const secondsElem = timer.querySelector('.seconds');
        
        if (!daysElem || !hoursElem || !minutesElem || !secondsElem) return;
        
        // Update countdown every second
        const countdownInterval = setInterval(() => {
            const now = new Date().getTime();
            const distance = targetDate - now;
            
            if (distance < 0) {
                clearInterval(countdownInterval);
                timer.innerHTML = '<div class="event-started">EVENT STARTED!</div>';
                return;
            }
            
            // Calculate time units
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Add leading zeros and update elements
            daysElem.textContent = days.toString().padStart(2, '0');
            hoursElem.textContent = hours.toString().padStart(2, '0');
            minutesElem.textContent = minutes.toString().padStart(2, '0');
            secondsElem.textContent = seconds.toString().padStart(2, '0');
        }, 1000);
    });
    
    // Animate counter numbers on scroll
    initCounterAnimation();
}

/**
 * Animate counter numbers when visible
 */
function initCounterAnimation() {
    const counters = document.querySelectorAll('.counter-number[data-count]');
    
    if (!counters.length) return;
    
    const counterObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-count'), 10);
                const duration = 2000; // 2 seconds
                const startTime = Date.now();
                const startValue = 0;
                
                function updateCounter() {
                    const currentTime = Date.now();
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    
                    // Easing function for smoother animation
                    const easeOutQuad = progress * (2 - progress);
                    const currentValue = Math.floor(startValue + (target - startValue) * easeOutQuad);
                    
                    counter.textContent = `${currentValue}+`;
                    
                    if (progress < 1) {
                        requestAnimationFrame(updateCounter);
                    }
                }
                
                updateCounter();
                observer.unobserve(counter);
            }
        });
    }, { threshold: 0.1 });
    
    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
}

/**
 * Initialize Lazy Loading for Images
 */
function initLazyLoading() {
    if ('loading' in HTMLImageElement.prototype) {
        // Browser supports native lazy loading
        const lazyImages = document.querySelectorAll('img[loading="lazy"]');
        lazyImages.forEach(img => {
            img.src = img.dataset.src;
        });
    } else {
        // Fallback for browsers that don't support native lazy loading
        const lazyImages = document.querySelectorAll('.lazy-image');
        
        if (lazyImages.length === 0) return;
        
        const lazyImageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy-image');
                    lazyImageObserver.unobserve(img);
                }
            });
        });
        
        lazyImages.forEach(img => {
            lazyImageObserver.observe(img);
        });
    }
}

/**
 * Initialize Animation on Scroll
 */
function initAnimations() {
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    if (animatedElements.length === 0) return;
    
    const animationObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const animation = element.dataset.animation || 'fade-in';
                const delay = element.dataset.delay || 0;
                
                setTimeout(() => {
                    element.classList.add(animation);
                    element.classList.add('animated');
                }, delay);
                
                animationObserver.unobserve(element);
            }
        });
    }, {
        threshold: 0.1
    });
    
    animatedElements.forEach(element => {
        animationObserver.observe(element);
    });
}

/**
 * Initialize Scroll to Top Button
 */
function initScrollToTop() {
    const scrollToTopBtn = document.getElementById('backToTop');
    
    if (!scrollToTopBtn) return;
    
    // Show/hide button based on scroll position
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            scrollToTopBtn.classList.add('show');
        } else {
            scrollToTopBtn.classList.remove('show');
        }
    });
    
    // Smooth scroll to top on click
    scrollToTopBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/**
 * Live Streaming Chat Features
 */
export class LiveStreamChat {
    constructor(options) {
        this.streamId = options.streamId;
        this.user = options.user;
        this.socketUrl = options.socketUrl || this.getDefaultSocketUrl();
        this.chatContainer = options.chatContainer;
        this.messageInput = options.messageInput;
        this.sendButton = options.sendButton;
        
        // Callbacks
        this.callbacks = {
            onConnect: options.onConnect || (() => {}),
            onDisconnect: options.onDisconnect || (() => {}),
            onMessage: options.onMessage || (() => {}),
            onUserJoin: options.onUserJoin || (() => {}),
            onUserLeave: options.onUserLeave || (() => {}),
            onUserCount: options.onUserCount || (() => {}),
            onError: options.onError || (() => {})
        };
        
        // Setup state
        this.socket = null;
        this.connected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectDelay = 3000;
        this.heartbeatInterval = null;
        this.messageCache = new Map();
        
        // Initialize
        this.init();
    }
    
    /**
     * Initialize the chat
     */
    init() {
        // Set up event listeners
        if (this.sendButton && this.messageInput) {
            this.sendButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.sendMessage();
            });
            
            this.messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            });
        }
        
        // Connect to WebSocket server
        this.connect();
    }
    
    /**
     * Get default WebSocket URL
     */
    getDefaultSocketUrl() {
        const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
        const host = window.location.host;
        return `${protocol}//${host}/ws`;
    }
    
    /**
     * Connect to WebSocket server
     */
    connect() {
        if (this.socket) {
            this.disconnect();
        }
        
        try {
            this.socket = new WebSocket(this.socketUrl);
            
            this.socket.onopen = () => this.handleOpen();
            this.socket.onmessage = (event) => this.handleMessage(event);
            this.socket.onclose = () => this.handleClose();
            this.socket.onerror = (error) => this.handleError(error);
        } catch (error) {
            this.callbacks.onError(error);
            this.scheduleReconnect();
        }
    }
    
    /**
     * Handle WebSocket open event
     */
    handleOpen() {
        this.connected = true;
        this.reconnectAttempts = 0;
        
        // Join the stream chat
        this.sendJoinMessage();
        
        // Start heartbeat
        this.startHeartbeat();
        
        // Notify client
        this.callbacks.onConnect();
    }
    
    /**
     * Handle WebSocket message event
     */
    handleMessage(event) {
        try {
            const data = JSON.parse(event.data);
            
            switch (data.type) {
                case 'join_confirmed':
                    this.callbacks.onUserCount(data.user_count);
                    break;
                
                case 'user_joined':
                    this.callbacks.onUserJoin({
                        userId: data.user_id,
                        username: data.username
                    });
                    this.callbacks.onUserCount(data.user_count);
                    break;
                
                case 'user_left':
                    this.callbacks.onUserLeave({
                        userId: data.user_id,
                        username: data.username
                    });
                    this.callbacks.onUserCount(data.user_count);
                    break;
                
                case 'chat_message':
                    const message = {
                        id: data.id,
                        userId: data.user_id,
                        username: data.username,
                        message: data.message,
                        timestamp: new Date(data.timestamp),
                        isPinned: data.is_pinned
                    };
                    
                    if (!this.messageCache.has(data.id)) {
                        this.messageCache.set(data.id, true);
                        this.callbacks.onMessage(message);
                    }
                    break;
                
                case 'recent_messages':
                    if (data.messages && Array.isArray(data.messages)) {
                        data.messages.forEach(msg => {
                            const message = {
                                id: msg.id,
                                userId: msg.user_id,
                                username: msg.username,
                                message: msg.message,
                                timestamp: new Date(msg.timestamp),
                                isPinned: msg.is_pinned
                            };
                            
                            if (!this.messageCache.has(msg.id)) {
                                this.messageCache.set(msg.id, true);
                                this.callbacks.onMessage(message);
                            }
                        });
                    }
                    break;
                
                case 'pong':
                    // Heartbeat response, nothing to do
                    break;
                
                case 'error':
                    this.callbacks.onError(new Error(data.message || 'Unknown server error'));
                    break;
            }
        } catch (error) {
            this.callbacks.onError(error);
        }
    }
    
    /**
     * Handle WebSocket close event
     */
    handleClose() {
        const wasConnected = this.connected;
        this.connected = false;
        this.clearHeartbeat();
        
        if (wasConnected) {
            this.callbacks.onDisconnect();
        }
        
        this.scheduleReconnect();
    }
    
    /**
     * Handle WebSocket error event
     */
    handleError(error) {
        this.callbacks.onError(error);
        
        if (!this.connected) {
            this.scheduleReconnect();
        }
    }
    
    /**
     * Schedule a reconnection attempt
     */
    scheduleReconnect() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            
            const delay = this.reconnectDelay * Math.pow(1.5, this.reconnectAttempts - 1);
            
            setTimeout(() => {
                if (!this.connected) {
                    this.connect();
                }
            }, delay);
        }
    }
    
    /**
     * Send a chat message
     */
    sendMessage() {
        if (!this.connected || !this.socket || !this.messageInput) {
            return false;
        }
        
        const message = this.messageInput.value.trim();
        
        if (!message) {
            return false;
        }
        
        const data = {
            action: 'message',
            stream_id: this.streamId,
            message: message
        };
        
        this.socket.send(JSON.stringify(data));
        this.messageInput.value = '';
        return true;
    }
    
    /**
     * Send join message
     */
    sendJoinMessage() {
        if (!this.socket) {
            return;
        }
        
        const data = {
            action: 'join',
            stream_id: this.streamId,
            user_id: this.user.id,
            username: this.user.name,
            token: this.user.token || 'demo-token'
        };
        
        this.socket.send(JSON.stringify(data));
    }
    
    /**
     * Send leave message
     */
    sendLeaveMessage() {
        if (!this.connected || !this.socket) {
            return;
        }
        
        const data = {
            action: 'leave',
            stream_id: this.streamId
        };
        
        try {
            this.socket.send(JSON.stringify(data));
        } catch (e) {
            // Ignore errors during disconnect
        }
    }
    
    /**
     * Start heartbeat
     */
    startHeartbeat() {
        this.clearHeartbeat();
        
        this.heartbeatInterval = setInterval(() => {
            if (this.connected && this.socket) {
                try {
                    this.socket.send(JSON.stringify({ action: 'ping' }));
                } catch (e) {
                    this.clearHeartbeat();
                }
            }
        }, 30000); // 30 seconds
    }
    
    /**
     * Clear heartbeat
     */
    clearHeartbeat() {
        if (this.heartbeatInterval) {
            clearInterval(this.heartbeatInterval);
            this.heartbeatInterval = null;
        }
    }
    
    /**
     * Disconnect from WebSocket
     */
    disconnect() {
        this.clearHeartbeat();
        
        if (this.socket) {
            if (this.connected) {
                this.sendLeaveMessage();
            }
            
            this.socket.close();
            this.socket = null;
            this.connected = false;
        }
    }
}

/**
 * Video Player Class
 */
export class VideoPlayer {
    constructor(options) {
        this.videoElement = options.videoElement;
        this.playbackUrl = options.playbackUrl;
        this.plyrOptions = options.plyrOptions || {
            controls: [
                'play-large', 'play', 'progress', 'current-time', 'mute', 
                'volume', 'captions', 'settings', 'pip', 'airplay', 'fullscreen'
            ],
            settings: ['captions', 'quality', 'speed'],
            quality: {
                default: 720,
                options: [1080, 720, 480, 360]
            }
        };
        
        // State
        this.player = null;
        this.hls = null;
        
        // Initialize
        if (this.videoElement) {
            this.initialize();
        }
    }
    
    /**
     * Initialize the player
     */
    async initialize() {
        try {
            // Check if HLS.js is supported
            if (typeof Hls !== 'undefined' && Hls.isSupported()) {
                this.initWithHls();
            } else if (this.videoElement.canPlayType('application/vnd.apple.mpegurl')) {
                // Native HLS support
                this.initWithNativeHls();
            } else {
                throw new Error('HLS is not supported in this browser');
            }
        } catch (error) {
            console.error('Error initializing video player:', error);
        }
    }
    
    /**
     * Initialize with HLS.js
     */
    initWithHls() {
        this.hls = new Hls();
        this.hls.loadSource(this.playbackUrl);
        this.hls.attachMedia(this.videoElement);
        this.hls.on(Hls.Events.MANIFEST_PARSED, () => {
            this.initPlyr();
        });
    }
    
    /**
     * Initialize with native HLS
     */
    initWithNativeHls() {
        this.videoElement.src = this.playbackUrl;
        this.initPlyr();
    }
    
    /**
     * Initialize Plyr
     */
    initPlyr() {
        this.player = new Plyr(this.videoElement, this.plyrOptions);
    }
    
    /**
     * Play the video
     */
    play() {
        if (this.player) {
            this.player.play();
        } else {
            this.videoElement.play();
        }
    }
    
    /**
     * Pause the video
     */
    pause() {
        if (this.player) {
            this.player.pause();
        } else {
            this.videoElement.pause();
        }
    }
    
    /**
     * Destroy the player
     */
    destroy() {
        if (this.player) {
            this.player.destroy();
        }
        
        if (this.hls) {
            this.hls.destroy();
        }
    }
}

// Make utilities available globally
window.NaraUtils = {
    formatCurrency: function(amount, currency = 'USD') {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency
        }).format(amount);
    },
    
    formatDate: function(dateString, format = 'medium') {
        const date = new Date(dateString);
        const options = {
            short: { month: 'short', day: 'numeric', year: 'numeric' },
            medium: { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' },
            long: { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' }
        };
        
        return date.toLocaleDateString('en-US', options[format] || options.medium);
    },
    
    truncateText: function(text, length = 100) {
        if (!text || text.length <= length) {
            return text;
        }
        
        return text.substring(0, length) + '...';
    }
};
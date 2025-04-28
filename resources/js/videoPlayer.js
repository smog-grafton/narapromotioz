/**
 * Nara Promotionz Advanced Video Player
 * 
 * This provides a wrapper around HLS.js and Plyr for advanced video playback
 */

class VideoPlayer {
    /**
     * Initialize the video player
     * 
     * @param {Object} options Configuration options
     * @param {string} options.videoElementId ID of the video element
     * @param {string} options.playbackUrl HLS stream URL
     * @param {Object} options.plyrOptions Plyr player options
     * @param {boolean} options.autoplay Whether to autoplay the video
     * @param {function} options.onReady Callback when player is ready
     * @param {function} options.onPlay Callback when playback starts
     * @param {function} options.onPause Callback when playback pauses
     * @param {function} options.onEnded Callback when playback ends
     * @param {function} options.onError Callback for errors
     * @param {function} options.onQualityChange Callback when quality changes
     */
    constructor(options) {
        this.videoElementId = options.videoElementId;
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
            },
            speed: {
                selected: 1,
                options: [0.5, 0.75, 1, 1.25, 1.5, 1.75, 2]
            }
        };
        this.autoplay = options.autoplay || false;
        
        this.callbacks = {
            onReady: options.onReady || (() => {}),
            onPlay: options.onPlay || (() => {}),
            onPause: options.onPause || (() => {}),
            onEnded: options.onEnded || (() => {}),
            onError: options.onError || (() => {}),
            onQualityChange: options.onQualityChange || (() => {})
        };
        
        this.video = document.getElementById(this.videoElementId);
        this.player = null;
        this.hls = null;
        
        this.availableQualities = [];
        this.currentQuality = null;
        
        // Make sure video element exists
        if (!this.video) {
            throw new Error(`Video element with ID "${this.videoElementId}" not found.`);
        }
        
        this.initialize();
    }
    
    /**
     * Initialize the player
     */
    async initialize() {
        try {
            // Check for HLS support
            if (Hls.isSupported()) {
                this.initWithHlsJs();
            } else if (this.video.canPlayType('application/vnd.apple.mpegurl')) {
                // Native HLS support (Safari, iOS)
                this.initWithNativeHls();
            } else {
                throw new Error('HLS is not supported in this browser.');
            }
        } catch (error) {
            this.callbacks.onError(error);
        }
    }
    
    /**
     * Initialize player with HLS.js
     */
    initWithHlsJs() {
        // Create HLS instance
        this.hls = new Hls({
            maxBufferLength: 30,
            maxMaxBufferLength: 60,
            capLevelToPlayerSize: true
        });
        
        // Bind HLS events
        this.hls.on(Hls.Events.MEDIA_ATTACHED, () => {
            console.log('HLS.js: Media attached');
            this.hls.loadSource(this.playbackUrl);
        });
        
        this.hls.on(Hls.Events.MANIFEST_PARSED, (event, data) => {
            console.log('HLS.js: Manifest parsed, levels: ', data.levels.length);
            
            // Store available qualities
            this.availableQualities = data.levels.map((level, index) => ({
                index: index,
                height: level.height,
                width: level.width,
                bitrate: level.bitrate
            }));
            
            // Initialize Plyr after manifest is loaded
            this.initPlyr();
            
            // Start playback if autoplay is enabled
            if (this.autoplay) {
                this.play();
            }
        });
        
        this.hls.on(Hls.Events.ERROR, (event, data) => {
            if (data.fatal) {
                switch(data.type) {
                    case Hls.ErrorTypes.NETWORK_ERROR:
                        console.error('HLS.js: Fatal network error', data);
                        // Try to recover
                        this.hls.startLoad();
                        break;
                    case Hls.ErrorTypes.MEDIA_ERROR:
                        console.error('HLS.js: Fatal media error', data);
                        // Try to recover
                        this.hls.recoverMediaError();
                        break;
                    default:
                        // Cannot recover
                        console.error('HLS.js: Fatal error, cannot recover', data);
                        this.destroyHls();
                        this.callbacks.onError(new Error('Video playback error: ' + data.details));
                        break;
                }
            } else {
                console.warn('HLS.js: Non-fatal error', data);
            }
        });
        
        this.hls.on(Hls.Events.LEVEL_SWITCHING, (event, data) => {
            const quality = this.availableQualities[data.level];
            if (quality) {
                this.currentQuality = quality;
                this.callbacks.onQualityChange(quality);
            }
        });
        
        // Attach HLS to video element
        this.hls.attachMedia(this.video);
    }
    
    /**
     * Initialize player with native HLS support
     */
    initWithNativeHls() {
        // For browsers with native HLS support (Safari)
        this.video.src = this.playbackUrl;
        
        // Initialize Plyr
        this.initPlyr();
        
        // Start playback if autoplay is enabled
        if (this.autoplay) {
            this.play();
        }
    }
    
    /**
     * Initialize Plyr player
     */
    initPlyr() {
        // Create Plyr instance
        this.player = new Plyr(this.video, this.plyrOptions);
        
        // Bind Plyr events
        this.player.on('ready', () => {
            console.log('Plyr: Ready');
            this.callbacks.onReady(this);
        });
        
        this.player.on('play', () => {
            console.log('Plyr: Play');
            this.callbacks.onPlay();
        });
        
        this.player.on('pause', () => {
            console.log('Plyr: Pause');
            this.callbacks.onPause();
        });
        
        this.player.on('ended', () => {
            console.log('Plyr: Ended');
            this.callbacks.onEnded();
        });
        
        this.player.on('error', (event) => {
            console.error('Plyr: Error', event);
            this.callbacks.onError(event);
        });
        
        // Connect Plyr quality selection with HLS if available
        if (this.hls) {
            this.player.on('qualitychange', (event) => {
                const newQuality = parseInt(event.detail.quality);
                
                // Find matching HLS level
                const levelIndex = this.availableQualities.findIndex(q => q.height === newQuality);
                
                if (levelIndex !== -1) {
                    console.log(`Plyr: Quality changed to ${newQuality}p, setting HLS level to ${levelIndex}`);
                    this.hls.currentLevel = levelIndex;
                }
            });
        }
    }
    
    /**
     * Start playback
     */
    play() {
        if (this.player) {
            this.player.play();
        } else {
            this.video.play();
        }
    }
    
    /**
     * Pause playback
     */
    pause() {
        if (this.player) {
            this.player.pause();
        } else {
            this.video.pause();
        }
    }
    
    /**
     * Toggle playback state
     */
    togglePlay() {
        if (this.player) {
            this.player.togglePlay();
        } else {
            if (this.video.paused) {
                this.video.play();
            } else {
                this.video.pause();
            }
        }
    }
    
    /**
     * Set volume (0-1)
     * 
     * @param {number} volume Volume level (0-1)
     */
    setVolume(volume) {
        if (this.player) {
            this.player.volume = volume;
        } else {
            this.video.volume = volume;
        }
    }
    
    /**
     * Toggle mute state
     */
    toggleMute() {
        if (this.player) {
            this.player.toggleMute();
        } else {
            this.video.muted = !this.video.muted;
        }
    }
    
    /**
     * Set video quality by height (e.g., 720, 1080)
     * 
     * @param {number} height Desired quality height
     */
    setQuality(height) {
        if (!this.hls) {
            return;
        }
        
        // Find the level index for the requested height
        const levelIndex = this.availableQualities.findIndex(q => q.height === height);
        
        if (levelIndex !== -1) {
            this.hls.currentLevel = levelIndex;
        }
    }
    
    /**
     * Enable automatic quality selection
     */
    setAutoQuality() {
        if (this.hls) {
            this.hls.currentLevel = -1; // Auto
        }
    }
    
    /**
     * Get current playback time in seconds
     * 
     * @returns {number} Current time in seconds
     */
    getCurrentTime() {
        return this.player ? this.player.currentTime : this.video.currentTime;
    }
    
    /**
     * Set current playback time in seconds
     * 
     * @param {number} time Time in seconds
     */
    setCurrentTime(time) {
        if (this.player) {
            this.player.currentTime = time;
        } else {
            this.video.currentTime = time;
        }
    }
    
    /**
     * Get total duration in seconds
     * 
     * @returns {number} Duration in seconds
     */
    getDuration() {
        return this.player ? this.player.duration : this.video.duration;
    }
    
    /**
     * Enter fullscreen mode
     */
    enterFullscreen() {
        if (this.player) {
            this.player.fullscreen.enter();
        }
    }
    
    /**
     * Exit fullscreen mode
     */
    exitFullscreen() {
        if (this.player) {
            this.player.fullscreen.exit();
        }
    }
    
    /**
     * Toggle fullscreen mode
     */
    toggleFullscreen() {
        if (this.player) {
            this.player.fullscreen.toggle();
        }
    }
    
    /**
     * Destroy the player instance
     */
    destroy() {
        this.destroyPlyr();
        this.destroyHls();
    }
    
    /**
     * Destroy Plyr instance
     */
    destroyPlyr() {
        if (this.player) {
            this.player.destroy();
            this.player = null;
        }
    }
    
    /**
     * Destroy HLS instance
     */
    destroyHls() {
        if (this.hls) {
            this.hls.destroy();
            this.hls = null;
        }
    }
    
    /**
     * Get available quality levels
     * 
     * @returns {Array} List of available quality options
     */
    getAvailableQualities() {
        return this.availableQualities;
    }
    
    /**
     * Get current quality
     * 
     * @returns {Object|null} Current quality details
     */
    getCurrentQuality() {
        return this.currentQuality;
    }
}

// Make available globally
window.VideoPlayer = VideoPlayer;
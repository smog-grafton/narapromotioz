/**
 * Nara Promotionz Live Chat Client
 * 
 * This is a WebSocket client for real-time chat communication during live streams.
 */

class LiveStreamChat {
    /**
     * Initialize the chat client
     * 
     * @param {Object} options Configuration options
     * @param {string} options.streamId The ID of the stream
     * @param {Object} options.user Current user information
     * @param {string} options.socketUrl WebSocket server URL
     * @param {function} options.onMessage Callback for received messages
     * @param {function} options.onUserJoin Callback for user join events
     * @param {function} options.onUserLeave Callback for user leave events
     * @param {function} options.onUserCount Callback for user count updates
     * @param {function} options.onConnect Callback when connection is established
     * @param {function} options.onDisconnect Callback when connection is lost
     * @param {function} options.onError Callback for errors
     */
    constructor(options) {
        this.streamId = options.streamId;
        this.user = options.user;
        this.socketUrl = options.socketUrl || this.getDefaultSocketUrl();
        this.callbacks = {
            onMessage: options.onMessage || (() => {}),
            onUserJoin: options.onUserJoin || (() => {}),
            onUserLeave: options.onUserLeave || (() => {}),
            onUserCount: options.onUserCount || (() => {}),
            onConnect: options.onConnect || (() => {}),
            onDisconnect: options.onDisconnect || (() => {}),
            onError: options.onError || (() => {}),
        };
        
        this.socket = null;
        this.connected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectDelay = 3000; // 3 seconds
        this.heartbeatInterval = null;
        
        // Store messages received when processing recent messages
        this.messageCache = new Map();
    }
    
    /**
     * Get default WebSocket URL based on current page protocol
     * 
     * @returns {string} The WebSocket URL
     */
    getDefaultSocketUrl() {
        const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
        const host = window.location.host;
        return `${protocol}//${host}/ws`;
    }
    
    /**
     * Connect to the WebSocket server
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
     * Disconnect from the WebSocket server
     */
    disconnect() {
        this.clearHeartbeat();
        
        if (this.socket) {
            // Send leave message if connected
            if (this.connected) {
                this.sendLeaveMessage();
            }
            
            this.socket.close();
            this.socket = null;
            this.connected = false;
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
     * 
     * @param {MessageEvent} event The WebSocket message event
     */
    handleMessage(event) {
        try {
            const data = JSON.parse(event.data);
            
            switch (data.type) {
                case 'join_confirmed':
                    // Successfully joined the stream
                    this.callbacks.onUserCount(data.user_count);
                    break;
                    
                case 'user_joined':
                    // Another user joined
                    this.callbacks.onUserJoin({
                        userId: data.user_id,
                        username: data.username,
                    });
                    this.callbacks.onUserCount(data.user_count);
                    break;
                    
                case 'user_left':
                    // A user left
                    this.callbacks.onUserLeave({
                        userId: data.user_id,
                        username: data.username,
                    });
                    this.callbacks.onUserCount(data.user_count);
                    break;
                    
                case 'chat_message':
                    // Received a chat message
                    const message = {
                        id: data.id,
                        userId: data.user_id,
                        username: data.username,
                        message: data.message,
                        timestamp: new Date(data.timestamp),
                        isPinned: data.is_pinned,
                    };
                    
                    // Check if we've already shown this message (for recent_messages deduplication)
                    if (!this.messageCache.has(data.id)) {
                        this.messageCache.set(data.id, true);
                        this.callbacks.onMessage(message);
                    }
                    break;
                    
                case 'recent_messages':
                    // Process recent messages
                    if (data.messages && Array.isArray(data.messages)) {
                        data.messages.forEach(msg => {
                            const message = {
                                id: msg.id,
                                userId: msg.user_id,
                                username: msg.username,
                                message: msg.message,
                                timestamp: new Date(msg.timestamp),
                                isPinned: msg.is_pinned,
                            };
                            
                            // Check if we've already shown this message
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
                    // Server reported an error
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
        
        // Only notify if we were previously connected
        if (wasConnected) {
            this.callbacks.onDisconnect();
        }
        
        this.scheduleReconnect();
    }
    
    /**
     * Handle WebSocket error event
     * 
     * @param {Event} error The WebSocket error event
     */
    handleError(error) {
        this.callbacks.onError(error);
        
        // If not connected, try to reconnect
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
            
            // Exponential backoff
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
     * 
     * @param {string} message The message text to send
     * @returns {boolean} True if sent, false otherwise
     */
    sendMessage(message) {
        if (!this.connected || !this.socket) {
            return false;
        }
        
        const data = {
            action: 'message',
            stream_id: this.streamId,
            message: message.trim(),
        };
        
        this.socket.send(JSON.stringify(data));
        return true;
    }
    
    /**
     * Send the join message to enter a stream chat
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
            token: this.user.token || 'demo-token', // In production, use a real auth token
        };
        
        this.socket.send(JSON.stringify(data));
    }
    
    /**
     * Send the leave message when exiting a stream chat
     */
    sendLeaveMessage() {
        if (!this.connected || !this.socket) {
            return;
        }
        
        const data = {
            action: 'leave',
            stream_id: this.streamId,
        };
        
        try {
            this.socket.send(JSON.stringify(data));
        } catch (e) {
            // Ignore errors during disconnect
        }
    }
    
    /**
     * Start the heartbeat to keep the connection alive
     */
    startHeartbeat() {
        this.clearHeartbeat();
        
        this.heartbeatInterval = setInterval(() => {
            if (this.connected && this.socket) {
                try {
                    this.socket.send(JSON.stringify({ action: 'ping' }));
                } catch (e) {
                    // Socket might be closed, clear interval
                    this.clearHeartbeat();
                }
            }
        }, 30000); // 30 seconds
    }
    
    /**
     * Clear the heartbeat interval
     */
    clearHeartbeat() {
        if (this.heartbeatInterval) {
            clearInterval(this.heartbeatInterval);
            this.heartbeatInterval = null;
        }
    }
}

// Make available globally
window.LiveStreamChat = LiveStreamChat;
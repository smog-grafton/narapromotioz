<?php

namespace App\Services;

use App\Models\Stream;
use App\Models\User;
use App\Models\StreamChatMessage;
use Illuminate\Support\Facades\Log;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\WebSocket\WsConnection;
use SplObjectStorage;

class WebSocketService implements MessageComponentInterface
{
    protected $clients;
    protected $streamClients;
    protected $userConnections;

    public function __construct()
    {
        $this->clients = new SplObjectStorage;
        $this->streamClients = [];
        $this->userConnections = [];
    }

    /**
     * When a new WebSocket connection is opened
     *
     * @param ConnectionInterface $conn
     * @return void
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        
        Log::debug("New connection! ({$conn->resourceId})");
    }

    /**
     * When a WebSocket message is received
     *
     * @param ConnectionInterface $from
     * @param string $msg
     * @return void
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        try {
            // Decode the JSON message
            $data = json_decode($msg, true);
            
            if (!$data || !isset($data['action'])) {
                return;
            }
            
            switch ($data['action']) {
                case 'join':
                    $this->handleJoin($from, $data);
                    break;
                
                case 'leave':
                    $this->handleLeave($from, $data);
                    break;
                
                case 'message':
                    $this->handleMessage($from, $data);
                    break;
                
                case 'ping':
                    $this->handlePing($from);
                    break;
            }
        } catch (\Exception $e) {
            Log::error('WebSocket message error', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle client join stream request
     *
     * @param ConnectionInterface $conn
     * @param array $data
     * @return void
     */
    protected function handleJoin(ConnectionInterface $conn, $data)
    {
        if (!isset($data['stream_id']) || !isset($data['user_id']) || !isset($data['token'])) {
            return;
        }
        
        $streamId = $data['stream_id'];
        $userId = $data['user_id'];
        $token = $data['token'];
        
        // Validate token (in production, implement proper JWT validation)
        if (!$this->validateToken($userId, $token)) {
            $conn->send(json_encode([
                'type' => 'error',
                'message' => 'Invalid authentication'
            ]));
            return;
        }
        
        // Add to stream clients
        if (!isset($this->streamClients[$streamId])) {
            $this->streamClients[$streamId] = new SplObjectStorage;
        }
        
        $this->streamClients[$streamId]->attach($conn);
        
        // Map user to connection
        $conn->userId = $userId;
        $conn->streamId = $streamId;
        $conn->username = $data['username'] ?? 'Anonymous';
        
        // Track this user's connections
        if (!isset($this->userConnections[$userId])) {
            $this->userConnections[$userId] = [];
        }
        $this->userConnections[$userId][] = $conn;
        
        // Send join confirmation
        $conn->send(json_encode([
            'type' => 'join_confirmed',
            'stream_id' => $streamId,
            'user_count' => $this->streamClients[$streamId]->count()
        ]));
        
        // Notify other clients
        $this->broadcastToStream($streamId, [
            'type' => 'user_joined',
            'user_id' => $userId,
            'username' => $conn->username,
            'user_count' => $this->streamClients[$streamId]->count()
        ], [$conn]);
        
        // Send recent messages
        $this->sendRecentMessages($conn, $streamId);
        
        Log::debug("Client {$conn->resourceId} joined stream {$streamId} as user {$userId}");
    }

    /**
     * Handle client leave stream request
     *
     * @param ConnectionInterface $conn
     * @param array $data
     * @return void
     */
    protected function handleLeave(ConnectionInterface $conn, $data)
    {
        if (!isset($conn->streamId)) {
            return;
        }
        
        $streamId = $conn->streamId;
        
        if (isset($this->streamClients[$streamId])) {
            $this->streamClients[$streamId]->detach($conn);
            
            // Notify other clients if we know who this user is
            if (isset($conn->userId)) {
                $this->broadcastToStream($streamId, [
                    'type' => 'user_left',
                    'user_id' => $conn->userId,
                    'username' => $conn->username ?? 'Anonymous',
                    'user_count' => $this->streamClients[$streamId]->count()
                ]);
            }
        }
        
        unset($conn->streamId);
        unset($conn->userId);
        unset($conn->username);
        
        Log::debug("Client {$conn->resourceId} left stream {$streamId}");
    }

    /**
     * Handle chat message
     *
     * @param ConnectionInterface $from
     * @param array $data
     * @return void
     */
    protected function handleMessage(ConnectionInterface $from, $data)
    {
        if (!isset($from->streamId) || !isset($from->userId) || !isset($data['message'])) {
            return;
        }
        
        $streamId = $from->streamId;
        $userId = $from->userId;
        $message = trim($data['message']);
        
        if (empty($message)) {
            return;
        }
        
        // Save message to database (async in production environment)
        try {
            $chatMessage = StreamChatMessage::create([
                'stream_id' => $streamId,
                'user_id' => $userId,
                'message' => $message,
                'is_pinned' => false,
                'is_hidden' => false,
            ]);
            
            // Broadcast message to everyone in the stream
            $this->broadcastToStream($streamId, [
                'type' => 'chat_message',
                'id' => $chatMessage->id,
                'user_id' => $userId,
                'username' => $from->username ?? 'Anonymous',
                'message' => $message,
                'is_pinned' => false,
                'timestamp' => $chatMessage->created_at->toIso8601String(),
            ]);
            
            Log::debug("User {$userId} sent message to stream {$streamId}");
        } catch (\Exception $e) {
            Log::error('Error saving chat message', ['error' => $e->getMessage()]);
            
            // Send error back to sender
            $from->send(json_encode([
                'type' => 'error',
                'message' => 'Failed to send message'
            ]));
        }
    }

    /**
     * Handle ping request to keep connection alive
     *
     * @param ConnectionInterface $from
     * @return void
     */
    protected function handlePing(ConnectionInterface $from)
    {
        $from->send(json_encode([
            'type' => 'pong',
            'timestamp' => now()->timestamp
        ]));
    }

    /**
     * Send recent chat messages to a user who just joined
     *
     * @param ConnectionInterface $conn
     * @param int $streamId
     * @return void
     */
    protected function sendRecentMessages(ConnectionInterface $conn, $streamId)
    {
        try {
            // Get last 50 visible messages
            $messages = StreamChatMessage::where('stream_id', $streamId)
                ->where('is_hidden', false)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get()
                ->reverse();
            
            if ($messages->isEmpty()) {
                return;
            }
            
            $formattedMessages = $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
                    'username' => $message->user->name ?? 'Anonymous',
                    'message' => $message->message,
                    'is_pinned' => $message->is_pinned,
                    'timestamp' => $message->created_at->toIso8601String(),
                ];
            });
            
            $conn->send(json_encode([
                'type' => 'recent_messages',
                'messages' => $formattedMessages
            ]));
        } catch (\Exception $e) {
            Log::error('Error sending recent messages', ['error' => $e->getMessage()]);
        }
    }

    /**
     * When a WebSocket connection is closed
     *
     * @param ConnectionInterface $conn
     * @return void
     */
    public function onClose(ConnectionInterface $conn)
    {
        // Remove from stream if connected to one
        if (isset($conn->streamId)) {
            $streamId = $conn->streamId;
            
            if (isset($this->streamClients[$streamId])) {
                $this->streamClients[$streamId]->detach($conn);
                
                // Notify other clients if we know who this user is
                if (isset($conn->userId)) {
                    $this->broadcastToStream($streamId, [
                        'type' => 'user_left',
                        'user_id' => $conn->userId,
                        'username' => $conn->username ?? 'Anonymous',
                        'user_count' => $this->streamClients[$streamId]->count()
                    ]);
                    
                    // Remove from user connections tracking
                    $this->removeUserConnection($conn->userId, $conn);
                }
            }
        }
        
        // Remove from clients list
        $this->clients->detach($conn);
        
        Log::debug("Connection {$conn->resourceId} has disconnected");
    }

    /**
     * Error handling for WebSocket connections
     *
     * @param ConnectionInterface $conn
     * @param \Exception $e
     * @return void
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        Log::error("An error has occurred: {$e->getMessage()}");
        
        $conn->close();
    }

    /**
     * Broadcast a message to all clients in a stream
     *
     * @param int $streamId
     * @param array $data
     * @param array $excludedConnections
     * @return void
     */
    protected function broadcastToStream($streamId, $data, $excludedConnections = [])
    {
        if (!isset($this->streamClients[$streamId])) {
            return;
        }
        
        $message = json_encode($data);
        
        foreach ($this->streamClients[$streamId] as $client) {
            if (in_array($client, $excludedConnections)) {
                continue;
            }
            
            $client->send($message);
        }
    }

    /**
     * Send a message directly to a specific user across all their connections
     *
     * @param int $userId
     * @param array $data
     * @return void
     */
    protected function sendToUser($userId, $data)
    {
        if (!isset($this->userConnections[$userId])) {
            return;
        }
        
        $message = json_encode($data);
        
        foreach ($this->userConnections[$userId] as $conn) {
            $conn->send($message);
        }
    }

    /**
     * Remove a specific connection from user's connections list
     *
     * @param int $userId
     * @param ConnectionInterface $conn
     * @return void
     */
    protected function removeUserConnection($userId, $conn)
    {
        if (!isset($this->userConnections[$userId])) {
            return;
        }
        
        foreach ($this->userConnections[$userId] as $key => $userConn) {
            if ($userConn === $conn) {
                unset($this->userConnections[$userId][$key]);
                break;
            }
        }
        
        // Clean up empty user entries
        if (empty($this->userConnections[$userId])) {
            unset($this->userConnections[$userId]);
        }
    }

    /**
     * Validate user authentication token
     * This is a placeholder - implement proper JWT validation in production
     *
     * @param int $userId
     * @param string $token
     * @return bool
     */
    protected function validateToken($userId, $token)
    {
        // In production, implement proper token validation
        // This is just a basic implementation for demonstration
        try {
            // Check if the user exists
            $user = User::find($userId);
            
            if (!$user) {
                return false;
            }
            
            // In a real implementation, validate JWT or other token
            // For now, we'll accept any token since this is just a demo
            return true;
        } catch (\Exception $e) {
            Log::error('Token validation error', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
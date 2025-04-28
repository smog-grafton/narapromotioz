<?php

namespace App\Services;

use App\Models\Stream;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AgoraService
{
    /**
     * Generate an Agora token for real-time communication
     *
     * @param  string  $channelName
     * @param  string  $uid
     * @param  int  $expireTimeInSeconds
     * @param  int  $role
     * @return string|null
     */
    public function generateToken($channelName, $uid, $expireTimeInSeconds = 3600, $role = 1)
    {
        $appId = config('services.agora.app_id');
        $appCertificate = config('services.agora.app_certificate');
        
        if (!$appId || !$appCertificate) {
            Log::error('Agora credentials are not configured');
            return null;
        }
        
        // Current timestamp in seconds
        $currentTimestamp = time();
        $expireTimestamp = $currentTimestamp + $expireTimeInSeconds;
        
        // Role can be either PUBLISHER (1) or SUBSCRIBER (2)
        $role = min(max(intval($role), 1), 2);
        
        // Build token structure
        $message = [
            'appID' => $appId,
            'channelName' => $channelName,
            'uid' => $uid,
            'expireTimestamp' => $expireTimestamp,
            'salt' => mt_rand(1, 99999999), // Random salt
            'role' => $role,
        ];
        
        // Serialize message
        $messageString = json_encode($message);
        
        // Sign message with HMAC
        $signature = hash_hmac('sha256', $messageString, $appCertificate, true);
        $base64Signature = base64_encode($signature);
        
        // Combine into token
        $tokenParts = [
            'AGORA',
            $appId,
            base64_encode($messageString),
            $base64Signature,
        ];
        
        return implode(':', $tokenParts);
    }
    
    /**
     * Generate a unique channel name for a stream
     *
     * @param  \App\Models\Stream  $stream
     * @return string
     */
    public function generateChannelName(Stream $stream)
    {
        return 'nara_' . strtolower(Str::slug($stream->title)) . '_' . $stream->id;
    }
    
    /**
     * Prepare stream configuration for a user
     *
     * @param  \App\Models\Stream  $stream
     * @param  \App\Models\User  $user
     * @param  bool  $isBroadcaster
     * @return array
     */
    public function prepareStreamConfig(Stream $stream, User $user, $isBroadcaster = false)
    {
        $channelName = $this->generateChannelName($stream);
        $uid = 'user_' . $user->id . '_' . Str::random(6);
        
        // Role: 1 = Publisher, 2 = Subscriber
        $role = $isBroadcaster ? 1 : 2;
        
        $token = $this->generateToken($channelName, $uid, 3600, $role);
        
        $config = [
            'appId' => config('services.agora.app_id'),
            'channelName' => $channelName,
            'uid' => $uid,
            'token' => $token,
            'role' => $role,
            'streamInfo' => [
                'id' => $stream->id,
                'title' => $stream->title,
            ],
            'userInfo' => [
                'id' => $user->id,
                'name' => $user->name,
                'isBroadcaster' => $isBroadcaster,
            ],
            'settings' => [
                'enableDualStream' => true,
                'enableBeautyEffect' => true,
                'videoEncoderConfig' => [
                    'low' => [
                        'width' => 640,
                        'height' => 360,
                        'frameRate' => 15,
                        'bitrate' => 400,
                    ],
                    'medium' => [
                        'width' => 960,
                        'height' => 540,
                        'frameRate' => 30,
                        'bitrate' => 1000,
                    ],
                    'high' => [
                        'width' => 1280,
                        'height' => 720,
                        'frameRate' => 30,
                        'bitrate' => 2000,
                    ],
                ],
            ],
        ];
        
        // Additional settings for broadcasters
        if ($isBroadcaster) {
            $config['settings']['screenShare'] = [
                'enabled' => true,
                'width' => 1920,
                'height' => 1080,
                'frameRate' => 15,
                'bitrate' => 2000,
            ];
            
            $config['settings']['rtmpConfig'] = [
                'enabled' => true,
                'url' => $stream->ingest_server,
                'streamKey' => $stream->stream_key,
            ];
        }
        
        return $config;
    }
    
    /**
     * Acquire status of a channel
     *
     * @param  string  $channelName
     * @return array|null
     */
    public function getChannelStatus($channelName)
    {
        $appId = config('services.agora.app_id');
        $appCertificate = config('services.agora.app_certificate');
        $customerId = config('services.agora.customer_id');
        $customerSecret = config('services.agora.customer_secret');
        
        if (!$appId || !$customerId || !$customerSecret) {
            Log::error('Agora credentials are not configured');
            return null;
        }
        
        try {
            $client = new \GuzzleHttp\Client();
            
            // Build authentication headers
            $now = time();
            $nonce = mt_rand(1, 99999999);
            
            $stringToSign = $customerId . $now . $nonce;
            $signature = hash_hmac('sha256', $stringToSign, $customerSecret);
            
            $response = $client->get("https://api.agora.io/dev/v1/channel/user/{$appId}/{$channelName}", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Customer-ID' => $customerId,
                    'X-Timestamp' => $now,
                    'X-Nonce' => $nonce,
                    'X-Signature' => $signature,
                ],
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Agora API error', [
                'message' => $e->getMessage(),
                'channel' => $channelName,
            ]);
        }
        
        return null;
    }
    
    /**
     * Record a channel (start cloud recording)
     *
     * @param  \App\Models\Stream  $stream
     * @return array|null
     */
    public function startRecording(Stream $stream)
    {
        $channelName = $this->generateChannelName($stream);
        $uid = 'recording_' . Str::random(10);
        
        $appId = config('services.agora.app_id');
        $appCertificate = config('services.agora.app_certificate');
        $customerId = config('services.agora.customer_id');
        $customerSecret = config('services.agora.customer_secret');
        
        if (!$appId || !$customerId || !$customerSecret) {
            Log::error('Agora credentials are not configured');
            return null;
        }
        
        try {
            $client = new \GuzzleHttp\Client();
            
            // Build authentication headers
            $now = time();
            $nonce = mt_rand(1, 99999999);
            
            $stringToSign = $customerId . $now . $nonce;
            $signature = hash_hmac('sha256', $stringToSign, $customerSecret);
            
            // Generate token for recording
            $token = $this->generateToken($channelName, $uid, 86400, 1); // 24 hours, publisher role
            
            $response = $client->post("https://api.agora.io/v1/apps/{$appId}/cloud_recording/acquire", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Customer-ID' => $customerId,
                    'X-Timestamp' => $now,
                    'X-Nonce' => $nonce,
                    'X-Signature' => $signature,
                ],
                'json' => [
                    'cname' => $channelName,
                    'uid' => $uid,
                    'clientRequest' => [
                        'resourceExpiredHour' => 24,
                    ],
                ],
            ]);
            
            $acquireData = json_decode($response->getBody(), true);
            
            if (!isset($acquireData['resourceId'])) {
                throw new \Exception('Failed to acquire recording resource');
            }
            
            // Start recording
            $resourceId = $acquireData['resourceId'];
            
            $response = $client->post("https://api.agora.io/v1/apps/{$appId}/cloud_recording/resourceid/{$resourceId}/mode/mix/start", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Customer-ID' => $customerId,
                    'X-Timestamp' => $now,
                    'X-Nonce' => $nonce,
                    'X-Signature' => $signature,
                ],
                'json' => [
                    'cname' => $channelName,
                    'uid' => $uid,
                    'clientRequest' => [
                        'token' => $token,
                        'recordingConfig' => [
                            'channelType' => 1, // 0: Communication, 1: Live broadcast
                            'streamTypes' => 2, // 0: Audio only, 1: Video only, 2: Audio and video
                            'audioProfile' => 1, // 0: Default, 1: Standard stereo
                            'videoStreamType' => 0, // 0: High stream, 1: Low stream
                            'maxIdleTime' => 30, // Maximum idle time in seconds
                            'transcodingConfig' => [
                                'width' => 1280,
                                'height' => 720,
                                'fps' => 30,
                                'bitrate' => 2000,
                                'mixedVideoLayout' => 1, // 0: Default, 1: Best Fit, 2: Vertical
                            ],
                        ],
                        'storageConfig' => [
                            'vendor' => 1, // 0: Qiniu Cloud, 1: AWS S3, 2: Alibaba Cloud
                            'region' => 1, // AWS: 1 (US East 1), 2 (US East 2), etc.
                            'bucket' => config('services.agora.s3_bucket'),
                            'accessKey' => config('services.agora.s3_access_key'),
                            'secretKey' => config('services.agora.s3_secret_key'),
                            'fileNamePrefix' => ["recordings", $stream->id],
                        ],
                    ],
                ],
            ]);
            
            $startData = json_decode($response->getBody(), true);
            
            if (isset($startData['sid'])) {
                return [
                    'resourceId' => $resourceId,
                    'sid' => $startData['sid'],
                    'channelName' => $channelName,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Agora recording error', [
                'message' => $e->getMessage(),
                'stream_id' => $stream->id,
                'channel' => $channelName,
            ]);
        }
        
        return null;
    }
    
    /**
     * Stop recording
     *
     * @param  string  $resourceId
     * @param  string  $sid
     * @param  string  $channelName
     * @param  string  $uid
     * @return array|null
     */
    public function stopRecording($resourceId, $sid, $channelName, $uid)
    {
        $appId = config('services.agora.app_id');
        $customerId = config('services.agora.customer_id');
        $customerSecret = config('services.agora.customer_secret');
        
        if (!$appId || !$customerId || !$customerSecret) {
            Log::error('Agora credentials are not configured');
            return null;
        }
        
        try {
            $client = new \GuzzleHttp\Client();
            
            // Build authentication headers
            $now = time();
            $nonce = mt_rand(1, 99999999);
            
            $stringToSign = $customerId . $now . $nonce;
            $signature = hash_hmac('sha256', $stringToSign, $customerSecret);
            
            $response = $client->post("https://api.agora.io/v1/apps/{$appId}/cloud_recording/resourceid/{$resourceId}/sid/{$sid}/mode/mix/stop", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Customer-ID' => $customerId,
                    'X-Timestamp' => $now,
                    'X-Nonce' => $nonce,
                    'X-Signature' => $signature,
                ],
                'json' => [
                    'cname' => $channelName,
                    'uid' => $uid,
                    'clientRequest' => []
                ]
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Agora stop recording error', [
                'message' => $e->getMessage(),
                'resourceId' => $resourceId,
                'sid' => $sid,
                'channel' => $channelName,
            ]);
        }
        
        return null;
    }
}
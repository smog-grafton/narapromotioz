<?php

namespace App\Services;

use App\Models\Stream;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MuxService
{
    /**
     * Guzzle HTTP client
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;
    
    /**
     * Mux API base URL
     *
     * @var string
     */
    protected $baseUrl = 'https://api.mux.com/';
    
    /**
     * Mux API credentials
     *
     * @var array
     */
    protected $auth;
    
    /**
     * Create a new MuxService instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->auth = [
            config('services.mux.token_id'),
            config('services.mux.token_secret')
        ];
        
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'auth' => $this->auth,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }
    
    /**
     * Create a new live stream in Mux
     *
     * @param  \App\Models\Stream  $stream
     * @return array|null
     */
    public function createLiveStream(Stream $stream)
    {
        try {
            $response = $this->client->post('video/v1/live-streams', [
                'json' => [
                    'playback_policy' => ['public'],
                    'new_asset_settings' => [
                        'playback_policy' => ['public'],
                        'mp4_support' => 'standard',
                    ],
                    'test' => config('app.env') !== 'production',
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            if (isset($data['data'])) {
                $liveStream = $data['data'];
                
                return [
                    'stream_id' => $liveStream['id'],
                    'stream_key' => $liveStream['stream_key'],
                    'playback_id' => $liveStream['playback_ids'][0]['id'],
                    'playback_url' => "https://stream.mux.com/{$liveStream['playback_ids'][0]['id']}.m3u8",
                    'ingest_server' => $liveStream['stream_key'] ? "rtmps://global-live.mux.com:443/app" : null,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Mux create live stream error', [
                'message' => $e->getMessage(),
                'stream_id' => $stream->id,
            ]);
        }
        
        return null;
    }
    
    /**
     * Get a live stream from Mux
     *
     * @param  string  $liveStreamId
     * @return array|null
     */
    public function getLiveStream($liveStreamId)
    {
        try {
            $response = $this->client->get("video/v1/live-streams/{$liveStreamId}");
            
            $data = json_decode($response->getBody(), true);
            
            if (isset($data['data'])) {
                return $data['data'];
            }
        } catch (\Exception $e) {
            Log::error('Mux get live stream error', [
                'message' => $e->getMessage(),
                'live_stream_id' => $liveStreamId,
            ]);
        }
        
        return null;
    }
    
    /**
     * End a live stream in Mux
     *
     * @param  string  $liveStreamId
     * @return bool
     */
    public function endLiveStream($liveStreamId)
    {
        try {
            $response = $this->client->post("video/v1/live-streams/{$liveStreamId}/complete");
            
            $data = json_decode($response->getBody(), true);
            
            return isset($data['data']);
        } catch (\Exception $e) {
            Log::error('Mux end live stream error', [
                'message' => $e->getMessage(),
                'live_stream_id' => $liveStreamId,
            ]);
        }
        
        return false;
    }
    
    /**
     * Delete a live stream in Mux
     *
     * @param  string  $liveStreamId
     * @return bool
     */
    public function deleteLiveStream($liveStreamId)
    {
        try {
            $response = $this->client->delete("video/v1/live-streams/{$liveStreamId}");
            
            return $response->getStatusCode() === 204;
        } catch (\Exception $e) {
            Log::error('Mux delete live stream error', [
                'message' => $e->getMessage(),
                'live_stream_id' => $liveStreamId,
            ]);
        }
        
        return false;
    }
    
    /**
     * Get metrics for a live stream
     *
     * @param  string  $liveStreamId
     * @return array|null
     */
    public function getLiveStreamMetrics($liveStreamId)
    {
        try {
            $response = $this->client->get("video/v1/live-streams/{$liveStreamId}/metrics");
            
            $data = json_decode($response->getBody(), true);
            
            if (isset($data['data'])) {
                return $data['data'];
            }
        } catch (\Exception $e) {
            Log::error('Mux get live stream metrics error', [
                'message' => $e->getMessage(),
                'live_stream_id' => $liveStreamId,
            ]);
        }
        
        return null;
    }
    
    /**
     * Create a signed playback URL
     *
     * @param  string  $playbackId
     * @param  int  $expiresIn  Seconds until URL expires
     * @return string|null
     */
    public function createSignedPlaybackUrl($playbackId, $expiresIn = 3600)
    {
        $signingKey = config('services.mux.signing_key');
        $signingKeyId = config('services.mux.signing_key_id');
        
        if (!$signingKey || !$signingKeyId) {
            return null;
        }
        
        try {
            $expiresAt = time() + $expiresIn;
            $audience = "v";
            
            $payload = [
                "sub" => $playbackId,
                "aud" => $audience,
                "exp" => $expiresAt,
                "kid" => $signingKeyId
            ];
            
            $token = $this->generateJWT($payload, $signingKey);
            
            return "https://stream.mux.com/{$playbackId}.m3u8?token={$token}";
        } catch (\Exception $e) {
            Log::error('Error creating signed playback URL', [
                'message' => $e->getMessage(),
                'playback_id' => $playbackId,
            ]);
        }
        
        return null;
    }
    
    /**
     * Generate a JWT token
     *
     * @param  array  $payload
     * @param  string  $key
     * @return string
     */
    protected function generateJWT($payload, $key)
    {
        $header = [
            "alg" => "HS256",
            "typ" => "JWT",
            "kid" => $payload['kid']
        ];
        
        $encodedHeader = $this->base64UrlEncode(json_encode($header));
        $encodedPayload = $this->base64UrlEncode(json_encode($payload));
        
        $signature = hash_hmac('sha256', "{$encodedHeader}.{$encodedPayload}", $key, true);
        $encodedSignature = $this->base64UrlEncode($signature);
        
        return "{$encodedHeader}.{$encodedPayload}.{$encodedSignature}";
    }
    
    /**
     * Base64 URL encode
     *
     * @param  string  $data
     * @return string
     */
    protected function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
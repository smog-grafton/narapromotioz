<?php

namespace App\Http\Middleware;

use App\Models\Stream;
use App\Models\StreamPurchase;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StreamAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the stream ID from the route
        $streamId = $request->route('id') ?? $request->route('stream');
        
        if (!$streamId) {
            Log::warning('Stream access middleware called without a stream ID');
            return redirect()->route('streams.index')
                ->with('error', 'Stream not found.');
        }
        
        // Find the stream
        $stream = Stream::find($streamId);
        
        if (!$stream) {
            return redirect()->route('streams.index')
                ->with('error', 'Stream not found.');
        }
        
        // Check if user is authenticated
        if (!Auth::check()) {
            // Store the intended URL for redirection after login
            session(['url.intended' => url()->current()]);
            
            return redirect()->route('login')
                ->with('error', 'You must be logged in to access this stream.');
        }
        
        /** @var User $user */
        $user = Auth::user();
        
        // Check if user is an admin or staff (always allow access)
        if ($user->hasRole(['admin', 'staff'])) {
            return $next($request);
        }
        
        // Check stream status - only allow live or scheduled streams
        if (!in_array($stream->status, [Stream::STATUS_SCHEDULED, Stream::STATUS_LIVE])) {
            return redirect()->route('streams.index')
                ->with('error', 'This stream is not currently available.');
        }
        
        // For free streams, allow access
        if ($stream->access_level === Stream::ACCESS_LEVEL_FREE) {
            return $next($request);
        }
        
        // For subscription-based streams, check user subscription
        if ($stream->access_level === Stream::ACCESS_LEVEL_SUBSCRIPTION) {
            if (!$user->hasActiveSubscription()) {
                return redirect()->route('subscription.plans')
                    ->with('error', 'This stream requires an active subscription.');
            }
            
            return $next($request);
        }
        
        // For paid streams, check if user has purchased
        if ($stream->access_level === Stream::ACCESS_LEVEL_PAID) {
            // Check if user has already purchased this stream
            $purchase = StreamPurchase::where('user_id', $user->id)
                ->where('stream_id', $stream->id)
                ->where('status', 'completed')
                ->first();
            
            if ($purchase) {
                return $next($request);
            }
            
            // Redirect to purchase page
            return redirect()->route('streams.purchase', $stream->id)
                ->with('info', 'This is a paid stream. Please purchase to access.');
        }
        
        // Default: deny access
        return redirect()->route('streams.index')
            ->with('error', 'You do not have access to this stream.');
    }
}
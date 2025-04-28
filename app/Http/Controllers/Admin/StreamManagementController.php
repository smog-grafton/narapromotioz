<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stream;
use App\Models\Event;
use App\Models\StreamPurchase;
use App\Models\StreamChatMessage;
use App\Services\MuxService;
use App\Services\AgoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StreamManagementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of streams.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        
        $query = Stream::with('event');
        
        // Filter by status
        if ($status) {
            $query->where('status', $status);
        }
        
        // Get streams ordered by scheduled start
        $streams = $query->orderBy('scheduled_start', 'desc')
                       ->paginate(15);
                       
        // Get counts for each status
        $statusCounts = [
            'scheduled' => Stream::where('status', Stream::STATUS_SCHEDULED)->count(),
            'live' => Stream::where('status', Stream::STATUS_LIVE)->count(),
            'ended' => Stream::where('status', Stream::STATUS_ENDED)->count(),
            'cancelled' => Stream::where('status', Stream::STATUS_CANCELLED)->count(),
        ];
                       
        return view('admin.streams.index', compact('streams', 'status', 'statusCounts'));
    }

    /**
     * Show the form for creating a new stream.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $events = Event::orderBy('date', 'desc')->get();
        
        return view('admin.streams.create', compact('events'));
    }

    /**
     * Store a newly created stream.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_id' => 'nullable|exists:events,id',
            'scheduled_start' => 'required|date',
            'scheduled_end' => 'required|date|after:scheduled_start',
            'access_level' => 'required|in:free,paid,subscription',
            'price' => 'required_if:access_level,paid|nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ]);
        
        $stream = new Stream();
        $stream->fill($request->except('thumbnail'));
        
        // Set status to scheduled
        $stream->status = Stream::STATUS_SCHEDULED;
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('stream-thumbnails', 'public');
            $stream->thumbnail_url = Storage::url($path);
        }
        
        // Generate stream key
        $streamKey = Str::random(32);
        $stream->stream_key = $streamKey;
        
        $stream->save();
        
        // Create streaming configuration with Mux
        $muxService = new MuxService();
        $liveStream = $muxService->createLiveStream($stream);
        
        if ($liveStream) {
            $stream->stream_key = $liveStream['stream_key'] ?? $streamKey;
            $stream->playback_url = $liveStream['playback_url'];
            $stream->ingest_server = $liveStream['ingest_server'];
            $stream->stream_meta = [
                'mux_stream_id' => $liveStream['stream_id'],
                'mux_playback_id' => $liveStream['playback_id'],
            ];
            $stream->save();
        }
        
        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream created successfully.');
    }

    /**
     * Show the form for editing a stream.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\View\View
     */
    public function edit(Stream $stream)
    {
        $events = Event::orderBy('date', 'desc')->get();
        
        return view('admin.streams.edit', compact('stream', 'events'));
    }

    /**
     * Update the specified stream.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Stream $stream)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_id' => 'nullable|exists:events,id',
            'scheduled_start' => 'required|date',
            'scheduled_end' => 'required|date|after:scheduled_start',
            'access_level' => 'required|in:free,paid,subscription',
            'price' => 'required_if:access_level,paid|nullable|numeric|min:0',
            'status' => 'required|in:scheduled,live,ended,cancelled',
            'thumbnail' => 'nullable|image|max:2048',
            'is_featured' => 'sometimes|boolean',
        ]);
        
        // Save the current status to check for changes
        $oldStatus = $stream->status;
        
        // Update stream details
        $stream->fill($request->except('thumbnail', 'is_featured'));
        
        // Handle featured flag
        $stream->is_featured = $request->has('is_featured');
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($stream->thumbnail_url) {
                $oldPath = str_replace('/storage/', '', $stream->thumbnail_url);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            $path = $request->file('thumbnail')->store('stream-thumbnails', 'public');
            $stream->thumbnail_url = Storage::url($path);
        }
        
        // Handle status changes
        if ($request->status !== $oldStatus) {
            if ($request->status === Stream::STATUS_LIVE && $oldStatus !== Stream::STATUS_LIVE) {
                $stream->actual_start = now();
            } elseif ($request->status === Stream::STATUS_ENDED && $oldStatus === Stream::STATUS_LIVE) {
                $stream->actual_end = now();
                
                // End the stream in Mux if needed
                if (isset($stream->stream_meta['mux_stream_id'])) {
                    $muxService = new MuxService();
                    $muxService->endLiveStream($stream->stream_meta['mux_stream_id']);
                }
            }
        }
        
        $stream->save();
        
        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream updated successfully.');
    }

    /**
     * Start a stream.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\RedirectResponse
     */
    public function startStream(Stream $stream)
    {
        if ($stream->status !== Stream::STATUS_SCHEDULED) {
            return redirect()->route('admin.streams.index')
                ->with('error', 'Only scheduled streams can be started.');
        }
        
        $stream->startStream();
        
        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream started successfully.');
    }

    /**
     * End a stream.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\RedirectResponse
     */
    public function endStream(Stream $stream)
    {
        if ($stream->status !== Stream::STATUS_LIVE) {
            return redirect()->route('admin.streams.index')
                ->with('error', 'Only live streams can be ended.');
        }
        
        $stream->endStream();
        
        // End the stream in Mux if needed
        if (isset($stream->stream_meta['mux_stream_id'])) {
            $muxService = new MuxService();
            $muxService->endLiveStream($stream->stream_meta['mux_stream_id']);
        }
        
        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream ended successfully.');
    }

    /**
     * Delete a stream.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Stream $stream)
    {
        // Check if stream has purchases
        $hasPurchases = $stream->purchases()->exists();
        
        if ($hasPurchases) {
            return redirect()->route('admin.streams.index')
                ->with('error', 'Cannot delete stream with purchases. Cancel it instead.');
        }
        
        // Delete from Mux if needed
        if (isset($stream->stream_meta['mux_stream_id'])) {
            $muxService = new MuxService();
            $muxService->deleteLiveStream($stream->stream_meta['mux_stream_id']);
        }
        
        // Delete thumbnail if exists
        if ($stream->thumbnail_url) {
            $path = str_replace('/storage/', '', $stream->thumbnail_url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $stream->delete();
        
        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream deleted successfully.');
    }

    /**
     * Show stream analytics.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\View\View
     */
    public function analytics(Stream $stream)
    {
        // Get viewers
        $viewers = $stream->viewers()
            ->with('user')
            ->orderBy('last_active_at', 'desc')
            ->paginate(20);
        
        // Get purchases
        $purchases = $stream->purchases()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Get chat stats
        $chatCount = $stream->chatMessages()->count();
        $topChatters = $stream->chatMessages()
            ->select('user_id')
            ->selectRaw('COUNT(*) as message_count')
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('message_count')
            ->limit(5)
            ->get();
        
        return view('admin.streams.analytics', compact(
            'stream',
            'viewers',
            'purchases',
            'chatCount',
            'topChatters'
        ));
    }

    /**
     * Show stream chat moderation.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\View\View
     */
    public function chatModeration(Stream $stream)
    {
        $messages = $stream->chatMessages()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('admin.streams.chat', compact('stream', 'messages'));
    }

    /**
     * Hide a chat message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function hideMessage(Request $request, $id)
    {
        $message = StreamChatMessage::findOrFail($id);
        $message->hide();
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Message hidden successfully.');
    }

    /**
     * Restore a hidden chat message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function unhideMessage(Request $request, $id)
    {
        $message = StreamChatMessage::findOrFail($id);
        $message->unhide();
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Message restored successfully.');
    }

    /**
     * Pin a chat message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function pinMessage(Request $request, $id)
    {
        $message = StreamChatMessage::findOrFail($id);
        $message->pin();
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Message pinned successfully.');
    }

    /**
     * Unpin a chat message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function unpinMessage(Request $request, $id)
    {
        $message = StreamChatMessage::findOrFail($id);
        $message->unpin();
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Message unpinned successfully.');
    }

    /**
     * Show broadcaster setup instructions and configuration.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\View\View
     */
    public function broadcasterSetup(Stream $stream)
    {
        // Get Agora configuration if needed
        $agoraConfig = null;
        
        if (config('services.agora.app_id')) {
            $agoraService = new AgoraService();
            $user = Auth::user();
            $agoraConfig = $agoraService->prepareStreamConfig($stream, $user, true);
        }
        
        return view('admin.streams.broadcaster-setup', compact('stream', 'agoraConfig'));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoxingEvent;
use App\Models\Boxer;
use App\Models\FightRecord;
use App\Models\BoxingVideo;
use App\Models\NewsArticle;
use App\Models\EventTicket;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        // Get upcoming events
        $upcomingEvents = BoxingEvent::where('event_date', '>', Carbon::now())
            ->where('status', 'upcoming')
            ->orderBy('event_date', 'asc')
            ->get();

        // Get past events
        $pastEvents = BoxingEvent::where('event_date', '<', Carbon::now())
            ->orWhere('status', 'completed')
            ->orderBy('event_date', 'desc')
            ->get();

        return view('events.index', compact('upcomingEvents', 'pastEvents'));
    }

    /**
     * Display the specified event.
     */
    public function show($slug)
    {
        // Find the event by slug
        $event = BoxingEvent::where('slug', $slug)
            ->with(['mainEventBoxer1', 'mainEventBoxer2', 'fights', 'tickets'])
            ->firstOrFail();

        // Add isUpcoming attribute
        $event->isUpcoming = $event->event_date > Carbon::now() && $event->status !== 'completed';

        // Get all fights associated with this event
        $fights = $event->fights()->with(['boxer1', 'boxer2'])->orderBy('order', 'asc')->get();

        // Get related videos for this event
        $videos = BoxingVideo::where('event_id', $event->id)->get();

        // Get related photos
        $photos = [];
        if ($event->photos) {
            $photos = json_decode($event->photos, true) ?: [];
        }

        // Get related news articles
        $relatedNews = NewsArticle::whereHas('events', function($query) use ($event) {
            $query->where('boxing_event_id', $event->id);
        })->latest()->take(3)->get();

        return view('events.show', compact(
            'event',
            'fights',
            'videos',
            'photos',
            'relatedNews'
        ));
    }

    /**
     * Track a view for an event.
     */
    public function trackView($id)
    {
        $event = BoxingEvent::findOrFail($id);
        $event->view_count = ($event->view_count ?? 0) + 1;
        $event->save();

        return response()->json(['success' => true]);
    }

    /**
     * Get tickets for an event.
     */
    public function getTickets($eventId)
    {
        $tickets = EventTicket::where('event_id', $eventId)
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('sale_ends_at')
                    ->orWhere('sale_ends_at', '>', Carbon::now());
            })
            ->get();

        return response()->json($tickets);
    }

    /**
     * Display the upcoming events page.
     */
    public function upcoming()
    {
        $upcomingEvents = BoxingEvent::where('event_date', '>', Carbon::now())
            ->where('status', 'upcoming')
            ->orderBy('event_date', 'asc')
            ->paginate(12);

        return view('events.upcoming', compact('upcomingEvents'));
    }

    /**
     * Display the past events page.
     */
    public function past()
    {
        $pastEvents = BoxingEvent::where(function($query) {
                $query->where('event_date', '<', Carbon::now())
                    ->orWhere('status', 'completed');
            })
            ->orderBy('event_date', 'desc')
            ->paginate(12);

        return view('events.past', compact('pastEvents'));
    }
} 
 
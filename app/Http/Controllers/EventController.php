<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        $upcomingEvents = Event::where('event_date', '>', now())
                               ->orderBy('event_date', 'asc')
                               ->paginate(6);
                               
        $pastEvents = Event::where('event_date', '<', now())
                          ->orderBy('event_date', 'desc')
                          ->paginate(6);
                          
        return view('events.index', compact('upcomingEvents', 'pastEvents'));
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        // Load the fights and fighters for this event
        $event->load(['fights.fighterOne', 'fights.fighterTwo']);
        
        // Sort fights by fight_order (main event first)
        $fights = $event->fights->sortBy('fight_order');
        
        return view('events.show', compact('event', 'fights'));
    }
    
    /**
     * Display the live stream for an event (authenticated users only)
     */
    public function stream(Event $event)
    {
        // Check if event is live
        if (!$event->is_live) {
            return redirect()->route('events.show', $event)
                           ->with('error', 'This event is not currently streaming live.');
        }
        
        // Check if user has access to this stream
        if (!auth()->user()->hasStreamAccess($event)) {
            return redirect()->route('events.purchase_stream', $event)
                           ->with('error', 'You need to purchase access to this stream.');
        }
        
        return view('events.stream', compact('event'));
    }
    
    /**
     * Display the purchase stream access page
     */
    public function purchaseStream(Event $event)
    {
        // If user already has access, redirect to stream
        if (auth()->check() && auth()->user()->hasStreamAccess($event)) {
            return redirect()->route('events.stream', $event);
        }
        
        return view('events.purchase_stream', compact('event'));
    }
}
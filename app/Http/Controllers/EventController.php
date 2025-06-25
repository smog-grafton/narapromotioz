<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoxingEvent;
use App\Models\Boxer;
use App\Models\FightRecord;
use App\Models\BoxingVideo;
use App\Models\NewsArticle;
use App\Models\EventTicket;
use App\Services\SeoService;
use Carbon\Carbon;

class EventController extends Controller
{
    protected $seoService;
    
    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }
    
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

        // SEO Data
        $seoData = [
            'title' => 'Boxing Events - Nara Promotionz | Upcoming & Past Boxing Matches',
            'description' => 'Discover upcoming and past boxing events by Nara Promotionz. Watch live streams, buy tickets, and follow your favorite boxers in championship fights.',
            'keywords' => 'boxing events, upcoming fights, boxing matches, live boxing, boxing tickets, championship fights, professional boxing',
            'type' => 'website'
        ];

        // Generate structured data for events listing
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Boxing Events',
            'description' => 'Professional boxing events organized by Nara Promotionz',
            'itemListElement' => []
        ];

        foreach ($upcomingEvents->take(10) as $index => $event) {
            $structuredData['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => $this->seoService->generateStructuredData('Event', $event)
            ];
        }

        return view('events.index', compact('upcomingEvents', 'pastEvents', 'seoData', 'structuredData'));
    }

    /**
     * Display the specified event.
     */
    public function show($slug)
    {
        $event = BoxingEvent::where('slug', $slug)->firstOrFail();
        
        // Add isUpcoming attribute and determine if event is past
        $event->isUpcoming = $event->event_date > Carbon::now();
        $isPastEvent = $event->event_date < Carbon::now() || $event->status === 'completed';
        
        // Get related fights
        $fights = FightRecord::where('boxing_event_id', $event->id)
            ->with(['boxer1', 'boxer2'])
            ->orderBy('order', 'asc')
            ->get();
        
        // Get related videos
        $videos = BoxingVideo::where('event_id', $event->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();
        
        // Get event photos
        $photos = $event->photos ?? [];
        
        // Get related news articles
        $relatedNews = NewsArticle::where('content', 'LIKE', "%{$event->name}%")
            ->orWhere('title', 'LIKE', "%{$event->name}%")
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // SEO Data
        $seoData = [
            'title' => $event->name . ' - Boxing Event | Nara Promotionz',
            'description' => $event->description ?: "Join us for {$event->name} on " . $event->event_date->format('F j, Y') . " at {$event->venue}. " . ($event->isUpcoming ? 'Tickets available now!' : 'Relive the excitement of this amazing boxing event.'),
            'keywords' => $event->name . ', boxing event, ' . $event->venue . ', professional boxing, fight night, boxing tickets',
            'type' => 'event',
            'url' => route('events.show', $event->slug),
            'image' => $event->image_path ? asset('storage/' . $event->image_path) : asset('assets/images/default-event.jpg')
        ];

        // Generate structured data for the event
        $structuredData = $this->seoService->generateStructuredData('Event', $event);

        return view('events.show', compact('event', 'fights', 'videos', 'photos', 'relatedNews', 'isPastEvent', 'seoData', 'structuredData'));
    }

    /**
     * Track event view
     */
    public function trackView($id)
    {
        $event = BoxingEvent::findOrFail($id);
        $event->increment('views_count');
        
        return response()->json(['success' => true]);
    }

    /**
     * Get tickets for an event
     */
    public function getTickets($eventId)
    {
        $tickets = EventTicket::where('event_id', $eventId)
            ->where('is_active', true)
            ->where('sale_end_date', '>', Carbon::now())
            ->get();
        
        return response()->json($tickets);
    }

    /**
     * Display upcoming events
     */
    public function upcoming()
    {
        $upcomingEvents = BoxingEvent::where('event_date', '>', Carbon::now())
            ->where('status', 'upcoming')
            ->orderBy('event_date', 'asc')
            ->paginate(12);

        // SEO Data
        $seoData = [
            'title' => 'Upcoming Boxing Events - Nara Promotionz | Book Your Tickets Now',
            'description' => 'Don\'t miss upcoming boxing events by Nara Promotionz. Book your tickets now for championship fights, title bouts, and exciting boxing matches.',
            'keywords' => 'upcoming boxing events, boxing tickets, championship fights, title bouts, professional boxing, fight tickets',
            'type' => 'website'
        ];

        return view('events.upcoming', compact('upcomingEvents', 'seoData'));
    }

    /**
     * Display past events
     */
    public function past()
    {
        $pastEvents = BoxingEvent::where('event_date', '<', Carbon::now())
            ->orWhere('status', 'completed')
            ->orderBy('event_date', 'desc')
            ->paginate(12);

        // SEO Data
        $seoData = [
            'title' => 'Past Boxing Events - Nara Promotionz | Event Archive',
            'description' => 'Explore past boxing events organized by Nara Promotionz. Relive the excitement of championship fights, title bouts, and memorable boxing matches.',
            'keywords' => 'past boxing events, boxing archive, championship fights, title bouts, boxing history, fight results',
            'type' => 'website'
        ];

        return view('events.past', compact('pastEvents', 'seoData'));
    }

    /**
     * Display Summer Showdown event
     */
    public function summerShowdown()
    {
        $event = BoxingEvent::where('slug', 'summer-showdown')->firstOrFail();
        
        // SEO Data
        $seoData = [
            'title' => 'Summer Showdown - Boxing Event | Nara Promotionz',
            'description' => 'Experience the Summer Showdown boxing event by Nara Promotionz. Championship fights, title bouts, and exciting boxing action.',
            'keywords' => 'Summer Showdown, boxing event, championship fights, professional boxing, Nara Promotionz',
            'type' => 'event',
            'url' => route('events.summer-showdown'),
            'image' => $event->image_path ? asset('storage/' . $event->image_path) : asset('assets/images/default-event.jpg')
        ];

        // Generate structured data
        $structuredData = $this->seoService->generateStructuredData('Event', $event);

        return view('events.summer-showdown', compact('event', 'seoData', 'structuredData'));
    }

    /**
     * Display Championship Fight event
     */
    public function championshipFight()
    {
        $event = BoxingEvent::where('slug', 'championship-fight')->firstOrFail();
        
        // SEO Data
        $seoData = [
            'title' => 'Championship Fight - Boxing Event | Nara Promotionz',
            'description' => 'Witness the Championship Fight boxing event by Nara Promotionz. Elite boxers compete for championship titles in this premier boxing event.',
            'keywords' => 'Championship Fight, boxing championship, title fight, professional boxing, Nara Promotionz',
            'type' => 'event',
            'url' => route('events.championship-fight'),
            'image' => $event->image_path ? asset('storage/' . $event->image_path) : asset('assets/images/default-event.jpg')
        ];

        // Generate structured data
        $structuredData = $this->seoService->generateStructuredData('Event', $event);

        return view('events.championship-fight', compact('event', 'seoData', 'structuredData'));
    }

    /**
     * Display International League event
     */
    public function internationalLeague()
    {
        $event = BoxingEvent::where('slug', 'international-league')->firstOrFail();
        
        // SEO Data
        $seoData = [
            'title' => 'International League - Boxing Event | Nara Promotionz',
            'description' => 'Join the International League boxing event by Nara Promotionz. International boxers compete in this prestigious boxing tournament.',
            'keywords' => 'International League, international boxing, boxing tournament, professional boxing, Nara Promotionz',
            'type' => 'event',
            'url' => route('events.international-league'),
            'image' => $event->image_path ? asset('storage/' . $event->image_path) : asset('assets/images/default-event.jpg')
        ];

        // Generate structured data
        $structuredData = $this->seoService->generateStructuredData('Event', $event);

        return view('events.international-league', compact('event', 'seoData', 'structuredData'));
    }
} 
 
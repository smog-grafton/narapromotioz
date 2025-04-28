<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Fighter;
use App\Models\NewsArticle;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage
     */
    public function index()
    {
        // Get upcoming events
        $upcomingEvents = Event::where('event_date', '>', now())
                                ->orderBy('event_date', 'asc')
                                ->take(3)
                                ->get();
        
        // Get featured fighters (top 5 ranked)
        $featuredFighters = Fighter::whereHas('ranking', function($query) {
                                $query->where('position', '<=', 5);
                            })
                            ->take(4)
                            ->get();
        
        // Get latest news
        $latestNews = NewsArticle::published()
                                ->take(3)
                                ->get();
        
        // Check if there's a live event
        $liveEvent = Event::where('is_live', true)->first();
        
        return view('home', compact('upcomingEvents', 'featuredFighters', 'latestNews', 'liveEvent'));
    }
}
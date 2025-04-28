<?php

namespace App\Http\Controllers;

use App\Models\Fighter;
use Illuminate\Http\Request;

class FighterController extends Controller
{
    /**
     * Display a listing of all fighters.
     */
    public function index(Request $request)
    {
        // Handle filtering by weight class
        $weightClass = $request->input('weight_class');
        
        $query = Fighter::query();
        
        if ($weightClass) {
            $query->where('weight_class', $weightClass);
        }
        
        $fighters = $query->orderBy('full_name')->paginate(12);
        
        // Get list of weight classes for filter dropdown
        $weightClasses = Fighter::distinct()->pluck('weight_class');
        
        return view('fighters.index', compact('fighters', 'weightClasses', 'weightClass'));
    }

    /**
     * Display the specified fighter.
     */
    public function show(Fighter $fighter)
    {
        // Load fighter's fights, ordered by date (most recent first)
        $fighter->load(['fightsAsOne.event', 'fightsAsTwo.event', 'ranking']);
        
        // Combine fights from both relations and sort by event date
        $fights = $fighter->fightsAsOne->concat($fighter->fightsAsTwo)
                        ->sortByDesc(function($fight) {
                            return $fight->event->event_date;
                        });
        
        return view('fighters.show', compact('fighter', 'fights'));
    }
}
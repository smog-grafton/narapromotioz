<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Models\Fighter;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    /**
     * Display rankings by weight class.
     */
    public function index(Request $request)
    {
        // Get weight class from request or use the first one available
        $weightClasses = Ranking::distinct()->pluck('weight_class')->toArray();
        $selectedWeightClass = $request->input('weight_class', $weightClasses[0] ?? null);
        
        // Get rankings for the selected weight class
        $rankings = Ranking::with('fighter')
                          ->byWeightClass($selectedWeightClass)
                          ->get();
        
        return view('rankings.index', compact('rankings', 'weightClasses', 'selectedWeightClass'));
    }
}
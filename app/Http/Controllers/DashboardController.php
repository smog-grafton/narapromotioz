<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TicketPurchase;
use App\Models\BoxingEvent;
use App\Models\NewsArticle;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's ticket purchases (renamed to match view expectations)
        $ticketPurchases = TicketPurchase::where('user_id', $user->id)
            ->with(['boxingEvent'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get upcoming boxing events
        $upcomingEvents = BoxingEvent::where('event_date', '>', now())
            ->orderBy('event_date')
            ->limit(3)
            ->get();
        
        // Get recent news articles (renamed to match view expectations)
        $latestNews = NewsArticle::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();
        
        return view('dashboard.index', compact('user', 'ticketPurchases', 'upcomingEvents', 'latestNews'));
    }

    /**
     * Show user profile settings
     */
    public function profile()
    {
        return view('dashboard.profile');
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->bio = $request->bio;

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && file_exists(storage_path('app/public/' . $user->avatar))) {
                unlink(storage_path('app/public/' . $user->avatar));
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->route('dashboard.profile')->with('success', 'Profile updated successfully!');
    }
} 
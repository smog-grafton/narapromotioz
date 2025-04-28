<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FighterAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user || !$user->isFighter() || !$user->hasFighterProfile()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. Fighter profile required.'], 403);
            }
            
            return redirect()->route('home')
                ->with('error', 'You need a fighter account to access this area.');
        }
        
        // Optionally check for verified fighter profile
        if ($request->route()->getName() && str_contains($request->route()->getName(), 'verified')) {
            if (!$user->hasVerifiedFighterProfile()) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Unauthorized. Verified fighter profile required.'], 403);
                }
                
                return redirect()->route('fighter.verification')
                    ->with('warning', 'You need to verify your fighter profile to access this feature.');
            }
        }
        
        return $next($request);
    }
}
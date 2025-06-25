<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsletterSubscription;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $email = $request->email;
            
            // Check if already subscribed
            $existing = NewsletterSubscription::where('email', $email)->first();
            
            if ($existing) {
                if ($existing->status === 'active') {
                    $message = 'You are already subscribed to our newsletter!';
                } else {
                    // Reactivate subscription
                    $existing->resubscribe();
                    $message = 'Welcome back! Your newsletter subscription has been reactivated.';
                }
            } else {
                // Create new subscription
                $subscription = NewsletterSubscription::create([
                    'email' => $email,
                    'status' => 'active',
                    'source' => 'website',
                    'subscribed_at' => now(),
                    'unsubscribe_token' => Str::random(64),
                    'metadata' => [
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'subscribed_from' => $request->header('referer')
                    ]
                ]);
                
                Log::info('New newsletter subscription', [
                    'id' => $subscription->id,
                    'email' => $subscription->email
                ]);
                
                $message = 'Thank you for subscribing to our newsletter!';
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error subscribing to newsletter', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, there was an error subscribing. Please try again.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Sorry, there was an error subscribing. Please try again.')
                ->withInput();
        }
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe(Request $request, $token = null)
    {
        try {
            $subscription = NewsletterSubscription::where('unsubscribe_token', $token)->first();
            
            if (!$subscription) {
                return redirect()->route('home')->with('error', 'Invalid unsubscribe link.');
            }
            
            $subscription->unsubscribe();
            
            return view('newsletter.unsubscribed', compact('subscription'));
            
        } catch (\Exception $e) {
            Log::error('Error unsubscribing from newsletter', [
                'error' => $e->getMessage(),
                'token' => $token
            ]);
            
            return redirect()->route('home')->with('error', 'Sorry, there was an error processing your request.');
        }
    }

    /**
     * Get newsletter stats for API
     */
    public function getStats()
    {
        return response()->json([
            'active_count' => NewsletterSubscription::getActiveCount(),
            'today_count' => NewsletterSubscription::getTodayCount(),
            'week_count' => NewsletterSubscription::getThisWeekCount(),
            'month_count' => NewsletterSubscription::getThisMonthCount(),
        ]);
    }
}

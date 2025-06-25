<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display the contact page
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
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
            // Create the contact message
            $contactMessage = ContactMessage::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => 'unread'
            ]);

            // Log the contact message creation
            Log::info('New contact message received', [
                'id' => $contactMessage->id,
                'name' => $contactMessage->name,
                'email' => $contactMessage->email
            ]);

            // Send notification email to admin (optional)
            try {
                // You can implement email notification here if needed
                // Mail::to('admin@narapromotionz.com')->send(new NewContactMessage($contactMessage));
            } catch (\Exception $e) {
                Log::warning('Failed to send contact notification email', [
                    'error' => $e->getMessage(),
                    'contact_id' => $contactMessage->id
                ]);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you for your message! We will get back to you soon.'
                ]);
            }

            return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');

        } catch (\Exception $e) {
            Log::error('Error creating contact message', [
                'error' => $e->getMessage(),
                'request_data' => $request->except(['_token'])
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, there was an error sending your message. Please try again.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Sorry, there was an error sending your message. Please try again.')
                ->withInput();
        }
    }

    /**
     * Get contact message for API (for dashboard widgets, etc.)
     */
    public function getStats()
    {
        return response()->json([
            'unread_count' => ContactMessage::getUnreadCount(),
            'today_count' => ContactMessage::getTodayCount(),
            'week_count' => ContactMessage::getThisWeekCount(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Fighter;
use App\Models\News;
use App\Models\Stream;
use App\Models\Ranking;
use App\Models\WeightClass;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display the home page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Cache key for live events to avoid repeated database queries
        $cacheKey = 'home_page_data_' . date('Y-m-d_H');
        
        return Cache::remember($cacheKey, 60 * 5, function () {
            // Get live streaming event if any
            $liveEvent = Stream::where('status', Stream::STATUS_LIVE)
                ->where('is_featured', true)
                ->first();
            
            // Get total active streams count for the navbar
            $activeStreams = Stream::where('status', Stream::STATUS_LIVE)->count();
            
            // Get upcoming events (next 6)
            $upcomingEvents = Event::with(['fights.fighterOne', 'fights.fighterTwo'])
                ->where('date', '>=', now())
                ->orderBy('date', 'asc')
                ->take(6)
                ->get();
            
            // Get featured fighters (top 8)
            $featuredFighters = Fighter::with('ranking')
                ->where('is_featured', true)
                ->take(8)
                ->get();
            
            // Get latest news (6 most recent)
            $latestNews = News::with('category')
                ->where('published', true)
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc')
                ->take(6)
                ->get();
            
            // Get site statistics
            $totalEvents = Event::count();
            $totalFighters = Fighter::count();
            $totalFights = collect(Event::with('fights')->get())->sum(function ($event) {
                return $event->fights->count();
            });
            $totalFans = Cache::get('total_users_count', 25000); // Default fallback
            
            return view('home', compact(
                'liveEvent',
                'activeStreams',
                'upcomingEvents',
                'featuredFighters',
                'latestNews',
                'totalEvents',
                'totalFighters',
                'totalFights',
                'totalFans'
            ));
        });
    }

    /**
     * Display the about page.
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        // Get executives/team information
        $executives = [
            [
                'name' => 'John Smith',
                'position' => 'CEO & Founder',
                'photo' => 'images/team/john-smith.jpg',
                'bio' => 'Visionary leader with over 20 years of experience in boxing promotion and sports management.'
            ],
            [
                'name' => 'Sarah Johnson',
                'position' => 'Chief Operating Officer',
                'photo' => 'images/team/sarah-johnson.jpg',
                'bio' => 'Former professional boxer and business executive specializing in sports operations and event management.'
            ],
            [
                'name' => 'Michael Rodriguez',
                'position' => 'Head of Fighter Relations',
                'photo' => 'images/team/michael-rodriguez.jpg',
                'bio' => 'Former boxing champion with extensive connections throughout the international boxing community.'
            ],
            [
                'name' => 'Emily Chen',
                'position' => 'Marketing Director',
                'photo' => 'images/team/emily-chen.jpg',
                'bio' => 'Award-winning sports marketing specialist with experience promoting major boxing events worldwide.'
            ]
        ];
        
        // Company history milestones
        $milestones = [
            [
                'year' => '2010',
                'title' => 'Foundation',
                'description' => 'Nara Promotionz was founded with a mission to revolutionize boxing promotion.'
            ],
            [
                'year' => '2012',
                'title' => 'First Major Event',
                'description' => 'Hosted our first championship bout at Madison Square Garden with worldwide broadcast.'
            ],
            [
                'year' => '2015',
                'title' => 'International Expansion',
                'description' => 'Expanded operations to Europe and Asia, hosting events in London, Tokyo, and Dubai.'
            ],
            [
                'year' => '2018',
                'title' => 'Digital Innovation',
                'description' => 'Launched our proprietary streaming platform for live boxing events worldwide.'
            ],
            [
                'year' => '2020',
                'title' => 'Fighter Development Program',
                'description' => 'Established comprehensive program to nurture upcoming boxing talent and champions.'
            ],
            [
                'year' => '2023',
                'title' => 'Global Leadership',
                'description' => 'Recognized as the leading boxing promotion company with events on five continents.'
            ],
        ];
        
        return view('about', compact('executives', 'milestones'));
    }

    /**
     * Display the contact page.
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Process contact form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'g-recaptcha-response' => 'required|recaptcha',
        ]);
        
        // Process contact form (e.g., send email)
        // Mail::to('contact@narapromotionz.com')->send(new ContactFormSubmission($request->all()));
        
        return redirect()->route('contact')->with('success', 'Thank you for your message. We will respond as soon as possible.');
    }

    /**
     * Display the FAQ page.
     *
     * @return \Illuminate\View\View
     */
    public function faq()
    {
        // Get FAQ categories and questions
        $faqCategories = [
            'General' => [
                [
                    'question' => 'What is Nara Promotionz?',
                    'answer' => 'Nara Promotionz is a premier boxing promotion company that organizes professional boxing events, manages fighters, and provides live streaming of matches worldwide.'
                ],
                [
                    'question' => 'How can I attend a boxing event?',
                    'answer' => 'You can purchase tickets for our events directly through our website by visiting the Events section and selecting the event you wish to attend.'
                ],
            ],
            'Tickets' => [
                [
                    'question' => 'How do I purchase tickets?',
                    'answer' => 'Tickets can be purchased online through our secure payment system. Simply create an account, select an event, choose your seats, and complete the payment process.'
                ],
                [
                    'question' => 'What payment methods do you accept?',
                    'answer' => 'We accept major credit cards (Visa, MasterCard, American Express), PayPal, and various mobile payment options including Airtel Money and MTN Mobile Money.'
                ],
                [
                    'question' => 'Can I get a refund if I can\'t attend?',
                    'answer' => 'Refund policies vary by event. Generally, tickets can be refunded up to 14 days before the event. Please check the specific event details for refund policies.'
                ],
            ],
            'Live Streaming' => [
                [
                    'question' => 'How do I access live streams?',
                    'answer' => 'To access our live streams, create an account on our website, navigate to the Streams section, and purchase access to the event you wish to watch.'
                ],
                [
                    'question' => 'What are the technical requirements for streaming?',
                    'answer' => 'You need a stable internet connection with at least 5 Mbps download speed. Our streams are compatible with all major browsers and mobile devices.'
                ],
                [
                    'question' => 'Can I watch the stream after the event?',
                    'answer' => 'Yes, if you've purchased access to a stream, you can watch the replay for up to 30 days after the event.'
                ],
            ],
            'Fighters' => [
                [
                    'question' => 'How can I become a Nara Promotionz fighter?',
                    'answer' => 'Professional fighters interested in joining Nara Promotionz should submit their professional record, recent fight videos, and contact information through our Fighter Application form.'
                ],
                [
                    'question' => 'How are rankings determined?',
                    'answer' => 'Our rankings are determined by a committee of boxing experts who consider win-loss records, quality of opposition, recent performances, and championship status.'
                ],
            ],
        ];
        
        return view('faq', compact('faqCategories'));
    }

    /**
     * Display the terms of service page.
     *
     * @return \Illuminate\View\View
     */
    public function terms()
    {
        return view('terms');
    }

    /**
     * Display the privacy policy page.
     *
     * @return \Illuminate\View\View
     */
    public function privacy()
    {
        return view('privacy');
    }
}
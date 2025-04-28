<?php

namespace App\Http\Controllers;

use App\Models\Fighter;
use App\Models\FighterPromotion;
use App\Models\Event;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FighterProfileController extends Controller
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
     * Display the fighter's profile dashboard
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        if (!$user->isFighter() || !$user->hasFighterProfile()) {
            return redirect()->route('home')->with('error', 'You do not have a fighter profile.');
        }
        
        $fighter = $user->fighter;
        $upcomingEvents = $fighter->getUpcomingEvents();
        $activePromotions = $fighter->promotions()->active()->get();
        $pendingWithdrawals = $fighter->withdrawalRequests()->pending()->get();
        $recentWithdrawals = $fighter->withdrawalRequests()
            ->whereIn('status', [WithdrawalRequest::STATUS_APPROVED, WithdrawalRequest::STATUS_PROCESSED])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('fighter.dashboard', compact(
            'fighter', 
            'upcomingEvents', 
            'activePromotions', 
            'pendingWithdrawals', 
            'recentWithdrawals'
        ));
    }

    /**
     * Show the fighter profile edit form
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();
        
        if (!$user->isFighter() || !$user->hasFighterProfile()) {
            return redirect()->route('home')->with('error', 'You do not have a fighter profile.');
        }
        
        $fighter = $user->fighter;
        
        return view('fighter.edit-profile', compact('fighter'));
    }

    /**
     * Update the fighter's profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isFighter() || !$user->hasFighterProfile()) {
            return redirect()->route('home')->with('error', 'You do not have a fighter profile.');
        }
        
        $fighter = $user->fighter;
        
        // Validate basic information
        $request->validate([
            'nickname' => 'nullable|string|max:255',
            'height' => 'nullable|numeric|min:0',
            'reach' => 'nullable|numeric|min:0',
            'stance' => 'nullable|string|max:20',
            'short_bio' => 'nullable|string|max:500',
            'biography' => 'nullable|string',
            'profile_image' => 'nullable|image|max:5120',
            'banner_image' => 'nullable|image|max:5120',
            'video_highlight_url' => 'nullable|url|max:255',
            'instagram_handle' => 'nullable|string|max:50',
            'twitter_handle' => 'nullable|string|max:50',
            'facebook_url' => 'nullable|url|max:255',
            'youtube_channel' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        // Handle file uploads
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($fighter->profile_image) {
                Storage::delete($fighter->profile_image);
            }
            
            $profileImagePath = $request->file('profile_image')->store('fighter-images', 'public');
            $fighter->profile_image = $profileImagePath;
        }
        
        if ($request->hasFile('banner_image')) {
            // Delete old image if exists
            if ($fighter->banner_image) {
                Storage::delete($fighter->banner_image);
            }
            
            $bannerImagePath = $request->file('banner_image')->store('fighter-banners', 'public');
            $fighter->banner_image = $bannerImagePath;
        }

        // Update fighter profile
        $fighter->update([
            'nickname' => $request->nickname,
            'height' => $request->height,
            'reach' => $request->reach,
            'stance' => $request->stance,
            'short_bio' => $request->short_bio,
            'biography' => $request->biography,
            'video_highlight_url' => $request->video_highlight_url,
            'instagram_handle' => $request->instagram_handle,
            'twitter_handle' => $request->twitter_handle,
            'facebook_url' => $request->facebook_url,
            'youtube_channel' => $request->youtube_channel,
            'website' => $request->website,
        ]);

        return redirect()->route('fighter.profile.edit')
            ->with('success', 'Your fighter profile has been updated successfully.');
    }

    /**
     * Show the verification documents form
     *
     * @return \Illuminate\View\View
     */
    public function showVerificationForm()
    {
        $user = Auth::user();
        
        if (!$user->isFighter() || !$user->hasFighterProfile()) {
            return redirect()->route('home')->with('error', 'You do not have a fighter profile.');
        }
        
        $fighter = $user->fighter;
        
        return view('fighter.verification', compact('fighter'));
    }

    /**
     * Submit verification documents
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitVerification(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isFighter() || !$user->hasFighterProfile()) {
            return redirect()->route('home')->with('error', 'You do not have a fighter profile.');
        }
        
        $fighter = $user->fighter;
        
        $request->validate([
            'id_document' => 'required|file|max:10240',
            'proof_of_boxing_career' => 'required|file|max:10240',
            'additional_documents.*' => 'nullable|file|max:10240',
        ]);

        $verificationDocuments = [];
        
        // Store ID document
        $idDocumentPath = $request->file('id_document')->store('verification-documents', 'private');
        $verificationDocuments['id_document'] = $idDocumentPath;
        
        // Store proof of boxing career
        $proofPath = $request->file('proof_of_boxing_career')->store('verification-documents', 'private');
        $verificationDocuments['proof_of_boxing_career'] = $proofPath;
        
        // Store additional documents if any
        if ($request->hasFile('additional_documents')) {
            $additionalPaths = [];
            
            foreach ($request->file('additional_documents') as $file) {
                $path = $file->store('verification-documents', 'private');
                $additionalPaths[] = $path;
            }
            
            $verificationDocuments['additional_documents'] = $additionalPaths;
        }
        
        // Update fighter verification status and documents
        $fighter->verification_documents = $verificationDocuments;
        $fighter->verification_status = Fighter::VERIFICATION_STATUS_PENDING;
        $fighter->save();

        return redirect()->route('fighter.dashboard')
            ->with('success', 'Your verification documents have been submitted successfully. They will be reviewed by our team.');
    }

    /**
     * List all promotions
     *
     * @return \Illuminate\View\View
     */
    public function promotions()
    {
        $user = Auth::user();
        
        if (!$user->isFighter() || !$user->hasFighterProfile()) {
            return redirect()->route('home')->with('error', 'You do not have a fighter profile.');
        }
        
        $fighter = $user->fighter;
        
        $activePromotions = $fighter->promotions()->active()->get();
        $expiredPromotions = $fighter->promotions()->expired()->get();
        
        return view('fighter.promotions', compact('fighter', 'activePromotions', 'expiredPromotions'));
    }

    /**
     * Create a new promotion
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPromotion(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isFighter() || !$user->hasFighterProfile()) {
            return redirect()->route('home')->with('error', 'You do not have a fighter profile.');
        }
        
        $fighter = $user->fighter;
        
        // Validate the request
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);
        
        // Check if fighter has a scheduled fight in this event
        $event = Event::findOrFail($request->event_id);
        $hasFight = $event->fights()
            ->where(function ($query) use ($fighter) {
                $query->where('fighter1_id', $fighter->id)
                      ->orWhere('fighter2_id', $fighter->id);
            })
            ->exists();
            
        if (!$hasFight) {
            return redirect()->route('fighter.promotions')
                ->with('error', 'You can only create promotions for events where you have a scheduled fight.');
        }
        
        // Check if a promotion already exists for this event
        $existingPromotion = $fighter->promotions()
            ->where('event_id', $event->id)
            ->exists();
            
        if ($existingPromotion) {
            return redirect()->route('fighter.promotions')
                ->with('error', 'You already have a promotion for this event.');
        }
        
        // Generate promo code if not exists
        if (!$fighter->promo_code) {
            $fighter->generatePromoCode();
        }
        
        // Create the promotion
        $promotion = FighterPromotion::create([
            'fighter_id' => $fighter->id,
            'event_id' => $event->id,
            'promo_code' => $fighter->promo_code,
            'commission_rate' => $fighter->commission_rate,
            'tickets_sold' => 0,
            'commission_earned' => 0,
            'status' => FighterPromotion::STATUS_ACTIVE,
            'expires_at' => $event->date,
        ]);
        
        return redirect()->route('fighter.promotions')
            ->with('success', 'Promotion created successfully. Your fans can use your promo code when purchasing tickets.');
    }

    /**
     * Show the commission and withdrawals page
     *
     * @return \Illuminate\View\View
     */
    public function commissions()
    {
        $user = Auth::user();
        
        if (!$user->isFighter() || !$user->hasFighterProfile()) {
            return redirect()->route('home')->with('error', 'You do not have a fighter profile.');
        }
        
        $fighter = $user->fighter;
        $withdrawalRequests = $fighter->withdrawalRequests()->orderBy('created_at', 'desc')->get();
        
        return view('fighter.commissions', compact('fighter', 'withdrawalRequests'));
    }

    /**
     * Request a withdrawal
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestWithdrawal(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isFighter() || !$user->hasFighterProfile()) {
            return redirect()->route('home')->with('error', 'You do not have a fighter profile.');
        }
        
        $fighter = $user->fighter;
        $availableCommission = $fighter->available_commission;
        
        // Validate the request
        $request->validate([
            'amount' => "required|numeric|min:10|max:{$availableCommission}",
            'payment_method' => [
                'required',
                Rule::in([
                    WithdrawalRequest::METHOD_BANK_TRANSFER,
                    WithdrawalRequest::METHOD_MOBILE_MONEY,
                    WithdrawalRequest::METHOD_PAYPAL,
                    WithdrawalRequest::METHOD_STRIPE,
                ]),
            ],
            'payment_details' => 'required|array',
            'payment_details.account_name' => 'required_if:payment_method,bank_transfer|string|max:255',
            'payment_details.account_number' => 'required_if:payment_method,bank_transfer|string|max:50',
            'payment_details.bank_name' => 'required_if:payment_method,bank_transfer|string|max:255',
            'payment_details.bank_branch' => 'nullable|string|max:255',
            'payment_details.swift_code' => 'nullable|string|max:50',
            'payment_details.phone_number' => 'required_if:payment_method,mobile_money|string|max:20',
            'payment_details.mobile_network' => 'required_if:payment_method,mobile_money|string|max:50',
            'payment_details.email' => 'required_if:payment_method,paypal,stripe|email|max:255',
        ]);
        
        // Check for pending requests
        $hasPendingRequest = $fighter->withdrawalRequests()
            ->where('status', WithdrawalRequest::STATUS_PENDING)
            ->exists();
            
        if ($hasPendingRequest) {
            return redirect()->route('fighter.commissions')
                ->with('error', 'You already have a pending withdrawal request. Please wait for it to be processed.');
        }
        
        // Create the withdrawal request
        $withdrawalRequest = WithdrawalRequest::create([
            'fighter_id' => $fighter->id,
            'amount' => $request->amount,
            'status' => WithdrawalRequest::STATUS_PENDING,
            'payment_method' => $request->payment_method,
            'payment_details' => $request->payment_details,
        ]);
        
        return redirect()->route('fighter.commissions')
            ->with('success', 'Withdrawal request submitted successfully. It will be processed by our team.');
    }

    /**
     * Show the fighter's social media links
     *
     * @return \Illuminate\View\View
     */
    public function socialLinks()
    {
        $user = Auth::user();
        
        if (!$user->isFighter() || !$user->hasFighterProfile()) {
            return redirect()->route('home')->with('error', 'You do not have a fighter profile.');
        }
        
        $fighter = $user->fighter;
        
        // Generate sharing links for various platforms
        $shareLinks = [];
        
        // For upcoming events
        $upcomingEvents = $fighter->getUpcomingEvents();
        
        foreach ($upcomingEvents as $fight) {
            $event = $fight->event;
            $opponent = $fight->fighter1_id == $fighter->id ? $fight->fighter2 : $fight->fighter1;
            
            $shareText = urlencode("Watch me fight {$opponent->full_name} at {$event->name} on " . 
                               $event->date->format('F j, Y') . ". Use my promo code {$fighter->promo_code} " .
                               "to get tickets!");
            
            $eventUrl = route('events.show', $event);
            
            $shareLinks[] = [
                'event' => $event,
                'opponent' => $opponent,
                'twitter' => "https://twitter.com/intent/tweet?text={$shareText}&url={$eventUrl}",
                'facebook' => "https://www.facebook.com/sharer/sharer.php?u={$eventUrl}&quote={$shareText}",
                'whatsapp' => "https://api.whatsapp.com/send?text={$shareText} {$eventUrl}",
                'telegram' => "https://t.me/share/url?url={$eventUrl}&text={$shareText}",
                'email' => "mailto:?subject=Watch my upcoming fight&body={$shareText} {$eventUrl}",
            ];
        }
        
        return view('fighter.social-links', compact('fighter', 'shareLinks'));
    }
}
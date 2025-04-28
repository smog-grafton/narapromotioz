<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fighter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FighterVerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of pending verifications.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pendingVerifications = Fighter::where('verification_status', Fighter::VERIFICATION_STATUS_PENDING)
            ->whereNotNull('user_id')
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);
        
        return view('admin.fighter-verifications.index', compact('pendingVerifications'));
    }

    /**
     * Show the verification details for a specific fighter.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $fighter = Fighter::with('user')->findOrFail($id);
        
        if ($fighter->verification_status !== Fighter::VERIFICATION_STATUS_PENDING) {
            return redirect()->route('admin.fighter-verifications.index')
                ->with('error', 'This fighter does not have a pending verification request.');
        }
        
        return view('admin.fighter-verifications.show', compact('fighter'));
    }

    /**
     * Approve a fighter's verification request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, $id)
    {
        $fighter = Fighter::findOrFail($id);
        
        if ($fighter->verification_status !== Fighter::VERIFICATION_STATUS_PENDING) {
            return redirect()->route('admin.fighter-verifications.index')
                ->with('error', 'This fighter does not have a pending verification request.');
        }
        
        // Update verification status
        $fighter->verification_status = Fighter::VERIFICATION_STATUS_VERIFIED;
        $fighter->save();
        
        // Notify the fighter (could be done via email, notification, etc.)
        
        return redirect()->route('admin.fighter-verifications.index')
            ->with('success', "Fighter '{$fighter->full_name}' has been verified successfully.");
    }

    /**
     * Reject a fighter's verification request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        $fighter = Fighter::findOrFail($id);
        
        if ($fighter->verification_status !== Fighter::VERIFICATION_STATUS_PENDING) {
            return redirect()->route('admin.fighter-verifications.index')
                ->with('error', 'This fighter does not have a pending verification request.');
        }
        
        // Update verification status
        $fighter->verification_status = Fighter::VERIFICATION_STATUS_REJECTED;
        $fighter->save();
        
        // Store rejection reason (could be in a separate model or as metadata)
        // For now, we'll add it to a session flash message
        
        // Notify the fighter (could be done via email, notification, etc.)
        
        return redirect()->route('admin.fighter-verifications.index')
            ->with('success', "Fighter '{$fighter->full_name}' verification request has been rejected.")
            ->with('rejection_reason', $request->rejection_reason);
    }

    /**
     * Download a verification document.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  string  $document
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadDocument(Request $request, $id, $document)
    {
        $fighter = Fighter::findOrFail($id);
        
        // Check if the document exists in the fighter's verification documents
        if (!isset($fighter->verification_documents[$document])) {
            abort(404, 'Document not found');
        }
        
        $path = $fighter->verification_documents[$document];
        
        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'Document file not found');
        }
        
        return Storage::disk('private')->download($path);
    }
}
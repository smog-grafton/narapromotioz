<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Models\Fighter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalRequestController extends Controller
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
     * Display a listing of withdrawal requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        
        $query = WithdrawalRequest::with('fighter');
        
        // Filter by status
        switch ($status) {
            case 'pending':
                $query->pending();
                break;
            case 'approved':
                $query->approved();
                break;
            case 'processed':
                $query->processed();
                break;
            case 'rejected':
                $query->rejected();
                break;
            default:
                // no filter
                break;
        }
        
        $withdrawalRequests = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.withdrawal-requests.index', compact('withdrawalRequests', 'status'));
    }

    /**
     * Show the details of a specific withdrawal request.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $withdrawalRequest = WithdrawalRequest::with('fighter')->findOrFail($id);
        
        return view('admin.withdrawal-requests.show', compact('withdrawalRequest'));
    }

    /**
     * Approve a withdrawal request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, $id)
    {
        $withdrawalRequest = WithdrawalRequest::findOrFail($id);
        
        if ($withdrawalRequest->status !== WithdrawalRequest::STATUS_PENDING) {
            return redirect()->route('admin.withdrawal-requests.show', $withdrawalRequest->id)
                ->with('error', 'This withdrawal request has already been processed.');
        }
        
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Get current authenticated admin
        $admin = Auth::user();
        
        // Approve the withdrawal request
        $withdrawalRequest->approve($admin, $request->notes);
        
        return redirect()->route('admin.withdrawal-requests.index')
            ->with('success', 'Withdrawal request has been approved successfully.');
    }

    /**
     * Reject a withdrawal request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        $withdrawalRequest = WithdrawalRequest::findOrFail($id);
        
        if ($withdrawalRequest->status !== WithdrawalRequest::STATUS_PENDING) {
            return redirect()->route('admin.withdrawal-requests.show', $withdrawalRequest->id)
                ->with('error', 'This withdrawal request has already been processed.');
        }
        
        $request->validate([
            'notes' => 'required|string|max:500',
        ]);
        
        // Get current authenticated admin
        $admin = Auth::user();
        
        // Reject the withdrawal request
        $withdrawalRequest->reject($admin, $request->notes);
        
        return redirect()->route('admin.withdrawal-requests.index')
            ->with('success', 'Withdrawal request has been rejected successfully.');
    }

    /**
     * Mark a withdrawal request as processed.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsProcessed(Request $request, $id)
    {
        $withdrawalRequest = WithdrawalRequest::findOrFail($id);
        
        if ($withdrawalRequest->status !== WithdrawalRequest::STATUS_APPROVED) {
            return redirect()->route('admin.withdrawal-requests.show', $withdrawalRequest->id)
                ->with('error', 'Only approved withdrawal requests can be marked as processed.');
        }
        
        $request->validate([
            'transaction_reference' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Get current authenticated admin
        $admin = Auth::user();
        
        // Create the notes with transaction reference
        $notes = "Transaction Reference: {$request->transaction_reference}";
        
        if ($request->notes) {
            $notes .= "\n\n{$request->notes}";
        }
        
        // Mark the withdrawal request as processed
        $withdrawalRequest->markAsProcessed($admin, $notes);
        
        return redirect()->route('admin.withdrawal-requests.index')
            ->with('success', 'Withdrawal request has been marked as processed successfully.');
    }
}
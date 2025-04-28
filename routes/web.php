<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FighterProfileController;
use App\Http\Controllers\Admin\FighterVerificationController;
use App\Http\Controllers\Admin\WithdrawalRequestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home & Public Routes
Route::get('/', function () {
    return view('home');
})->name('home');

// Authentication Routes (Laravel's built-in routes)
Route::middleware('auth')->group(function () {
    // User Profile
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});

// Stream Routes
Route::prefix('streams')->name('streams.')->group(function () {
    // Public stream routes
    Route::get('/', [StreamController::class, 'index'])->name('index');
    Route::get('/{stream}', [StreamController::class, 'show'])->name('show');
    
    // Purchase routes (auth required)
    Route::middleware('auth')->group(function () {
        Route::get('/{stream}/purchase', [StreamController::class, 'showPurchase'])->name('purchase');
        Route::get('/{stream}/purchase/stripe', [StreamController::class, 'purchaseWithStripe'])->name('purchase.stripe');
        Route::post('/{stream}/purchase/stripe', [StreamController::class, 'purchaseWithStripe']);
        Route::get('/{stream}/purchase/paypal', [StreamController::class, 'purchaseWithPayPal'])->name('purchase.paypal');
        Route::get('/{stream}/purchase/{method}', [StreamController::class, 'purchaseWithAfricanMethod'])->name('purchase.african');
        Route::post('/{stream}/purchase/{method}', [StreamController::class, 'purchaseWithAfricanMethod']);
        
        // Stream chat
        Route::post('/{stream}/chat', [StreamController::class, 'postChatMessage'])->name('chat.post');
        Route::get('/{stream}/chat', [StreamController::class, 'getChatMessages'])->name('chat.get');
        
        // User's purchases
        Route::get('/my-purchases', [StreamController::class, 'myPurchases'])->name('my-purchases');
    });
});

// Payment Routes
Route::prefix('payments')->name('payment.')->group(function () {
    // Payment callbacks
    Route::get('/callback', [StreamController::class, 'handlePaymentCallback'])->name('callback');
    
    // Payment methods
    Route::get('/methods/{eventId}', [PaymentController::class, 'showPaymentMethods'])->name('methods');
    
    // Stripe
    Route::get('/stripe/{eventId}', [PaymentController::class, 'processStripe'])->name('stripe');
    Route::post('/stripe/{eventId}', [PaymentController::class, 'processStripe']);
    
    // PayPal
    Route::get('/paypal/{eventId}', [PaymentController::class, 'processPayPal'])->name('paypal');
    Route::get('/paypal/callback', [PaymentController::class, 'paypalCallback'])->name('paypal.callback');
    Route::get('/paypal/cancelled', [PaymentController::class, 'paypalCancelled'])->name('paypal.cancelled');
    
    // Pesapal
    Route::get('/pesapal/{eventId}', [PaymentController::class, 'processPesapal'])->name('pesapal');
    Route::get('/pesapal/callback', [PaymentController::class, 'pesapalCallback'])->name('pesapal.callback');
    
    // Mobile Money
    Route::get('/airtel/{eventId}', [PaymentController::class, 'processAirtelMoney'])->name('airtel');
    Route::post('/airtel/{eventId}', [PaymentController::class, 'processAirtelMoney']);
    Route::get('/mtn/{eventId}', [PaymentController::class, 'processMTNMoney'])->name('mtn');
    Route::post('/mtn/{eventId}', [PaymentController::class, 'processMTNMoney']);
    
    // Payment status
    Route::get('/status/{payment}', [PaymentController::class, 'showPaymentStatus'])->name('status');
});

// Fighter Profile Routes
Route::prefix('fighter-profile')->name('fighter.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [FighterProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/edit', [FighterProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/update', [FighterProfileController::class, 'update'])->name('profile.update');
    Route::get('/verification', [FighterProfileController::class, 'showVerificationForm'])->name('verification');
    Route::post('/verification', [FighterProfileController::class, 'submitVerification'])->name('verification.submit');
    Route::get('/promotions', [FighterProfileController::class, 'promotions'])->name('promotions');
    Route::post('/promotions', [FighterProfileController::class, 'createPromotion'])->name('promotions.create');
    Route::get('/commissions', [FighterProfileController::class, 'commissions'])->name('commissions');
    Route::post('/withdraw', [FighterProfileController::class, 'requestWithdrawal'])->name('withdraw');
    Route::get('/social-links', [FighterProfileController::class, 'socialLinks'])->name('social-links');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Fighter Verification
    Route::prefix('fighter-verifications')->name('fighter-verifications.')->group(function () {
        Route::get('/', [FighterVerificationController::class, 'index'])->name('index');
        Route::get('/{id}', [FighterVerificationController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [FighterVerificationController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [FighterVerificationController::class, 'reject'])->name('reject');
        Route::get('/{id}/document/{document}', [FighterVerificationController::class, 'downloadDocument'])->name('document');
    });
    
    // Withdrawal Requests
    Route::prefix('withdrawal-requests')->name('withdrawal-requests.')->group(function () {
        Route::get('/', [WithdrawalRequestController::class, 'index'])->name('index');
        Route::get('/{id}', [WithdrawalRequestController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [WithdrawalRequestController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [WithdrawalRequestController::class, 'reject'])->name('reject');
        Route::post('/{id}/process', [WithdrawalRequestController::class, 'markAsProcessed'])->name('process');
    });
    
    // Streams Management
    Route::prefix('streams')->name('streams.')->group(function () {
        Route::get('/', [StreamController::class, 'adminIndex'])->name('index');
        Route::get('/create', [StreamController::class, 'adminCreate'])->name('create');
        Route::post('/', [StreamController::class, 'adminStore'])->name('store');
        Route::get('/{stream}/edit', [StreamController::class, 'adminCreate'])->name('edit');
        Route::put('/{stream}', [StreamController::class, 'adminUpdate'])->name('update');
        Route::post('/{stream}/start', [StreamController::class, 'adminStartStream'])->name('start');
        Route::post('/{stream}/end', [StreamController::class, 'adminEndStream'])->name('end');
        Route::delete('/{stream}', [StreamController::class, 'adminDestroy'])->name('destroy');
    });
});

// API Webhooks
Route::prefix('api/webhooks')->group(function () {
    Route::post('/stripe', [PaymentController::class, 'handleWebhook'])->name('webhooks.stripe');
    Route::post('/paypal', [PaymentController::class, 'handleWebhook'])->name('webhooks.paypal');
    Route::post('/pesapal', [PaymentController::class, 'handleWebhook'])->name('webhooks.pesapal');
    Route::post('/airtel', [PaymentController::class, 'handleWebhook'])->name('webhooks.airtel');
    Route::post('/mtn', [PaymentController::class, 'handleWebhook'])->name('webhooks.mtn');
    Route::post('/mux', [StreamController::class, 'handleMuxWebhook'])->name('webhooks.mux');
});
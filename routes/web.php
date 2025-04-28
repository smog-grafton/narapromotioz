<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FighterController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FighterProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FighterVerificationController;
use App\Http\Controllers\Admin\WithdrawalRequestController;
use App\Http\Controllers\Admin\StreamManagementController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\NewsletterController;

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
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('contact.send');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');

// Newsletter Subscription
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Event Routes
Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/{event}', [EventController::class, 'show'])->name('show');
    Route::get('/{event}/tickets', [EventController::class, 'tickets'])->name('tickets');
});

// Fighter Routes
Route::prefix('fighters')->name('fighters.')->group(function () {
    Route::get('/', [FighterController::class, 'index'])->name('index');
    Route::get('/{fighter}', [FighterController::class, 'show'])->name('show');
});

// Rankings Routes
Route::prefix('rankings')->name('rankings.')->group(function () {
    Route::get('/', [RankingController::class, 'index'])->name('index');
    Route::get('/{weightClass}', [RankingController::class, 'show'])->name('show');
});

// News Routes
Route::prefix('news')->name('news.')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('index');
    Route::get('/category/{category}', [NewsController::class, 'category'])->name('category');
    Route::get('/tag/{tag}', [NewsController::class, 'tag'])->name('tag');
    Route::get('/{news}', [NewsController::class, 'show'])->name('show');
});

// Stream Routes
Route::prefix('streams')->name('streams.')->group(function () {
    // Public stream routes
    Route::get('/', [StreamController::class, 'index'])->name('index');
    Route::get('/upcoming', [StreamController::class, 'upcoming'])->name('upcoming');
    Route::get('/past', [StreamController::class, 'past'])->name('past');
    Route::get('/{stream}', [StreamController::class, 'show'])->name('show');
    
    // Purchase routes (auth required)
    Route::middleware('auth')->group(function () {
        Route::get('/{stream}/purchase', [StreamController::class, 'showPurchase'])->name('purchase');
        Route::get('/{stream}/purchase/stripe', [StreamController::class, 'purchaseWithStripe'])->name('purchase.stripe');
        Route::post('/{stream}/purchase/stripe', [StreamController::class, 'processStripePayment']);
        Route::get('/{stream}/purchase/paypal', [StreamController::class, 'purchaseWithPayPal'])->name('purchase.paypal');
        Route::get('/{stream}/purchase/{method}', [StreamController::class, 'purchaseWithAfricanMethod'])->name('purchase.african');
        Route::post('/{stream}/purchase/{method}', [StreamController::class, 'processAfricanPayment']);
        
        // Stream chat
        Route::post('/{stream}/chat', [StreamController::class, 'postChatMessage'])->name('chat.post');
        Route::get('/{stream}/chat', [StreamController::class, 'getChatMessages'])->name('chat.get');
        
        // User's purchases
        Route::get('/my-purchases', [StreamController::class, 'myPurchases'])->name('my-purchases');
    });
});

// Tickets Routes
Route::prefix('tickets')->name('tickets.')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/create/{event}', [TicketController::class, 'create'])->name('create');
        Route::post('/create/{event}', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');
        Route::get('/{ticket}/download', [TicketController::class, 'download'])->name('download');
    });
});

// Payment Routes
Route::prefix('payments')->name('payment.')->group(function () {
    // Payment callbacks
    Route::get('/callback', [PaymentController::class, 'handlePaymentCallback'])->name('callback');
    
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

// Authentication & User Profile Routes
Route::middleware('auth')->group(function () {
    // User Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Password Management
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.password');
    Route::patch('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // Purchase History
    Route::get('/profile/purchases', [ProfileController::class, 'purchases'])->name('profile.purchases');
});

// Social Login Routes
Route::get('/auth/{provider}', [SocialiteController::class, 'redirect'])->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('socialite.callback');

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
    Route::post('/social-links', [FighterProfileController::class, 'updateSocialLinks'])->name('social-links.update');
    Route::get('/stats', [FighterProfileController::class, 'stats'])->name('stats');
    Route::post('/stats', [FighterProfileController::class, 'updateStats'])->name('stats.update');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
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
        Route::get('/', [StreamManagementController::class, 'index'])->name('index');
        Route::get('/create', [StreamManagementController::class, 'create'])->name('create');
        Route::post('/', [StreamManagementController::class, 'store'])->name('store');
        Route::get('/{stream}/edit', [StreamManagementController::class, 'edit'])->name('edit');
        Route::put('/{stream}', [StreamManagementController::class, 'update'])->name('update');
        Route::post('/{stream}/start', [StreamManagementController::class, 'startStream'])->name('start');
        Route::post('/{stream}/end', [StreamManagementController::class, 'endStream'])->name('end');
        Route::delete('/{stream}', [StreamManagementController::class, 'destroy'])->name('destroy');
        Route::get('/{stream}/analytics', [StreamManagementController::class, 'analytics'])->name('analytics');
        Route::get('/{stream}/chat', [StreamManagementController::class, 'chatModeration'])->name('chat');
        Route::post('/message/{id}/hide', [StreamManagementController::class, 'hideMessage'])->name('message.hide');
        Route::post('/message/{id}/unhide', [StreamManagementController::class, 'unhideMessage'])->name('message.unhide');
        Route::post('/message/{id}/pin', [StreamManagementController::class, 'pinMessage'])->name('message.pin');
        Route::post('/message/{id}/unpin', [StreamManagementController::class, 'unpinMessage'])->name('message.unpin');
        Route::get('/{stream}/broadcaster', [StreamManagementController::class, 'broadcasterSetup'])->name('broadcaster');
    });
    
    // Events Management (more advanced routes as needed)
    Route::resource('events', 'App\Http\Controllers\Admin\EventController');
    Route::resource('fighters', 'App\Http\Controllers\Admin\FighterController');
    Route::resource('news', 'App\Http\Controllers\Admin\NewsController');
    Route::resource('rankings', 'App\Http\Controllers\Admin\RankingController');
    Route::resource('tickets', 'App\Http\Controllers\Admin\TicketController');
    Route::resource('users', 'App\Http\Controllers\Admin\UserController');
});

// API Webhooks
Route::prefix('api/webhooks')->group(function () {
    Route::post('/stripe', [PaymentController::class, 'handleStripeWebhook'])->name('webhooks.stripe');
    Route::post('/paypal', [PaymentController::class, 'handlePayPalWebhook'])->name('webhooks.paypal');
    Route::post('/pesapal', [PaymentController::class, 'handlePesapalWebhook'])->name('webhooks.pesapal');
    Route::post('/airtel', [PaymentController::class, 'handleAirtelWebhook'])->name('webhooks.airtel');
    Route::post('/mtn', [PaymentController::class, 'handleMTNWebhook'])->name('webhooks.mtn');
    Route::post('/mux', [StreamController::class, 'handleMuxWebhook'])->name('webhooks.mux');
});

// Fallback Route
Route::fallback(function () {
    return view('errors.404');
});
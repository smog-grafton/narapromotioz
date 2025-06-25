<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

// Authentication Routes
Auth::routes();

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/contact', [FrontController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/about', [FrontController::class, 'about'])->name('about');

// Newsletter routes
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// News routes
Route::prefix('news')->group(function () {
    Route::get('/', [FrontController::class, 'newsIndex'])->name('news.index');
    Route::get('/category/{category}', [FrontController::class, 'newsByCategory'])->name('news.category');
    Route::get('/tag/{tag}', [FrontController::class, 'newsByTag'])->name('news.tag');
    Route::get('/archive/{year}/{month}', [FrontController::class, 'newsByArchive'])->name('news.archive');
    Route::get('/{article}', [FrontController::class, 'newsShow'])->name('news.show');
    Route::post('/{article}/comment', [FrontController::class, 'storeComment'])->name('news.comment');
});

// Boxers routes
Route::get('/boxers', [FrontController::class, 'boxersIndex'])->name('boxers.index');
Route::get('/boxers/{slug}', [FrontController::class, 'boxerShow'])->name('boxers.show');

// Events routes
Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('events.index');
    Route::get('/upcoming', [EventController::class, 'upcoming'])->name('events.upcoming');
    Route::get('/past', [EventController::class, 'past'])->name('events.past');
    Route::get('/{slug}', [EventController::class, 'show'])->name('events.show');
    Route::post('/{id}/track-view', [EventController::class, 'trackView'])->name('events.track-view');
    Route::get('/{eventId}/tickets', [EventController::class, 'getTickets'])->name('events.tickets');
});

// Ticket routes
Route::prefix('tickets')->group(function () {
    // Public ticket routes
    Route::get('/event/{event}', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/purchase/{ticket}', [TicketController::class, 'purchase'])->name('tickets.purchase');
    Route::post('/purchase/{ticket}', [TicketController::class, 'processPurchase'])->name('tickets.process-purchase');
    
    // Payment routes
    Route::get('/payment/callback', [TicketController::class, 'paymentCallback'])->name('tickets.payment.callback');
    Route::get('/payment/success/{orderNumber}', [TicketController::class, 'paymentSuccess'])->name('tickets.payment.success');
    Route::get('/payment/failed/{orderNumber}', [TicketController::class, 'paymentFailed'])->name('tickets.payment.failed');
    
    // Success/failure pages
    Route::get('/success/{orderNumber}', [TicketController::class, 'paymentSuccess'])->name('tickets.success');
    Route::get('/failed/{orderNumber}', [TicketController::class, 'paymentFailed'])->name('tickets.failed');
    
    // Ticket verification (public API)
    Route::get('/verify/{orderNumber}', [TicketController::class, 'verify'])->name('tickets.verify');
    Route::post('/checkin/{orderNumber}', [TicketController::class, 'checkIn'])->name('tickets.checkin');
    
    // Authenticated ticket routes
    Route::middleware('auth')->group(function () {
        Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.my-tickets');
        Route::get('/download/{orderNumber}', [TicketController::class, 'download'])->name('tickets.download');
        Route::get('/stream-access/{event}', [TicketController::class, 'checkStreamAccess'])->name('tickets.stream-access');
    });
});

// Video routes
Route::prefix('videos')->name('videos.')->group(function () {
    Route::get('/', [VideoController::class, 'index'])->name('index');
    Route::get('/{video:slug}', [VideoController::class, 'show'])->name('show');
    Route::post('/{video:slug}/like', [VideoController::class, 'like'])->name('like');
});

// Keep the welcome route for reference

// Dashboard routes (protected by auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::put('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.update-profile');
    
    // Ticket routes
    Route::get('/tickets/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.my-tickets');
    Route::get('/tickets/{purchase}/download', [TicketController::class, 'downloadTicket'])->name('tickets.download');
});

// Public Event routes
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/upcoming', [EventController::class, 'upcoming'])->name('events.upcoming');
Route::get('/events/past', [EventController::class, 'past'])->name('events.past');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

// Ticket purchase routes (accessible to authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/events/{event}/tickets', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/events/{event}/tickets/purchase', [TicketController::class, 'purchase'])->name('tickets.purchase');
});

// Special event routes
Route::get('/summer-showdown', [EventController::class, 'summerShowdown'])->name('events.summer-showdown');
Route::get('/championship-fight', [EventController::class, 'championshipFight'])->name('events.championship-fight');
Route::get('/international-league', [EventController::class, 'internationalLeague'])->name('events.international-league');

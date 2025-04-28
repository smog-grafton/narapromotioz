<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FighterController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\StreamAccessController;
use App\Http\Controllers\ProfileController;

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

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Fighter routes
Route::get('/fighters', [FighterController::class, 'index'])->name('fighters.index');
Route::get('/fighters/{fighter}', [FighterController::class, 'show'])->name('fighters.show');

// Event routes
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// News routes
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{newsArticle}', [NewsController::class, 'show'])->name('news.show');

// Rankings route
Route::get('/rankings', [RankingController::class, 'index'])->name('rankings.index');

// Authentication required routes
Route::middleware(['auth'])->group(function () {
    // User profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Tickets
    Route::get('/my-tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/events/{event}/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/events/{event}/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}/confirmation', [TicketController::class, 'confirmation'])->name('tickets.confirmation');
    
    // Live streams
    Route::get('/events/{event}/stream', [EventController::class, 'stream'])->name('events.stream');
    Route::get('/events/{event}/purchase-stream', [EventController::class, 'purchaseStream'])->name('events.purchase_stream');
    Route::post('/events/{event}/purchase-stream', [StreamAccessController::class, 'purchase'])->name('stream.purchase');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
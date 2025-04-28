<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share common data with all views
        View::composer('*', function ($view) {
            $view->with('siteTitle', 'Nara Promotionz');
            
            // Check if there's a live event
            if (class_exists(\App\Models\Event::class)) {
                try {
                    $liveEvent = \App\Models\Event::where('is_live', true)->first();
                    $view->with('globalLiveEvent', $liveEvent);
                } catch (\Exception $e) {
                    // Ignore database exceptions during initial setup
                }
            }
        });
    }
}
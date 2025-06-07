<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TicketGenerationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TicketGenerationService::class, function ($app) {
            return new TicketGenerationService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

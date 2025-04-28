<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Set custom colors for Filament
        FilamentColor::register([
            'primary' => Color::Sky, // Sky Blue brand color
            'danger' => Color::Red, // Action Red brand color
        ]);

        // Set up the admin panel theme
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Boxing Management')
                    ->icon('heroicon-o-trophy'),
                NavigationGroup::make()
                    ->label('Content Management')
                    ->icon('heroicon-o-newspaper'),
                NavigationGroup::make()
                    ->label('Sales & Payments')
                    ->icon('heroicon-o-banknotes'),
                NavigationGroup::make()
                    ->label('User Management')
                    ->icon('heroicon-o-users'),
                NavigationGroup::make()
                    ->label('Website Settings')
                    ->icon('heroicon-o-cog'),
            ]);

            // Add custom styles & branding
            Filament::registerStyles([
                // Add any custom CSS files here if needed
            ]);

            Filament::registerViteTheme('resources/css/filament.css');
        });

        // Customize the brand
        Filament::registerRenderHook(
            'panels::page.start',
            fn (): string => '<div class="border-b dark:border-gray-700 mb-4"></div>'
        );
    }
}
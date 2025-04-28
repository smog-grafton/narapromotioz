<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;
use App\Models\Fighter;
use App\Models\Event;
use App\Models\Fight;
use App\Models\NewsArticle;
use App\Models\Ticket;
use App\Models\Stream;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?int $navigationSort = -2;
    
    protected function getHeaderWidgets(): array
    {
        return [
            DashboardStatsOverview::class,
        ];
    }
}

class DashboardStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Total registered users')
                ->descriptionIcon('heroicon-m-user')
                ->color('primary'),
                
            Stat::make('Total Ticket Sales', '$' . number_format(Payment::where('payable_type', 'App\\Models\\Ticket')->sum('amount'), 2))
                ->description(Ticket::count() . ' tickets sold')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('success'),
                
            Stat::make('Upcoming Events', Event::where('event_date', '>=', now())->count())
                ->description('Next event: ' . (Event::where('event_date', '>=', now())->orderBy('event_date')->first()?->event_date->format('M d, Y') ?? 'None scheduled'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),
                
            Stat::make('Active Fighters', Fighter::count())
                ->description(Fight::where('event.event_date', '>=', now())->count() . ' upcoming fights')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('danger'),
                
            Stat::make('Live Streams', Stream::where('is_live', true)->count())
                ->description(Stream::where('is_premium', true)->count() . ' premium streams available')
                ->descriptionIcon('heroicon-m-play')
                ->color('primary'),
                
            Stat::make('Total Articles', NewsArticle::count())
                ->description('Latest: ' . (NewsArticle::latest()->first()?->created_at->format('M d') ?? 'None'))
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('gray'),
        ];
    }
}
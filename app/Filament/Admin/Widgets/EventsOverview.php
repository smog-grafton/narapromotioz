<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\BoxingEvent;
use App\Models\EventTicket;
use App\Models\TicketPurchase;
use Carbon\Carbon;

class EventsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Event counts by status
        $upcomingEvents = BoxingEvent::where('event_date', '>', now())->where('status', 'upcoming')->count();
        $ongoingEvents = BoxingEvent::where('status', 'ongoing')->count();
        $completedEvents = BoxingEvent::where('status', 'completed')->count();
        $featuredEvents = BoxingEvent::where('is_featured', true)->count();
        
        // Event types
        $championshipEvents = BoxingEvent::where('event_type', 'championship')->count();
        $ppvEvents = BoxingEvent::where('is_ppv', true)->count();
        
        // Ticket metrics
        $totalRevenue = TicketPurchase::where('status', 'completed')->sum('grand_total');
        $ticketsSoldThisMonth = TicketPurchase::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('quantity');
            
        // Recent activity
        $eventsThisMonth = BoxingEvent::whereMonth('created_at', now()->month)->count();
        $viewsThisWeek = BoxingEvent::whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('views_count');

        return [
            Stat::make('Upcoming Events', $upcomingEvents)
                ->description($ongoingEvents . ' ongoing now')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success')
                ->chart([2, 4, 3, 6, 5, 7, 4]),
                
            Stat::make('Completed Events', $completedEvents)
                ->description($eventsThisMonth . ' created this month')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info')
                ->chart([8, 6, 12, 9, 15, 11, 18]),
                
            Stat::make('Championship Events', $championshipEvents)
                ->description($featuredEvents . ' featured events')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('warning')
                ->chart([1, 2, 1, 3, 2, 4, 2]),
                
            Stat::make('PPV Events', $ppvEvents)
                ->description('Pay-per-view events')
                ->descriptionIcon('heroicon-m-tv')
                ->color('danger')
                ->chart([0, 1, 0, 2, 1, 1, 2]),
                
            Stat::make('Ticket Revenue', '$' . number_format($totalRevenue, 2))
                ->description($ticketsSoldThisMonth . ' tickets sold this month')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([1200, 1800, 1500, 2200, 1900, 2500, 2100]),
                
            Stat::make('Event Views', number_format($viewsThisWeek))
                ->description('Total views this week')
                ->descriptionIcon('heroicon-m-eye')
                ->color('primary')
                ->chart([150, 200, 180, 300, 250, 350, 280]),
        ];
    }
}

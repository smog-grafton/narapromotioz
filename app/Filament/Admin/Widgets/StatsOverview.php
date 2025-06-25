<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Boxer;
use App\Models\BoxingEvent;
use App\Models\BoxingVideo;
use App\Models\NewsArticle;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscription;
use App\Models\User;
use App\Models\EventTicket;
use App\Models\TicketPurchase;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Get current counts
        $totalBoxers = Boxer::count();
        $activeBoxers = Boxer::where('is_active', true)->count();
        
        $totalEvents = BoxingEvent::count();
        $upcomingEvents = BoxingEvent::where('event_date', '>', now())->where('status', 'upcoming')->count();
        
        $totalVideos = BoxingVideo::count();
        $publishedVideos = BoxingVideo::where('status', 'published')->count();
        
        $totalNews = NewsArticle::count();
        $publishedNews = NewsArticle::where('status', 'published')->count();
        
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();
        
        $unreadMessages = ContactMessage::where('status', 'unread')->count();
        $totalMessages = ContactMessage::count();
        
        $activeSubscriptions = NewsletterSubscription::where('status', 'active')->count();
        $newSubscriptionsThisWeek = NewsletterSubscription::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        
        $totalTickets = EventTicket::count();
        $ticketsSold = TicketPurchase::where('status', 'completed')->sum('quantity');

        return [
            Stat::make('Total Boxers', $totalBoxers)
                ->description($activeBoxers . ' active boxers')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
                
            Stat::make('Boxing Events', $totalEvents)
                ->description($upcomingEvents . ' upcoming events')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info')
                ->chart([3, 5, 2, 8, 4, 6, 3]),
                
            Stat::make('Videos', $publishedVideos . '/' . $totalVideos)
                ->description('Published videos')
                ->descriptionIcon('heroicon-m-play-circle')
                ->color('warning')
                ->chart([12, 8, 14, 6, 18, 10, 16]),
                
            Stat::make('News Articles', $publishedNews . '/' . $totalNews)
                ->description('Published articles')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('primary')
                ->chart([4, 6, 3, 9, 5, 7, 4]),
                
            Stat::make('Total Users', $totalUsers)
                ->description($newUsersThisMonth . ' new this month')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([15, 12, 18, 9, 22, 14, 20]),
                
            Stat::make('Contact Messages', $totalMessages)
                ->description($unreadMessages . ' unread messages')
                ->descriptionIcon($unreadMessages > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($unreadMessages > 0 ? 'danger' : 'success')
                ->chart([2, 4, 1, 6, 3, 5, 2]),
                
            Stat::make('Newsletter Subscribers', $activeSubscriptions)
                ->description($newSubscriptionsThisWeek . ' new this week')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('info')
                ->chart([8, 6, 12, 4, 15, 7, 11]),
                
            Stat::make('Ticket Sales', $ticketsSold)
                ->description($totalTickets . ' tickets available')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('warning')
                ->chart([5, 8, 3, 12, 6, 10, 7]),
        ];
    }
}

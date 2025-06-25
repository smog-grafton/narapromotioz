<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\TicketPurchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TicketSalesChart extends ChartWidget
{
    protected static ?string $heading = 'Ticket Sales Performance';
    protected static ?string $description = 'Daily ticket sales and revenue over the last 30 days';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $salesData = [];
        $revenueData = [];
        $labels = [];
        
        // Get data for the last 30 days
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            $dailySales = TicketPurchase::whereDate('created_at', $date->toDateString())
                ->where('status', 'completed')
                ->sum('quantity');
                
            $dailyRevenue = TicketPurchase::whereDate('created_at', $date->toDateString())
                ->where('status', 'completed')
                ->sum('grand_total');
            
            $salesData[] = $dailySales;
            $revenueData[] = round($dailyRevenue, 2);
            $labels[] = $date->format('M j');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tickets Sold',
                    'data' => $salesData,
                    'backgroundColor' => 'rgba(99, 102, 241, 0.8)',
                    'borderColor' => 'rgb(99, 102, 241)',
                    'borderWidth' => 1,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Revenue ($)',
                    'data' => $revenueData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.8)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 1,
                    'type' => 'line',
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Tickets Sold'
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Revenue ($)'
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
        ];
    }
}

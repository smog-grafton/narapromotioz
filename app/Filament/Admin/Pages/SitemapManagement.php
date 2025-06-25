<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use App\Services\SeoService;

class SitemapManagement extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static string $view = 'filament.pages.sitemap-management';
    
    protected static ?string $navigationGroup = 'SEO Management';
    
    protected static ?string $title = 'Sitemap Management';
    
    protected static ?int $navigationSort = 1;

    public function getHeaderActions(): array
    {
        return [
            Action::make('generateSitemap')
                ->label('Generate Sitemap')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->action('generateSitemap'),
            
            Action::make('downloadSitemap')
                ->label('Download Sitemap')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->url(url('/sitemap.xml'))
                ->openUrlInNewTab()
                ->visible(fn () => File::exists(public_path('sitemap.xml'))),
                
            Action::make('viewSitemap')
                ->label('View Sitemap')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url(route('sitemap'))
                ->openUrlInNewTab()
                ->visible(fn () => File::exists(public_path('sitemap.xml'))),
        ];
    }

    public function generateSitemap(): void
    {
        try {
            $seoService = app(SeoService::class);
            $urls = $seoService->generateSitemapData();
            
            // Generate XML sitemap content
            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
            
            foreach ($urls as $url) {
                $xml .= '  <url>' . PHP_EOL;
                $xml .= '    <loc>' . htmlspecialchars($url['url']) . '</loc>' . PHP_EOL;
                
                if (isset($url['lastmod'])) {
                    $xml .= '    <lastmod>' . htmlspecialchars($url['lastmod']) . '</lastmod>' . PHP_EOL;
                }
                
                if (isset($url['changefreq'])) {
                    $xml .= '    <changefreq>' . htmlspecialchars($url['changefreq']) . '</changefreq>' . PHP_EOL;
                }
                
                if (isset($url['priority'])) {
                    $xml .= '    <priority>' . htmlspecialchars($url['priority']) . '</priority>' . PHP_EOL;
                }
                
                $xml .= '  </url>' . PHP_EOL;
            }
            
            $xml .= '</urlset>';
            
            // Write sitemap to public directory
            File::put(public_path('sitemap.xml'), $xml);
            
            Notification::make()
                ->title('Sitemap Generated Successfully')
                ->body('Sitemap has been generated with ' . count($urls) . ' URLs and saved to /public/sitemap.xml')
                ->success()
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Generating Sitemap')
                ->body('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getSitemapStats(): array
    {
        if (!File::exists(public_path('sitemap.xml'))) {
            return [
                'exists' => false,
                'size' => 0,
                'last_modified' => null,
                'url_count' => 0,
            ];
        }

        $content = File::get(public_path('sitemap.xml'));
        $urlCount = substr_count($content, '<url>');
        
        return [
            'exists' => true,
            'size' => File::size(public_path('sitemap.xml')),
            'last_modified' => File::lastModified(public_path('sitemap.xml')),
            'url_count' => $urlCount,
        ];
    }
} 
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Services\SeoService;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {--force : Force regeneration even if sitemap exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate XML sitemap for the website';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting sitemap generation...');

        try {
            $seoService = app(SeoService::class);
            $urls = $seoService->generateSitemapData();

            if (empty($urls)) {
                $this->error('No URLs found for sitemap generation.');
                return Command::FAILURE;
            }

            // Check if sitemap exists and force flag is not set
            if (File::exists(public_path('sitemap.xml')) && !$this->option('force')) {
                if (!$this->confirm('Sitemap already exists. Do you want to overwrite it?')) {
                    $this->info('Sitemap generation cancelled.');
                    return Command::SUCCESS;
                }
            }

            // Generate XML sitemap content
            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;

            $urlCount = 0;
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
                $urlCount++;
            }

            $xml .= '</urlset>';

            // Write sitemap to public directory
            File::put(public_path('sitemap.xml'), $xml);

            $this->info("✅ Sitemap generated successfully!");
            $this->info("📊 Total URLs: {$urlCount}");
            $this->info("📁 Location: " . public_path('sitemap.xml'));
            $this->info("🌐 URL: " . url('/sitemap.xml'));

            // Display some statistics
            $fileSize = File::size(public_path('sitemap.xml'));
            $this->info("📦 File size: " . number_format($fileSize / 1024, 1) . " KB");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error generating sitemap: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
} 
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NewsArticle;
use Illuminate\Support\Facades\DB;

class EnsureSingleMainArticle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:ensure-single-main-article';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensures only one article is set as the main featured article';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for multiple main articles...');
        
        $mainArticlesCount = NewsArticle::where('is_main_article', true)->count();
        
        if ($mainArticlesCount === 0) {
            $this->info('No main article found. Setting the most recent featured article as main...');
            
            // Find the most recent featured article and set it as main
            $article = NewsArticle::published()
                        ->where('is_featured', true)
                        ->orderBy('published_at', 'desc')
                        ->first();
                        
            if ($article) {
                $article->update(['is_main_article' => true]);
                $this->info("Article '{$article->title}' has been set as the main article.");
            } else {
                $this->warn('No featured articles found to set as main.');
            }
        } elseif ($mainArticlesCount > 1) {
            $this->warn("Found {$mainArticlesCount} main articles. Keeping only the most recent one...");
            
            // Get the most recent main article
            $mostRecentMainArticle = NewsArticle::where('is_main_article', true)
                                    ->orderBy('published_at', 'desc')
                                    ->first();
            
            // Set all other main articles to non-main
            DB::table('news_articles')
                ->where('is_main_article', true)
                ->where('id', '!=', $mostRecentMainArticle->id)
                ->update(['is_main_article' => false]);
                
            $this->info("Fixed. Now only article '{$mostRecentMainArticle->title}' is set as main.");
        } else {
            $this->info('Exactly one main article found. No action needed.');
        }
        
        return Command::SUCCESS;
    }
}

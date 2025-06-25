<?php

namespace App\Services;

use Illuminate\Support\Facades\URL;
use App\Models\BoxingEvent;
use App\Models\Boxer;
use App\Models\NewsArticle;
use App\Models\BoxingVideo;

class SeoService
{
    /**
     * Generate meta tags HTML from SEO data
     */
    public function generateMetaTags(array $seoData): string
    {
        $html = '';
        
        // Basic meta tags
        if (isset($seoData['title'])) {
            $html .= '<title>' . e($seoData['title']) . '</title>' . "\n";
            $html .= '<meta property="og:title" content="' . e($seoData['title']) . '">' . "\n";
            $html .= '<meta name="twitter:title" content="' . e($seoData['title']) . '">' . "\n";
        }
        
        if (isset($seoData['description'])) {
            $html .= '<meta name="description" content="' . e($seoData['description']) . '">' . "\n";
            $html .= '<meta property="og:description" content="' . e($seoData['description']) . '">' . "\n";
            $html .= '<meta name="twitter:description" content="' . e($seoData['description']) . '">' . "\n";
        }
        
        if (isset($seoData['keywords'])) {
            $html .= '<meta name="keywords" content="' . e($seoData['keywords']) . '">' . "\n";
        }
        
        // Open Graph tags
        if (isset($seoData['type'])) {
            $html .= '<meta property="og:type" content="' . e($seoData['type']) . '">' . "\n";
        }
        
        if (isset($seoData['url'])) {
            $html .= '<meta property="og:url" content="' . e($seoData['url']) . '">' . "\n";
            $html .= '<link rel="canonical" href="' . e($seoData['url']) . '">' . "\n";
        }
        
        if (isset($seoData['image'])) {
            $html .= '<meta property="og:image" content="' . e($seoData['image']) . '">' . "\n";
            $html .= '<meta name="twitter:image" content="' . e($seoData['image']) . '">' . "\n";
        }
        
        // Twitter Card
        $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
        $html .= '<meta name="twitter:site" content="@narapromotionz">' . "\n";
        
        // Article specific tags
        if (isset($seoData['published_time'])) {
            $html .= '<meta property="article:published_time" content="' . e($seoData['published_time']) . '">' . "\n";
        }
        
        if (isset($seoData['modified_time'])) {
            $html .= '<meta property="article:modified_time" content="' . e($seoData['modified_time']) . '">' . "\n";
        }
        
        if (isset($seoData['author'])) {
            $html .= '<meta property="article:author" content="' . e($seoData['author']) . '">' . "\n";
        }
        
        // Video specific tags
        if (isset($seoData['video:duration'])) {
            $html .= '<meta property="video:duration" content="' . e($seoData['video:duration']) . '">' . "\n";
        }
        
        if (isset($seoData['video:release_date'])) {
            $html .= '<meta property="video:release_date" content="' . e($seoData['video:release_date']) . '">' . "\n";
        }
        
        return $html;
    }
    
    /**
     * Generate JSON-LD structured data
     */
    public function generateStructuredData($type, $data): array
    {
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => $type
        ];
        
        switch ($type) {
            case 'Organization':
                return $this->generateOrganizationData($data);
            case 'Event':
                return $this->generateEventData($data);
            case 'Person':
                return $this->generatePersonData($data);
            case 'Article':
                return $this->generateArticleData($data);
            case 'VideoObject':
                return $this->generateVideoData($data);
            case 'BreadcrumbList':
                return $this->generateBreadcrumbData($data);
            default:
                return $structuredData;
        }
    }
    
    /**
     * Generate organization structured data
     */
    private function generateOrganizationData($data): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Nara Promotionz',
            'url' => url('/'),
            'logo' => asset('assets/images/logo.png'),
            'description' => 'Professional boxing promotion company organizing world-class boxing events and managing professional boxers.',
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+1-XXX-XXX-XXXX',
                'contactType' => 'customer service',
                'availableLanguage' => 'English'
            ],
            'sameAs' => [
                'https://facebook.com/narapromotionz',
                'https://twitter.com/narapromotionz',
                'https://instagram.com/narapromotionz'
            ]
        ];
    }
    
    /**
     * Generate event structured data
     */
    private function generateEventData(BoxingEvent $event): array
    {
        $eventData = [
            '@context' => 'https://schema.org',
            '@type' => 'SportsEvent',
            'name' => $event->name,
            'description' => $event->description,
            'startDate' => $event->event_date->toISOString(),
            'url' => route('events.show', $event->slug),
            'organizer' => [
                '@type' => 'Organization',
                'name' => 'Nara Promotionz',
                'url' => url('/')
            ]
        ];
        
        if ($event->venue) {
            $eventData['location'] = [
                '@type' => 'Place',
                'name' => $event->venue,
                'address' => $event->location
            ];
        }
        
        if ($event->image_path) {
            $eventData['image'] = asset('storage/' . $event->image_path);
        }
        
        // Add ticket information if available
        if ($event->tickets()->exists()) {
            $eventData['offers'] = $event->tickets->map(function ($ticket) {
                return [
                    '@type' => 'Offer',
                    'name' => $ticket->name,
                    'price' => $ticket->price,
                    'priceCurrency' => 'USD',
                    'availability' => $ticket->quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                    'url' => route('tickets.purchase', $ticket->id)
                ];
            })->toArray();
        }
        
        return $eventData;
    }
    
    /**
     * Generate person (boxer) structured data
     */
    private function generatePersonData(Boxer $boxer): array
    {
        $personData = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $boxer->name,
            'description' => $boxer->bio,
            'url' => route('boxers.show', $boxer->slug),
            'jobTitle' => 'Professional Boxer',
            'nationality' => $boxer->nationality,
            'birthDate' => $boxer->date_of_birth ? $boxer->date_of_birth->toDateString() : null
        ];
        
        if ($boxer->image) {
            $personData['image'] = asset('storage/' . $boxer->image);
        }
        
        // Add professional stats
        if ($boxer->wins || $boxer->losses || $boxer->draws) {
            $personData['award'] = "Professional Boxing Record: {$boxer->wins} wins, {$boxer->losses} losses, {$boxer->draws} draws";
        }
        
        return array_filter($personData);
    }
    
    /**
     * Generate article structured data
     */
    private function generateArticleData(NewsArticle $article): array
    {
        $articleData = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article->title,
            'description' => $article->excerpt ?: substr(strip_tags($article->content), 0, 160),
            'url' => route('news.show', $article->slug),
            'datePublished' => $article->published_at->toISOString(),
            'dateModified' => $article->updated_at->toISOString(),
            'author' => [
                '@type' => 'Person',
                'name' => $article->user->name ?? 'Nara Promotionz'
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Nara Promotionz',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('assets/images/logo.png')
                ]
            ]
        ];
        
        if ($article->featured_image) {
            $articleData['image'] = [
                '@type' => 'ImageObject',
                'url' => asset('storage/' . $article->featured_image),
                'width' => 1200,
                'height' => 630
            ];
        }
        
        return $articleData;
    }
    
    /**
     * Generate video structured data
     */
    private function generateVideoData(BoxingVideo $video): array
    {
        $videoData = [
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'name' => $video->title,
            'description' => $video->description,
            'url' => route('videos.show', $video->slug),
            'thumbnailUrl' => asset($video->getThumbnailPathAttribute()),
            'uploadDate' => $video->published_at->toISOString(),
            'duration' => $video->duration ? 'PT' . $video->duration . 'S' : null
        ];
        
        if ($video->boxer) {
            $videoData['about'] = [
                '@type' => 'Person',
                'name' => $video->boxer->name
            ];
        }
        
        return array_filter($videoData);
    }
    
    /**
     * Generate breadcrumb structured data
     */
    private function generateBreadcrumbData(array $breadcrumbs): array
    {
        $items = [];
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['name'],
                'item' => $breadcrumb['url'] ?? null
            ];
        }
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items
        ];
    }
    
    /**
     * Generate sitemap data
     */
    public function generateSitemapData(): array
    {
        $urls = [];
        
        // Static pages - using safe URLs
        $staticPages = [
            ['url' => url('/'), 'priority' => 1.0, 'changefreq' => 'daily'],
        ];
        
        // Add other static pages only if routes exist
        try {
            $staticPages[] = ['url' => route('about'), 'priority' => 0.8, 'changefreq' => 'monthly'];
        } catch (\Exception $e) {
            $staticPages[] = ['url' => url('/about'), 'priority' => 0.8, 'changefreq' => 'monthly'];
        }
        
        try {
            $staticPages[] = ['url' => route('contact'), 'priority' => 0.8, 'changefreq' => 'monthly'];
        } catch (\Exception $e) {
            $staticPages[] = ['url' => url('/contact'), 'priority' => 0.8, 'changefreq' => 'monthly'];
        }
        
        try {
            $staticPages[] = ['url' => route('events.index'), 'priority' => 0.9, 'changefreq' => 'daily'];
        } catch (\Exception $e) {
            $staticPages[] = ['url' => url('/events'), 'priority' => 0.9, 'changefreq' => 'daily'];
        }
        
        try {
            $staticPages[] = ['url' => route('boxers.index'), 'priority' => 0.9, 'changefreq' => 'weekly'];
        } catch (\Exception $e) {
            $staticPages[] = ['url' => url('/boxers'), 'priority' => 0.9, 'changefreq' => 'weekly'];
        }
        
        try {
            $staticPages[] = ['url' => route('news.index'), 'priority' => 0.9, 'changefreq' => 'daily'];
        } catch (\Exception $e) {
            $staticPages[] = ['url' => url('/news'), 'priority' => 0.9, 'changefreq' => 'daily'];
        }
        
        try {
            $staticPages[] = ['url' => route('videos.index'), 'priority' => 0.9, 'changefreq' => 'daily'];
        } catch (\Exception $e) {
            $staticPages[] = ['url' => url('/videos'), 'priority' => 0.9, 'changefreq' => 'daily'];
        }
        
        $urls = array_merge($urls, $staticPages);
        
        // Boxing Events
        try {
            $events = BoxingEvent::all();
            foreach ($events as $event) {
                try {
                    $eventUrl = route('events.show', $event->slug);
                } catch (\Exception $e) {
                    $eventUrl = url('/events/' . $event->slug);
                }
                
                $urls[] = [
                    'url' => $eventUrl,
                    'lastmod' => $event->updated_at->toISOString(),
                    'priority' => 0.8,
                    'changefreq' => 'weekly'
                ];
            }
        } catch (\Exception $e) {
            // Skip if BoxingEvent model has issues
        }
        
        // Boxers
        try {
            $boxers = Boxer::where('status', 'active')->get();
            foreach ($boxers as $boxer) {
                try {
                    $boxerUrl = route('boxers.show', $boxer->slug);
                } catch (\Exception $e) {
                    $boxerUrl = url('/boxers/' . $boxer->slug);
                }
                
                $urls[] = [
                    'url' => $boxerUrl,
                    'lastmod' => $boxer->updated_at->toISOString(),
                    'priority' => 0.7,
                    'changefreq' => 'weekly'
                ];
            }
        } catch (\Exception $e) {
            // Skip if Boxer model has issues
        }
        
        // News Articles
        try {
            $articles = NewsArticle::where('status', 'published')->get();
            foreach ($articles as $article) {
                try {
                    $articleUrl = route('news.show', $article->slug);
                } catch (\Exception $e) {
                    $articleUrl = url('/news/' . $article->slug);
                }
                
                $urls[] = [
                    'url' => $articleUrl,
                    'lastmod' => $article->updated_at->toISOString(),
                    'priority' => 0.6,
                    'changefreq' => 'monthly'
                ];
            }
        } catch (\Exception $e) {
            // Skip if NewsArticle model has issues
        }
        
        // Videos
        try {
            $videos = BoxingVideo::where('status', 'published')->get();
            foreach ($videos as $video) {
                try {
                    $videoUrl = route('videos.show', $video->slug);
                } catch (\Exception $e) {
                    $videoUrl = url('/videos/' . $video->slug);
                }
                
                $urls[] = [
                    'url' => $videoUrl,
                    'lastmod' => $video->updated_at->toISOString(),
                    'priority' => 0.6,
                    'changefreq' => 'monthly'
                ];
            }
        } catch (\Exception $e) {
            // Skip if BoxingVideo model has issues
        }
        
        return $urls;
    }
} 
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsArticle;
use App\Models\NewsCategory;
use App\Models\NewsTag;
use App\Models\NewsComment;
use App\Services\SeoService;
use Illuminate\Support\Facades\DB;
use App\Models\Boxer;

class FrontController extends Controller
{
    protected $seoService;
    
    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }
    
    /**
     * Display the home page
     */
    public function home()
    {
        // First check if we have a designated main article
        $mainArticle = NewsArticle::published()
            ->where('is_main_article', true)
            ->with(['categories', 'user'])
            ->first();
            
        // Fetch featured news articles for the top section
        // If there's a main article, exclude it from the featured articles
        $featuredArticlesQuery = NewsArticle::published()
            ->where('is_featured', true)
            ->with(['categories', 'user'])
            ->orderBy('published_at', 'desc');
            
        // If we have a main article, exclude it from the featured list
        if ($mainArticle) {
            $featuredArticlesQuery->where('id', '!=', $mainArticle->id);
        }
        
        // Get 7 featured articles (or 8 if there's no main article)
        $featuredArticles = $featuredArticlesQuery->limit($mainArticle ? 7 : 8)->get();
        
        // If no main article was found but we have featured articles, use the first one
        if (!$mainArticle && $featuredArticles->count() > 0) {
            $mainArticle = $featuredArticles->shift();
        }

        // Fetch recent news articles for the home page
        $recentArticles = NewsArticle::published()
            ->with(['categories', 'user'])
            ->recent()
            ->take(3)
            ->get();

        // SEO Data for home page
        $seoData = [
            'title' => 'Nara Promotionz - Professional Boxing Promotion & Events',
            'description' => 'Nara Promotionz is a premier boxing promotion company organizing world-class boxing events, managing professional boxers, and delivering exciting fight entertainment.',
            'keywords' => 'boxing promotion, professional boxing, boxing events, boxing management, fight promotion, boxing news, boxing videos, championship fights',
            'type' => 'website',
            'url' => url('/'),
            'image' => asset('assets/images/logo.png')
        ];

        // Generate structured data for home page
        $structuredData = $this->seoService->generateStructuredData('Organization', []);

        return view('home', compact('recentArticles', 'featuredArticles', 'mainArticle', 'seoData', 'structuredData'));
    }

    /**
     * Display the contact page
     */
    public function contact()
    {
        // SEO Data for contact page
        $seoData = [
            'title' => 'Contact Us - Nara Promotionz | Get in Touch',
            'description' => 'Contact Nara Promotionz for boxing event inquiries, boxer management, sponsorship opportunities, and partnership discussions. We\'re here to help.',
            'keywords' => 'contact Nara Promotionz, boxing inquiries, event booking, boxer management contact, sponsorship opportunities',
            'type' => 'website',
            'url' => route('contact')
        ];

        return view('contact', compact('seoData'));
    }

    /**
     * Display the about page
     */
    public function about()
    {
        // SEO Data for about page
        $seoData = [
            'title' => 'About Nara Promotionz - Leading Boxing Promotion Company',
            'description' => 'Learn about Nara Promotionz, our mission to promote professional boxing, our history of successful events, and our commitment to the sport.',
            'keywords' => 'about Nara Promotionz, boxing promotion company, professional boxing management, boxing history, fight promotion',
            'type' => 'website',
            'url' => route('about')
        ];

        return view('about', compact('seoData'));
    }

    /**
     * Display news articles listing
     */
    public function newsIndex(Request $request)
    {
        $articles = NewsArticle::published()
            ->with(['categories', 'tags', 'user'])
            ->recent()
            ->paginate(9);

        // SEO Data for news index
        $seoData = [
            'title' => 'Boxing News - Latest Updates from Nara Promotionz',
            'description' => 'Stay updated with the latest boxing news, fight announcements, boxer profiles, and industry insights from Nara Promotionz.',
            'keywords' => 'boxing news, fight news, boxing updates, professional boxing, boxing articles, fight announcements',
            'type' => 'website',
            'url' => route('news.index')
        ];

        return view('news.index', compact('articles', 'seoData'));
    }

    /**
     * Display a specific news article
     */
    public function newsShow(NewsArticle $article)
    {
        // Increment view count
        $article->incrementViews();

        // Get related data for sidebar
        $recentPosts = NewsArticle::published()
            ->where('id', '!=', $article->id)
            ->recent()
            ->take(5)
            ->get(['title', 'slug', 'featured_image', 'created_at']);

        $recentComments = NewsComment::approved()
            ->with('article')
            ->recent()
            ->take(5)
            ->get(['name', 'comment', 'created_at']);

        $categories = NewsCategory::active()
            ->withCount(['articles' => function ($query) {
                $query->published();
            }])
            ->ordered()
            ->get();

        $archives = NewsArticle::published()
            ->select(
                DB::raw('YEAR(published_at) as year'),
                DB::raw('MONTH(published_at) as month'),
                DB::raw('MONTHNAME(published_at) as month_name'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year', 'month', 'month_name')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        // Load article with relationships
        $article->load(['categories', 'tags', 'comments.replies', 'user']);

        // SEO Data for specific article
        $seoData = [
            'title' => $article->title . ' - Nara Promotionz Boxing News',
            'description' => $article->excerpt ?: substr(strip_tags($article->content), 0, 160),
            'keywords' => implode(', ', $article->tags->pluck('name')->toArray()) ?: 'boxing news, professional boxing, fight news',
            'type' => 'article',
            'url' => route('news.show', $article->slug),
            'image' => $article->featured_image ? asset('storage/' . $article->featured_image) : asset('assets/images/default-news.jpg'),
            'published_time' => $article->published_at->toISOString(),
            'modified_time' => $article->updated_at->toISOString(),
            'author' => $article->user->name ?? 'Nara Promotionz'
        ];

        // Generate structured data for the article
        $structuredData = $this->seoService->generateStructuredData('Article', $article);

        return view('news.show', compact('article', 'recentPosts', 'recentComments', 'categories', 'archives', 'seoData', 'structuredData'));
    }

    /**
     * Display articles by category
     */
    public function newsByCategory(NewsCategory $category)
    {
        $articles = $category->articles()
            ->published()
            ->with(['categories', 'tags', 'user'])
            ->recent()
            ->paginate(9);

        // SEO Data for category page
        $seoData = [
            'title' => $category->name . ' - Boxing News Category | Nara Promotionz',
            'description' => $category->description ?: "Read the latest boxing news in the {$category->name} category from Nara Promotionz.",
            'keywords' => $category->name . ', boxing news, professional boxing, fight news, boxing category',
            'type' => 'website',
            'url' => route('news.category', $category->slug)
        ];

        return view('news.index', compact('articles', 'category', 'seoData'));
    }

    /**
     * Display articles by tag
     */
    public function newsByTag(NewsTag $tag)
    {
        $articles = $tag->articles()
            ->published()
            ->with(['categories', 'tags', 'user'])
            ->recent()
            ->paginate(9);

        // SEO Data for tag page
        $seoData = [
            'title' => $tag->name . ' - Boxing News Tag | Nara Promotionz',
            'description' => "Explore boxing news tagged with '{$tag->name}' from Nara Promotionz. Stay updated with relevant boxing content.",
            'keywords' => $tag->name . ', boxing news, professional boxing, fight news, boxing tag',
            'type' => 'website',
            'url' => route('news.tag', $tag->slug)
        ];

        return view('news.index', compact('articles', 'tag', 'seoData'));
    }

    /**
     * Display articles by archive (year/month)
     */
    public function newsByArchive(Request $request, $year, $month)
    {
        $articles = NewsArticle::published()
            ->whereYear('published_at', $year)
            ->whereMonth('published_at', $month)
            ->with(['categories', 'tags', 'user'])
            ->recent()
            ->paginate(9);

        $monthName = date('F', mktime(0, 0, 0, $month, 1));

        // SEO Data for archive page
        $seoData = [
            'title' => "Boxing News Archive - {$monthName} {$year} | Nara Promotionz",
            'description' => "Browse boxing news from {$monthName} {$year}. Historical boxing content and fight coverage from Nara Promotionz.",
            'keywords' => "boxing news archive, {$monthName} {$year}, historical boxing news, fight coverage",
            'type' => 'website',
            'url' => route('news.archive', [$year, $month])
        ];

        return view('news.index', compact('articles', 'year', 'month', 'seoData'));
    }

    /**
     * Store a new comment
     */
    public function storeComment(Request $request, NewsArticle $article)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:news_comments,id',
        ]);

        $comment = $article->allComments()->create([
            'name' => $request->name,
            'email' => $request->email,
            'comment' => $request->comment,
            'parent_id' => $request->parent_id,
            'status' => 'pending', // Comments need approval
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment submitted successfully and is awaiting approval.'
            ]);
        }

        return redirect()->back()->with('success', 'Comment submitted successfully and is awaiting approval.');
    }

    /**
     * Display boxers listing page
     */
    public function boxersIndex()
    {
        $boxers = Boxer::active()
            ->orderBy('global_ranking', 'asc')
            ->paginate(12);

        // SEO Data for boxers index
        $seoData = [
            'title' => 'Professional Boxers - Nara Promotionz Roster',
            'description' => 'Meet the professional boxers managed by Nara Promotionz. View fighter profiles, stats, upcoming fights, and career highlights.',
            'keywords' => 'professional boxers, boxing roster, fighter profiles, boxing management, championship boxers, boxing stats',
            'type' => 'website',
            'url' => route('boxers.index')
        ];

        // Generate structured data for boxers listing
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Professional Boxers',
            'description' => 'Professional boxers managed by Nara Promotionz',
            'itemListElement' => []
        ];

        foreach ($boxers->take(10) as $index => $boxer) {
            $structuredData['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => $this->seoService->generateStructuredData('Person', $boxer)
            ];
        }
            
        return view('boxers.index', compact('boxers', 'seoData', 'structuredData'));
    }

    /**
     * Display individual boxer detail page
     */
    public function boxerShow($slug)
    {
        $boxer = Boxer::where('slug', $slug)->firstOrFail();
        
        // Load boxer relationships
        $boxer->load([
            'fights' => function($query) {
                $query->orderBy('fight_date', 'desc');
            },
            'upcoming_events' => function($query) {
                $query->with(['tickets', 'fights']);
            },
            'videos' => function($query) {
                $query->where('status', 'published')
                      ->orderBy('published_at', 'desc')
                      ->limit(8);
            }
        ]);
        
        // Get similar boxers in the same weight class
        $similarBoxers = Boxer::where('weight_class', $boxer->weight_class)
                              ->where('id', '!=', $boxer->id)
                              ->where('is_active', true)
                              ->orderBy('global_ranking', 'asc')
                              ->limit(4)
                              ->get();

        // SEO Data for individual boxer
        $seoData = [
            'title' => $boxer->name . ' - Professional Boxer Profile | Nara Promotionz',
            'description' => "Learn about {$boxer->name}, professional boxer in the {$boxer->weight_class} division. View fight record, stats, upcoming fights, and career highlights.",
            'keywords' => $boxer->name . ', professional boxer, ' . $boxer->weight_class . ', boxing record, fight stats, boxing profile',
            'type' => 'profile',
            'url' => route('boxers.show', $boxer->slug),
            'image' => $boxer->thumbnail ? asset('storage/' . $boxer->thumbnail) : asset('assets/images/default-boxer.jpg')
        ];

        // Generate structured data for the boxer
        $structuredData = $this->seoService->generateStructuredData('Person', $boxer);
        
        return view('boxers.show', compact('boxer', 'similarBoxers', 'seoData', 'structuredData'));
    }
}

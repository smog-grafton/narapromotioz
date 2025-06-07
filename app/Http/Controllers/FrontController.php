<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsArticle;
use App\Models\NewsCategory;
use App\Models\NewsTag;
use App\Models\NewsComment;
use Illuminate\Support\Facades\DB;
use App\Models\Boxer;

class FrontController extends Controller
{
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

        return view('home', compact('recentArticles', 'featuredArticles', 'mainArticle'));
    }

    /**
     * Display the contact page
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Display the about page
     */
    public function about()
    {
        return view('about');
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

        return view('news.index', compact('articles'));
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

        return view('news.show', compact('article', 'recentPosts', 'recentComments', 'categories', 'archives'));
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

        return view('news.index', compact('articles', 'category'));
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

        return view('news.index', compact('articles', 'tag'));
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

        return view('news.index', compact('articles', 'year', 'month'));
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
            
        return view('boxers.index', compact('boxers'));
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
        
        return view('boxers.show', compact('boxer', 'similarBoxers'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of news articles.
     */
    public function index()
    {
        $newsArticles = NewsArticle::published()->paginate(9);
        
        return view('news.index', compact('newsArticles'));
    }

    /**
     * Display the specified news article.
     */
    public function show(NewsArticle $newsArticle)
    {
        // Get related news articles (excluding current one)
        $relatedNews = NewsArticle::published()
                                  ->where('id', '!=', $newsArticle->id)
                                  ->take(3)
                                  ->get();
        
        return view('news.show', compact('newsArticle', 'relatedNews'));
    }
}
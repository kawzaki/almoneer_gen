<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;

class NewsController extends Controller
{
    public function index()
    {
        $articles = Article::active()->where('type', '!=', 'bio')->latest()->paginate(9);
        $categories = Category::where('module', 'article')->active()->get();
        return view('pages.news', compact('articles', 'categories'));
    }

    public function show($slug)
    {
        $article = Article::active()->where('slug', $slug)->firstOrFail();
        $article->increment('views_count');

        $recentNews = Article::active()
            ->where('id', '!=', $article->id)
            ->where('type', '!=', 'bio')
            ->latest()
            ->take(4)
            ->get();

        return view('pages.news_show', compact('article', 'recentNews'));
    }
}

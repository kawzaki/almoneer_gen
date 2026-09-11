<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $selectedTag = trim($request->query('tag', ''));
        $query = Article::active()->where('type', '!=', 'bio');

        if (!empty($selectedTag)) {
            $query->where('tags', 'like', '%' . $selectedTag . '%');
        }

        $activeCategory = null;
        if ($request->filled('category')) {
            $catParam = $request->category;
            $activeCategory = Category::where('module', 'article')
                ->where(function($q) use ($catParam) {
                    $q->where('slug', $catParam)->orWhere('id', $catParam);
                })->first();
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        $articles = $query->latest()->paginate(9)->withQueryString();
        $categories = Category::where('module', 'article')->active()->orderBy('order')->get();

        // Extract distinct tags across all active news articles
        $allTags = Article::active()
            ->where('type', '!=', 'bio')
            ->whereNotNull('tags')
            ->where('tags', '!=', '')
            ->pluck('tags');

        $tagsCloud = [];
        foreach ($allTags as $tagStr) {
            $parts = preg_split('/[,،|]+/u', $tagStr);
            foreach ($parts as $p) {
                $p = trim($p);
                if ($p !== '') {
                    $tagsCloud[$p] = ($tagsCloud[$p] ?? 0) + 1;
                }
            }
        }
        arsort($tagsCloud);

        return view('pages.news', compact('articles', 'categories', 'selectedTag', 'tagsCloud', 'activeCategory'));
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MediaItem;
use App\Models\Book;
use App\Models\Article;
use App\Models\Poem;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $term = trim($request->get('q', ''));
        $audios = collect();
        $videos = collect();
        $books = collect();
        $news = collect();
        $poems = collect();
        $totalResults = 0;

        if (mb_strlen($term) >= 2) {
            $audios = MediaItem::active()
                ->audios()
                ->where(function ($query) use ($term) {
                    $query->where('title', 'like', "%{$term}%")
                          ->orWhere('description', 'like', "%{$term}%")
                          ->orWhere('tags', 'like', "%{$term}%");
                })
                ->latest()
                ->take(12)
                ->get();

            $videos = MediaItem::active()
                ->videos()
                ->where(function ($query) use ($term) {
                    $query->where('title', 'like', "%{$term}%")
                          ->orWhere('description', 'like', "%{$term}%")
                          ->orWhere('tags', 'like', "%{$term}%");
                })
                ->latest()
                ->take(12)
                ->get();

            $books = Book::active()
                ->where(function ($query) use ($term) {
                    $query->where('title', 'like', "%{$term}%")
                          ->orWhere('description', 'like', "%{$term}%")
                          ->orWhere('author', 'like', "%{$term}%");
                })
                ->latest()
                ->take(12)
                ->get();

            $news = Article::active()
                ->where(function ($query) use ($term) {
                    $query->where('title', 'like', "%{$term}%")
                          ->orWhere('summary', 'like', "%{$term}%")
                          ->orWhere('content', 'like', "%{$term}%")
                          ->orWhere('tags', 'like', "%{$term}%");
                })
                ->latest()
                ->take(12)
                ->get();

            $poems = Poem::active()
                ->where(function ($query) use ($term) {
                    $query->where('title', 'like', "%{$term}%")
                          ->orWhere('content', 'like', "%{$term}%");
                })
                ->latest()
                ->take(12)
                ->get();

            $totalResults = $audios->count() + $videos->count() + $books->count() + $news->count() + $poems->count();
        }

        return view('pages.search', compact('term', 'audios', 'videos', 'books', 'news', 'poems', 'totalResults'));
    }
}

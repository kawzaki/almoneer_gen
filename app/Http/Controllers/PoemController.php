<?php

namespace App\Http\Controllers;

use App\Models\Poem;
use App\Models\Category;

class PoemController extends Controller
{
    public function index()
    {
        $poems = Poem::active()->latest()->paginate(12);
        $categories = Category::where('module', 'poem')->active()->get();
        return view('pages.poems', compact('poems', 'categories'));
    }

    public function show($slug)
    {
        $poem = Poem::active()->where('slug', $slug)->firstOrFail();
        $poem->increment('views_count');

        $otherPoems = Poem::active()
            ->where('id', '!=', $poem->id)
            ->latest()
            ->take(3)
            ->get();

        return view('pages.poem_show', compact('poem', 'otherPoems'));
    }
}

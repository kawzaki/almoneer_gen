<?php

namespace App\Http\Controllers;

use App\Models\Article;

class BioController extends Controller
{
    public function index()
    {
        $bioArticle = Article::where('type', 'bio')->latest()->first();
        return view('pages.bio', compact('bioArticle'));
    }
}

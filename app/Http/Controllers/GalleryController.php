<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::active()->with('items')->orderBy('order')->latest()->paginate(12);
        return view('pages.gallery', compact('albums'));
    }

    public function show($slug)
    {
        $album = GalleryAlbum::active()->with('items')->where('slug', $slug)->firstOrFail();
        return view('pages.gallery_show', compact('album'));
    }
}

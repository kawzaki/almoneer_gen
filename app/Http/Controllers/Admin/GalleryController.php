<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::withCount('items')->orderBy('order')->paginate(15);
        return view('admin.gallery.index', compact('albums'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->title, '-', null) ?: 'album-' . time();
        if (GalleryAlbum::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        GalleryAlbum::create([
            'title'       => $request->title,
            'slug'        => $slug,
            'cover_image' => $request->cover_image,
            'description' => $request->description,
            'event_date'  => $request->event_date,
            'order'       => $request->order ?? 0,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'تم إنشاء ألبوم الصور بنجاح!');
    }

    public function edit(GalleryAlbum $gallery)
    {
        $gallery->load('items');
        return view('admin.gallery.edit', ['album' => $gallery]);
    }

    public function update(Request $request, GalleryAlbum $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $gallery->update([
            'title'       => $request->title,
            'cover_image' => $request->cover_image,
            'description' => $request->description,
            'event_date'  => $request->event_date,
            'order'       => $request->order ?? 0,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'تم تحديث الألبوم بنجاح!');
    }

    public function destroy(GalleryAlbum $gallery)
    {
        $gallery->delete();
        return redirect()->route('admin.gallery.index')->with('success', 'تم حذف الألبوم بنجاح!');
    }
}

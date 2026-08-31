<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index()
    {
        $items = MediaItem::with('category')->latest()->paginate(15);
        return view('admin.media.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::where('module', 'media')->get();
        return view('admin.media.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'type'      => 'required|in:audio,video,short',
            'media_url' => 'required|string',
        ]);

        $slug = Str::slug($request->title, '-', null) ?: 'media-' . time();
        if (MediaItem::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        MediaItem::create([
            'title'       => $request->title,
            'slug'        => $slug,
            'category_id' => $request->category_id,
            'type'        => $request->type,
            'media_url'   => $request->media_url,
            'thumbnail'   => $request->thumbnail,
            'duration'    => $request->duration,
            'season_year' => $request->season_year,
            'description' => $request->description,
            'transcript'  => $request->transcript,
            'is_featured' => $request->boolean('is_featured'),
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.media.index')->with('success', 'تم حفظ المادة الإعلامية بنجاح!');
    }

    public function edit(MediaItem $medium)
    {
        $categories = Category::where('module', 'media')->get();
        return view('admin.media.edit', ['item' => $medium, 'categories' => $categories]);
    }

    public function update(Request $request, MediaItem $medium)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'type'      => 'required|in:audio,video,short',
            'media_url' => 'required|string',
        ]);

        $medium->update([
            'title'       => $request->title,
            'category_id' => $request->category_id,
            'type'        => $request->type,
            'media_url'   => $request->media_url,
            'thumbnail'   => $request->thumbnail,
            'duration'    => $request->duration,
            'season_year' => $request->season_year,
            'description' => $request->description,
            'transcript'  => $request->transcript,
            'is_featured' => $request->boolean('is_featured'),
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.media.index')->with('success', 'تم تحديث المادة الإعلامية بنجاح!');
    }

    public function destroy(MediaItem $medium)
    {
        $medium->delete();
        return redirect()->route('admin.media.index')->with('success', 'تم حذف المادة الإعلامية بنجاح!');
    }
}

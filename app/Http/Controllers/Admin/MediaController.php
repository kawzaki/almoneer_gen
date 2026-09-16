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
            'media_url' => 'nullable|string',
            'tags'      => 'nullable|string',
        ]);

        $slug = Str::slug($request->title, '-', null) ?: 'media-' . time();
        if (MediaItem::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        // Infer Hijri year & season if not explicitly set
        $sy = $request->season_year ?? '';
        $title = $request->title ?? '';
        $year = '1448';
        if (preg_match('/(14\d\d)/', $sy, $m) || preg_match('/(14\d\d)/', $title, $m)) {
            $year = $m[1];
        }

        $season = 'محاضرات عامة';
        $seasonSlug = 'general';
        if (mb_strpos($sy, 'محرم') !== false || mb_strpos($sy, 'عاشوراء') !== false || mb_strpos($title, 'عاشوراء') !== false) {
            $season = 'محرم الحرام';
            $seasonSlug = 'muharram';
        } elseif (mb_strpos($sy, 'صفر') !== false || mb_strpos($title, 'الأربعين') !== false) {
            $season = 'صفر الخير';
            $seasonSlug = 'safar';
        } elseif (mb_strpos($sy, 'رمضان') !== false) {
            $season = 'شهر رمضان';
            $seasonSlug = 'ramadan';
        } elseif (mb_strpos($sy, 'فاطم') !== false) {
            $season = 'الأيام الفاطمية';
            $seasonSlug = 'fatimiya';
        }

        $num = null;
        if (preg_match('/14\d\d-\d\d-(\d+)/', $title, $m)) {
            $num = (int)$m[1];
        }

        MediaItem::create([
            'title'          => $request->title,
            'slug'           => $slug,
            'category_id'    => $request->category_id,
            'type'           => $request->type,
            'media_url'      => $request->media_url,
            'thumbnail'      => $request->thumbnail,
            'duration'       => $request->duration,
            'season_year'    => $request->season_year,
            'hijri_year'     => $year,
            'season'         => $season,
            'season_slug'    => $seasonSlug,
            'lecture_number' => $num,
            'description'    => $request->description,
            'tags'           => $request->tags,
            'transcript'     => $request->transcript,
            'is_featured'    => $request->boolean('is_featured'),
            'is_active'      => $request->boolean('is_active', true),
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
            'media_url' => 'nullable|string',
            'tags'      => 'nullable|string',
        ]);

        $updateData = [
            'title'       => $request->title,
            'category_id' => $request->category_id,
            'type'        => $request->type,
            'media_url'   => $request->media_url,
            'thumbnail'   => $request->thumbnail,
            'duration'    => $request->duration,
            'season_year' => $request->season_year,
            'description' => $request->description,
            'tags'        => $request->tags,
            'transcript'  => $request->transcript,
            'is_featured' => $request->boolean('is_featured'),
            'is_active'   => $request->boolean('is_active', true),
        ];

        // Update season metadata from season_year or title
        $sy = $request->season_year ?? '';
        $title = $request->title ?? '';
        $year = '1448';
        if (preg_match('/(14\d\d)/', $sy, $m) || preg_match('/(14\d\d)/', $title, $m)) {
            $year = $m[1];
        }

        $season = 'محاضرات عامة';
        $seasonSlug = 'general';
        if (mb_strpos($sy, 'محرم') !== false || mb_strpos($sy, 'عاشوراء') !== false || mb_strpos($title, 'عاشوراء') !== false) {
            $season = 'محرم الحرام';
            $seasonSlug = 'muharram';
        } elseif (mb_strpos($sy, 'صفر') !== false || mb_strpos($title, 'الأربعين') !== false) {
            $season = 'صفر الخير';
            $seasonSlug = 'safar';
        } elseif (mb_strpos($sy, 'رمضان') !== false) {
            $season = 'شهر رمضان';
            $seasonSlug = 'ramadan';
        } elseif (mb_strpos($sy, 'فاطم') !== false) {
            $season = 'الأيام الفاطمية';
            $seasonSlug = 'fatimiya';
        }

        $updateData['hijri_year'] = $year;
        $updateData['season'] = $season;
        $updateData['season_slug'] = $seasonSlug;

        $medium->update($updateData);

        return redirect()->route('admin.media.index')->with('success', 'تم تحديث المادة الإعلامية بنجاح!');
    }

    public function destroy(MediaItem $medium)
    {
        $medium->delete();
        return redirect()->route('admin.media.index')->with('success', 'تم حذف المادة الإعلامية بنجاح!');
    }
}

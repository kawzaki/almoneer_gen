<?php

namespace App\Http\Controllers;

use App\Models\MediaItem;
use App\Models\Category;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'all'); // all, video, short

        $query = MediaItem::active()->videos();

        if ($type === 'short') {
            $query->where('type', 'short');
        } elseif ($type === 'video') {
            $query->where('type', 'video');
        }

        $videos = $query->latest()->paginate(12);
        $categories = Category::where('module', 'media')->active()->get();

        return view('pages.videos', compact('videos', 'categories', 'type'));
    }

    public function show($slug)
    {
        $video = MediaItem::active()->videos()->where('slug', $slug)->firstOrFail();
        $video->increment('views_count');

        $relatedVideos = MediaItem::active()->videos()
            ->where('id', '!=', $video->id)
            ->latest()
            ->take(4)
            ->get();

        return view('pages.video_show', compact('video', 'relatedVideos'));
    }
}

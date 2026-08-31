<?php

namespace App\Http\Controllers;

use App\Models\MediaItem;
use App\Models\Category;
use Illuminate\Http\Request;

class AudioController extends Controller
{
    public function index(Request $request)
    {
        $query = MediaItem::active()->audios();

        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('season') && !empty($request->season)) {
            $query->where('season_year', $request->season);
        }

        $audios = $query->latest()->paginate(12);
        $categories = Category::where('module', 'media')->active()->get();
        $seasons = MediaItem::active()->audios()->whereNotNull('season_year')->distinct()->pluck('season_year');

        return view('pages.audios', compact('audios', 'categories', 'seasons'));
    }

    public function show($slug)
    {
        $audio = MediaItem::active()->audios()->where('slug', $slug)->firstOrFail();
        $audio->increment('views_count');

        $relatedAudios = MediaItem::active()->audios()
            ->where('id', '!=', $audio->id)
            ->latest()
            ->take(4)
            ->get();

        return view('pages.audio_show', compact('audio', 'relatedAudios'));
    }
}

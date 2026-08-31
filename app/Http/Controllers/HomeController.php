<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Models\Article;
use App\Models\WeeklyWisdom;
use App\Models\MediaItem;
use App\Models\Book;
use App\Models\Poem;
use App\Models\GalleryAlbum;
use App\Models\Inquiry;

class HomeController extends Controller
{
    public function index()
    {
        // Aggregated home payload cached forever until an admin modifies any model
        $data = Cache::rememberForever('site.home.payload', function () {
            return [
                'featuredArticle' => Article::active()->featured()->latest()->first() ?? Article::active()->latest()->first(),
                'recentNews'      => Article::active()->where('type', '!=', 'bio')->latest()->take(3)->get(),
                'weeklyWisdom'    => WeeklyWisdom::active()->latest()->first(),
                'featuredAudios'  => MediaItem::active()->audios()->latest()->take(4)->get(),
                'featuredVideos'  => MediaItem::active()->videos()->latest()->take(4)->get(),
                'featuredBooks'   => Book::active()->orderBy('order')->take(4)->get(),
                'featuredPoem'    => Poem::active()->latest()->first(),
                'latestAlbum'     => GalleryAlbum::active()->with('items')->latest()->first(),
                'recentInquiries' => Inquiry::published()->latest()->take(3)->get(),
            ];
        });

        // Live/cached YouTube uploads from Alm0neer1 channel (Top 3 latest uploads)
        $data['latestYouTubeVideos'] = \App\Services\YouTubeService::getLatestChannelVideos(3);

        return view('home', $data);
    }
}

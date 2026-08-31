<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Traits\ClearsFrontendCache;
use App\Models\Article;
use App\Models\MediaItem;
use App\Models\Book;
use App\Models\Poem;
use App\Models\Inquiry;
use App\Models\ContactMessage;
use App\Models\AuditLog;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'articles_count'  => Article::count(),
            'media_count'     => MediaItem::count(),
            'books_count'     => Book::count(),
            'poems_count'     => Poem::count(),
            'new_inquiries'   => Inquiry::where('status', 'new')->count(),
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
        ];

        $recentAudits = AuditLog::with('user')->latest()->take(8)->get();
        $recentInquiries = Inquiry::where('status', 'new')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentAudits', 'recentInquiries'));
    }

    public function flushCache()
    {
        // 1. Purge all frontend application cache keys
        ClearsFrontendCache::flushAllFrontendCache();

        // 2. Clear compiled blade views and framework cache
        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return redirect()->back()->with('success', 'تم تفريغ كافة ملفات الكاش وتحديث الموقع بالكامل بنجاح!');
    }
}

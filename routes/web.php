<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BioController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\PoemController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SocialHubController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Auth\LoginController;

// =========================================================================
// 1. الواجهة العامة (Frontend Public Routes)
// =========================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/bio', [BioController::class, 'index'])->name('bio');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// المحاضرات والمواسم السنوية (Lectures & Seasonal Archives)
Route::get('/lectures', [LectureController::class, 'index'])->name('lectures.index');
Route::get('/lectures/{year}', [LectureController::class, 'year'])->where('year', '[0-9]{4}')->name('lectures.year');
Route::get('/lectures/{year}/{season}', [LectureController::class, 'season'])->where('year', '[0-9]{4}')->name('lectures.season');
Route::get('/lectures/{year}/{season}/{slug}', [LectureController::class, 'show'])->where('year', '[0-9]{4}')->name('lectures.show');
Route::get('/lectures/{slug}', [LectureController::class, 'showBySlug'])->name('lectures.single');

// توافق المسارات القديمة وتوجيهها تلقائياً للمحاضرات (Backwards Compatibility)
Route::get('/videos', fn() => redirect()->route('lectures.index'))->name('videos.index');
Route::get('/videos/{slug}', fn($slug) => redirect()->route('lectures.single', ['slug' => $slug]))->name('videos.show');
Route::get('/audios', fn() => redirect()->route('lectures.index', ['format' => 'audio']))->name('audios.index');
Route::get('/audios/{slug}', fn($slug) => redirect()->route('lectures.single', ['slug' => $slug]))->name('audios.show');

// الشعر والقصائد (Poetry)
Route::get('/poems', [PoemController::class, 'index'])->name('poems.index');
Route::get('/poems/{slug}', [PoemController::class, 'show'])->name('poems.show');

// كتب ومؤلفات (Books & Publications)
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{slug}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/{slug}/download', [BookController::class, 'download'])->name('books.download');

// أخبار ونشاطات (News & Activities)
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

// ألبوم وصور (Gallery)
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{slug}', [GalleryController::class, 'show'])->name('gallery.show');

// استفسارات (Inquiries & Q&A)
Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');
Route::get('/inquiries/track', [InquiryController::class, 'track'])->name('inquiries.track');

// تواصل والمركز الإعلامي
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
Route::get('/social-hub', [SocialHubController::class, 'index'])->name('social.index');

// التبديل الفوري للثيم وتجربة القوالب (Theme Quick Switcher)
Route::get('/theme/switch/{name}', function ($name) {
    if (in_array($name, ['almoneer-emerald', 'almoneer-turquoise'])) {
        session(['site_theme_preview' => $name]);
    } elseif ($name === 'reset') {
        session()->forget('site_theme_preview');
    }
    return redirect()->back();
})->name('theme.switch');

// =========================================================================
// 2. تسجيل الدخول ولوحة التحكم (Auth & Admin Routes)
// =========================================================================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('admin')->middleware(['admin'])->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // 1. تفريغ الكاش المباشر (Instant Manual Cache Flush)
    Route::post('/cache/flush', [\App\Http\Controllers\Admin\DashboardController::class, 'flushCache'])->name('cache.flush');

    // 2. إدارة القوائم الأفقية والعمودية (Menu Management)
    Route::get('/menus', [\App\Http\Controllers\Admin\MenuController::class, 'index'])->name('menus.index');
    Route::post('/menus/{id}', [\App\Http\Controllers\Admin\MenuController::class, 'update'])->name('menus.update');
    Route::get('/menus-list', [\App\Http\Controllers\Admin\MenuController::class, 'getList'])->name('menus.list');

    // 3. إدارة الثيمات (Theme Management)
    Route::get('/themes', [\App\Http\Controllers\Admin\ThemeController::class, 'index'])->name('themes.index');
    Route::post('/themes/{name}/activate', [\App\Http\Controllers\Admin\ThemeController::class, 'activate'])->name('themes.activate');
    Route::post('/themes/upload', [\App\Http\Controllers\Admin\ThemeController::class, 'store'])->name('themes.store');
    Route::delete('/themes/{name}', [\App\Http\Controllers\Admin\ThemeController::class, 'destroy'])->name('themes.destroy');

    // 4. إدارة المحتوى والتصنيفات (Content & Category Management)
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('articles', \App\Http\Controllers\Admin\ArticleController::class);
    Route::resource('media', \App\Http\Controllers\Admin\MediaController::class);
    Route::resource('poems', \App\Http\Controllers\Admin\PoemController::class);
    Route::resource('books', \App\Http\Controllers\Admin\BookController::class);
    Route::post('/gallery/upload', [\App\Http\Controllers\Admin\GalleryController::class, 'upload'])->name('gallery.upload');
    Route::delete('/gallery/upload', [\App\Http\Controllers\Admin\GalleryController::class, 'revertUpload'])->name('gallery.upload.revert');
    Route::delete('/gallery/items/{id}', [\App\Http\Controllers\Admin\GalleryController::class, 'destroyItem'])->name('gallery.items.destroy');
    Route::post('/gallery/{gallery}/cover', [\App\Http\Controllers\Admin\GalleryController::class, 'setCover'])->name('gallery.cover.set');
    Route::resource('gallery', \App\Http\Controllers\Admin\GalleryController::class);
    Route::resource('inquiries', \App\Http\Controllers\Admin\InquiryController::class);
    Route::resource('wisdom', \App\Http\Controllers\Admin\WeeklyWisdomController::class);
    
    // 5. أدوات التحرير والإعدادات والرقابة
    Route::get('/vacum', function() { return view('admin.tools.vacum'); })->name('tools.vacum');
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit.index');
});

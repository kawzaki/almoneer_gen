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
use App\Http\Controllers\Auth\LoginController;

// =========================================================================
// 1. الواجهة العامة (Frontend Public Routes)
// =========================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/bio', [BioController::class, 'index'])->name('bio');

// الصوتيات (Audios)
Route::get('/audios', [AudioController::class, 'index'])->name('audios.index');
Route::get('/audios/{slug}', [AudioController::class, 'show'])->name('audios.show');

// المرئيات (Videos & Shorts)
Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
Route::get('/videos/{slug}', [VideoController::class, 'show'])->name('videos.show');

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

    // 4. إدارة المحتوى (Content Management)
    Route::resource('articles', \App\Http\Controllers\Admin\ArticleController::class);
    Route::resource('media', \App\Http\Controllers\Admin\MediaController::class);
    Route::resource('poems', \App\Http\Controllers\Admin\PoemController::class);
    Route::resource('books', \App\Http\Controllers\Admin\BookController::class);
    Route::resource('gallery', \App\Http\Controllers\Admin\GalleryController::class);
    Route::resource('inquiries', \App\Http\Controllers\Admin\InquiryController::class);
    Route::resource('wisdom', \App\Http\Controllers\Admin\WeeklyWisdomController::class);
    
    // 5. الإعدادات والرقابة
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit.index');
});

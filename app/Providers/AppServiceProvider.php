<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Share globally cached layout variables with all public views
        View::composer(['layouts.app', 'layouts.admin', 'home', 'pages.*'], function ($view) {
            $horizontalMenu = Cache::rememberForever('site.menus.horizontal', function () {
                $path = storage_path('app/menus/menu.htm');
                return File::exists($path) ? File::get($path) : '';
            });

            $verticalMenu = Cache::rememberForever('site.menus.vertical', function () {
                $path = storage_path('app/menus/menuv.htm');
                return File::exists($path) ? File::get($path) : '';
            });

            $footerMenu1 = Cache::rememberForever('site.menus.footer1', function () {
                $path = storage_path('app/menus/menu_f1.htm');
                return File::exists($path) ? File::get($path) : '';
            });

            $footerMenu2 = Cache::rememberForever('site.menus.footer2', function () {
                $path = storage_path('app/menus/menu_f2.htm');
                return File::exists($path) ? File::get($path) : '';
            });

            $siteSettings = Cache::rememberForever('site.settings', function () {
                try {
                    return Setting::pluck('value', 'key')->toArray();
                } catch (\Throwable $e) {
                    return [];
                }
            });

            $activeTheme = Cache::rememberForever('site.theme.active', function () use ($siteSettings) {
                return $siteSettings['site.theme'] ?? 'almoneer-emerald';
            });

            // Allow session-based live preview override
            if (session()->has('site_theme_preview')) {
                $activeTheme = session('site_theme_preview');
            }

            $hawzaPortalUrl = env('HAWZA_PORTAL_URL', 'https://almoneer-droos.onrender.com');

            $view->with([
                'globalHorizontalMenu' => $horizontalMenu,
                'globalVerticalMenu'   => $verticalMenu,
                'globalFooterMenu1'    => $footerMenu1,
                'globalFooterMenu2'    => $footerMenu2,
                'siteSettings'         => $siteSettings,
                'activeTheme'          => $activeTheme,
                'hawzaPortalUrl'       => $hawzaPortalUrl,
            ]);
        });
    }
}

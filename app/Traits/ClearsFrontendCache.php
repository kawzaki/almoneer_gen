<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ClearsFrontendCache
{
    public static function bootClearsFrontendCache(): void
    {
        static::saved(function ($model) {
            static::invalidateFrontendCache($model);
        });

        static::deleted(function ($model) {
            static::invalidateFrontendCache($model);
        });
    }

    public static function invalidateFrontendCache($model): void
    {
        // Invalidate Home Page Payload
        Cache::forget('site.home.payload');

        // Target-specific invalidation based on model class
        $class = class_basename($model);

        switch ($class) {
            case 'Article':
                Cache::forget('site.articles.recent');
                break;

            case 'MediaItem':
                Cache::forget('site.media.recent');
                Cache::forget('site.audios.recent');
                Cache::forget('site.videos.recent');
                break;

            case 'Book':
                Cache::forget('site.books.featured');
                break;

            case 'Poem':
                Cache::forget('site.poems.featured');
                break;

            case 'GalleryAlbum':
            case 'GalleryItem':
                Cache::forget('site.gallery.recent');
                break;

            case 'WeeklyWisdom':
                Cache::forget('site.wisdom.active');
                break;

            case 'Setting':
                Cache::forget('site.settings');
                Cache::forget('site.theme.active');
                break;

            case 'MenuItem':
                Cache::forget('site.menus.horizontal');
                Cache::forget('site.menus.vertical');
                break;

            case 'Category':
                Cache::forget('site.categories.all');
                break;
        }
    }

    /**
     * Helper to manually purge all frontend caches.
     */
    public static function flushAllFrontendCache(): void
    {
        $keys = [
            'site.home.payload',
            'site.settings',
            'site.menus.horizontal',
            'site.menus.vertical',
            'site.theme.active',
            'site.articles.recent',
            'site.media.recent',
            'site.audios.recent',
            'site.videos.recent',
            'site.books.featured',
            'site.poems.featured',
            'site.gallery.recent',
            'site.wisdom.active',
            'site.categories.all',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}

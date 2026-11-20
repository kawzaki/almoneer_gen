<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MediaDurationService
{
    /**
     * Attempt to fetch duration from YouTube or SoundCloud URLs.
     *
     * @param string|null $youtubeUrl
     * @param string|null $soundcloudUrl
     * @return string|null Formatted duration string (e.g. "52:40" or "1:04:12")
     */
    public static function fetchDuration(?string $youtubeUrl, ?string $soundcloudUrl): ?string
    {
        // 1. Try SoundCloud first if present (often has exact audio lecture length)
        if (!empty($soundcloudUrl)) {
            $duration = self::fetchSoundCloudDuration($soundcloudUrl);
            if (!empty($duration)) {
                return $duration;
            }
        }

        // 2. Try YouTube if present
        if (!empty($youtubeUrl)) {
            $duration = self::fetchYouTubeDuration($youtubeUrl);
            if (!empty($duration)) {
                return $duration;
            }
        }

        return null;
    }

    /**
     * Fetch duration from a YouTube URL.
     *
     * @param string $url
     * @return string|null
     */
    public static function fetchYouTubeDuration(string $url): ?string
    {
        // Extract video ID
        $videoId = null;
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m)) {
            $videoId = $m[1];
        }

        if (!$videoId) {
            return null;
        }

        $watchUrl = "https://www.youtube.com/watch?v={$videoId}";

        try {
            $context = stream_context_create([
                'http' => [
                    'timeout'    => 5,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                ],
            ]);

            $html = @file_get_contents($watchUrl, false, $context);
            if (!$html) {
                return null;
            }

            // Look for lengthSeconds or approxDurationMs
            if (preg_match('/"lengthSeconds":"(\d+)"/', $html, $m)) {
                $seconds = (int)$m[1];
                if ($seconds > 0) {
                    return self::formatSeconds($seconds);
                }
            }

            if (preg_match('/"approxDurationMs":"(\d+)"/', $html, $m)) {
                $seconds = (int)round((int)$m[1] / 1000);
                if ($seconds > 0) {
                    return self::formatSeconds($seconds);
                }
            }
        } catch (\Throwable $e) {
            Log::warning("MediaDurationService: Failed fetching YouTube duration for {$watchUrl}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Fetch duration from a SoundCloud track URL.
     *
     * @param string $url
     * @return string|null
     */
    public static function fetchSoundCloudDuration(string $url): ?string
    {
        if (!str_contains($url, 'soundcloud.com')) {
            return null;
        }

        try {
            $context = stream_context_create([
                'http' => [
                    'timeout'    => 5,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                ],
            ]);

            $html = @file_get_contents($url, false, $context);
            if (!$html) {
                return null;
            }

            // 1. Look for ISO 8601 duration in schema.org meta: itemprop="duration" content="PT01H04M12S"
            if (preg_match('/itemprop="duration"\s+content="([^"]+)"/i', $html, $m)) {
                $iso = $m[1];
                $seconds = self::parseIso8601Duration($iso);
                if ($seconds > 0) {
                    return self::formatSeconds($seconds);
                }
            }

            // 2. Look for full_duration or duration in ms in embedded hydration JSON
            if (preg_match('/"full_duration":\s*(\d+)/', $html, $m) || preg_match('/"duration":\s*(\d+)/', $html, $m)) {
                $ms = (int)$m[1];
                $seconds = (int)round($ms / 1000);
                if ($seconds > 0) {
                    return self::formatSeconds($seconds);
                }
            }
        } catch (\Throwable $e) {
            Log::warning("MediaDurationService: Failed fetching SoundCloud duration for {$url}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Parse ISO 8601 duration (e.g. PT01H04M12S, PT52M40S, PT1H20S) into seconds.
     *
     * @param string $iso
     * @return int
     */
    public static function parseIso8601Duration(string $iso): int
    {
        $seconds = 0;
        if (preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/i', $iso, $matches)) {
            $h = isset($matches[1]) && $matches[1] !== '' ? (int)$matches[1] : 0;
            $m = isset($matches[2]) && $matches[2] !== '' ? (int)$matches[2] : 0;
            $s = isset($matches[3]) && $matches[3] !== '' ? (int)$matches[3] : 0;
            $seconds = ($h * 3600) + ($m * 60) + $s;
        }
        return $seconds;
    }

    /**
     * Format seconds into "HH:MM:SS" or "MM:SS".
     *
     * @param int $seconds
     * @return string
     */
    public static function formatSeconds(int $seconds): string
    {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds % 60;

        if ($h > 0) {
            return sprintf('%d:%02d:%02d', $h, $m, $s);
        }

        return sprintf('%02d:%02d', $m, $s);
    }
}

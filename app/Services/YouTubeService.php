<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class YouTubeService
{
    /**
     * Fetch latest video uploads from official YouTube channel (Alm0neer1)
     *
     * @param int $limit
     * @return array
     */
    public static function getLatestChannelVideos(int $limit = 6): array
    {
        $allVideos = Cache::remember('youtube.channel.all_feed_videos', 3600, function () {
            try {
                $feedUrl = 'https://www.youtube.com/feeds/videos.xml?user=Alm0neer1';
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 4,
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AlmoneerNetwork/1.0',
                    ],
                ]);

                $xmlString = @file_get_contents($feedUrl, false, $context);
                if (!$xmlString) {
                    return [];
                }

                $xml = simplexml_load_string($xmlString);
                if (!$xml || !isset($xml->entry)) {
                    return [];
                }

                $namespaces = $xml->getNamespaces(true);
                $videos = [];
                $count = 0;

                foreach ($xml->entry as $entry) {
                    $yt = $entry->children($namespaces['yt'] ?? 'http://www.youtube.com/xml/schemas/2015');
                    $media = $entry->children($namespaces['media'] ?? 'http://search.yahoo.com/mrss/');

                    $videoId = (string)($yt->videoId ?? '');
                    if (empty($videoId)) {
                        continue;
                    }

                    $title = (string)$entry->title;
                    $published = (string)$entry->published;
                    $link = (string)($entry->link['href'] ?? "https://www.youtube.com/watch?v={$videoId}");
                    $thumbnail = "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";

                    $description = '';
                    if (isset($media->group) && isset($media->group->description)) {
                        $description = (string)$media->group->description;
                    }

                    $videos[] = (object)[
                        'id'           => $videoId,
                        'title'        => $title,
                        'published_at' => !empty($published) ? Carbon::parse($published) : Carbon::now(),
                        'url'          => $link,
                        'thumbnail'    => $thumbnail,
                        'description'  => Str::limit($description, 130),
                    ];

                    $count++;
                    if ($count >= 20) {
                        break;
                    }
                }

                return $videos;
            } catch (\Throwable $e) {
                return [];
            }
        }) ?? [];

        return array_slice($allVideos, 0, $limit);
    }
}

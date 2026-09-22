<?php

namespace App\Http\Controllers;

use App\Models\MediaItem;
use App\Models\Category;
use Illuminate\Http\Request;

class LectureController extends Controller
{
    // Canonical season mapping
    protected array $seasonMap = [
        'muharram'  => ['name' => 'محرم الحرام (عاشوراء)', 'short' => 'محرم', 'slug' => 'muharram', 'icon' => 'fa-moon', 'color' => 'amber'],
        'safar'     => ['name' => 'صفر الخير (الأربعين والوفيات)', 'short' => 'صفر', 'slug' => 'safar', 'icon' => 'fa-mosque', 'color' => 'emerald'],
        'ramadan'   => ['name' => 'شهر رمضان المبارك', 'short' => 'رمضان', 'slug' => 'ramadan', 'icon' => 'fa-star-and-crescent', 'color' => 'yellow'],
        'fatimiya'  => ['name' => 'موسم الأيام الفاطمية', 'short' => 'الفاطمية', 'slug' => 'fatimiya', 'icon' => 'fa-feather', 'color' => 'teal'],
        'general'   => ['name' => 'محاضرات ومناسبات عامة', 'short' => 'عامة', 'slug' => 'general', 'icon' => 'fa-book-open', 'color' => 'slate'],
    ];

    /**
     * Display all lectures & available Hijri years
     */
    public function index(Request $request)
    {
        $years = MediaItem::active()
            ->whereNotNull('hijri_year')
            ->distinct()
            ->orderBy('hijri_year', 'desc')
            ->pluck('hijri_year');

        if ($years->isEmpty()) {
            $years = collect(['1448', '1447', '1446']);
        }

        $selectedYear = $request->get('year');
        $selectedFormat = $request->get('format', 'all'); // all, video, audio, text
        $selectedTag = $request->get('tag');
        $search = $request->get('q');
        $viewMode = $request->get('view', 'grid'); // grid, list

        $query = MediaItem::active();

        if (!empty($selectedYear)) {
            $query->where('hijri_year', $selectedYear);
        }

        if (!empty($selectedTag)) {
            $query->withTag($selectedTag);
        }

        if ($selectedFormat === 'video') {
            $query->whereNotNull('media_url')->whereNotIn('media_url', ['', '#', 'pending', 'none']);
        } elseif ($selectedFormat === 'audio') {
            $query->where(function ($q) {
                $q->whereNotNull('soundcloud_url')->where('soundcloud_url', '!=', '')
                  ->orWhere('type', 'audio');
            });
        } elseif ($selectedFormat === 'text') {
            $query->where(function ($q) {
                $q->whereNotNull('transcript')->where('transcript', '!=', '')
                  ->orWhereNotNull('pdf_file');
            });
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%")
                  ->orWhere('transcript', 'like', "%{$search}%");
            });
        }

        $lectures = $query->orderBy('hijri_year', 'desc')
            ->orderByRaw('COALESCE(lecture_number, 999) ASC')
            ->orderBy('id', 'desc')
            ->paginate(12)
            ->withQueryString();

        // Build all years list with lecture counts
        $allYearsWithCounts = MediaItem::active()
            ->whereNotNull('hijri_year')
            ->select('hijri_year')
            ->selectRaw('count(*) as total')
            ->groupBy('hijri_year')
            ->orderBy('hijri_year', 'desc')
            ->get();

        $topYears = $years->take(3);
        $olderYears = $allYearsWithCounts->slice(3);

        // Build seasons data for the featured years cards
        $yearsData = [];
        foreach ($topYears as $yr) {
            $seasonsInYear = MediaItem::active()
                ->where('hijri_year', $yr)
                ->whereNotNull('season_slug')
                ->where('season_slug', '!=', '')
                ->select('season_slug', 'season')
                ->selectRaw('count(*) as count')
                ->groupBy('season_slug', 'season')
                ->orderByRaw("CASE 
                    WHEN season_slug = 'muharram' THEN 1 
                    WHEN season_slug = 'safar' THEN 2 
                    WHEN season_slug = 'ramadan' THEN 3 
                    WHEN season_slug = 'fatimiya' THEN 4 
                    ELSE 5 END")
                ->get();

            $yearsData[$yr] = $seasonsInYear;
        }

        return view('pages.lectures.index', compact(
            'lectures',
            'years',
            'allYearsWithCounts',
            'olderYears',
            'yearsData',
            'selectedYear',
            'selectedFormat',
            'selectedTag',
            'search',
            'viewMode'
        ));
    }

    /**
     * Display seasons within a specific Hijri year
     */
    public function year($year, Request $request)
    {
        $viewMode = $request->get('view', 'grid');
        $search = $request->get('q');
        $selectedTag = $request->get('tag');

        $seasons = MediaItem::active()
            ->where('hijri_year', $year)
            ->whereNotNull('season_slug')
            ->where('season_slug', '!=', '')
            ->select('season_slug', 'season')
            ->selectRaw('count(*) as lectures_count')
            ->groupBy('season_slug', 'season')
            ->orderByRaw("CASE 
                WHEN season_slug = 'muharram' THEN 1 
                WHEN season_slug = 'safar' THEN 2 
                WHEN season_slug = 'ramadan' THEN 3 
                WHEN season_slug = 'fatimiya' THEN 4 
                ELSE 5 END")
            ->get();

        $query = MediaItem::active()->where('hijri_year', $year);

        if (!empty($selectedTag)) {
            $query->withTag($selectedTag);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        $lectures = $query->orderByRaw('COALESCE(lecture_number, 999) ASC')
            ->orderBy('id', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('pages.lectures.year', compact('year', 'seasons', 'lectures', 'viewMode', 'search', 'selectedTag'));
    }

    /**
     * Display all lectures in a specific season of a year
     */
    public function season($year, $season, Request $request)
    {
        $viewMode = $request->get('view', 'grid');
        $search = $request->get('q');
        $selectedTag = $request->get('tag');

        // Resolve canonical season details
        $seasonInfo = $this->seasonMap[$season] ?? [
            'name'  => $season,
            'short' => $season,
            'slug'  => $season,
            'icon'  => 'fa-calendar-days',
            'color' => 'emerald'
        ];

        $query = MediaItem::active()
            ->where('hijri_year', $year)
            ->where(function ($q) use ($season) {
                $q->where('season_slug', $season)
                  ->orWhere('season', $season);
            });

        if (!empty($selectedTag)) {
            $query->withTag($selectedTag);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%")
                  ->orWhere('transcript', 'like', "%{$search}%");
            });
        }

        $lectures = $query->orderByRaw('COALESCE(lecture_number, 999) ASC')
            ->orderBy('id', 'asc')
            ->paginate(15)
            ->withQueryString();

        $totalLectures = MediaItem::active()
            ->where('hijri_year', $year)
            ->where(function ($q) use ($season) {
                $q->where('season_slug', $season)
                  ->orWhere('season', $season);
            })->count();

        // Other seasons in same year for sidebar/tab navigation
        $siblingSeasons = MediaItem::active()
            ->where('hijri_year', $year)
            ->whereNotNull('season_slug')
            ->where('season_slug', '!=', '')
            ->select('season_slug', 'season')
            ->selectRaw('count(*) as count')
            ->groupBy('season_slug', 'season')
            ->get();

        // Extract available tags for this season
        $allSeasonTags = MediaItem::active()
            ->where('hijri_year', $year)
            ->where(function ($q) use ($season) {
                $q->where('season_slug', $season)
                  ->orWhere('season', $season);
            })
            ->whereNotNull('tags')
            ->where('tags', '!=', '')
            ->pluck('tags');

        $availableTags = [];
        foreach ($allSeasonTags as $tagStr) {
            $parts = preg_split('/[,،|]+/u', $tagStr);
            foreach ($parts as $p) {
                $clean = trim($p);
                if (!empty($clean)) {
                    $availableTags[$clean] = ($availableTags[$clean] ?? 0) + 1;
                }
            }
        }
        arsort($availableTags);

        return view('pages.lectures.season', compact(
            'year',
            'season',
            'seasonInfo',
            'lectures',
            'totalLectures',
            'siblingSeasons',
            'availableTags',
            'selectedTag',
            'viewMode',
            'search'
        ));
    }

    /**
     * Show single lecture with full seasonal breadcrumbs and 3 formats
     */
    public function show($year, $season, $slug)
    {
        $video = MediaItem::active()
            ->where('slug', $slug)
            ->firstOrFail();

        $video->increment('views_count');

        $resolvedYear = $video->hijri_year ?? $year;
        $resolvedSeasonSlug = $video->season_slug ?? $season;
        $resolvedSeasonName = $video->season ?? ($this->seasonMap[$resolvedSeasonSlug]['name'] ?? 'الموسم العام');

        // Next & Previous lecture in this season
        $previousLecture = null;
        $nextLecture = null;

        if ($video->lecture_number) {
            $previousLecture = MediaItem::active()
                ->where('hijri_year', $resolvedYear)
                ->where('season_slug', $resolvedSeasonSlug)
                ->where('lecture_number', '<', $video->lecture_number)
                ->orderBy('lecture_number', 'desc')
                ->first();

            $nextLecture = MediaItem::active()
                ->where('hijri_year', $resolvedYear)
                ->where('season_slug', $resolvedSeasonSlug)
                ->where('lecture_number', '>', $video->lecture_number)
                ->orderBy('lecture_number', 'asc')
                ->first();
        }

        // Related lectures in this season
        $relatedVideos = MediaItem::active()
            ->where('hijri_year', $resolvedYear)
            ->where('season_slug', $resolvedSeasonSlug)
            ->where('id', '!=', $video->id)
            ->orderByRaw('COALESCE(lecture_number, 999) ASC')
            ->take(6)
            ->get();

        return view('pages.lectures.show', compact(
            'video',
            'resolvedYear',
            'resolvedSeasonSlug',
            'resolvedSeasonName',
            'previousLecture',
            'nextLecture',
            'relatedVideos'
        ));
    }

    /**
     * Convenience route for /lectures/{slug}
     */
    public function showBySlug($slug)
    {
        $video = MediaItem::active()->where('slug', $slug)->firstOrFail();
        $year = $video->hijri_year ?? '1448';
        $season = $video->season_slug ?? 'general';

        return redirect()->route('lectures.show', [
            'year'   => $year,
            'season' => $season,
            'slug'   => $slug
        ]);
    }

    /**
     * Short URL redirect: /l/{id} -> canonical lecture URL
     */
    public function shortRedirect($id)
    {
        $video = MediaItem::active()->where('id', $id)->firstOrFail();
        $year = $video->hijri_year ?? '1448';
        $season = $video->season_slug ?? 'general';

        return redirect()->route('lectures.show', [
            'year'   => $year,
            'season' => $season,
            'slug'   => $video->slug,
        ], 301);
    }
}


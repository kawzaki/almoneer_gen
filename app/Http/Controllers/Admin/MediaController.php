<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index()
    {
        $items = MediaItem::with('category')->latest()->paginate(15);
        return view('admin.media.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::where('module', 'media')->get();
        return view('admin.media.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'type'           => 'required|in:audio,video,short',
            'media_url'      => 'nullable|string',
            'soundcloud_url' => 'nullable|string|max:500',
            'audio_file'     => 'nullable|file|mimes:mp3,wav,m4a,ogg,aac,mp4|max:153600',
            'pdf_file'       => 'nullable|file|mimes:pdf|max:102400',
            'tags'           => 'nullable|string',
            'lecture_number' => 'nullable|integer|min:1|max:999',
        ]);

        $slug = Str::slug($request->title, '-', null) ?: 'media-' . time();
        if (MediaItem::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        // Infer Hijri year & season if not explicitly set
        $sy = $request->season_year ?? '';
        $title = $request->title ?? '';
        $year = '1448';
        if (preg_match('/(14\d\d)/', $sy, $m) || preg_match('/(14\d\d)/', $title, $m)) {
            $year = $m[1];
        }

        $season = 'محاضرات عامة';
        $seasonSlug = 'general';
        if (mb_strpos($sy, 'محرم') !== false || mb_strpos($sy, 'عاشوراء') !== false || mb_strpos($title, 'عاشوراء') !== false) {
            $season = 'محرم الحرام';
            $seasonSlug = 'muharram';
        } elseif (mb_strpos($sy, 'صفر') !== false || mb_strpos($title, 'الأربعين') !== false) {
            $season = 'صفر الخير';
            $seasonSlug = 'safar';
        } elseif (mb_strpos($sy, 'رمضان') !== false) {
            $season = 'شهر رمضان';
            $seasonSlug = 'ramadan';
        } elseif (mb_strpos($sy, 'فاطم') !== false) {
            $season = 'الأيام الفاطمية';
            $seasonSlug = 'fatimiya';
        }

        $num = $this->resolveLectureNumber($request, $title);

        $audioPath = null;
        if ($request->hasFile('audio_file')) {
            $file = $request->file('audio_file');
            $filename = 'audio_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/audio'), $filename);
            $audioPath = 'uploads/audio/' . $filename;
        }

        $pdfPath = null;
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = 'transcript_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pdf'), $filename);
            $pdfPath = 'uploads/pdf/' . $filename;
        }

        MediaItem::create([
            'title'          => $request->title,
            'slug'           => $slug,
            'category_id'    => $request->category_id,
            'type'           => $request->type,
            'media_url'      => $request->media_url,
            'soundcloud_url' => $request->soundcloud_url,
            'audio_file'     => $audioPath,
            'thumbnail'      => $request->thumbnail,
            'duration'       => $request->duration,
            'season_year'    => $request->season_year,
            'hijri_year'     => $year,
            'season'         => $season,
            'season_slug'    => $seasonSlug,
            'lecture_number' => $num,
            'description'    => $request->description,
            'tags'           => $request->tags,
            'transcript'     => $request->transcript,
            'pdf_file'       => $pdfPath,
            'is_featured'    => $request->boolean('is_featured'),
            'is_active'      => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.media.index')->with('success', 'تم حفظ المادة الإعلامية بنجاح!');
    }

    public function edit(MediaItem $medium)
    {
        $categories = Category::where('module', 'media')->get();
        return view('admin.media.edit', ['item' => $medium, 'categories' => $categories]);
    }

    public function update(Request $request, MediaItem $medium)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'type'           => 'required|in:audio,video,short',
            'media_url'      => 'nullable|string',
            'soundcloud_url' => 'nullable|string|max:500',
            'audio_file'     => 'nullable|file|mimes:mp3,wav,m4a,ogg,aac,mp4|max:153600',
            'pdf_file'       => 'nullable|file|mimes:pdf|max:102400',
            'tags'           => 'nullable|string',
            'lecture_number' => 'nullable|integer|min:1|max:999',
        ]);

        $updateData = [
            'title'          => $request->title,
            'category_id'    => $request->category_id,
            'type'           => $request->type,
            'media_url'      => $request->media_url,
            'soundcloud_url' => $request->soundcloud_url,
            'thumbnail'      => $request->thumbnail,
            'duration'       => $request->duration,
            'season_year'    => $request->season_year,
            'description'    => $request->description,
            'tags'           => $request->tags,
            'transcript'     => $request->transcript,
            'is_featured'    => $request->boolean('is_featured'),
            'is_active'      => $request->boolean('is_active', true),
        ];

        // Handle Audio file upload or removal
        if ($request->boolean('remove_audio_file')) {
            if ($medium->audio_file && file_exists(public_path($medium->audio_file))) {
                @unlink(public_path($medium->audio_file));
            }
            $updateData['audio_file'] = null;
        } elseif ($request->hasFile('audio_file')) {
            if ($medium->audio_file && file_exists(public_path($medium->audio_file))) {
                @unlink(public_path($medium->audio_file));
            }
            $file = $request->file('audio_file');
            $filename = 'audio_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/audio'), $filename);
            $updateData['audio_file'] = 'uploads/audio/' . $filename;
        }

        // Handle PDF file upload or removal
        if ($request->boolean('remove_pdf_file')) {
            if ($medium->pdf_file && file_exists(public_path($medium->pdf_file))) {
                @unlink(public_path($medium->pdf_file));
            }
            $updateData['pdf_file'] = null;
        } elseif ($request->hasFile('pdf_file')) {
            if ($medium->pdf_file && file_exists(public_path($medium->pdf_file))) {
                @unlink(public_path($medium->pdf_file));
            }
            $file = $request->file('pdf_file');
            $filename = 'transcript_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pdf'), $filename);
            $updateData['pdf_file'] = 'uploads/pdf/' . $filename;
        }

        // Update season metadata from season_year or title
        $sy = $request->season_year ?? '';
        $title = $request->title ?? '';
        $year = '1448';
        if (preg_match('/(14\d\d)/', $sy, $m) || preg_match('/(14\d\d)/', $title, $m)) {
            $year = $m[1];
        }

        $season = 'محاضرات عامة';
        $seasonSlug = 'general';
        if (mb_strpos($sy, 'محرم') !== false || mb_strpos($sy, 'عاشوراء') !== false || mb_strpos($title, 'عاشوراء') !== false) {
            $season = 'محرم الحرام';
            $seasonSlug = 'muharram';
        } elseif (mb_strpos($sy, 'صفر') !== false || mb_strpos($title, 'الأربعين') !== false) {
            $season = 'صفر الخير';
            $seasonSlug = 'safar';
        } elseif (mb_strpos($sy, 'رمضان') !== false) {
            $season = 'شهر رمضان';
            $seasonSlug = 'ramadan';
        } elseif (mb_strpos($sy, 'فاطم') !== false) {
            $season = 'الأيام الفاطمية';
            $seasonSlug = 'fatimiya';
        }

        $num = $this->resolveLectureNumber($request, $title);

        $updateData['hijri_year']     = $year;
        $updateData['season']         = $season;
        $updateData['season_slug']    = $seasonSlug;
        $updateData['lecture_number'] = $num;

        $medium->update($updateData);

        return redirect()->route('admin.media.index')->with('success', 'تم تحديث المادة الإعلامية بنجاح!');
    }

    private function resolveLectureNumber(Request $request, string $title): ?int
    {
        // 1. If explicitly specified in form
        if ($request->has('lecture_number')) {
            $raw = $request->input('lecture_number');
            if ($raw !== null && $raw !== '') {
                return (int) $raw;
            }
        }

        // 2. Try auto-detecting: 1448-02-19 format in title
        if (preg_match('/14\d\d-\d\d-(\d+)/', $title, $m)) {
            return (int) $m[1];
        }

        // 3. Try auto-detecting: الليلة 19 or ليلة 19 or محاضرة 19
        if (preg_match('/(?:الليلة|ليلة|محاضرة)\s*(\d+)/u', $title, $m)) {
            return (int) $m[1];
        }

        // 4. Try auto-detecting Arabic ordinal words
        $ordinals = [
            'الأولى' => 1, 'الاولى' => 1, 'الثانية' => 2, 'الثالثة' => 3, 'الرابعة' => 4,
            'الخامسة' => 5, 'السادسة' => 6, 'السابعة' => 7, 'الثامنة' => 8, 'التاسعة' => 9,
            'العاشرة' => 10, 'الحادية عشرة' => 11, 'الحادية عشر' => 11, 'الثانية عشرة' => 12,
            'الثانية عشر' => 12, 'الثالثة عشرة' => 13, 'الثالثة عشر' => 13, 'الرابعة عشرة' => 14,
            'الرابعة عشر' => 14, 'الخامسة عشرة' => 15, 'الخامسة عشر' => 15, 'السادسة عشرة' => 16,
            'السادسة عشر' => 16, 'السابعة عشرة' => 17, 'السابعة عشر' => 17, 'الثامنة عشرة' => 18,
            'الثامنة عشر' => 18, 'التاسعة عشرة' => 19, 'التاسعة عشر' => 19, 'العشرون' => 20,
            'العشرين' => 20
        ];
        foreach ($ordinals as $word => $number) {
            if (mb_strpos($title, 'الليلة ' . $word) !== false || mb_strpos($title, 'ليلة ' . $word) !== false) {
                return $number;
            }
        }

        return null;
    }

    public function destroy(MediaItem $medium)
    {
        $medium->delete();
        return redirect()->route('admin.media.index')->with('success', 'تم حذف المادة الإعلامية بنجاح!');
    }
}

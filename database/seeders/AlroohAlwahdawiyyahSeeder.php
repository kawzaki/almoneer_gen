<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MediaItem;
use App\Models\Category;
use Illuminate\Support\Str;

class AlroohAlwahdawiyyahSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 'video-lectures')->first() 
            ?? Category::where('name', 'like', '%المرئية%')->first()
            ?? Category::first();

        // Read raw transcript from database export file
        $rawFilePath = base_path('scratch/article_2093_raw.txt');
        $transcript = '';
        if (file_exists($rawFilePath)) {
            $transcript = file_get_contents($rawFilePath);
        }

        $pdfPath = 'transcripts/1441-01-05-alrooh-alwahdawiyyah.pdf';

        $mediaItem = MediaItem::updateOrCreate(
            ['slug' => 'الروح-الوحدوية-في-الشخصية-المحمدية'],
            [
                'category_id'    => $category ? $category->id : 3,
                'title'          => 'الروح الوحدوية في الشخصية المحمدية',
                'type'           => 'video',
                'media_url'      => 'https://www.youtube.com/watch?v=rDmMvfDQO7Y',
                'audio_file'     => 'https://www.almoneer.org/sounds/Moharm/1441/1441-01-05.mp3',
                'thumbnail'      => 'https://img.youtube.com/vi/rDmMvfDQO7Y/hqdefault.jpg',
                'duration'       => '1:05:22',
                'description'    => 'محاضرة الليلة الخامسة من محرم الحرام لعام 1441هـ - حسينية السنان بالقطيف: الروح الوحدوية في الشخصية المحمدية للعلامة السيد منير الخباز.',
                'tags'           => 'محرم 1441,الوحدة الإسلامية,المجتمع الرشيد,خطر الفتنة,السيرة النبوية,الرسول الأعظم',
                'transcript'     => $transcript,
                'pdf_file'       => $pdfPath,
                'season_year'    => 1441,
                'hijri_year'     => 1441,
                'season'         => 'محرم الحرام (عاشوراء)',
                'season_slug'    => 'muharram',
                'lecture_number' => 5,
                'is_featured'    => true,
                'is_active'      => true,
                'views_count'    => 1450,
            ]
        );

        $this->command->info("Lecture seeded successfully! ID: {$mediaItem->id}, Title: {$mediaItem->title}");
    }
}

<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Services\TranscriptFormatter;
use Illuminate\Database\Seeder;

class CleanArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Article::all() as $a) {
            // 1. Clean title of any unwanted title like "العلامة"
            $title = str_replace('العلامة المنير', 'سماحة السيد منير الخباز', $a->title);
            $title = str_replace('العلامة', '', $title);
            $title = preg_replace('/\s{2,}/u', ' ', trim($title));

            // 2. Clean content
            $content = $a->content;

            // Specifically for Bio (type=bio or ID=3): tag known standalone section titles
            if ($a->type === 'bio' || $a->id === 3) {
                $content = preg_replace('/(?<=^|\n)[ \t]*(?:<p[^>]*>)?\s*(مقدمة|الأسرة الكريمة|الولادة ومرحلة الطفولة|الالتحاق بالحوزة|الهجرة إلى النجف الأشرف|الدراسة في قم المقدسة|أبرز أساتذته في البحث الخارج|المشروع الفكري والتبليغي|المؤلفات والآثار)\s*(?:<\/p>)?(?=\n|$)/u', "\n\n[t]$1[/t]\n\n", $content);
            }

            // Remove "العلامة" from content
            $content = str_replace('العلامة المنير', 'سماحة السيد منير الخباز', $content);
            $content = str_replace('العلامة', '', $content);

            // Process through TranscriptFormatter / المخمة
            $processed = TranscriptFormatter::process($content);
            $cleanText = $processed['clean_text'];

            // Update article
            $a->title = $title;
            $a->content = $cleanText;
            if ($a->summary) {
                $a->summary = TranscriptFormatter::cleanInlineText(strip_tags($a->summary));
            }
            $a->save();
        }
    }
}

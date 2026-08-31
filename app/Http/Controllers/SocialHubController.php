<?php

namespace App\Http\Controllers;

use App\Services\YouTubeService;

class SocialHubController extends Controller
{
    public function index()
    {
        // 1. YouTube Official Channel (Last 3 videos from Alm0neer1)
        $youtubePosts = YouTubeService::getLatestChannelVideos(3);

        // 2. Instagram Official Account (Last 3 posts & reels)
        $instagramPosts = [
            (object)[
                'title' => 'قبس نوراني: "جوهر العبادة هو التحرر من أسر الأنا"',
                'type' => 'ريلز إنستغرام',
                'url' => 'https://www.instagram.com/almoneerorg',
                'image' => 'images/sayyid-muneer-portrait.jpg',
                'date' => 'منذ يومين',
                'likes' => '1.4K',
                'summary' => 'مقطع مرئي مصمم يبرز المعاني الروحية والأخلاقية في تهذيب النفس والسلوك إلى الله تعالى.',
            ],
            (object)[
                'title' => 'بطاقة إنفوجرافيك: مقومات التماسك الأسري في فكر أهل البيت (ع)',
                'type' => 'منشور وتصميم',
                'url' => 'https://www.instagram.com/almoneerorg',
                'image' => null,
                'date' => 'منذ 4 أيام',
                'likes' => '980',
                'summary' => 'إنفوجرافيك يلخص أهم النقاط التوجيهية في بناء المودة والرحمة داخل المحيط الأسري.',
            ],
            (object)[
                'title' => 'ريلز: كيف يتعامل المؤمن مع الشبهات الفكرية المعاصرة؟',
                'type' => 'ريلز إنستغرام',
                'url' => 'https://www.instagram.com/almoneerorg',
                'image' => null,
                'date' => 'منذ أسبوع',
                'likes' => '2.1K',
                'summary' => 'إضاءة مركزة في دقيقة واحدة حول التسلح بالبرهان والرجوع لأهل الاختصاص.',
            ],
        ];

        // 3. TikTok Official Account (Last 3 youth clips)
        $tiktokPosts = [
            (object)[
                'title' => 'دقيقة معرفية: هل يتعارض العلم الحديث مع الإيمان بالغيب؟',
                'type' => 'فيديو تيك توك',
                'url' => 'https://www.tiktok.com/@almoneerorg',
                'duration' => '00:58',
                'views' => '18.5K',
                'date' => 'منذ 3 أيام',
                'summary' => 'إجابة موجزة وعميقة موجهة للشباب حول حدود المنهج التجريبي والتكامل المعرفي.',
            ],
            (object)[
                'title' => 'ومضة: ثلاث وصايا للشباب الجامعي قبل بدء العام الدراسي',
                'type' => 'فيديو تيك توك',
                'url' => 'https://www.tiktok.com/@almoneerorg',
                'duration' => '01:15',
                'views' => '24.1K',
                'date' => 'منذ 5 أيام',
                'summary' => 'توجيهات قيمة في الجمع بين التفوق الأكاديمي والتحصين الفكري والأخلاقي.',
            ],
            (object)[
                'title' => 'حوار سريع: كيف نحافظ على صفاء الروح وسط صخب العالم الرقمي؟',
                'type' => 'فيديو تيك توك',
                'url' => 'https://www.tiktok.com/@almoneerorg',
                'duration' => '01:05',
                'views' => '15.3K',
                'date' => 'منذ أسبوع',
                'summary' => 'نصائح عملية في ضبط استخدام وسائل التواصل وإعطاء الروح حقها من السكينة.',
            ],
        ];

        return view('pages.social_hub', compact('youtubePosts', 'instagramPosts', 'tiktokPosts'));
    }
}

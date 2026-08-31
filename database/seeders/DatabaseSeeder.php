<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Article;
use App\Models\WeeklyWisdom;
use App\Models\MediaItem;
use App\Models\Book;
use App\Models\Poem;
use App\Models\GalleryAlbum;
use App\Models\GalleryItem;
use App\Models\Inquiry;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin User
        User::updateOrCreate(
            ['email' => 'admin@almoneer.org'],
            [
                'name'     => 'مدير شبكة المنير',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role'     => 'super_admin',
            ]
        );

        // 2. Site Settings
        $settings = [
            'site.title'           => 'شبكة سماحة العلامة السيد منير الخباز',
            'site.subtitle'        => 'الموقع العام والفكري الرسمي',
            'site.hadith'          => 'لا يزال المرء عالماً ما طلب العلم، فإذا ظن أنه قد علم فقد جهل',
            'site.theme'           => 'almoneer-emerald',
            'site.email'           => 'info@almoneer.org',
            'site.phone'           => '+966 13 855 0000',
            'site.address'         => 'النجف الأشرف / القطيف',
            'social.youtube'       => 'https://youtube.com/@almoneerorg',
            'social.instagram'     => 'https://instagram.com/almoneerorg',
            'social.tiktok'        => 'https://tiktok.com/@almoneerorg',
            'social.twitter'       => 'https://x.com/almoneerorg',
            'social.telegram'      => 'https://t.me/almoneerorg',
            'livestream.is_live'   => '0',
            'livestream.title'     => 'البث المباشر لمحاضرات سماحة العلامة السيد منير الخباز',
            'livestream.url'       => 'https://www.youtube.com/embed/live_stream',
        ];

        foreach ($settings as $key => $val) {
            Setting::updateOrCreate(['key' => $key], ['value' => $val]);
        }

        // 3. Weekly Wisdom (كلمة الأسبوع وحكمة أمير المؤمنين)
        WeeklyWisdom::updateOrCreate(
            ['quote' => 'عليك بالرضا في الشدة والرخاء'],
            [
                'title'     => 'من حكم أمير المؤمنين (عليه السلام)',
                'source'    => 'الإمام علي بن أبي طالب (ع)',
                'is_active' => true,
            ]
        );

        // 4. Categories
        $catNews = Category::firstOrCreate(['slug' => 'news-activities'], [
            'name' => 'أخبار ونشاطات', 'module' => 'article'
        ]);

        $catMediaAudio = Category::firstOrCreate(['slug' => 'audio-lectures'], [
            'name' => 'المحاضرات الصوتية', 'module' => 'media'
        ]);

        $catMediaVideo = Category::firstOrCreate(['slug' => 'video-lectures'], [
            'name' => 'المحاضرات المرئية', 'module' => 'media'
        ]);

        $catMediaShorts = Category::firstOrCreate(['slug' => 'shorts-reels'], [
            'name' => 'قبسات وريلز (Shorts)', 'module' => 'media'
        ]);

        $catBooks = Category::firstOrCreate(['slug' => 'intellectual-books'], [
            'name' => 'الفكر والدراسات الإسلامية', 'module' => 'book'
        ]);

        $catPoems = Category::firstOrCreate(['slug' => 'karbala-elegies'], [
            'name' => 'مراثي عاشوراء والطف', 'module' => 'poem'
        ]);

        $catInquiries = Category::firstOrCreate(['slug' => 'doctrine-inquiries'], [
            'name' => 'المسائل العقائدية والفكرية', 'module' => 'inquiry'
        ]);

        // 5. Featured Articles & News
        Article::firstOrCreate(['slug' => 'ashura-1447-titles'], [
            'category_id'  => $catNews->id,
            'title'        => 'عناوين محاضرات سماحة العلامة المنير لموسم عاشوراء 1447هـ',
            'summary'      => 'ستكون محاضرات سماحة العلامة السيد منير الخباز في المركز الإسلامي في الولايات المتحدة الأمريكية، ديربورن، تحت عنوان المنظومة الأخلاقية وتحديات المعاصرة.',
            'content'      => '<p>تتناول سلسلة المحاضرات لهذا العام أهم القضايا الفكرية والتربوية المعاصرة التي تواجه الأجيال الصاعدة، مع تفكيك الرؤى المادية المعاصرة وتقديم المعالجة المستوحاة من النهضة الحسينية المباركة.</p>',
            'type'         => 'news',
            'is_featured'  => true,
            'is_active'    => true,
            'published_at' => now(),
        ]);

        Article::firstOrCreate(['slug' => 'london-imam-ali-foundation-visit'], [
            'category_id'  => $catNews->id,
            'title'        => 'لندن: تستضيف مؤسسة الإمام علي (ع) العلامة المنير في العشرة الثانية',
            'summary'      => 'تقام المحاضرات الفكرية في العاصمة البريطانية لندن بمشاركة الجالية الإسلامية والمهتمين بالدراسات الدينية.',
            'content'      => '<p>ضمن الجولة التبليغية السنوية لسماحته في المملكة المتحدة، تتضمن الجولة ندوات حوارية مع الشباب والأكاديميين حول قضايا الفكر الإسلامي الحديث.</p>',
            'type'         => 'activity',
            'is_featured'  => false,
            'is_active'    => true,
            'published_at' => now()->subDays(3),
        ]);

        // Biography Article
        Article::firstOrCreate(['slug' => 'biography-sayyid-muneer'], [
            'title'        => 'السيرة الذاتية والعلمية لسماحة العلامة السيد منير الخباز',
            'summary'      => 'إطلالة مفصلة على مسيرة الطلب العلمي، أساتذة البحث الخارج، إجازات الاجتهاد، والمشروع الفكري والتبليغي الرائد.',
            'content'      => '<p>ولد سماحة العلامة السيد منير بن السيد عدنان الخباز في مدينة القطيف، وهاجر إلى النجف الأشرف في سن مبكرة لتلقي العلوم الحوزوية الشريفة على أيدي كبار المراجع العظام كالمرجع الديني الأعلى السيد علي السيستاني، والسيد الخوئي (قدس سره)، والشيخ التبريزي، والوحيد الخراساني...</p>',
            'type'         => 'bio',
            'is_featured'  => true,
            'is_active'    => true,
            'published_at' => now(),
        ]);

        // 6. Media Items (Audios & Videos)
        MediaItem::firstOrCreate(['slug' => 'marital-relationship-emotional-wealth'], [
            'category_id'  => $catMediaAudio->id,
            'title'        => 'العلاقة الزوجية والثراء العاطفي',
            'type'         => 'audio',
            'media_url'    => 'https://ia800300.us.archive.org/1/items/test-audio-sample/sample.mp3',
            'duration'     => '42:15',
            'season_year'  => 'الموسم العام',
            'description'  => 'محاضرة اجتماعية وأخلاقية تعالج مقومات الاستقرار الأسري وبناء الدفء العاطفي في ظل ضغوط الحياة الحديثة.',
            'is_featured'  => true,
            'is_active'    => true,
        ]);

        MediaItem::firstOrCreate(['slug' => 'spiritual-stability-intuitive-knowledge'], [
            'category_id'  => $catMediaAudio->id,
            'title'        => 'المعرفة الوجدانية ودورها في الاستقرار الروحي',
            'type'         => 'audio',
            'media_url'    => 'https://ia800300.us.archive.org/1/items/test-audio-sample/sample.mp3',
            'duration'     => '38:40',
            'season_year'  => 'رمضان 1446هـ',
            'description'  => 'سلسلة قبسات قرآنية تبين عمق العلاقة بين الفطرة الإنسانية والسكينة النفسية.',
            'is_featured'  => false,
            'is_active'    => true,
        ]);

        MediaItem::firstOrCreate(['slug' => 'ashura-10th-night-steadfastness'], [
            'category_id'  => $catMediaVideo->id,
            'title'        => 'حوار حول المقارنة بين دعاء كميل ودعاء أبي حمزة الثمالي',
            'type'         => 'video',
            'media_url'    => 'https://www.youtube.com/watch?v=HfBND4_sOjE',
            'duration'     => '54:10',
            'season_year'  => 'صفر 1448هـ',
            'description'  => 'حوار فكري وأخلاقي مع سماحة العلامة السيد منير الخباز في بيان أبعاد المقارنة بين مضامين دعاء كميل ودعاء أبي حمزة الثمالي.',
            'is_featured'  => true,
            'is_active'    => true,
        ]);

        MediaItem::firstOrCreate(['slug' => 'ashura-educational-necessity'], [
            'category_id'  => $catMediaVideo->id,
            'title'        => 'الشعائر ضرورة إعلامية وتربوية',
            'type'         => 'video',
            'media_url'    => 'https://www.youtube.com/watch?v=AG8AJJMXX7s',
            'duration'     => '48:25',
            'season_year'  => 'صفر 1448هـ',
            'description'  => 'محاضرة تبين موقع الشعائر الحسينية في صياغة الوعي الجمعي والتربية القيمية للأجيال.',
            'is_featured'  => true,
            'is_active'    => true,
        ]);

        MediaItem::firstOrCreate(['slug' => 'reels-overcoming-anxiety'], [
            'category_id'  => $catMediaShorts->id,
            'title'        => 'تعقيبًا على عزاء أهالي القطيف بمراسم الأربعين',
            'type'         => 'short',
            'media_url'    => 'https://www.youtube.com/watch?v=I0eyOI67Ihg',
            'duration'     => '02:40',
            'season_year'  => 'مقتطفات فكرية',
            'description'  => 'كلمة توجيهية وومضة مباركة لسماحة العلامة السيد منير الخباز.',
            'is_featured'  => false,
            'is_active'    => true,
        ]);

        MediaItem::firstOrCreate(['slug' => 'reels-alamatal-zohour'], [
            'category_id'  => $catMediaShorts->id,
            'title'        => 'ماهو موقفنا من علامات الظهور؟',
            'type'         => 'short',
            'media_url'    => 'https://www.youtube.com/watch?v=OoQxy0Pnmoc',
            'duration'     => '03:15',
            'season_year'  => 'مقتطفات فكرية',
            'description'  => 'تأصيل عقائدي ومنهجي في التعامل مع روايات علامات الظهور وتطبيقها على الواقع المعاصر.',
            'is_featured'  => false,
            'is_active'    => true,
        ]);

        // 7. Poetry (الشعر)
        Poem::firstOrCreate(['slug' => 'poem-call-of-eternity'], [
            'category_id' => $catPoems->id,
            'title'       => 'نداء الخلود - في رثاء سيد الشهداء (عليه السلام)',
            'occasion'    => 'موسم عاشوراء الحسين (ع)',
            'meter'       => 'بحر البسيط',
            'verses'      => "قف بالطفوف وجُد بالدمع منسكبا | والثم تراباً به نبل الهدى انسكبا\nس سقى ضريحك يا بن المصطفى ديمٌ | تهمي على رمسك الوضاح ما اغتربا\nأنت الحسين ونور الله مشعله | ما غاب ضوؤك أنى أظلم الحِقبا\nفالدهر يركع في محراب نهضتكم | والمجد سطّر في أفق العلى أدبا",
            'description' => 'من نفحات ديوان سماحة السيد منير الخباز في التعبير عن خلود الفداء الحسيني وتجدد جذوة الرسالة المحمدية.',
            'is_featured' => true,
            'is_active'   => true,
        ]);

        // 8. Books (كتب ومؤلفات)
        Book::firstOrCreate(['slug' => 'dialogue-with-atheists'], [
            'category_id'       => $catBooks->id,
            'title'             => 'حوار مع الملحدين - رؤية برهانية وعقلية',
            'author'            => 'سماحة العلامة السيد منير الخباز',
            'publisher'         => 'دار المحجة البيضاء',
            'publication_year'  => '2024',
            'pages_count'       => 360,
            'isbn'              => '978-9953-0-5843-1',
            'summary'           => 'دراسة فكرية نقدية تناقش الشبهات الإلحادية المعاصرة بأسلوب فلسفي وعلمي معاصر رصين.',
            'table_of_contents' => "المقدمة\nالفصل الأول: مناشئ الإلحاد المعاصر\nالفصل الثاني: البراهين الفلسفية على إثبات الصانع\nالفصل الثالث: نقد النظرية المادية للكون\nالخاتمة والنتائج",
            'is_featured'       => true,
            'is_active'         => true,
        ]);

        Book::firstOrCreate(['slug' => 'intellectual-horizons'], [
            'category_id'       => $catBooks->id,
            'title'             => 'آفاق فكرية - قراءات في المعرفة الإسلامية',
            'author'            => 'سماحة العلامة السيد منير الخباز',
            'publisher'         => 'مؤسسة الرافد للمطبوعات',
            'publication_year'  => '2023',
            'pages_count'       => 280,
            'summary'           => 'بحوث متقدمة في فلسفة الدين وحرية الإرادة وفلسفة الأخلاق.',
            'is_featured'       => true,
            'is_active'         => true,
        ]);

        // 9. Photo Gallery
        $album = GalleryAlbum::firstOrCreate(['slug' => 'ashura-season-gallery'], [
            'title'       => 'الموسم العاشورائي - تغطية المجالس والمحاضرات',
            'description' => 'لقطات حصرية لجموع المستمعين والمهتمين خلال المجالس المركزية لسماحة السيد.',
            'event_date'  => '2025-07-20',
            'is_active'   => true,
        ]);

        GalleryItem::firstOrCreate([
            'album_id'   => $album->id,
            'image_path' => 'assets/images/gallery-1.jpg',
            'caption'    => 'سماحة العلامة السيد منير الخباز ملقياً خطابه الفكري والعاشورائي'
        ]);

        // 10. Inquiries (استفسارات وفتاوى)
        Inquiry::firstOrCreate(['tracking_code' => 'INQ-1447-001'], [
            'category_id'   => $catInquiries->id,
            'name'          => 'أبو باقر - بريطانيا',
            'email'         => 'inquiry1@example.com',
            'country'       => 'المملكة المتحدة',
            'question'      => 'كيف يمكن للشباب المغترب في الغرب تحصين عقيدتهم وهويتهم الدينية في ظل انتشار النزعات النسبية والمادية؟',
            'answer'        => 'ينبغي التركيز على ركيزتين: الأولى: بناء الوعي الفكري الممنهج من خلال دراسة الأسس البرهانية للعقيدة وعدم الاكتفاء بالتلقين، والثانية: الارتباط بالبيئة الإيمانية والمراكز الإسلامية الرصينة ومجالس أهل البيت (ع) التي تبث الروح وتغذي الوجدان بالقيم.',
            'status'        => 'answered',
            'is_published'  => true,
            'answered_at'   => now()->subDays(2),
        ]);
    }
}

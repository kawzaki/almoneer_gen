<?php

namespace App\Services;

class TranscriptFormatter
{
    /**
     * Clean raw transcript text based on Almoneer Vacum rules.
     * (المخمة - تنظيف النص القادم من Word)
     */
    public static function clean(string $text): string
    {
        if (empty(trim($text))) {
            return '';
        }

        // 1. علامات الترقيم والتنصيص
        $text = str_replace(',', '،', $text);
        $text = preg_replace('/،\s*/u', '، ', $text);
        $text = preg_replace('/\.\s*/u', '. ', $text);
        $text = preg_replace('/:\s*/u', ': ', $text);
        $text = preg_replace('/؛\s*/u', '؛ ', $text);
        $text = preg_replace('/؟\s*/u', '؟ ', $text);
        $text = preg_replace('/!\s*/u', '! ', $text);

        // الأقواس المزدوجة
        $text = str_replace('((', '«', $text);
        $text = str_replace('))', '»', $text);
        $text = preg_replace('/«\s*/u', ' «', $text);
        $text = preg_replace('/\s*»/u', '» ', $text);

        // علامات الاقتباس
        $text = preg_replace('/"([^"]+?)"/u', ' ”$1“ ', $text);

        // الآيات القرآنية: توحيد الأقواس
        $text = str_replace('﴿', '{', $text);
        $text = str_replace('﴾', '}', $text);

        // الشرطات
        $text = str_replace('–', '-', $text);
        $text = preg_replace('/(?<!\w)-(?!\w)/u', ' - ', $text);

        // 2. ضبط الأرقام من الهندية إلى العربية القياسية
        $easternDigits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $standardDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $text = str_replace($easternDigits, $standardDigits, $text);

        // 3. إزالة الفراغات والمسافات الفاسدة
        $text = preg_replace('/[\t]+/u', ' ', $text);
        $text = preg_replace('/[ ]{2,}/u', ' ', $text);
        $text = preg_replace('/^[ \t]+|[ \t]+$/um', '', $text);
        $text = preg_replace('/\n[ \t]+/u', "\n", $text);
        $text = preg_replace('/[ \t]+\n/u', "\n", $text);

        // 4. ضبط العناوين @@عنوان@@
        $text = preg_replace('/@@(.*?)@@/u', "\n[t]$1[/t]\n", $text);
        $text = preg_replace('/@@(.*?)\n/u', "\n[t]$1[/t]\n", $text);

        // 5. ضبط الاختصارات والأدعية والصلوات (قواعد المخمة الأصلية)
        $text = str_replace('ـ', '', $text); // إزالة التطويل
        $text = preg_replace('/\sه\s/u', ' هـ ', $text);
        $text = preg_replace('/\sه\./u', ' هـ.', $text);
        $text = preg_replace('/\sه،/u', ' هـ،', $text);
        $text = preg_replace('/\sه:/u', ' هـ:', $text);
        $text = preg_replace('/([0-9]+)ه(?!\w)/u', '$1 هـ ', $text);

        // الصلوات والأدعية
        $replacements = [
            '«ص»'                       => '(ص)',
            '«ع»'                       => '(ع)',
            '«عع»'                      => '(عع)',
            '«عه»'                      => '(عه)',
            '«عم»'                      => '(عم)',
            '"ص"'                       => '(ص)',
            '"ع"'                       => '(ع)',
            '"عع"'                      => '(عع)',
            '"عه"'                      => '(عه)',
            '"عم"'                      => '(عم)',
            '”ص“'                       => '(ص)',
            '”ع“'                       => '(ع)',
            '”عع“'                      => '(عع)',
            '”عه“'                      => '(عه)',
            '”عم“'                      => '(عم)',
            '«صلى الله عليه وآله وسلم»' => '(ص)',
            '"صلى الله عليه وآله وسلم"' => '(ص)',
            '”صلى الله عليه وآله وسلم“' => '(ص)',
            'صلى الله عليه وآله وسلم'   => '(ص)',
            '«صلى الله عليه وآله»'      => '(ص)',
            '"صلى الله عليه وآله"'      => '(ص)',
            '”صلى الله عليه وآله“'      => '(ص)',
            'صلى الله عليه وآله'        => '(ص)',
            '«عليه السلام»'             => '(ع)',
            '"عليه السلام"'             => '(ع)',
            'عليه السلام'               => '(ع)',
            '«عليهم السلام»'            => '(عع)',
            '"عليهم السلام"'            => '(عع)',
            'عليهم السلام'              => '(عع)',
            '«عليها السلام»'            => '(عه)',
            '"عليها السلام"'            => '(عه)',
            'عليها السلام'              => '(عه)',
        ];
        $text = str_replace(array_keys($replacements), array_values($replacements), $text);

        // 6. تشذيب علامات الترقيم والفراغات بعد التعديل
        $text = str_replace('. .', '..', $text);
        $text = str_replace('، ،', '،،', $text);
        $text = preg_replace('/\s+،/u', '،', $text);
        $text = preg_replace('/\s+\./u', '.', $text);
        $text = preg_replace('/\s+:/u', ':', $text);
        $text = preg_replace('/\s+؛/u', '؛', $text);
        $text = preg_replace('/\s+؟/u', '؟', $text);
        $text = preg_replace('/\s+!/u', '!', $text);
        $text = preg_replace('/«\s+/u', '«', $text);
        $text = preg_replace('/\s+»/u', '»', $text);
        $text = preg_replace('/\{\s+/u', '{', $text);
        $text = preg_replace('/\s+\}/u', '}', $text);

        // متفرقات: اتصال حرف العطف (و)
        $text = preg_replace('/\sو\s/u', ' و', $text);
        $text = preg_replace('/\nو\s/u', "\nو", $text);
        $text = preg_replace('/^و\s/um', 'و', $text);

        // ضبط الهوامش [1] -> (1)
        $text = preg_replace('/\[\s*(\d+)\s*\]/u', '($1)', $text);

        // تصحيح الأرقام العشرية
        $text = preg_replace('/(\d+\.)\s+(\d+)/u', '$1$2', $text);

        // ضبط الأسطر والفراغات النهائية
        $text = preg_replace("/\n{3,}/u", "\n\n", $text);

        return trim($text);
    }

    /**
     * Render the transcript to rich, scholarly HTML with Uthmanic font for Quranic verses.
     */
    public static function format(?string $text): string
    {
        if (empty(trim($text ?? ''))) {
            return '<p class="text-slate-500 italic">لا يوجد تفريغ نصي متوفر لهذه المحاضرة حالياً.</p>';
        }

        $isHtml = (bool) preg_match('/<\s*(?:p|div|h[1-6]|ul|ol|li|blockquote)\b/i', $text);

        if ($isHtml) {
            $formatted = $text;

            // 1. استبدال العناوين [t]عنوان[/t] إذا وجدت
            // 1. استبدال العناوين [t]عنوان[/t]
            $formatted = preg_replace_callback('/\[t\](.*?)\[\/t\]/u', function ($matches) {
                $title = trim($matches[1]);
                return '<h3 class="text-lg sm:text-xl font-bold font-scholarly text-red-800 border-r-4 border-red-600 pr-3.5 mt-8 mb-4 pt-1 leading-snug drop-shadow-sm">' . e($title) . '</h3>';
            }, $formatted);

            // استبدال العناوين والصفات الفرعية [c]...[/c]
            $formatted = preg_replace_callback('/\[c\](.*?)\[\/c\]/u', function ($matches) {
                return '<span class="text-amber-800 font-bold font-scholarly inline-block my-2" style="color: #8B4513;">' . e(trim($matches[1])) . '</span>';
            }, $formatted);

            // 2. استبدال الآيات القرآنية {الآية} أو ﴿الآية﴾ بالخط العثماني
            $formatted = preg_replace_callback('/(?:\{|﴿)([^}﴾]+)(?:\}|﴾)/u', function ($matches) {
                $verse = trim($matches[1]);
                return '<span class="quran-verse font-quran text-red-900 font-normal" style="color: #BB1111;">﴿ ' . e($verse) . ' ﴾</span>';
            }, $formatted);

            // 3. استبدال مراجع السور والآيات [المؤمنون : 115] أو [البقرة: 2]
            $formatted = preg_replace_callback('/\[([\p{Arabic}\s]+:\s*\d+)\]/u', function ($matches) {
                return '<span class="quran-ref inline-flex items-center gap-1 font-sans text-xs bg-gold-50 text-gold-700 px-2 py-0.5 rounded-md border border-gold-200 font-semibold">' . e($matches[0]) . '</span>';
            }, $formatted);

            // 4. استبدال الصلوات والأدعية المختصرة (ص)، (ع)، (عع)، (عه)
            $formatted = preg_replace('/(?:\(|«)(ص)(?:\)|»)/u', '<span class="text-gold-600 font-bold text-xs px-0.5" title="صلى الله عليه وآله وسلم">(ص)</span>', $formatted);
            $formatted = preg_replace('/(?:\(|«)(ع)(?:\)|»)/u', '<span class="text-gold-600 font-bold text-xs px-0.5" title="عليه السلام">(ع)</span>', $formatted);
            $formatted = preg_replace('/(?:\(|«)(عع)(?:\)|»)/u', '<span class="text-gold-600 font-bold text-xs px-0.5" title="عليهم السلام">(عع)</span>', $formatted);
            $formatted = preg_replace('/(?:\(|«)(عه)(?:\)|»)/u', '<span class="text-gold-600 font-bold text-xs px-0.5" title="عليها السلام">(عه)</span>', $formatted);

            // 5. استبدال علامات التنصيص المقتبسة «...» بتنسيق بارز
            $formatted = preg_replace('/«([^»]+)»/u', '<span class="text-emerald-900 font-medium font-scholarly px-0.5">«$1»</span>', $formatted);

            return $formatted;
        }

        // Clean first if not already formatted
        $cleaned = self::clean($text);

        // 1. استبدال العناوين [t]عنوان[/t]
        $cleaned = preg_replace_callback('/\[t\](.*?)\[\/t\]/u', function ($matches) {
            $title = trim($matches[1]);
            return '<h3 class="text-lg sm:text-xl font-bold font-scholarly text-red-800 border-r-4 border-red-600 pr-3.5 mt-8 mb-4 pt-1 leading-snug drop-shadow-sm">' . e($title) . '</h3>';
        }, $cleaned);

        // استبدال العناوين والصفات الفرعية [c]...[/c]
        $cleaned = preg_replace_callback('/\[c\](.*?)\[\/c\]/u', function ($matches) {
            return '<span class="text-amber-800 font-bold font-scholarly inline-block my-2" style="color: #8B4513;">' . e(trim($matches[1])) . '</span>';
        }, $cleaned);

        // 2. استبدال الآيات القرآنية {الآية} أو ﴿الآية﴾ بالخط العثماني
        $cleaned = preg_replace_callback('/(?:\{|﴿)([^}﴾]+)(?:\}|﴾)/u', function ($matches) {
            $verse = trim($matches[1]);
            return '<span class="quran-verse font-quran text-red-900 font-normal" style="color: #BB1111;">﴿ ' . e($verse) . ' ﴾</span>';
        }, $cleaned);

        // 3. استبدال مراجع السور والآيات [المؤمنون : 115] أو [البقرة: 2]
        $cleaned = preg_replace_callback('/\[([\p{Arabic}\s]+:\s*\d+)\]/u', function ($matches) {
            return '<span class="quran-ref inline-flex items-center gap-1 font-sans text-xs bg-gold-50 text-gold-700 px-2 py-0.5 rounded-md border border-gold-200 font-semibold">' . e($matches[0]) . '</span>';
        }, $cleaned);

        // 4. استبدال الصلوات والأدعية المختصرة (ص)، (ع)، (عع)، (عه) بأيقونات ورموز وقورة
        $cleaned = preg_replace('/(?:\(|«)(ص)(?:\)|»)/u', '<span class="text-gold-600 font-bold text-xs px-0.5" title="صلى الله عليه وآله وسلم">(ص)</span>', $cleaned);
        $cleaned = preg_replace('/(?:\(|«)(ع)(?:\)|»)/u', '<span class="text-gold-600 font-bold text-xs px-0.5" title="عليه السلام">(ع)</span>', $cleaned);
        $cleaned = preg_replace('/(?:\(|«)(عع)(?:\)|»)/u', '<span class="text-gold-600 font-bold text-xs px-0.5" title="عليهم السلام">(عع)</span>', $cleaned);
        $cleaned = preg_replace('/(?:\(|«)(عه)(?:\)|»)/u', '<span class="text-gold-600 font-bold text-xs px-0.5" title="عليها السلام">(عه)</span>', $cleaned);

        // 5. استبدال علامات التنصيص المقتبسة «...» أو ”...“ بتنسيق بارز
        $cleaned = preg_replace('/«([^»]+)»/u', '<span class="text-emerald-900 font-medium font-scholarly px-0.5">«$1»</span>', $cleaned);

        // 6. تقسيم الفقرات على فواصل الأسطر المزدوجة
        $paragraphs = preg_split("/\n\s*\n/u", $cleaned);
        $html = '';

        foreach ($paragraphs as $p) {
            $p = trim($p);
            if (empty($p)) continue;

            // إذا كانت الفقرة عنواناً أصلاً
            if (str_starts_with($p, '<h3')) {
                $html .= $p . "\n";
            } else {
                // فحص القوائم النقطية
                $lines = array_filter(array_map('trim', explode("\n", $p)));
                $isList = count($lines) > 1 && count(array_filter($lines, fn($l) => str_starts_with($l, '- ') || str_starts_with($l, '• '))) === count($lines);
                if ($isList) {
                    $html .= '<ul class="list-disc pr-6 space-y-1.5 my-4 text-slate-800">' . "\n";
                    foreach ($lines as $line) {
                        $item = preg_replace('/^[-•]\s*/u', '', $line);
                        $html .= '  <li>' . $item . '</li>' . "\n";
                    }
                    $html .= '</ul>' . "\n";
                } elseif (preg_match('/^(بسم الله الرحمن الرحيم|صدق الله العلي العظيم|والحمد لله رب العالمين|والحمدلله رب العالمين)$/u', $p)) {
                    $html .= '<p class="text-center font-bold text-slate-900 my-4 text-base font-scholarly">' . $p . '</p>' . "\n";
                } else {
                    $html .= '<p class="transcript-p mb-6 leading-loose text-justify text-slate-800 font-scholarly">' . nl2br($p) . '</p>' . "\n";
                }
            }
        }

        return $html;
    }
}

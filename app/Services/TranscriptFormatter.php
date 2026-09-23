<?php

namespace App\Services;

class TranscriptFormatter
{
    /**
     * Clean raw transcript text based on Almoneer Vacum rules.
     * (المخمة - تنظيف النص القادم من Word أو كتل النصوص الخام)
     */
    public static function clean(string $text): string
    {
        if (empty(trim($text))) {
            return '';
        }

        // 0. معالجة كسر الأسطر الحرفية وفك رموز HTML (إن وجدت من تفريغ قواعد البيانات أو النسخ المشفر)
        $text = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $text);
        $text = str_replace("\\t", " ", $text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // تطبيق قواعد التنظيف اللغوي الأساسية
        $text = self::cleanInlineText($text);

        // ضبط الأقواس للآيات القرآنية
        $text = str_replace('﴿', '{', $text);
        $text = str_replace('﴾', '}', $text);

        // إزالة الفراغات والمسافات الفاسدة
        $text = preg_replace('/[\t]+/u', ' ', $text);
        $text = preg_replace('/[ ]{2,}/u', ' ', $text);
        $text = preg_replace('/^[ \t]+|[ \t]+$/um', '', $text);
        $text = preg_replace('/\n[ \t]+/u', "\n", $text);
        $text = preg_replace('/[ \t]+\n/u', "\n", $text);

        // ضبط العناوين @@عنوان@@ و %%نص فرعي%%
        $text = preg_replace('/@@(.*?)@@/u', "\n[t]$1[/t]\n", $text);
        $text = preg_replace('/@@(.*?)\n/u', "\n[t]$1[/t]\n", $text);
        $text = str_replace('@@', '', $text);

        $text = preg_replace('/%%(.*?)%%/u', "[c]$1[/c]", $text);
        $text = preg_replace('/%%(.*?)\n/u', "[c]$1[/c]\n", $text);
        $text = str_replace('%%', '', $text);

        // ضبط الأسطر والفراغات النهائية
        $text = preg_replace("/\n{3,}/u", "\n\n", $text);

        return trim($text);
    }

    /**
     * Clean inline text segments (punctuation, Indian-to-standard numbers, honorifics, quotes).
     * This is safe to run on non-tag text nodes inside HTML.
     */
    public static function cleanInlineText(string $text): string
    {
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

        // الشرطات
        $text = str_replace('–', '-', $text);
        $text = preg_replace('/(?<!\w)-(?!\w)/u', ' - ', $text);

        // 2. ضبط الأرقام من الهندية (٠-٩) إلى العربية القياسية (0-9)
        $easternDigits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $standardDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $text = str_replace($easternDigits, $standardDigits, $text);

        // 3. إزالة التطويل
        $text = str_replace('ـ', '', $text);

        // 4. ضبط التاريخ الهجري
        $text = preg_replace('/\sه\s/u', ' هـ ', $text);
        $text = preg_replace('/\sه\./u', ' هـ.', $text);
        $text = preg_replace('/\sه،/u', ' هـ،', $text);
        $text = preg_replace('/\sه:/u', ' هـ:', $text);
        $text = preg_replace('/([0-9]+)ه(?!\w)/u', '$1 هـ ', $text);

        // 5. الصلوات والأدعية (قواعد المخمة الأصلية)
        $replacements = [
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

        // اتصال حرف العطف (و)
        $text = preg_replace('/\sو\s/u', ' و', $text);
        $text = preg_replace('/\nو\s/u', "\nو", $text);
        $text = preg_replace('/^و\s/um', 'و', $text);

        // ضبط الهوامش [1] -> (1)
        $text = preg_replace('/\[\s*(\d+)\s*\]/u', '($1)', $text);

        // تصحيح الأرقام العشرية
        $text = preg_replace('/(\d+\.)\s+(\d+)/u', '$1$2', $text);

        return $text;
    }

    /**
     * Clean text nodes inside HTML while strictly preserving HTML tags and attributes.
     */
    public static function cleanHtmlTextNodes(string $html): string
    {
        $parts = preg_split('/(<[^>]+>)/u', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        $result = '';

        foreach ($parts as $part) {
            if (empty($part)) continue;
            if (str_starts_with($part, '<') && str_ends_with($part, '>')) {
                $result .= $part;
            } else {
                $result .= self::cleanInlineText($part);
            }
        }

        return $result;
    }

    /**
     * Sanitize messy Microsoft Word / Office HTML tags, comments, styles, and namespaces.
     */
    public static function sanitizeWordHtml(string $html): string
    {
        // 1. Remove XML declarations and conditional MS Office comments
        $html = preg_replace('/<!--\[if[^\]]*\]>.*?<!\[endif\]-->/is', '', $html);
        $html = preg_replace('/<!--.*?-->/s', '', $html);
        $html = preg_replace('/<xml\b[^>]*>.*?<\/xml>/is', '', $html);
        $html = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $html);
        $html = preg_replace('/<meta\b[^>]*>/is', '', $html);
        $html = preg_replace('/<link\b[^>]*>/is', '', $html);
        $html = preg_replace('/<title\b[^>]*>.*?<\/title>/is', '', $html);
        $html = preg_replace('/<\?xml\b[^>]*>/is', '', $html);

        // 2. Remove Word namespace tags like <o:p>, </o:p>, <w:sdt>, etc.
        $html = preg_replace('/<\/?\w+:[^>]*>/is', '', $html);

        // 3. Convert Word Headings (MsoHeading, MsoTitle) to standard headings
        $html = preg_replace('/<p\b[^>]*class=["\']?(?:MsoHeading1|MsoTitle)["\']?[^>]*>(.*?)<\/p>/is', '<h3>$1</h3>', $html);
        $html = preg_replace('/<p\b[^>]*class=["\']?MsoHeading2["\']?[^>]*>(.*?)<\/p>/is', '<h4>$1</h4>', $html);
        $html = preg_replace('/<p\b[^>]*class=["\']?MsoHeading[3-6]["\']?[^>]*>(.*?)<\/p>/is', '<h5>$1</h5>', $html);

        // 4. Strip Word classes (class="MsoNormal", class="MsoListParagraph", etc.)
        $html = preg_replace('/\s+class=["\']?Mso\w*["\']?/i', '', $html);

        // 5. Clean noisy inline styles while preserving intentional colors and alignments
        $html = preg_replace_callback('/\s+style=(["\'])(.*?)\1/is', function ($matches) {
            $style = $matches[2];
            $keep = [];
            if (preg_match('/text-align\s*:\s*(center|right|justify)/i', $style, $m)) {
                $keep[] = 'text-align: ' . strtolower($m[1]) . ';';
            }
            if (preg_match('/color\s*:\s*(#[0-9a-fA-F]{3,6}|rgb\([^)]+\)|[a-zA-Z]+)/i', $style, $m)) {
                $c = strtolower($m[1]);
                if (!in_array($c, ['#000', '#000000', 'black', '#333', '#333333', '#111', '#222'])) {
                    $keep[] = 'color: ' . $m[1] . ';';
                }
            }
            if (preg_match('/font-weight\s*:\s*(bold|[7-9]00)/i', $style)) {
                $keep[] = 'font-weight: bold;';
            }
            return empty($keep) ? '' : ' style="' . implode(' ', $keep) . '"';
        }, $html);

        // 6. Clean empty paragraphs or redundant spans
        $html = preg_replace('/<span\s*>\s*<\/span>/is', '', $html);
        $html = preg_replace('/<p\b[^>]*>\s*(?:&nbsp;|\s)*<\/p>/is', '', $html);

        return trim($html);
    }

    /**
     * Render the transcript to rich, scholarly HTML with Uthmanic font for Quranic verses,
     * Almoneer standard header sizes, colors, and layout.
     */
    public static function format(?string $text): string
    {
        if (empty(trim($text ?? ''))) {
            return '<p class="text-slate-500 italic">لا يوجد تفريغ نصي متوفر لهذه المحاضرة حالياً.</p>';
        }

        // 0. تنظيف كسر الأسطر الحرفية وفك رموز HTML المشفرة (مثل &#1648; للألف الخنجرية وعلامات الوقف)
        $text = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $text);
        $text = str_replace("\\t", " ", $text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $isHtml = (bool) preg_match('/<\s*(?:p|div|h[1-6]|ul|ol|li|blockquote|table)\b/i', $text);

        if ($isHtml) {
            // أ. تنظيف شوائب Word
            $formatted = self::sanitizeWordHtml($text);

            // ب. تنظيف علامات الترقيم والأرقام الهندية والصلوات في نصوص الـ HTML
            $formatted = self::cleanHtmlTextNodes($formatted);

            // 1. استبدال وتنسيق العناوين [t]عنوان[/t] وعناوين h1..h6
            $formatted = preg_replace_callback('/\[t\](.*?)\[\/t\]/su', function ($matches) {
                return self::renderMainHeading(trim($matches[1]));
            }, $formatted);

            $formatted = preg_replace_callback('/<h([1-6])[^>]*>(.*?)<\/h\1>/su', function ($matches) {
                $level = (int)$matches[1];
                $content = trim($matches[2]);
                $plain = strip_tags($content);

                // فحص ما إذا كان العنوان فرعياً: إما يحمل لون البني #8B4513 أو مصطلحات فرعية أو h4
                $isSubheading = $level === 4
                    || str_contains($content, '8B4513')
                    || str_contains($content, '[c]')
                    || preg_match('/^(?:الصفة|المصداق|الخطوة|الشاهد|الفرع|الأمر|الوجه|التنبيه|المسألة|الجهة|البند)\s+(?:الأول|الثاني|الثالث|الرابع|الخامس|السادس|السابع|الثامن|التاسع|العاشر|\d+)/u', $plain);

                // العناوين الكبرى الرئيسية (المحاور، الفصول، المنطلقات، الأبواب، أو h1..h3)
                $isMainHeading = !$isSubheading && (
                    $level <= 3
                    || preg_match('/^(?:المحور|الفصل|الباب|المنطلق|المقدمة|الخاتمة)\s+(?:الأول|الثاني|الثالث|الرابع|الخامس|\d+)/u', $plain)
                );

                if ($isSubheading) {
                    $cleanContent = str_replace(['[c]', '[/c]'], '', $content);
                    return self::renderSubHeading($cleanContent);
                }

                if ($isMainHeading) {
                    return self::renderMainHeading($content);
                }

                // افتراضي لعناوين h5, h6 الأخرى
                return self::renderSubHeading($content);
            }, $formatted);

            // استبدال العناوين والصفات الفرعية [c]...[/c]
            $formatted = preg_replace_callback('/\[c\](.*?)\[\/c\]/su', function ($matches) {
                return '<span class="text-amber-800 font-bold font-scholarly inline-block my-2" style="color: #8B4513 !important; font-weight: bold !important;">' . e(trim($matches[1])) . '</span>';
            }, $formatted);

            // 2. استبدال الآيات القرآنية {الآية} أو ﴿الآية﴾ بالخط العثماني واللون الأحمر المعتمد
            $formatted = preg_replace_callback('/(?:\{|﴿)([^}﴾]+)(?:\}|﴾)/u', function ($matches) {
                $verse = html_entity_decode(trim($matches[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                return '<span class="quran-verse font-quran text-red-900 font-normal" style="color: #BB1111; font-family: \'Traditional Arabic\', \'Amiri\', serif;">﴿ ' . $verse . ' ﴾</span>';
            }, $formatted);

            // 3. استبدال مراجع السور والآيات [المؤمنون : 115] أو [البقرة: 2]
            $formatted = preg_replace_callback('/\[([\p{Arabic}\s]+:\s*\d+)\]/u', function ($matches) {
                return '<span class="quran-ref inline-flex items-center gap-1 font-sans text-xs bg-gold-50 text-gold-700 px-2 py-0.5 rounded-md border border-gold-200 font-semibold">' . e($matches[0]) . '</span>';
            }, $formatted);

            // 4. استبدال الصلوات والأدعية المختصرة (ص)، (ع)، (عع)، (عه) مع رمز النبي ﷺ بالرسم المصحفي
            $formatted = preg_replace('/(?:\(|«)(ص)(?:\)|»)/u', '<span class="prophet-symbol font-bold px-0.5 text-slate-900" title="صلى الله عليه وآله وسلم" style="font-family: \'Traditional Arabic\', \'Amiri\', serif !important; font-size: 1.15em !important; font-weight: bold !important; color: #0f172a !important;">ﷺ</span>', $formatted);
            $formatted = preg_replace('/(?:\(|«)(ع)(?:\)|»)/u', '<span class="text-gold-600 font-bold text-xs px-0.5" title="عليه السلام">(ع)</span>', $formatted);
            $formatted = preg_replace('/(?:\(|«)(عع)(?:\)|»)/u', '<span class="text-gold-600 font-bold text-xs px-0.5" title="عليهم السلام">(عع)</span>', $formatted);
            $formatted = preg_replace('/(?:\(|«)(عه)(?:\)|»)/u', '<span class="text-gold-600 font-bold text-xs px-0.5" title="عليها السلام">(عه)</span>', $formatted);

            // 5. استبدال علامات التنصيص المقتبسة «...» بتنسيق بارز
            $formatted = preg_replace('/«([^»]+)»/u', '<span class="text-emerald-900 font-medium font-scholarly px-0.5">«$1»</span>', $formatted);

            // 6. استبدال وتنسيق القوائم النقطية <ul> و <li> لتطابق هوية الموقع
            $formatted = preg_replace_callback('/<ul[^>]*>(.*?)<\/ul>/su', function ($matches) {
                $inner = $matches[1];
                $inner = preg_replace_callback('/<li[^>]*>(.*?)<\/li>/su', function ($m) {
                    $item = trim($m[1]);
                    return '<li class="lecture-bullet-item font-bold text-slate-900 leading-loose" style="list-style-type: disc !important; display: list-item !important; margin-bottom: 0.6rem !important; font-weight: bold !important; color: #1e293b !important; line-height: 2.1 !important;">' . $item . '</li>';
                }, $inner);
                return '<ul class="lecture-bullets list-disc pr-8 my-6 space-y-2 text-slate-900 font-bold" style="list-style-type: disc !important; padding-right: 2.25rem !important; margin: 1.25rem 0 !important; display: block !important;">' . $inner . '</ul>';
            }, $formatted);

            // 7. تحسين تنسيق الفقرات العادية وتوسيط البسملة والحمدلة
            $formatted = preg_replace_callback('/<p\b([^>]*)>(.*?)<\/p>/su', function ($matches) {
                $attrs = $matches[1];
                $content = trim($matches[2]);
                $plain = trim(strip_tags($content));

                if (empty($plain)) {
                    return '';
                }

                // توسيط البسملة والتصديق والحمدلة
                if (preg_match('/^(بسم الله الرحمن الرحيم|صدق الله العلي العظيم|والحمد لله رب العالمين|والحمدلله رب العالمين)$/u', $plain)) {
                    return '<p class="text-center font-bold text-slate-900 my-4 text-base font-scholarly" style="text-align: center !important; font-weight: bold !important; margin: 1.25rem 0 !important;">' . $content . '</p>';
                }

                // الحفاظ على الفقرات التي فيها محاذاة في الوسط
                if (str_contains($attrs, 'text-align: center') || str_contains($attrs, 'text-center')) {
                    return '<p class="text-center font-scholarly my-3" style="text-align: center !important; margin: 1rem 0 !important;">' . $content . '</p>';
                }

                return '<p class="transcript-p mb-6 leading-loose text-justify text-slate-800 font-scholarly" style="line-height: 2.2 !important; text-align: justify !important; margin-bottom: 1.5rem !important;">' . $content . '</p>';
            }, $formatted);

            return $formatted;
        }

        // ==========================
        // ب. في حال كان النص خاماً (Plain Text)
        // ==========================
        $cleaned = self::clean($text);

        // 1. استبدال العناوين [t]عنوان[/t]
        $cleaned = preg_replace_callback('/\[t\](.*?)\[\/t\]/su', function ($matches) {
            return self::renderMainHeading(e(trim($matches[1])));
        }, $cleaned);

        // استبدال العناوين والصفات الفرعية [c]...[/c]
        $cleaned = preg_replace_callback('/\[c\](.*?)\[\/c\]/su', function ($matches) {
            return '<span class="text-amber-800 font-bold font-scholarly inline-block my-2" style="color: #8B4513 !important; font-weight: bold !important;">' . e(trim($matches[1])) . '</span>';
        }, $cleaned);

        // 2. استبدال الآيات القرآنية {الآية} أو ﴿الآية﴾ بالخط العثماني
        $cleaned = preg_replace_callback('/(?:\{|﴿)([^}﴾]+)(?:\}|﴾)/u', function ($matches) {
            $verse = html_entity_decode(trim($matches[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            return '<span class="quran-verse font-quran text-red-900 font-normal" style="color: #BB1111; font-family: \'Traditional Arabic\', \'Amiri\', serif;">﴿ ' . $verse . ' ﴾</span>';
        }, $cleaned);

        // 3. استبدال مراجع السور والآيات [المؤمنون : 115] أو [البقرة: 2]
        $cleaned = preg_replace_callback('/\[([\p{Arabic}\s]+:\s*\d+)\]/u', function ($matches) {
            return '<span class="quran-ref inline-flex items-center gap-1 font-sans text-xs bg-gold-50 text-gold-700 px-2 py-0.5 rounded-md border border-gold-200 font-semibold">' . e($matches[0]) . '</span>';
        }, $cleaned);

        // 4. استبدال الصلوات والأدعية المختصرة (ص)، (ع)، (عع)، (عه)
        $cleaned = preg_replace('/(?:\(|«)(ص)(?:\)|»)/u', '<span class="prophet-symbol font-bold px-0.5 text-slate-900" title="صلى الله عليه وآله وسلم" style="font-family: \'Traditional Arabic\', \'Amiri\', serif !important; font-size: 1.15em !important; font-weight: bold !important; color: #0f172a !important;">ﷺ</span>', $cleaned);
        $cleaned = preg_replace('/(?:\(|«)(ع)(?:\)|»)/u', '<span class="text-gold-600 font-bold text-xs px-0.5" title="عليه السلام">(ع)</span>', $cleaned);
        $cleaned = preg_replace('/(?:\(|«)(عع)(?:\)|»)/u', '<span class="text-gold-600 font-bold text-xs px-0.5" title="عليهم السلام">(عع)</span>', $cleaned);
        $cleaned = preg_replace('/(?:\(|«)(عه)(?:\)|»)/u', '<span class="text-gold-600 font-bold text-xs px-0.5" title="عليها السلام">(عه)</span>', $cleaned);

        // 5. استبدال علامات التنصيص المقتبسة «...»
        $cleaned = preg_replace('/«([^»]+)»/u', '<span class="text-emerald-900 font-medium font-scholarly px-0.5">«$1»</span>', $cleaned);

        // 6. تقسيم الفقرات على فواصل الأسطر المزدوجة
        $paragraphs = preg_split("/\n\s*\n/u", $cleaned);
        $html = '';

        foreach ($paragraphs as $p) {
            $p = trim($p);
            if (empty($p)) continue;

            if (str_starts_with($p, '<h3') || str_starts_with($p, '<h4')) {
                $html .= $p . "\n\n";
            } else {
                // فحص القوائم النقطية
                $lines = array_filter(array_map('trim', explode("\n", $p)));
                $isList = count($lines) > 1 && count(array_filter($lines, fn($l) => str_starts_with($l, '- ') || str_starts_with($l, '• ') || str_starts_with($l, '* '))) === count($lines);
                if ($isList) {
                    $html .= '<ul class="lecture-bullets list-disc pr-8 my-6 space-y-2 text-slate-900 font-bold" style="list-style-type: disc !important; padding-right: 2.25rem !important; margin: 1.25rem 0 !important; display: block !important;">' . "\n";
                    foreach ($lines as $line) {
                        $item = preg_replace('/^[-•*]\s*/u', '', $line);
                        $html .= '  <li class="lecture-bullet-item font-bold text-slate-900 leading-loose" style="list-style-type: disc !important; display: list-item !important; margin-bottom: 0.6rem !important; font-weight: bold !important; color: #1e293b !important; line-height: 2.1 !important;">' . $item . '</li>' . "\n";
                    }
                    $html .= '</ul>' . "\n\n";
                } elseif (preg_match('/^(بسم الله الرحمن الرحيم|صدق الله العلي العظيم|والحمد لله رب العالمين|والحمدلله رب العالمين)$/u', $p)) {
                    $html .= '<p class="text-center font-bold text-slate-900 my-4 text-base font-scholarly" style="text-align: center !important; font-weight: bold !important; margin: 1.25rem 0 !important;">' . $p . '</p>' . "\n\n";
                } else {
                    $html .= '<p class="transcript-p mb-6 leading-loose text-justify text-slate-800 font-scholarly" style="line-height: 2.2 !important; text-align: justify !important; margin-bottom: 1.5rem !important;">' . nl2br($p) . '</p>' . "\n\n";
                }
            }
        }

        return trim($html);
    }

    /**
     * Helper to render major lecture headings (#990000).
     */
    protected static function renderMainHeading(string $title): string
    {
        return '<h3 class="lecture-heading text-xl sm:text-2xl font-bold font-scholarly text-red-800 my-8 block leading-snug" style="color: #990000 !important; font-weight: bold !important; font-size: 1.55rem !important; margin-top: 2.25rem !important; margin-bottom: 1rem !important; font-family: \'Traditional Arabic\', \'Amiri\', serif !important;">' . $title . '</h3>';
    }

    /**
     * Helper to render subheadings (#8B4513).
     */
    protected static function renderSubHeading(string $title): string
    {
        return '<h4 class="lecture-subheading text-lg sm:text-xl font-bold font-scholarly my-6 block leading-snug" style="color: #8B4513 !important; font-weight: bold !important; font-size: 1.35rem !important; margin-top: 1.75rem !important; margin-bottom: 0.75rem !important; font-family: \'Traditional Arabic\', \'Amiri\', serif !important;">' . $title . '</h4>';
    }

    /**
     * Convert formatted HTML back into clean plain text with Almoneer markdown tags ([t], [c], { }).
     */
    public static function toCleanText(string $html): string
    {
        // 1. Convert headings
        $text = preg_replace_callback('/<h[1-3][^>]*>(.*?)<\/h[1-3]>/su', function ($m) {
            return "\n\n[t]" . trim(strip_tags($m[1])) . "[/t]\n\n";
        }, $html);

        $text = preg_replace_callback('/<h[4-6][^>]*>(.*?)<\/h[4-6]>/su', function ($m) {
            return "\n\n[c]" . trim(strip_tags($m[1])) . "[/c]\n\n";
        }, $text);

        // 2. Convert bullet lists
        $text = preg_replace_callback('/<li[^>]*>(.*?)<\/li>/su', function ($m) {
            return "- " . trim(strip_tags($m[1])) . "\n";
        }, $text);

        // 3. Convert Quranic verses to { }
        $text = preg_replace('/<span[^>]*class=["\']?quran-verse["\']?[^>]*>﴿?\s*(.*?)\s*﴾?<\/span>/su', '{$1}', $text);

        // 4. Convert Prophet symbol to (ص)
        $text = preg_replace('/<span[^>]*class=["\']?prophet-symbol["\']?[^>]*>.*?<\/span>/su', '(ص)', $text);

        // 5. Convert quotes
        $text = preg_replace('/<span[^>]*class=["\']?text-emerald-900["\']?[^>]*>«?(.*?)»?<\/span>/su', '«$1»', $text);

        // 6. Convert paragraphs and linebreaks
        $text = str_replace(['<br />', '<br>', '<br/>'], "\n", $text);
        $text = preg_replace('/<\/p>\s*<p[^>]*>/i', "\n\n", $text);
        $text = preg_replace('/<\/?(?:p|div|ul|ol|table|tr|td|span)[^>]*>/i', '', $text);

        return self::clean($text);
    }

    /**
     * Full processing pipeline for Almoneer Vacum tool.
     * Returns formatted HTML, clean text, and extracted content stats.
     */
    public static function process(string $input): array
    {
        $formattedHtml = self::format($input);
        $cleanText = self::toCleanText($formattedHtml);

        $plainText = strip_tags($formattedHtml);
        $words = count(preg_split('/\s+/u', trim($plainText), -1, PREG_SPLIT_NO_EMPTY));
        $chars = mb_strlen($plainText);
        $versesCount = substr_count($formattedHtml, 'quran-verse');
        $headingsCount = substr_count($formattedHtml, 'lecture-heading') + substr_count($formattedHtml, 'lecture-subheading');

        return [
            'html' => $formattedHtml,
            'clean_text' => $cleanText,
            'stats' => [
                'chars' => $chars,
                'words' => $words,
                'verses' => $versesCount,
                'headings' => $headingsCount,
            ]
        ];
    }
}

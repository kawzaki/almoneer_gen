<?php

namespace App\Services;

class TranscriptFormatter
{
    /**
     * Exact Unicode Private Use Area (PUA) glyph mapping for the `arb3een313-L` (Arb3een) font
     * (`2_5424879041599508935.ttf` -> `public/fonts/arb3een.{woff2,woff,ttf}`),
     * matching all 12 honorific symbols in `قالب المحاضرة والرموز.docx`.
     */
    public const HONORIFIC_SYMBOLS = [
        'ص'        => ['char' => "\u{F032}", 'entity' => '&#xF032;', 'title' => 'صلى الله عليه وآله'],
        'ع'        => ['char' => "\u{F034}", 'entity' => '&#xF034;', 'title' => 'عليه السلام'],
        'عه'       => ['char' => "\u{F035}", 'entity' => '&#xF035;', 'title' => 'عليها السلام'],
        'عما'      => ['char' => "\u{F036}", 'entity' => '&#xF036;', 'title' => 'عليهما السلام'],
        'عع'       => ['char' => "\u{F037}", 'entity' => '&#xF037;', 'title' => 'عليهم السلام'],
        'عج'       => ['char' => "\u{F049}", 'entity' => '&#xF049;', 'title' => 'عجل الله تعالى فرجه الشريف'],
        'قده'      => ['char' => "\u{F042}", 'entity' => '&#xF042;', 'title' => 'قدس سره'],
        'دام ظله'  => ['char' => "\u{F04B}", 'entity' => '&#xF04B;', 'title' => 'دام ظله'],
        'ره'       => ['char' => "\u{F045}", 'entity' => '&#xF045;', 'title' => 'رحمه الله'],
        'رض'       => ['char' => "\u{F050}", 'entity' => '&#xF050;', 'title' => 'رضي الله عنه'],
        'طاب ثراه' => ['char' => "\u{F04F}", 'entity' => '&#xF04F;', 'title' => 'طاب ثراه'],
        'مد ظله'   => ['char' => "\u{F04C}", 'entity' => '&#xF04C;', 'title' => 'مد ظله'],
    ];

    /**
     * Clean raw Arabic text according to Almoneer Vacum (المخمة) rules.
     * Normalizes punctuation, brackets, numbers, honorific markers, headings, and poems.
     */
    public static function clean(?string $text): string
    {
        if (empty($text)) {
            return '';
        }

        // 0. فك رموز الكيانات وتوحيد المسافات الخاصة وأسطر الويندوز
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace('/[\x{00A0}\x{2000}-\x{200B}\x{202F}\x{FEFF}]/u', ' ', $text);

        // 0.1 إزالة أسطر التعليمات التجريبية إن نُسخت بالخطأ من ملف الاختبار
        $text = preg_replace('/^إليك نص المقال بعد تعديله[^\n]*\n*/um', '', $text);
        $text = preg_replace('/^نص المقال للاختبار:[ \t]*\n*/um', '', $text);

        // 0.2 دمج النقطتين الرأسيتين المنفصلتين في سطر مستقل (مثل "الحوارات\n:" أو "الهوامش\n:")
        $text = preg_replace('/\n+[ \t]*:[ \t]*(?=\n|$)/u', ':', $text);

        // 0.3 تنظيف النص الداخلي (علامات الترقيم، الأقواس، الرموز، الأرقام)
        $text = self::cleanInlineText($text);

        // 0.4 تنظيم فواصل البسملة والآية الافتتاحية والتصديق
        $text = preg_replace(
            '/^(بسم الله الرحمن الرحيم)[ \t]*(\{[^}]+\}(?:[ \t]*\[[^\]]+\])?)[ \t]*(صدق الله العلي العظيم)?$/um',
            "$1\n\n$2\n\n$3",
            $text
        );
        $text = preg_replace('/^(بسم الله الرحمن الرحيم)[ \t]+(.+)$/um', "$1\n\n$2", $text);
        $text = preg_replace('/^(\{[^}]+\}(?:[ \t]*\[[^\]]+\])?)[ \t]+(صدق الله العلي العظيم)$/um', "$1\n\n$2", $text);

        // 0.5 فصل جملة التقديم الشعري (مثل "كما ورد في القول:") عن البيت الشعري وتقسيم الأبيات ذات الفاصلين (|)
        $text = preg_replace(
            '/^((?:كما ورد في القول|قال الشاعر|يقول الشاعر|كقول الشاعر|ومنه قوله|كقوله)[ \t]*:)[ \t]*(.+?\|.+)$/um',
            "$1\n\n[poem]\n$2\n[/poem]",
            $text
        );

        // معالجة الأسطر الشعرية وتقسيم السطر الذي يضم بيتين بعلامتي (|) إلى سطرين مستقلين
        $lines = explode("\n", $text);
        $normalizedLines = [];
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed !== '' && !str_starts_with($trimmed, '[t]') && !str_starts_with($trimmed, '[س]') && substr_count($trimmed, '|') === 2) {
                $splitCouplets = self::splitTwoCoupletLine($trimmed);
                foreach ($splitCouplets as $sc) {
                    $normalizedLines[] = $sc;
                }
            } else {
                $normalizedLines[] = $line;
            }
        }
        $text = implode("\n", $normalizedLines);

        // 6. تنظيف الأسطر الفارغة المتكررة والمسافات في بداية ونهاية كل سطر
        $text = preg_replace('/^[ \t]+|[ \t]+$/m', '', $text);
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        return trim($text);
    }

    /**
     * Split a single line that contains two couplets (2 pipe characters `|`) into two separate couplet lines.
     */
    protected static function splitTwoCoupletLine(string $rawLine): array
    {
        if (substr_count($rawLine, '|') !== 2) {
            return [$rawLine];
        }
        $parts = array_map('trim', explode('|', $rawLine));
        $middleWords = preg_split('/\s+/u', $parts[1], -1, PREG_SPLIT_NO_EMPTY);
        if (count($middleWords) >= 4) {
            $half = (int) floor(count($middleWords) / 2);
            $secondHemistich1 = implode(' ', array_slice($middleWords, 0, $half));
            $firstHemistich2 = implode(' ', array_slice($middleWords, $half));
            return [
                $parts[0] . ' | ' . $secondHemistich1,
                $firstHemistich2 . ' | ' . $parts[2],
            ];
        }
        return [$rawLine];
    }

    /**
     * Clean inline text fragment (safe to run on plain text or individual HTML text nodes).
     * Never consumes newline (\n) characters when adjusting horizontal spacing.
     */
    public static function cleanInlineText(string $text): string
    {
        if (empty($text)) {
            return '';
        }

        // 0. توحيد المسافات غير القابلة للكسر والمحارف الخفية
        $text = preg_replace('/[\x{00A0}\x{2000}-\x{200B}\x{202F}\x{FEFF}]/u', ' ', $text);

        // 0.1 تحويل علامات التنصيص الإنجليزية <نص> إلى «نص»
        $text = preg_replace('/>[ \t]*([^<>\n]+?)[ \t]*</u', ' «$1» ', $text);

        // 0.2 تحويل وسوم @عنوان@ أو @@عنوان@@ إلى [t]عنوان[/t]، و %%فرعي%% إلى [c]فرعي[/c]
        $text = preg_replace('/@{1,2}([^@\n]+)@{1,2}/u', "\n\n[t]$1[/t]\n\n", $text);
        $text = preg_replace('/%%([^%\n]+)%%/u', '[c]$1[/c]', $text);

        // 0.3 توحيد زخرفة وسط البيت الشعري ✤ إلى علامة الشطرين |
        $text = preg_replace('/[ \t]*✤[ \t]*/u', ' | ', $text);

        // 0.4 تحويل محارف منطقة الاستخدام الخاص (PUA) الخاصة بخط الأربعين إلى اختصاراتها القياسية أولاً
        $text = preg_replace_callback('/[\x{F030}-\x{F059}]/u', function ($m) {
            return ' ' . self::symbolCharToMarker($m[0]) . ' ';
        }, $text);

        // 1. ضبط الفواصل وعلامات الترقيم (باستخدام [ \t]* بدلاً من \s* لعدم التهام الأسطر الجديدة \n)
        $text = str_replace(',', '،', $text);
        $text = preg_replace('/،[ \t]*/u', '، ', $text);
        $text = preg_replace('/\.(?![ \t]*\.)[ \t]*/u', '. ', $text);
        $text = preg_replace('/:[ \t]*/u', ': ', $text);
        $text = preg_replace('/؛[ \t]*/u', '؛ ', $text);
        $text = preg_replace('/؟[ \t]*/u', '؟ ', $text);
        $text = preg_replace('/![ \t]*/u', '! ', $text);

        // تصحيح الأسماء المختصرة مثل أ.د.
        $text = str_replace('أ. د.', 'أ.د.', $text);
        $text = str_replace('د. ', 'د.', $text);

        // الأقواس المزدوجة (( )) -> « » (للأحاديث الشريفة)
        $text = str_replace('((', '«', $text);
        $text = str_replace('))', '»', $text);
        $text = preg_replace('/«[ \t]*/u', ' «', $text);
        $text = preg_replace('/[ \t]*»/u', '» ', $text);

        // الأقواس العادية ( ) و [ ]
        $text = preg_replace('/\([ \t]+/u', '(', $text);
        $text = preg_replace('/[ \t]+\)/u', ')', $text);
        $text = preg_replace('/\[[ \t]+/u', '[', $text);
        $text = preg_replace('/[ \t]+\]/u', ']', $text);

        // توحيد مراجع الآيات مثل [آل عمران: 140] بدلاً من [آل عمران : 140]
        $text = preg_replace('/\[([\p{Arabic}\s]+?)[ \t]*:[ \t]*(\d+)\]/u', '[$1: $2]', $text);

        // الأقواس القرآنية ﴿ ﴾ -> { } بدون مسافات داخلية
        $text = str_replace(['﴿', '﴾'], ['{', '}'], $text);
        $text = preg_replace('/([\{﴿])[ \t]+/u', '$1', $text);
        $text = preg_replace('/[ \t]+([\}﴾])/u', '$1', $text);

        // علامات الاقتباس " " أو “ ” -> ”...“
        $text = preg_replace('/["“”]([^"“”\n]+?)["“”]/u', ' ”$1“ ', $text);

        // الشرطات (مع عدم المساس بشرطة القائمة النقطية في أول السطر)
        $text = str_replace('–', '-', $text);
        $text = preg_replace('/(?<!^)(?<!\n)(?<!\w)-(?!\w)/u', ' - ', $text);

        // 2. ضبط الأرقام من الهندية (٠-٩) إلى العربية القياسية (0-9)
        $easternDigits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $standardDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $text = str_replace($easternDigits, $standardDigits, $text);

        // ملاحظة: نحافظ على الكشيدة العربية (التطويل ـ) في متن الفقرات ولا نحذفها

        // 4. ضبط التاريخ الهجري
        $text = preg_replace('/[ \t]ه[ \t]/u', ' هـ ', $text);
        $text = preg_replace('/[ \t]ه\./u', ' هـ.', $text);
        $text = preg_replace('/[ \t]ه،/u', ' هـ،', $text);
        $text = preg_replace('/[ \t]ه:/u', ' هـ:', $text);
        $text = preg_replace('/([0-9]+)ه(?!\w)/u', '$1 هـ ', $text);

        // 4.1 تصحيح ألقاب النساء المعصومات (الزهراء عليها السلام بدلاً من التذكير الخطأ)
        $text = preg_replace('/(الزهراء|فاطمة|خديجة|زينب|أم كلثوم|مريم|معصومة)[ \t]*(?:\(|«|\[)[ \t]*ع[ \t]*(?:\)|»|\])/u', '$1 (عه)', $text);
        $text = preg_replace('/(الزهراء|فاطمة|خديجة|زينب|أم كلثوم|مريم|معصومة)[ \t]+A(?=[\p{Arabic}\s،.؛:؟!«»\(\)\[\]"\'”„…–—]|$)/u', '$1 h', $text);

        // 5. الصلوات والأدعية الشاملة وفق جدول "قالب المحاضرة والرموز.docx"
        // نسمح بوجود \s+ (بما في ذلك التفاف الأسطر العارض) داخل العبارة التكريمية الواحدة
        $honorificPatterns = [
            // 1. (صلى الله عليه وآله وسلم) / (صلى الله عليه وآله) / (ص)
            '/(?:[\(\[«"”]\s*)?صلى\s+الله\s+عليه\s+وآله\s+وسلم(?:\s*[\)\]»"”])?/u' => ' (ص) ',
            '/(?:[\(\[«"”]\s*)?صلى\s+الله\s+عليه\s+وآله(?:\s*[\)\]»"”])?/u'        => ' (ص) ',
            '/(?:[\(\[«"”]\s*)?صلى\s+الله\s+عليه\s+وسلم(?:\s*[\)\]»"”])?/u'        => ' (ص) ',
            '/﵌|&#xFD4C;|ﷺ/u'                                                      => ' (ص) ',

            // 5 & 4 & 3 & 2: عليهما / عليهم / عليها / عليه السلام (الأطول أولاً!)
            '/(?:[\(\[«"”]\s*)?(?:عليهما\s+السلام|سلام\s+الله\s+عليهما)(?:\s*[\)\]»"”])?/u' => ' (عما) ',
            '/(?:[\(\[«"”]\s*)?(?:عليهم\s+السلام|سلام\s+الله\s+عليهم)(?:\s*[\)\]»"”])?/u'   => ' (عع) ',
            '/(?:[\(\[«"”]\s*)?(?:عليها\s+السلام|سلام\s+الله\s+عليها)(?:\s*[\)\]»"”])?/u'   => ' (عه) ',
            '/(?:[\(\[«"”]\s*)?(?:عليه\s+السلام|سلام\s+الله\s+عليه)(?![ \t]*(?:ما|م|ها))(?:\s*[\)\]»"”])?/u' => ' (ع) ',

            // 6. (عجل الله فرجه) / (عجل الله تعالى فرجه) / (عجل الله فرجه الشريف) / (عج)
            '/(?:[\(\[«"”]\s*)?عجل\s+الله(?:\s+تعالى)?\s+فرجه(?:\s+الشريف)?(?:\s*[\)\]»"”])?/u' => ' (عج) ',

            // 7. (قدس سره) / (قده) / (قدس)
            '/(?:[\(\[«"”]\s*)?قدس\s+سره(?:\s+الشريف)?(?:\s*[\)\]»"”])?/u'         => ' (قده) ',
            '/(?:[\(\[«"”]\s*)?(?:قده|قدس)(?:\s*[\)\]»"”])/u'                      => ' (قده) ',

            // 8. (دام ظله) / (دام ظله العالي) / (دامت بركاته)
            '/(?:[\(\[«"”]\s*)?دام\s+ظله(?:\s+العالي|\s+الشريف|\s+الوارف)?(?:\s*[\)\]»"”])?/u' => ' (دام ظله) ',
            '/(?:[\(\[«"”]\s*)?دامت\s+بركاته(?:\s*[\)\]»"”])?/u'                   => ' (دام ظله) ',

            // 9. (رحمه الله) / (رحمة الله عليه) / (رضوان الله عليه) / (ره)
            '/(?:[\(\[«"”]\s*)?(?:رحمه\s+الله|رحمة\s+الله\s+عليه|رضوان\s+الله\s+(?:تعالى\s+)?عليه)(?:\s*[\)\]»"”])?/u' => ' (ره) ',

            // 10. (رضي الله عنه) / (رضي الله عنها) / (رضي الله عنهم) / (رض)
            '/(?:[\(\[«"”]\s*)?رضي\s+الله\s+(?:تعالى\s+)?(?:عنهما|عنهم|عنها|عنه)(?:\s*[\)\]»"”])?/u' => ' (رض) ',

            // 11. (طاب ثراه) / (طاب ثراهما)
            '/(?:[\(\[«"”]\s*)?طاب\s+(?:ثراهما|ثراهم|ثراه)(?:\s*[\)\]»"”])?/u'     => ' (طاب ثراه) ',

            // 12. (مد ظله) / (مد ظله العالي) / (دام عزه)
            '/(?:[\(\[«"”]\s*)?(?:مد\s+ظله(?:\s+العالي)?|دام\s+عزه)(?:\s*[\)\]»"”])?/u' => ' (مد ظله) ',
        ];
        $text = preg_replace(array_keys($honorificPatterns), array_values($honorificPatterns), $text);

        // 5.1 استبدال الحروف اللاتينية المفردة الموروثة من خط أبو ذر القديم (A, C, D, H, J, K, h)
        $text = preg_replace('/(?:\(|«|\[|”|")[ \t]*A[ \t]*(?:\)|»|\]|“|")/u', '(ع)', $text);
        $text = preg_replace('/(?:\(|«|\[|”|")[ \t]*C[ \t]*(?:\)|»|\]|“|")/u', '(عما)', $text);
        $text = preg_replace('/(?:\(|«|\[|”|")[ \t]*h[ \t]*(?:\)|»|\]|“|")/u', '(عه)', $text);
        $text = preg_replace('/(?:\(|«|\[|”|")[ \t]*D[ \t]*(?:\)|»|\]|“|")/u', '(عع)', $text);
        $text = preg_replace('/(?:\(|«|\[|”|")[ \t]*H[ \t]*(?:\)|»|\]|“|")/u', '(قده)', $text);
        $text = preg_replace('/(?:\(|«|\[|”|")[ \t]*J[ \t]*(?:\)|»|\]|“|")/u', '(ص)', $text);
        $text = preg_replace('/(?:\(|«|\[|”|")[ \t]*K[ \t]*(?:\)|»|\]|“|")/u', '(دام ظله)', $text);

        $glyphMap = [
            'A' => '(ع)',
            'C' => '(عما)',
            'h' => '(عه)',
            'D' => '(عع)',
            'H' => '(قده)',
            'J' => '(ص)',
            'K' => '(دام ظله)',
        ];
        $text = preg_replace_callback('/(?<=[\p{Arabic}\s،.؛:؟!«»\(\)\[\]"\'”„…–—]|^)([ACDHJKh])(?=[\p{Arabic}\s،.؛:؟!«»\(\)\[\]"\'”„…–—]|$)/u', function ($m) use ($glyphMap) {
            return $glyphMap[$m[1]];
        }, $text);

        // توحيد (عم) و (قدس) إلى (عع) و (قده)
        $text = preg_replace('/(?:\(|«|\[)[ \t]*عم[ \t]*(?:\)|»|\])/u', '(عع)', $text);
        $text = preg_replace('/(?:\(|«|\[)[ \t]*قدس[ \t]*(?:\)|»|\])/u', '(قده)', $text);

        // إزالة أي أقواس مزدوجة أو مكررة حول الرموز
        $allMarkersRegex = 'ص|ع|عه|عما|عع|عج|قده|دام ظله|ره|رض|طاب ثراه|مد ظله';
        $text = preg_replace('/[\(\[«"”\s]*[\(\[«"”][ \t]*(' . $allMarkersRegex . ')[ \t]*[\)\]»"“][\)\]»"“\s]*/u', ' ($1) ', $text);
        $text = preg_replace('/(?:\(|«|\[|”|")[ \t]*(' . $allMarkersRegex . ')[ \t]*(?:\)|»|\]|“|")/u', '($1)', $text);

        // 6. تنظيف المسافات الأفقية وعلامات الترقيم
        $text = preg_replace('/[\t]+/u', ' ', $text);
        $text = preg_replace('/[ ]{2,}/u', ' ', $text);

        $text = str_replace('. .', '..', $text);
        $text = str_replace('، ،', '،،', $text);
        $text = preg_replace('/[ \t]+،/u', '،', $text);
        $text = preg_replace('/[ \t]+\./u', '.', $text);
        $text = preg_replace('/[ \t]+:/u', ':', $text);
        $text = preg_replace('/[ \t]+؛/u', '؛', $text);
        $text = preg_replace('/[ \t]+؟/u', '؟', $text);
        $text = preg_replace('/[ \t]+!/u', '!', $text);
        $text = preg_replace('/«[ \t]+/u', '«', $text);
        $text = preg_replace('/[ \t]+»/u', '»', $text);
        $text = preg_replace('/”[ \t]+/u', '”', $text);
        $text = preg_replace('/[ \t]+“/u', '“', $text);
        $text = preg_replace('/\{[ \t]+/u', '{', $text);
        $text = preg_replace('/[ \t]+\}/u', '}', $text);

        // التصاق حرف الواو بالكلمة التي تليه
        $text = preg_replace('/([ \t\n])و[ \t]+/u', '$1و', $text);
        $text = preg_replace('/^و[ \t]+/u', 'و', $text);

        // الأرقام العشرية (1. 5 -> 1.5)
        $text = preg_replace('/(\d+\.)[ \t]+(\d+)/u', '$1$2', $text);

        return $text;
    }

    /**
     * Convert any existing symbol spans (`symbol-arb3een` or `symbol-abothar`) or `prophet-pbuhaf.svg` images
     * back into their canonical markers `(ص)`, `(ع)`, `(عه)`, etc.
     */
    public static function convertSymbolSpansToMarkers(string $html): string
    {
        $html = preg_replace('/<img\b[^>]*src=["\'][^"\']*prophet-pbuhaf\.svg[^"\']*["\'][^>]*>/i', ' (ص) ', $html);
        $html = preg_replace_callback('/<span\b[^>]*class=["\'][^"\']*(?:symbol-arb3een|symbol-abothar)[^"\']*["\'][^>]*>\s*(.*?)\s*<\/span>/isu', function ($m) {
            $ch = html_entity_decode(trim(strip_tags($m[1])), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            return ' ' . self::symbolCharToMarker($ch) . ' ';
        }, $html);
        return $html;
    }

    /**
     * Map a symbol glyph (Arb3een PUA or legacy Abo-Thar ASCII) back to its clean marker.
     */
    public static function symbolCharToMarker(string $ch): string
    {
        return match ($ch) {
            "\u{F031}", "\u{F032}", "\u{F033}", "\u{F059}", 'J', 'a', 'd', 'n', 's', '2' => '(ص)',
            "\u{F034}", "\u{F038}", 'A', '8', 'g', 'j', '4' => '(ع)',
            "\u{F035}", "\u{F039}", 'h', '$', '3', '5' => '(عه)',
            "\u{F036}", "\u{F03A}", 'B', 'C', 'b', 'c', '6' => '(عما)',
            "\u{F037}", "\u{F03B}", 'D', '%', '7' => '(عع)',
            "\u{F049}", 'I' => '(عج)',
            "\u{F042}", "\u{F043}", "\u{F044}", 'H', 'u', 'v' => '(قده)',
            "\u{F04A}", "\u{F04B}", 'K' => '(دام ظله)',
            "\u{F04C}", 'L' => '(مد ظله)',
            "\u{F045}", "\u{F046}", "\u{F047}", "\u{F048}", "\u{F03C}", "\u{F03D}", "\u{F03E}", "\u{F03F}", "\u{F040}", "\u{F041}", 'E', 'w' => '(ره)',
            "\u{F050}", "\u{F051}", "\u{F052}", "\u{F053}", 'P' => '(رض)',
            "\u{F04F}", 'O' => '(طاب ثراه)',
            default => '(ع)',
        };
    }

    /**
     * Render all canonical markers (ص), (ع), (عه), etc. into Arb3een313-L HTML spans.
     */
    public static function replaceMarkersWithSymbols(string $text): string
    {
        $text = preg_replace('/<img\b[^>]*src=["\'][^"\']*prophet-pbuhaf\.svg[^"\']*["\'][^>]*>/i', '(ص)', $text);

        foreach (self::HONORIFIC_SYMBOLS as $marker => $info) {
            $pattern = '/(?:\(|«|\[)[ \t]*' . preg_quote($marker, '/') . '[ \t]*(?:\)|»|\])/u';
            $replacement = '<span class="symbol-arb3een symbol-abothar text-emerald-800" title="' . $info['title'] . '">' . $info['entity'] . '</span>';
            $text = preg_replace($pattern, $replacement, $text);
        }

        $prophetSpan = '<span class="symbol-arb3een symbol-abothar text-emerald-800" title="صلى الله عليه وآله">&#xF032;</span>';
        $text = preg_replace('/(?:ﷺ|﵌|&#xFD4C;)/u', $prophetSpan, $text);

        return $text;
    }

    /**
     * Clean text nodes inside HTML while strictly preserving HTML tags and attributes.
     */
    public static function cleanHtmlTextNodes(string $html): string
    {
        $html = self::convertSymbolSpansToMarkers($html);

        $parts = preg_split('/(<\/?(?:[a-zA-Z][a-zA-Z0-9]*|!--.*?--)(?:\s+[^>]*)?>)/su', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        $result = '';

        foreach ($parts as $part) {
            if (empty($part)) continue;
            if (preg_match('/^<\/?(?:[a-zA-Z][a-zA-Z0-9]*|!--.*?--)(?:\s+[^>]*)?>$/su', $part)) {
                $result .= $part;
            } else {
                $result .= self::cleanInlineText($part);
            }
        }

        return $result;
    }

    /**
     * Sanitize messy Microsoft Word / Office HTML tags, comments, styles, and namespaces.
     * Unwraps Word's fragmented <span>/<font> runs so multi-word honorifics, quotes, and brackets aren't split.
     */
    public static function sanitizeWordHtml(string $html): string
    {
        $html = self::convertSymbolSpansToMarkers($html);

        $html = preg_replace('/<!--\[if[^\]]*\]>.*?<!\[endif\]-->/is', '', $html);
        $html = preg_replace('/<!--.*?-->/s', '', $html);
        $html = preg_replace('/<xml\b[^>]*>.*?<\/xml>/is', '', $html);
        $html = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $html);
        $html = preg_replace('/<meta\b[^>]*>/is', '', $html);
        $html = preg_replace('/<link\b[^>]*>/is', '', $html);
        $html = preg_replace('/<title\b[^>]*>.*?<\/title>/is', '', $html);
        $html = preg_replace('/<\?xml\b[^>]*>/is', '', $html);
        $html = preg_replace('/<\/?\w+:[^>]*>/is', '', $html);

        $html = preg_replace('/<p\b[^>]*class=["\']?(?:MsoHeading1|MsoTitle)["\']?[^>]*>(.*?)<\/p>/is', '<h3>$1</h3>', $html);
        $html = preg_replace('/<p\b[^>]*class=["\']?MsoHeading2["\']?[^>]*>(.*?)<\/p>/is', '<h4>$1</h4>', $html);
        $html = preg_replace('/<p\b[^>]*class=["\']?MsoHeading[3-6]["\']?[^>]*>(.*?)<\/p>/is', '<h5>$1</h5>', $html);

        $html = preg_replace_callback('/(?:<p\b[^>]*class=["\']?[^"\']*MsoListParagraph[^"\']*["\']?[^>]*>.*?<\/p>\s*)+/is', function ($m) {
            preg_match_all('/<p\b[^>]*>(.*?)<\/p>/is', $m[0], $items);
            $lis = '';
            foreach ($items[1] as $item) {
                $cleanItem = preg_replace('/^[\s\x{00A0}·•*\-–—]+/u', '', trim(strip_tags($item)));
                if ($cleanItem !== '') {
                    $lis .= '<li>' . $cleanItem . '</li>';
                }
            }
            return $lis ? '<ul>' . $lis . '</ul>' : '';
        }, $html);

        $html = preg_replace('/\s+class=["\']?Mso\w*["\']?/i', '', $html);
        $html = preg_replace('/<\/?(?:span|font)\b[^>]*>/is', '', $html);

        $html = preg_replace_callback('/\s+style=(["\'])(.*?)\1/is', function ($matches) {
            $style = $matches[2];
            $keep = [];
            if (preg_match('/text-align\s*:\s*(center|right|justify)/i', $style, $m)) {
                $keep[] = 'text-align: ' . strtolower($m[1]) . ';';
            }
            return empty($keep) ? '' : ' style="' . implode(' ', $keep) . '"';
        }, $html);

        $html = preg_replace('/<\/(p|h[1-6])>\s*<p\b[^>]*>\s*:\s*<\/p>/iu', ':</$1>', $html);
        $html = preg_replace('/<p\b[^>]*>\s*(?:&nbsp;|\s)*<\/p>/is', '', $html);

        return trim($html);
    }

    /**
     * Render the transcript to rich, scholarly HTML with Uthmanic font for Quranic verses,
     * Arb3een313-L honorific symbols, Q&A badges, linked footnotes, couplets, and Almoneer headings.
     */
    public static function format(?string $text): string
    {
        if (empty(trim($text ?? ''))) {
            return '<p class="text-slate-500 italic">لا يوجد تفريغ نصي متوفر لهذه المحاضرة حالياً.</p>';
        }

        $text = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $text);
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = str_replace("\\t", " ", $text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $plainNormalized = self::extractNormalizedBlocks($text);

        return self::renderNormalizedBlocks($plainNormalized);
    }

    /**
     * Extract normalized logical blocks from either Word HTML or Plain Text.
     */
    protected static function extractNormalizedBlocks(string $input): string
    {
        $isHtml = (bool) preg_match('/<\s*(?:p|div|h[1-6]|ul|ol|li|blockquote|table|span|sup|br)\b/i', $input);

        if ($isHtml) {
            return self::toCleanText($input);
        }

        return self::clean($input);
    }

    /**
     * Render normalized clean text with markers into full scholarly HTML.
     */
    protected static function renderNormalizedBlocks(string $cleaned): string
    {
        // 0. إذا كانت البسملة متصلة بالآية الافتتاحية و"صدق الله العلي العظيم" في سطر واحد، نفصلها في 3 أسطر مستقلة
        $cleaned = preg_replace(
            '/^(بسم الله الرحمن الرحيم)[ \t]*(\{[^}]+\}(?:[ \t]*\[[^\]]+\])?)[ \t]*(صدق الله العلي العظيم)?$/um',
            "$1\n\n$2\n\n$3",
            $cleaned
        );
        $cleaned = preg_replace('/^(بسم الله الرحمن الرحيم)[ \t]+(.+)$/um', "$1\n\n$2", $cleaned);
        $cleaned = preg_replace('/^(\{[^}]+\}(?:[ \t]*\[[^\]]+\])?)[ \t]+(صدق الله العلي العظيم)$/um', "$1\n\n$2", $cleaned);

        // 0.1 معالجة صيغ الشعر المسبوقة بـ "كما ورد في القول:" أو "قال الشاعر:" في نفس السطر
        $cleaned = preg_replace(
            '/^((?:كما ورد في القول|قال الشاعر|يقول الشاعر|كقول الشاعر|ومنه قوله|كقوله)[ \t]*:)[ \t]*(.+?\|.+)$/um',
            "$1\n\n[poem]\n$2\n[/poem]",
            $cleaned
        );

        // 0.2 تحويل الأسطر الشعرية الصريحة التي تحتوي على فاصل الشطرين (|) خارج وسوم [poem] إلى [poem]...[/poem]
        $lines = explode("\n", $cleaned);
        $rebuiltLines = [];
        $poemBuffer = [];
        $inExplicitPoem = false;

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (preg_match('/^\[(?:poem|شعر)\]$/iu', $trimmed)) {
                $inExplicitPoem = true;
                $rebuiltLines[] = $line;
                continue;
            }
            if (preg_match('/^\[\/(?:poem|شعر)\]$/iu', $trimmed)) {
                $inExplicitPoem = false;
                $rebuiltLines[] = $line;
                continue;
            }
            if (!$inExplicitPoem && str_contains($trimmed, '|') && !str_starts_with($trimmed, '[t]') && !str_starts_with($trimmed, '[س]')) {
                // إذا بدأ السطر بـ "كما ورد في القول:" نفصله قبل القصيدة
                if (preg_match('/^((?:كما ورد في القول|قال الشاعر|يقول الشاعر|كقول الشاعر|ومنه قوله|كقوله)[ \t]*:)[ \t]*(.+)$/u', $trimmed, $pm)) {
                    if (!empty($poemBuffer)) {
                        $rebuiltLines[] = "\n[poem]\n" . implode("\n", $poemBuffer) . "\n[/poem]\n";
                        $poemBuffer = [];
                    }
                    $rebuiltLines[] = $pm[1];
                    $trimmed = trim($pm[2]);
                }
                foreach (self::splitTwoCoupletLine($trimmed) as $sc) {
                    $poemBuffer[] = $sc;
                }
            } else {
                if ($trimmed === '' && !empty($poemBuffer)) {
                    continue;
                }
                if (!empty($poemBuffer)) {
                    $rebuiltLines[] = "\n[poem]\n" . implode("\n", $poemBuffer) . "\n[/poem]\n";
                    $poemBuffer = [];
                }
                $rebuiltLines[] = $line;
            }
        }
        if (!empty($poemBuffer)) {
            $rebuiltLines[] = "\n[poem]\n" . implode("\n", $poemBuffer) . "\n[/poem]\n";
        }
        $cleaned = implode("\n", $rebuiltLines);

        // 1. استخراج قسم "الهوامش:" في نهاية المحاضرة (بما في ذلك الأسطر الملتفة لأي هامش)
        $footnotesHtml = '';
        if (preg_match('/(?:^|\n)[ \t]*(?:\[t\])?[ \t]*الهوامش[ \t]*:?[ \t]*(?:\[\/t\])?[ \t]*\n+([\s\S]+)$/u', $cleaned, $fnMatch)) {
            $footnotesRaw = trim($fnMatch[1]);
            $cleaned = substr($cleaned, 0, -strlen($fnMatch[0])) . "\n\n";
            $footnotesHtml = self::renderFootnotesSection($footnotesRaw);
        } elseif (preg_match('/(?:^|\n)((?:[ \t]*\[(?:\d+|[a-zA-Z0-9_\-]+)\][ \t]+[^\n]+(?:\n+|$))+)\s*$/u', $cleaned, $fnMatch)) {
            $footnotesRaw = trim($fnMatch[1]);
            $cleaned = substr($cleaned, 0, -strlen($fnMatch[0])) . "\n\n";
            $footnotesHtml = self::renderFootnotesSection($footnotesRaw);
        }

        // 2. استبدال أبيات وقصائد الشعر [poem]...[/poem] أو [شعر]...[/شعر]
        $cleaned = preg_replace_callback('/\[(?:poem|شعر)\](.*?)\[\/(?:poem|شعر)\]/su', function ($matches) {
            return "\n\n" . self::renderPoemBlock($matches[1]) . "\n\n";
        }, $cleaned);

        // 3. تقسيم النص إلى فقرات منطقية لمعالجة العناوين، المحاور التمهيدية، الحوارات، والفقرات
        $rawParagraphs = preg_split("/\n[ \t]*\n/u", trim($cleaned));
        $blocks = [];
        foreach ($rawParagraphs as $p) {
            $p = trim($p);
            if ($p !== '') {
                $blocks[] = $p;
            }
        }

        $htmlBlocks = [];
        $count = count($blocks);
        $i = 0;

        while ($i < $count) {
            $block = $blocks[$i];

            // أ. إذا كانت الكتلة صندوق شعر جاهز
            if (str_starts_with($block, '<div class="lecture-poem-box')) {
                $htmlBlocks[] = $block;
                $i++;
                continue;
            }

            // ب. فحص ما إذا كانت هناك عناصر محاور تمهيدية متتالية
            if (self::isSummaryAxisLine($block) && ($i + 1 < $count) && self::isSummaryAxisLine($blocks[$i + 1])) {
                $listItems = [];
                $seenOrdinals = [];
                while ($i < $count && self::isSummaryAxisLine($blocks[$i])) {
                    $ord = self::extractAxisOrdinal($blocks[$i]);
                    if ($ord !== null && in_array($ord, $seenOrdinals, true)) {
                        break;
                    }
                    if ($ord !== null) {
                        $seenOrdinals[] = $ord;
                    }
                    $itemText = preg_replace('/^[-•*][ \t]*/u', '', $blocks[$i]);
                    $listItems[] = self::formatInlineElements($itemText);
                    $i++;
                }
                $htmlBlocks[] = self::renderBulletList($listItems);
                continue;
            }

            // ج. فحص القوائم النقطية المبدوءة بـ - أو • أو *
            $lines = array_values(array_filter(array_map('trim', explode("\n", $block))));
            $isBulletBlock = count($lines) >= 1 && count(array_filter($lines, fn($l) => (bool) preg_match('/^[-•*][ \t]+/u', $l))) === count($lines);
            if ($isBulletBlock) {
                $listItems = [];
                while ($i < $count) {
                    $curLines = array_values(array_filter(array_map('trim', explode("\n", $blocks[$i]))));
                    $allBullets = count($curLines) >= 1 && count(array_filter($curLines, fn($l) => (bool) preg_match('/^[-•*][ \t]+/u', $l))) === count($curLines);
                    if (!$allBullets) break;
                    foreach ($curLines as $cl) {
                        $itemText = preg_replace('/^[-•*][ \t]*/u', '', $cl);
                        $listItems[] = self::formatInlineElements($itemText);
                    }
                    $i++;
                }
                $htmlBlocks[] = self::renderBulletList($listItems);
                continue;
            }

            // د. معالجة كتلة مفردة
            $htmlBlocks[] = self::renderSingleBlock($block);
            $i++;
        }

        if ($footnotesHtml !== '') {
            $htmlBlocks[] = $footnotesHtml;
        }

        return implode("\n\n", array_filter($htmlBlocks));
    }

    /**
     * Extract ordinal word from an axis summary line (الأول، الثاني، الثالث...).
     */
    protected static function extractAxisOrdinal(string $block): ?string
    {
        $plain = trim(str_replace(['[t]', '[/t]', '[c]', '[/c]'], '', $block));
        if (preg_match('/^(?:[-•*][ \t]*)?(?:المحور[ \t]+)?(الأول|الثاني|الثالث|الرابع|الخامس|السادس)[ \t]*:/u', $plain, $m)) {
            return $m[1];
        }
        return null;
    }

    /**
     * Check if a paragraph is a short introductory axis item (e.g. "الأول: ..." or "المحور الأول: ..." in a consecutive list).
     */
    protected static function isSummaryAxisLine(string $block): bool
    {
        if (str_contains($block, "\n")) {
            return false;
        }
        $plain = trim(str_replace(['[t]', '[/t]', '[c]', '[/c]'], '', $block));
        if (preg_match('/^(?:[-•*][ \t]*)?(?:الأول|الثاني|الثالث|الرابع|الخامس|السادس)[ \t]*:/u', $plain)) {
            return true;
        }
        if (preg_match('/^(?:[-•*][ \t]*)?المحور[ \t]+(?:الأول|الثاني|الثالث|الرابع|الخامس|السادس)[ \t]*:/u', $plain) && mb_strlen($plain) < 140) {
            return true;
        }
        return false;
    }

    /**
     * Render a single logical block into HTML.
     * Joins any accidental single-line wraps within a prose paragraph so full justification/kashida works without broken lines.
     */
    protected static function renderSingleBlock(string $block): string
    {
        // توحيد الأسطر المنكسرة داخل الفقرة الواحدة إلى مسافة واحدة
        $block = trim(preg_replace('/\s*\n\s*/u', ' ', $block));

        // 1. أسئلة الحوارات [س]...[/س]
        if (preg_match('/^\[س\][ \t]*(.*?)[ \t]*\[\/س\]$/su', $block, $m)) {
            return self::renderQuestionBlock(self::formatInlineElements($m[1]));
        }

        // 2. أجوبة الحوارات [ج]...[/ج]
        if (preg_match('/^\[ج\][ \t]*(.*?)[ \t]*\[\/ج\]$/su', $block, $m)) {
            return self::renderAnswerBlock(self::formatInlineElements($m[1]));
        }

        // 3. عنوان رئيسي موسوم بـ [t]...[/t]
        if (preg_match('/^\[t\][ \t]*(.*?)[ \t]*\[\/t\]$/su', $block, $m)) {
            $inner = trim($m[1]);
            if (self::isSubheadingText($inner)) {
                return self::renderSubHeading(self::formatInlineElements($inner));
            }
            return self::renderMainHeading(self::formatInlineElements($inner));
        }

        // 4. عنوان فرعي موسوم بـ [c]...[/c]
        if (preg_match('/^\[c\][ \t]*(.*?)[ \t]*\[\/c\]$/su', $block, $m)) {
            return self::renderSubHeading(self::formatInlineElements(trim($m[1])));
        }

        $plain = trim($block);

        // 5. البسملة والتصديق والحمدلة والآية الافتتاحية المستقلة وخاتمة المحاضرة
        if (preg_match('/^(بسم الله الرحمن الرحيم|صدق الله العلي العظيم|والحمد لله رب العالمين|والحمدلله رب العالمين|وصلى الله على سيدنا محمد وآله الطاهرين|وصلى الله على محمد وآله الطاهرين|وصلى الله على محمد وآله الطيبين الطاهرين)$/u', $plain)) {
            return '<p class="text-center font-bold text-slate-900 my-4 text-base font-scholarly" style="text-align: center !important; font-weight: bold !important; margin: 1.25rem 0 !important;">' . self::formatInlineElements($plain) . '</p>';
        }
        if (preg_match('/^\{[^}]+\}(?:[ \t]*\[[^\]]+\])?$/u', $plain)) {
            return '<p class="text-center font-bold text-slate-900 my-4 text-base font-scholarly" style="text-align: center !important; font-weight: bold !important; margin: 1.25rem 0 !important;">' . self::formatInlineElements($plain) . '</p>';
        }

        // 6. العناوين الكبرى المستقلة (المحور الأول:...، المحاور:، الحوارات:، الفصل الأول:...، إلخ)
        if (self::isMainHeadingText($plain)) {
            return self::renderMainHeading(self::formatInlineElements($plain));
        }

        // 7. العناوين الفرعية المستقلة القصيرة (الركيزة الأولى:، الأمر الأول:، إلخ)
        if (self::isSubheadingText($plain)) {
            return self::renderSubHeading(self::formatInlineElements($plain));
        }

        // 8. فقرة تبدأ بعنوان فرعي متبوع بالشرح في نفس الفقرة (مثل "الركيزة الأولى: أكدت السيرة النبوية...")
        $formattedInline = self::formatInlineElements($plain);
        $subPrefixPattern = '/^((?:الركيزة|الصفة|المصداق|الخطوة|الشاهد|الفرع|الأمر|الوجه|التنبيه|المسألة|الجهة|البند|النقطة|المقام|القاعدة)[ \t]+(?:الأول|الأولى|الثاني|الثانية|الثالث|الثالثة|الرابع|الرابعة|الخامس|الخامسة|السادس|السادسة|السابع|السابعة|الثامن|الثامنة|التاسع|التاسعة|العاشر|العاشرة|\d+)[ \t]*:)[ \t]*(.+)$/su';
        if (preg_match($subPrefixPattern, $formattedInline, $pm)) {
            $formattedInline = '<strong class="lecture-inline-subheading font-bold" style="color: #8B4513 !important; font-weight: bold !important; font-size: 1.08em !important;">' . $pm[1] . '</strong> ' . $pm[2];
        }

        return '<p class="transcript-p mb-6 leading-loose text-justify text-slate-800 font-scholarly" style="line-height: 2.2 !important; text-align: justify !important; text-justify: kashida !important; -ms-text-justify: kashida !important; margin-bottom: 1.5rem !important;">' . $formattedInline . '</p>';
    }

    /**
     * Determine if a standalone line is a Main Heading (#990000).
     */
    protected static function isMainHeadingText(string $plain): bool
    {
        if (mb_strlen($plain) > 130 || str_contains($plain, "\n")) {
            return false;
        }
        return (bool) preg_match(
            '/^(?:المحاور|محاور المحاضرة|محاور البحث|الحوارات|الأسئلة والحوارات|المحور|الفصل|الباب|المنطلق|المقدمة|الخاتمة|المبحث)(?:[ \t]+(?:الأول|الأولى|الثاني|الثانية|الثالث|الثالثة|الرابع|الرابعة|الخامس|الخامسة|السادس|السادسة|\d+))?(?:[ \t]*:.*|[ \t]*:)?$/u',
            $plain
        );
    }

    /**
     * Determine if a standalone short line is a Subheading (#8B4513).
     */
    protected static function isSubheadingText(string $plain): bool
    {
        if (mb_strlen($plain) > 95 || str_contains($plain, "\n")) {
            return false;
        }
        return (bool) preg_match(
            '/^(?:الركيزة|الصفة|المصداق|الخطوة|الشاهد|الفرع|الأمر|الوجه|التنبيه|المسألة|الجهة|البند|النقطة|المقام)[ \t]+(?:الأول|الأولى|الثاني|الثانية|الثالث|الثالثة|الرابع|الرابعة|الخامس|الخامسة|السادس|السادسة|السابع|السابعة|الثامن|الثامنة|التاسع|التاسعة|العاشر|العاشرة|\d+)[ \t]*:?$/u',
            $plain
        );
    }

    /**
     * Build a base-safe anchor href that works whether the app runs from domain root or a subfolder,
     * and even when <base href="..."> is present in <head>.
     */
    protected static function buildAnchorHref(string $targetId): string
    {
        try {
            if (function_exists('request') && request() && !request()->isMethod('POST')) {
                return e(url()->current()) . '#' . $targetId;
            }
        } catch (\Throwable $e) {
            // Fallback below
        }
        return '#' . $targetId;
    }

    /**
     * Format inline elements within a paragraph or heading:
     * Quranic verses, Quranic references, quotes, footnotes [1], custom anchors, [c] tags, and Arb3een honorific symbols.
     */
    protected static function formatInlineElements(string $text): string
    {
        $text = str_replace(['[t]', '[/t]'], '', $text);

        // 1. العناوين الفرعية المضمنة [c]...[/c]
        $text = preg_replace_callback('/\[c\](.*?)\[\/c\]/su', function ($matches) {
            return '<strong class="lecture-inline-subheading font-bold" style="color: #8B4513 !important; font-weight: bold !important; font-size: 1.08em !important;">' . e(trim($matches[1])) . '</strong>';
        }, $text);

        // 2. الآيات القرآنية {الآية} أو ﴿الآية﴾ بالخط العثماني واللون الأخضر المعتمد
        $text = preg_replace_callback('/(?:\{|﴿)([^}﴾]+)(?:\}|﴾)/u', function ($matches) {
            $verse = html_entity_decode(trim($matches[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            return '<span class="quran-verse font-quran font-normal" style="color: #09B182; font-family: \'Traditional Arabic\', \'Amiri\', serif;">﴿' . $verse . '﴾</span>';
        }, $text);

        // 3. مراجع السور والآيات [آل عمران: 140] أو [البقرة: 2]
        $text = preg_replace_callback('/\[([\p{Arabic}\s]+:\s*\d+)\]/u', function ($matches) {
            return '<span class="quran-ref inline-flex items-center gap-1 font-sans text-xs bg-gold-50 text-gold-700 px-2 py-0.5 rounded-md border border-gold-200 font-semibold">' . e($matches[0]) . '</span>';
        }, $text);

        // 4. علامات التنصيص «...» و ”...“ بتنسيق علمي مميز (#153D63)
        $text = preg_replace('/«([^»]+)»/u', '<span class="text-emerald-900 font-semibold font-scholarly px-0.5" style="color: #153D63;">«$1»</span>', $text);
        $text = preg_replace('/”([^“]+)“/u', '<span class="hadith-quote font-semibold font-scholarly px-0.5" style="color: #153D63;">”$1“</span>', $text);

        // 5. الروابط المرجعية والمراسي المخصصة [إحالة:anchor]النص[/إحالة] و [مرساة:anchor]
        $text = preg_replace_callback('/\[(?:إحالة|رابط|ref):([^\]]+)\](.*?)\[\/(?:إحالة|رابط|ref)\]/su', function ($m) {
            $slug = preg_replace('/[^a-zA-Z0-9_\-\p{Arabic}]/u', '-', trim($m[1]));
            $label = trim($m[2]) !== '' ? trim($m[2]) : '[' . $slug . ']';
            $targetId = str_starts_with($slug, 'fn-') ? $slug : 'fn-' . $slug;
            $refId = 'fnref-' . preg_replace('/^fn-/', '', $slug);
            $href = self::buildAnchorHref($targetId);
            return '<sup class="footnote-ref font-sans font-bold mx-0.5" style="color: #8B4513 !important; font-size: 0.78em !important; font-weight: bold !important;"><a href="' . $href . '" id="' . e($refId) . '" data-scroll-to="' . e($targetId) . '" onclick="const t=document.getElementById(this.dataset.scrollTo);if(t){t.scrollIntoView({behavior:\'smooth\',block:\'center\'});return false;}" style="color: #8B4513 !important; text-decoration: none !important;">' . e($label) . '</a></sup>';
        }, $text);

        $text = preg_replace_callback('/\[(?:مرساة|anchor):([^\]]+)\]/u', function ($m) {
            $slug = preg_replace('/[^a-zA-Z0-9_\-\p{Arabic}]/u', '-', trim($m[1]));
            $fnSlug = str_starts_with($slug, 'fn-') ? $slug : 'fn-' . $slug;
            return '<span id="' . e($slug) . '" class="custom-anchor"><span id="' . e($fnSlug) . '"></span></span>';
        }, $text);

        // 6. أرقام الهوامش المرجعية في المتن [1]، [2]
        $text = preg_replace_callback('/\[(\d+)\]/u', function ($m) {
            $num = $m[1];
            $targetId = 'fn-' . $num;
            $refId = 'fnref-' . $num;
            $href = self::buildAnchorHref($targetId);
            return '<sup class="footnote-ref font-sans font-bold mx-0.5" style="color: #8B4513 !important; font-size: 0.78em !important; font-weight: bold !important;"><a href="' . $href . '" id="' . $refId . '" data-scroll-to="' . $targetId . '" onclick="const t=document.getElementById(this.dataset.scrollTo);if(t){t.scrollIntoView({behavior:\'smooth\',block:\'center\'});return false;}" style="color: #8B4513 !important; text-decoration: none !important;">[' . $num . ']</a></sup>';
        }, $text);

        // 7. استبدال الرموز التكريمية والصلوات بخط الأربعين (Arb3een313-L)
        $text = self::replaceMarkersWithSymbols($text);

        return $text;
    }

    /**
     * Helper to render major lecture headings (#990000).
     */
    protected static function renderMainHeading(string $title): string
    {
        return '<h3 class="lecture-heading text-xl sm:text-2xl font-bold font-scholarly text-red-800 my-8 block leading-snug" style="color: #990000 !important; font-weight: bold !important; font-size: 1.55rem !important; margin-top: 2.25rem !important; margin-bottom: 1rem !important; font-family: \'Traditional Arabic\', \'Amiri\', \'Arb3een\', serif !important;">' . $title . '</h3>';
    }

    /**
     * Helper to render subheadings (#8B4513).
     */
    protected static function renderSubHeading(string $title): string
    {
        return '<h4 class="lecture-subheading text-lg sm:text-xl font-bold font-scholarly my-6 block leading-snug" style="color: #8B4513 !important; font-weight: bold !important; font-size: 1.35rem !important; margin-top: 1.75rem !important; margin-bottom: 0.75rem !important; font-family: \'Traditional Arabic\', \'Amiri\', \'Arb3een\', serif !important;">' . $title . '</h4>';
    }

    /**
     * Helper to render a styled bullet list.
     */
    protected static function renderBulletList(array $items): string
    {
        $lis = '';
        foreach ($items as $item) {
            $lis .= '  <li class="lecture-bullet-item font-bold text-slate-900 leading-loose" style="list-style-type: disc !important; display: list-item !important; margin-bottom: 0.6rem !important; font-weight: bold !important; color: #1e293b !important; line-height: 2.1 !important;">' . $item . '</li>' . "\n";
        }
        return '<ul class="lecture-bullets list-disc pr-8 my-6 space-y-2 text-slate-900 font-bold" style="list-style-type: disc !important; padding-right: 2.25rem !important; margin: 1.25rem 0 !important; display: block !important;">' . "\n" . $lis . '</ul>';
    }

    /**
     * Render a dialogue Question block [س]...[/س] matching قالب المحاضرة والرموز.docx (docx_image1.png).
     */
    protected static function renderQuestionBlock(string $questionHtml): string
    {
        return '<div class="lecture-qa-q my-4 flex items-start gap-3 font-scholarly" style="margin: 1.1rem 0 0.6rem 0 !important; display: flex !important; align-items: flex-start !important; gap: 0.75rem !important;">'
             . '<span class="qa-badge-q inline-flex items-center justify-center rounded-md text-white font-bold shrink-0 select-none" style="background-color: #a61c1c !important; color: #ffffff !important; width: 1.85rem !important; height: 1.85rem !important; border-radius: 0.375rem !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; font-weight: bold !important; font-size: 1rem !important; margin-top: 0.25rem !important; flex-shrink: 0 !important;">س</span>'
             . '<div class="qa-text-q font-bold leading-loose text-justify" style="color: #990000 !important; font-weight: bold !important; line-height: 2.1 !important; text-align: justify !important; text-justify: kashida !important; flex: 1 !important;">' . $questionHtml . '</div>'
             . '</div>';
    }

    /**
     * Render a dialogue Answer block [ج]...[/ج] matching قالب المحاضرة والرموز.docx (docx_image1.png).
     */
    protected static function renderAnswerBlock(string $answerHtml): string
    {
        return '<div class="lecture-qa-a my-3 mb-6 flex items-start gap-3 font-scholarly" style="margin: 0.5rem 0 1.5rem 0 !important; display: flex !important; align-items: flex-start !important; gap: 0.75rem !important;">'
             . '<span class="qa-badge-a inline-flex items-center justify-center rounded-md text-white font-bold shrink-0 select-none" style="background-color: #689f38 !important; color: #ffffff !important; width: 1.85rem !important; height: 1.85rem !important; border-radius: 0.375rem !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; font-weight: bold !important; font-size: 1rem !important; margin-top: 0.25rem !important; flex-shrink: 0 !important;">ج</span>'
             . '<div class="qa-text-a font-bold text-slate-800 leading-loose text-justify" style="color: #1e293b !important; font-weight: bold !important; line-height: 2.1 !important; text-align: justify !important; text-justify: kashida !important; flex: 1 !important;">' . $answerHtml . '</div>'
             . '</div>';
    }

    /**
     * Render the bottom Footnotes section matching قالب المحاضرة والرموز.docx (docx_image3.png).
     * Automatically joins wrapped lines belonging to the same footnote item and builds URL-aware anchor links.
     */
    protected static function renderFootnotesSection(string $rawFootnotes): string
    {
        $rawLines = preg_split('/\n+/u', trim($rawFootnotes));
        $mergedItems = [];

        foreach ($rawLines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            if (preg_match('/^\[([^\]]+)\][ \t]*(.+)$/u', $line, $m)) {
                $mergedItems[] = [
                    'id'   => trim($m[1]),
                    'text' => trim($m[2]),
                ];
            } elseif (!empty($mergedItems)) {
                $mergedItems[count($mergedItems) - 1]['text'] .= ' ' . $line;
            }
        }

        $itemsHtml = '';
        foreach ($mergedItems as $item) {
            $id = preg_replace('/[^a-zA-Z0-9_\-\p{Arabic}]/u', '-', $item['id']);
            $targetId = 'fn-' . $id;
            $refId = 'fnref-' . $id;
            $href = self::buildAnchorHref($refId);
            $body = self::replaceMarkersWithSymbols(e(trim($item['text'])));

            $itemsHtml .= '  <div id="' . e($targetId) . '" class="footnote-item text-sm leading-relaxed flex items-start gap-2" style="font-size: 0.92rem !important; line-height: 1.9 !important; display: flex !important; align-items: flex-start !important; gap: 0.5rem !important;">'
                        . '<a href="' . $href . '" data-scroll-to="' . e($refId) . '" onclick="const t=document.getElementById(this.dataset.scrollTo);if(t){t.scrollIntoView({behavior:\'smooth\',block:\'center\'});return false;}" class="footnote-num font-mono font-bold shrink-0" style="color: #0284c7 !important; font-weight: bold !important; text-decoration: none !important;" dir="ltr">[' . e($item['id']) . ']</a>'
                        . '<span class="footnote-text" style="color: #57534e !important;">' . $body . '</span>'
                        . '</div>' . "\n";
        }

        if ($itemsHtml === '') {
            return '';
        }

        return '<div class="lecture-footnotes-box mt-10 pt-4 border-t border-slate-300 rounded-t-xl px-5 py-4 space-y-1.5 font-scholarly" style="margin-top: 2.5rem !important; border-top: 1px solid #cbd5e1 !important; background: linear-gradient(to bottom, #f5f5f4, rgba(245,245,244,0.2)) !important; padding: 1rem 1.25rem !important; border-radius: 0.75rem 0.75rem 0 0 !important;">' . "\n"
             . '  <h4 class="lecture-footnotes-title font-bold text-base mb-2.5" style="color: #78716c !important; font-weight: bold !important; font-size: 1.05rem !important; margin-bottom: 0.65rem !important;">الهوامش</h4>' . "\n"
             . $itemsHtml
             . '</div><!-- /lecture-footnotes-box -->';
    }

    /**
     * Helper to render poetic couplets ([poem]...[/poem] or [شعر]...[/شعر] or lines with | or ✤).
     */
    public static function renderPoemBlock(string $content): string
    {
        $content = str_replace(["\r\n", "\r", "<br />", "<br>", "<br/>", "</p>", "</div>"], "\n", $content);
        $content = strip_tags($content);
        $content = preg_replace('/\[\/?(?:poem|شعر)\]/iu', '', $content);
        $content = preg_replace('/[ \t]*✤[ \t]*/u', ' | ', $content);

        $rawLines = explode("\n", $content);
        $lines = [];

        foreach ($rawLines as $rawLine) {
            $rawLine = trim($rawLine);
            if ($rawLine === '') continue;

            foreach (self::splitTwoCoupletLine($rawLine) as $sc) {
                $lines[] = $sc;
            }
        }

        $coupletsHtml = '';

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            if (str_contains($line, '|')) {
                $parts = explode('|', $line, 2);
                $first = trim($parts[0]);
                $second = trim($parts[1]);
            } elseif (preg_match('/[ \t]{3,}|\t|\.{3,}|…/u', $line)) {
                $parts = preg_split('/[ \t]{3,}|\t|\.{3,}|…/u', $line, 2);
                $first = trim($parts[0]);
                $second = trim($parts[1]);
            } else {
                $first = $line;
                $second = '';
            }

            if (empty($second)) {
                $coupletsHtml .= '  <div class="couplet-line text-center py-2.5 border-b border-dotted border-amber-200/60 leading-loose" style="display: block !important; text-align: center !important;">' . "\n";
                $coupletsHtml .= '    <span class="text-slate-900 font-bold font-scholarly w-full text-center block text-base sm:text-lg" style="color: #501549 !important; font-weight: bold !important; text-align: center !important; text-align-last: center !important;">' . self::replaceMarkersWithSymbols(e($first)) . '</span>' . "\n";
                $coupletsHtml .= '  </div>' . "\n";
            } else {
                $coupletsHtml .= '  <div class="couplet-line py-2.5 border-b border-dotted border-amber-200/60 leading-loose" style="display: grid !important; grid-template-columns: 1fr auto 1fr !important; align-items: center !important; gap: 0.75rem !important;">' . "\n";
                $coupletsHtml .= '    <span class="couplet-first font-bold font-scholarly text-base sm:text-lg" style="color: #501549 !important; font-weight: bold !important; width: 100% !important; text-align: left !important; text-align-last: left !important;">' . self::replaceMarkersWithSymbols(e($first)) . '</span>' . "\n";
                $coupletsHtml .= '    <span class="couplet-center-icon text-gold-600 text-xs sm:text-sm shrink-0 px-2 select-none" style="color: #501549 !important;">✤</span>' . "\n";
                $coupletsHtml .= '    <span class="couplet-second font-bold font-scholarly text-base sm:text-lg" style="color: #501549 !important; font-weight: bold !important; width: 100% !important; text-align: right !important; text-align-last: right !important;">' . self::replaceMarkersWithSymbols(e($second)) . '</span>' . "\n";
                $coupletsHtml .= '  </div>' . "\n";
            }
        }

        return '<div class="lecture-poem-box couplets-container my-8 py-5 px-4 sm:px-8 bg-amber-50/40 rounded-2xl border border-amber-200/60 max-w-2xl mx-auto shadow-2xs">' . "\n"
             . $coupletsHtml
             . '</div><!-- /lecture-poem-box -->';
    }

    /**
     * Convert formatted HTML back into clean plain text with Almoneer formatting inputs
     * ([t]...[/t], [c]...[/c], { }, « », ” “, [poem]...[/poem], [س]...[/س], [ج]...[/ج], [1], الهوامش:).
     */
    public static function toCleanText(string $html): string
    {
        if (empty(trim($html))) {
            return '';
        }

        // If the input is already plain text with no HTML tags, just clean it directly
        if (!preg_match('/<\s*(?:p|div|h[1-6]|ul|ol|li|blockquote|table|span|sup|br|strong|b)\b/i', $html)) {
            return self::clean($html);
        }

        $html = str_replace(["\r\n", "\r"], "\n", $html);

        // 0.1 Convert poem blocks back to [poem]...[/poem] (handling <br> and ✤ inside edited poem boxes)
        $html = preg_replace_callback('/<div\b[^>]*class=["\'][^"\']*(?:lecture-poem-box|couplets-container)[^"\']*["\'][^>]*>((?:\s*<div\b[^>]*class=["\'][^"\']*couplet-line[^"\']*["\'][^>]*>.*?<\/div>\s*)+)<\/div>(?:\s*<!--\s*\/lecture-poem-box\s*-->)?/su', function ($m) {
            $inner = self::convertSymbolSpansToMarkers($m[1]);
            preg_match_all('/<div\b[^>]*class=["\'][^"\']*couplet-line[^"\']*["\'][^>]*>(.*?)<\/div>/su', $inner, $lines);
            $rawPoemLines = [];

            if (!empty($lines[1])) {
                foreach ($lines[1] as $lineHtml) {
                    if (preg_match('/class=["\'][^"\']*couplet-first[^"\']*["\'][^>]*>(.*?)<\/span>.*?class=["\'][^"\']*couplet-second[^"\']*["\'][^>]*>(.*?)<\/span>/su', $lineHtml, $part)) {
                        $rawPoemLines[] = trim(strip_tags($part[1])) . ' | ' . trim(strip_tags($part[2]));
                    } else {
                        $subLines = preg_split('/<br\s*\/?>|\n/iu', $lineHtml);
                        foreach ($subLines as $sl) {
                            $cleanSl = trim(strip_tags(str_replace('✤', ' | ', $sl)));
                            if ($cleanSl !== '' && !preg_match('/^\[\/?(?:poem|شعر)\]$/iu', $cleanSl)) {
                                $rawPoemLines[] = $cleanSl;
                            }
                        }
                    }
                }
            }

            if (empty($rawPoemLines)) {
                return '';
            }

            $introLine = '';
            if (preg_match('/^((?:كما ورد في القول|قال الشاعر|يقول الشاعر|كقول الشاعر|ومنه قوله|كقوله)[ \t]*:?)$/u', $rawPoemLines[0])) {
                $introLine = array_shift($rawPoemLines) . "\n\n";
            }

            return "\n\n" . $introLine . "[poem]\n" . implode("\n", $rawPoemLines) . "\n[/poem]\n\n";
        }, $html);

        // 0.2 Convert Q&A blocks back to [س]...[/س] and [ج]...[/ج]
        $html = preg_replace_callback('/<div\b[^>]*class=["\'][^"\']*lecture-qa-q[^"\']*["\'][^>]*>.*?<div\b[^>]*class=["\'][^"\']*qa-text-q[^"\']*["\'][^>]*>(.*?)<\/div>\s*<\/div>/su', function ($m) {
            $qText = preg_replace('/\s+/u', ' ', trim(strip_tags(self::convertSymbolSpansToMarkers($m[1]))));
            return "\n\n[س] " . $qText . " [/س]\n\n";
        }, $html);

        $html = preg_replace_callback('/<div\b[^>]*class=["\'][^"\']*lecture-qa-a[^"\']*["\'][^>]*>.*?<div\b[^>]*class=["\'][^"\']*qa-text-a[^"\']*["\'][^>]*>(.*?)<\/div>\s*<\/div>/su', function ($m) {
            $aText = preg_replace('/\s+/u', ' ', trim(strip_tags(self::convertSymbolSpansToMarkers($m[1]))));
            return "\n\n[ج] " . $aText . " [/ج]\n\n";
        }, $html);

        // 0.3 Convert Footnotes box back to الهوامش:\n[1] ...
        $extractedFootnotes = [];
        $html = preg_replace_callback('/<div\b[^>]*class=["\'][^"\']*lecture-footnotes-box[^"\']*["\'][^>]*>((?:.*?<div\b[^>]*class=["\'][^"\']*footnote-item[^"\']*["\'][^>]*>.*?<\/div>\s*)+)<\/div>(?:\s*<!--\s*\/lecture-footnotes-box\s*-->)?/su', function ($m) use (&$extractedFootnotes) {
            preg_match_all('/<div\b[^>]*class=["\'][^"\']*footnote-item[^"\']*["\'][^>]*>.*?(\[[^\]]+\]).*?<span\b[^>]*class=["\'][^"\']*footnote-text[^"\']*["\'][^>]*>(.*?)<\/span>\s*<\/div>/su', $m[1], $items, PREG_SET_ORDER);
            foreach ($items as $item) {
                $extractedFootnotes[] = $item[1] . ' ' . preg_replace('/\s+/u', ' ', trim(strip_tags(self::convertSymbolSpansToMarkers($item[2]))));
            }
            return '';
        }, $html);

        // 0.4 Convert inline footnote superscripts and custom anchors back to [1] or [إحالة:anchor]label[/إحالة]
        $html = preg_replace_callback('/<sup\b[^>]*class=["\'][^"\']*footnote-ref[^"\']*["\'][^>]*>\s*(?:<a\b[^>]*id=["\']fnref-([^"\']+)["\'][^>]*>)?\s*(.*?)\s*(?:<\/a>)?\s*<\/sup>/isu', function ($m) {
            $slug = trim($m[1] ?? '');
            $label = trim(strip_tags($m[2] ?? ''));
            if ($slug !== '' && !preg_match('/^\d+$/', $slug)) {
                return '[إحالة:' . $slug . ']' . $label . '[/إحالة]';
            }
            if (preg_match('/^\[([^\]]+)\]$/u', $label, $lm)) {
                return '[' . $lm[1] . ']';
            }
            return $slug !== '' ? '[' . $slug . ']' : $label;
        }, $html);

        $html = preg_replace('/<span\b[^>]*id=["\']([^"\']+)["\'][^>]*class=["\'][^"\']*custom-anchor[^"\']*["\'][^>]*>.*?<\/span>(?:\s*<\/span>)?/isu', '[مرساة:$1]', $html);

        // 0.5 Convert inline subheadings <strong class="lecture-inline-subheading">...</strong>
        $html = preg_replace('/<(?:strong|span)\b[^>]*class=["\'][^"\']*lecture-inline-subheading[^"\']*["\'][^>]*>(.*?)<\/(?:strong|span)>/isu', '$1', $html);

        // 1. Convert Prophet & Arb3een / Abo-Thar symbols back to clean markers
        $html = self::convertSymbolSpansToMarkers($html);

        // 1.1 Remove XML declarations and conditional MS Office comments
        $html = preg_replace('/<!--\[if[^\]]*\]>.*?<!\[endif\]-->/is', '', $html);
        $html = preg_replace('/<!--.*?-->/s', '', $html);
        $html = preg_replace('/<(?:xml|style|meta|link|title)\b[^>]*>.*?<\/(?:xml|style|meta|link|title)>/is', '', $html);
        $html = preg_replace('/<\/?\w+:[^>]*>/is', '', $html);

        // 1.2 Remove accidental <br> inside normal prose paragraphs (<p class="transcript-p...">)
        $html = preg_replace_callback('/<p\b[^>]*>(.*?)<\/p>/isu', function ($m) {
            $inner = preg_replace('/<br\s*\/?>/i', ' ', $m[1]);
            $inner = preg_replace('/\s*\n\s*/u', ' ', $inner);
            return '<p>' . $inner . '</p>';
        }, $html);

        // 1.3 Collapse soft HTML source line-wraps inside <li> and <h1-6>
        $html = preg_replace_callback('/<(li|h[1-6])\b[^>]*>(.*?)<\/\1>/isu', function ($m) {
            $inner = preg_replace('/\s*\n\s*/u', ' ', $m[2]);
            return '<' . $m[1] . '>' . $inner . '</' . $m[1] . '>';
        }, $html);

        // 2. Convert headings
        $text = preg_replace_callback('/<h[1-3][^>]*>(.*?)<\/h[1-3]>/su', function ($m) {
            return "\n\n[t]" . trim(strip_tags($m[1])) . "[/t]\n\n";
        }, $html);

        $text = preg_replace_callback('/<h[4-6][^>]*>(.*?)<\/h[4-6]>/su', function ($m) {
            return "\n\n[c]" . trim(strip_tags($m[1])) . "[/c]\n\n";
        }, $text);

        // 3. Convert Word MsoListParagraph or <li> bullet lists
        $text = preg_replace_callback('/<p\b[^>]*class=["\']?[^"\']*MsoListParagraph[^"\']*["\']?[^>]*>(.*?)<\/p>/isu', function ($m) {
            $cleanItem = preg_replace('/^[\s\x{00A0}·•*\-–—]+/u', '', trim(strip_tags($m[1])));
            return "\n- " . $cleanItem . "\n";
        }, $text);

        $text = preg_replace_callback('/<li[^>]*>(.*?)<\/li>/su', function ($m) {
            $cleanItem = preg_replace('/^[\s\x{00A0}·•*\-–—]+/u', '', trim(strip_tags($m[1])));
            return "\n- " . $cleanItem . "\n";
        }, $text);

        // 4. Convert Quranic verses to { }
        $text = preg_replace('/<span[^>]*class=["\']?[^"\']*quran-verse[^"\']*["\']?[^>]*>﴿?\s*(.*?)\s*﴾?<\/span>/su', '{$1}', $text);

        // 5. Convert quotes
        $text = preg_replace('/<span[^>]*class=["\']?[^"\']*text-emerald-900[^"\']*["\']?[^>]*>«?(.*?)»?<\/span>/su', '«$1»', $text);
        $text = preg_replace('/<span[^>]*class=["\']?[^"\']*hadith-quote[^"\']*["\']?[^>]*>”?(.*?)“?<\/span>/su', '”$1“', $text);

        // 6. Convert paragraphs and linebreaks
        $text = str_replace(['<br />', '<br>', '<br/>'], "\n", $text);
        $text = preg_replace('/<\/(?:p|div|ul|ol|table|tr|blockquote)>/i', "\n\n", $text);
        $text = strip_tags($text);

        // 7. Re-attach extracted footnotes box (and merge any stray trailing footnote lines like "/ ص 77." or "[2] ...")
        if (!empty($extractedFootnotes)) {
            // Check if the end of $text has stray continuation lines from a previously split footnote box
            if (preg_match('/\n+([ \t]*\/[ \t]*ص[ \t]*\d+[^\n]*)(?:\n+([ \t]*\[\d+\][^\n]+))*\s*$/u', $text, $strayMatch)) {
                $lines = preg_split('/\n+/u', trim($strayMatch[0]));
                $text = substr($text, 0, -strlen($strayMatch[0]));
                foreach ($lines as $sl) {
                    $sl = trim($sl);
                    if ($sl === '') continue;
                    if (preg_match('/^\[\d+\]/u', $sl)) {
                        $extractedFootnotes[] = $sl;
                    } else {
                        $extractedFootnotes[count($extractedFootnotes) - 1] .= ' ' . $sl;
                    }
                }
            }
            $text = rtrim($text) . "\n\nالهوامش:\n" . implode("\n", $extractedFootnotes) . "\n";
        }

        return self::clean($text);
    }

    /**
     * Full processing pipeline for Almoneer Vacum tool.
     * Returns formatted HTML, clean text with formatting inputs, and extracted content stats.
     */
    public static function process(string $input): array
    {
        $cleanText = self::extractNormalizedBlocks($input);
        $formattedHtml = self::renderNormalizedBlocks($cleanText);
        // Re-extract clean text from formatted HTML so automatic [t], [poem], bullets, and footnotes are explicitly marked in clean_text
        $canonicalCleanText = self::toCleanText($formattedHtml);

        $plainText = strip_tags($formattedHtml);
        $words = count(preg_split('/\s+/u', trim($plainText), -1, PREG_SPLIT_NO_EMPTY));
        $chars = mb_strlen($plainText);
        $versesCount = substr_count($formattedHtml, 'quran-verse');
        $headingsCount = substr_count($formattedHtml, 'lecture-heading') + substr_count($formattedHtml, 'lecture-subheading');

        return [
            'html' => $formattedHtml,
            'clean_text' => $canonicalCleanText,
            'stats' => [
                'chars' => $chars,
                'words' => $words,
                'verses' => $versesCount,
                'headings' => $headingsCount,
            ]
        ];
    }

    /**
     * Format short description / lecture summary with proper heading colors (#990000),
     * bullet lists, and Arb3een honorific symbols.
     */
    public static function formatDescription(?string $text): string
    {
        if (empty(trim($text ?? ''))) {
            return '';
        }

        $text = self::sanitizeWordHtml($text);
        $text = self::cleanHtmlTextNodes($text);
        $text = self::replaceMarkersWithSymbols($text);

        $text = preg_replace_callback('/<p\b[^>]*>\s*(?:<strong>|<b>)?\s*(المحاور|محاور المحاضرة|محاور البحث):?\s*(?:<\/strong>|<\/b>)?\s*<\/p>/u', function ($m) {
            return '<div class="lecture-themes-heading text-lg sm:text-xl font-bold font-scholarly text-red-900 mt-1 mb-2 flex items-center gap-2" style="color: #990000 !important; font-weight: bold !important; font-family: \'Traditional Arabic\', \'Amiri\', \'Arb3een\', serif !important; font-size: 1.35rem !important;">'
                 . '<i class="fa-solid fa-compass text-gold-600 text-sm"></i>'
                 . '<span>' . trim($m[1]) . ':</span>'
                 . '</div>';
        }, $text);

        $text = preg_replace_callback('/<p\b[^>]*>\s*[-•]\s*(.*?)<\/p>/su', function ($m) {
            return '<div class="lecture-theme-item flex items-start gap-2.5 text-slate-800 my-1.5 font-scholarly text-sm sm:text-base leading-relaxed" style="line-height: 2 !important;">'
                 . '<span class="text-emerald-700 font-bold mt-0.5">•</span>'
                 . '<span>' . trim($m[1]) . '</span>'
                 . '</div>';
        }, $text);

        return $text;
    }
}

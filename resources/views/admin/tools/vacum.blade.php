@extends('layouts.admin')

@section('title', 'أداة تنظيف النصوص (المخمة)')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="p-6 rounded-3xl bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 text-white shadow-xl flex flex-wrap items-center justify-between gap-4 border border-gold-500/30">
        <div class="space-y-1">
            <span class="text-xs text-gold-300 font-semibold flex items-center gap-1.5">
                <i class="fa-solid fa-broom text-gold-400"></i>
                <span>أدوات إدارة المحتوى والتحرير</span>
            </span>
            <h2 class="text-2xl font-bold font-scholarly text-white">أداة «المخمة» لتنظيف وتنسيق نصوص المحاضرات</h2>
            <p class="text-xs text-slate-300">انسخ النص من ملف Word وضعه هنا، ثم اضغط على «خم النص» لتنظيفه وتنسيق الآيات القرآنية وعلامات الترقيم والأقواس فورياً.</p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="cleanIt()" class="px-5 py-2.5 rounded-xl bg-gold-500 hover:bg-gold-400 text-emerald-950 font-bold text-xs shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>خم النص وتنظيفه الآن</span>
            </button>
        </div>
    </div>

    <!-- Main Cleaning Panels (2 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Left/Start Panel: Raw Text Input -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4 flex flex-col justify-between">
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span>النص الخام (المسودة أو من ملف Word):</span>
                    </h3>
                    <button type="button" onclick="clearInput()" class="text-xs text-slate-400 hover:text-red-600 transition">
                        <i class="fa-solid fa-trash-can ml-1"></i>
                        <span>مسح</span>
                    </button>
                </div>
                <textarea id="inText" rows="16" placeholder="الصق هنا النص المنسوخ من الوورد..." class="w-full text-xs rounded-2xl border-slate-200 p-4 bg-slate-50 font-mono leading-relaxed focus:bg-white focus:border-emerald-700 transition resize-y"></textarea>
            </div>

            <div class="pt-2 flex items-center justify-between border-t border-slate-100">
                <span class="text-[11px] text-slate-400" id="inTextStats">0 حرف • 0 كلمة</span>
                <button type="button" onclick="cleanIt()" class="px-5 py-2 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs shadow transition flex items-center gap-1.5">
                    <i class="fa-solid fa-broom"></i>
                    <span>خم النص</span>
                </button>
            </div>
        </div>

        <!-- Right/End Panel: Clean Text Output -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4 flex flex-col justify-between">
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span>النص النظيف المنسق:</span>
                    </h3>
                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs">
                        <button type="button" onclick="showTextMode()" id="btn-mode-text" class="px-3 py-1 bg-white rounded-lg shadow-sm font-bold text-emerald-900 transition">نص منسق</button>
                        <button type="button" onclick="showHtmlMode()" id="btn-mode-html" class="px-3 py-1 text-slate-600 rounded-lg hover:bg-white/60 transition">كود HTML</button>
                    </div>
                </div>

                <!-- Clean Text Box -->
                <textarea id="outText" rows="16" readonly placeholder="ستظهر النتيجة النظيفة هنا..." class="w-full text-xs rounded-2xl border-slate-200 p-4 bg-emerald-50/30 text-slate-900 font-mono leading-relaxed transition resize-y"></textarea>
                
                <!-- Clean HTML Box (Hidden by default) -->
                <textarea id="outTextHtml" rows="16" readonly style="display:none" class="w-full text-xs rounded-2xl border-slate-200 p-4 bg-slate-900 text-emerald-400 font-mono leading-relaxed transition resize-y" dir="ltr"></textarea>
            </div>

            <div class="pt-2 flex items-center justify-between border-t border-slate-100">
                <span class="text-[11px] text-slate-400" id="outTextStats">0 حرف • 0 كلمة</span>
                <button type="button" onclick="copyCleanText()" id="copy-btn" class="px-5 py-2 rounded-xl bg-gold-500 hover:bg-gold-400 text-emerald-950 font-bold text-xs shadow transition flex items-center gap-1.5">
                    <i class="fa-regular fa-copy"></i>
                    <span>نسخ النص النظيف</span>
                </button>
            </div>
        </div>

    </div>

    <!-- Rules & Quick Guide -->
    <div class="p-6 rounded-2xl bg-slate-100/80 border border-slate-200 text-xs space-y-3">
        <h4 class="font-bold text-slate-800 flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-emerald-800"></i>
            <span>قواعد الاستبدال والتنظيف المطبقة في «المخمة»:</span>
        </h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-slate-600 leading-relaxed">
            <div class="bg-white p-3 rounded-xl border border-slate-200/80">
                <strong class="text-slate-800 block mb-1">الآيات والأقواس:</strong>
                توحيد أقواس الآيات القرآنية { }، وتحويل الأقواس المزدوجة (( )) إلى أقواس تنصيص عربية « » و ” “.
            </div>
            <div class="bg-white p-3 rounded-xl border border-slate-200/80">
                <strong class="text-slate-800 block mb-1">علامات الترقيم:</strong>
                ضبط المسافات الصحيحة بعد (، . : ؛ ؟ !)، وإزالة المسافات الزائدة قبلها ودمج النقاط المزدوجة.
            </div>
            <div class="bg-white p-3 rounded-xl border border-slate-200/80">
                <strong class="text-slate-800 block mb-1">الأرقام والهوامش:</strong>
                تحويل الأرقام الهندية (٠-٩) إلى العربية القياسية (0-9)، وضبط الإحالات المرجعية [1] إلى (1).
            </div>
            <div class="bg-white p-3 rounded-xl border border-slate-200/80">
                <strong class="text-slate-800 block mb-1">الصلوات والأدعية:</strong>
                توحيد عبارات «عليه السلام» إلى (ع)، و«عليهم السلام» إلى (عع)، و«صلى الله عليه وآله» إلى (ص).
            </div>
        </div>
    </div>

</div>

<script>
let currentMode = 'text';

function updateStats(text, targetId) {
    const chars = text.length;
    const words = text.trim() ? text.trim().split(/\s+/).length : 0;
    document.getElementById(targetId).innerText = `${chars} حرف • ${words} كلمة`;
}

document.getElementById('inText').addEventListener('input', function() {
    updateStats(this.value, 'inTextStats');
});

function clearInput() {
    document.getElementById('inText').value = '';
    document.getElementById('outText').value = '';
    document.getElementById('outTextHtml').value = '';
    updateStats('', 'inTextStats');
    updateStats('', 'outTextStats');
}

function cleanIt() {
    let inText = document.getElementById('inText').value;
    if (!inText.trim()) {
        alert('يرجى وضع النص في حقل النص الخام أولاً');
        return;
    }

    // 0. معالجة كسر الأسطر الحرفية وفك رموز HTML المشفرة (مثل &#1648; للألف الخنجرية وعلامات الوقف)
    inText = inText.replace(/\\r\\n|\\n|\\r/g, "\n");
    inText = inText.replace(/\\t/g, " ");

    const tempDecoder = document.createElement('textarea');
    tempDecoder.innerHTML = inText;
    inText = tempDecoder.value;

    // قواعد المخمة الأصلية المطابقة لـ vacum.js
    inText = inText.replace(/,/g, "،");
    inText = inText.replace(/\،\s*/g, "، ");
    inText = inText.replace(/\.\s*/g, ". ");
    inText = inText.replace(/:\s*/g, ": ");
    inText = inText.replace(/؛\s*/g, "؛ ");
    inText = inText.replace(/؟\s*/g, "؟ ");
    inText = inText.replace(/!\s*/g, "! ");
    inText = inText.replace(/\(\(/g, "«");
    inText = inText.replace(/\)\)/g, "»");
    inText = inText.replace(/«\s*/g, " «");
    inText = inText.replace(/\s*»/g, "» ");
    inText = inText.replace(/"([^"]+?)"/g, function($0, $1) { return " ”" + $1 + "“ "; });
    inText = inText.replace(/﴿/g, "{");
    inText = inText.replace(/﴾/g, "}");
    inText = inText.replace(/–/g, "-");
    inText = inText.replace(/(?<!\w)-(?!\w)/g, " - ");

    // الأرقام
    inText = inText.replace(/[٠-٩]/g, function(d) {
        return "٠١٢٣٤٥٦٧٨٩".indexOf(d);
    });

    // إزالة الفراغات
    inText = inText.replace(/[\t]+/g, " ");
    inText = inText.replace(/[ ]{2,}/g, " ");
    inText = inText.replace(/^[ \t]+|[ \t]+$/gm, "");
    inText = inText.replace(/\n[ \t]+/g, "\n");
    inText = inText.replace(/[ \t]+\n/g, "\n");

    // العناوين والنصوص المميزة (مطابقة لنظام الموقع القديم)
    inText = inText.replace(/@@(.*?)@@/g, function($0, $1) { return "\n\n[t]" + $1.trim() + "[/t]\n\n"; });
    inText = inText.replace(/@@(.*?)\n/g, function($0, $1) { return "\n\n[t]" + $1.trim() + "[/t]\n\n"; });
    inText = inText.replace(/@@/g, "");

    // النصوص والعناوين الفرعية الملونة (كالصفات والخطوات %%...%%)
    inText = inText.replace(/%%(.*?)%%/g, function($0, $1) { return "[c]" + $1.trim() + "[/c]"; });
    inText = inText.replace(/%%(.*?)\n/g, function($0, $1) { return "[c]" + $1.trim() + "[/c]\n"; });
    inText = inText.replace(/%%/g, "");

    // الاختصارات والأدعية
    inText = inText.replace(/ـ/g, "");
    inText = inText.replace(/\sه\s/g, " هـ ");
    inText = inText.replace(/\sه\./g, " هـ.");
    inText = inText.replace(/\sه،/g, " هـ،");
    inText = inText.replace(/\sه:/g, " هـ:");
    inText = inText.replace(/([0-9]+)ه(?!\w)/g, "$1 هـ ");

    inText = inText.replace(/«صلى الله عليه وآله وسلم»/g, "(ص)");
    inText = inText.replace(/"صلى الله عليه وآله وسلم"/g, "(ص)");
    inText = inText.replace(/”صلى الله عليه وآله وسلم“/g, "(ص)");
    inText = inText.replace(/صلى الله عليه وآله وسلم/g, "(ص)");
    inText = inText.replace(/«صلى الله عليه وآله»/g, "(ص)");
    inText = inText.replace(/"صلى الله عليه وآله"/g, "(ص)");
    inText = inText.replace(/”صلى الله عليه وآله“/g, "(ص)");
    inText = inText.replace(/صلى الله عليه وآله/g, "(ص)");

    inText = inText.replace(/«عليه السلام»/g, "(ع)");
    inText = inText.replace(/"عليه السلام"/g, "(ع)");
    inText = inText.replace(/عليه السلام/g, "(ع)");
    inText = inText.replace(/«عليهم السلام»/g, "(عع)");
    inText = inText.replace(/"عليهم السلام"/g, "(عع)");
    inText = inText.replace(/عليهم السلام/g, "(عع)");
    inText = inText.replace(/«عليها السلام»/g, "(عه)");
    inText = inText.replace(/"عليها السلام"/g, "(عه)");
    inText = inText.replace(/عليها السلام/g, "(عه)");

    // تشذيب
    inText = inText.replace(/\. \./g, "..");
    inText = inText.replace(/\، \،/g, "،،");
    inText = inText.replace(/\s+،/g, "،");
    inText = inText.replace(/\s+\./g, ".");
    inText = inText.replace(/\s+:/g, ":");
    inText = inText.replace(/\s+؛/g, "؛");
    inText = inText.replace(/\s+؟/g, "؟");
    inText = inText.replace(/\s+!/g, "!");
    inText = inText.replace(/«\s+/g, "«");
    inText = inText.replace(/\s+»/g, "»");
    inText = inText.replace(/\{\s+/g, "{");
    inText = inText.replace(/\s+\}/g, "}");

    inText = inText.replace(/\sو\s/g, " و");
    inText = inText.replace(/\nو\s/g, "\nو");
    inText = inText.replace(/^و\s/gm, "و");

    inText = inText.replace(/\[\s*(\d+)\s*\]/g, "($1)");
    inText = inText.replace(/(\d+\.)\s+(\d+)/g, "$1$2");
    inText = inText.replace(/\n{3,}/g, "\n\n");

    const cleanedText = inText.trim();
    document.getElementById('outText').value = cleanedText;
    updateStats(cleanedText, 'outTextStats');

    // توليد كود HTML
    generateHtml(cleanedText);
}

function generateHtml(cleanText) {
    const paragraphs = cleanText.split(/\n\s*\n/);
    let html = '';
    paragraphs.forEach(p => {
        p = p.trim();
        if (!p) return;

        // فحص ما إذا كانت الفقرة عبارة عن عنوان رئيسي
        if (p.startsWith('[t]') && p.endsWith('[/t]')) {
            const title = p.replace('[t]', '').replace('[/t]', '').trim();
            html += `<h3 class="lecture-heading" style="color: #990000; font-weight: bold; margin: 20px 0 10px 0;">${title}</h3>\n\n`;
            return;
        }

        // فحص ما إذا كانت الفقرة قائمة نقطية
        const lines = p.split('\n').map(l => l.trim()).filter(Boolean);
        const isBulletList = lines.length > 1 && lines.every(l => l.startsWith('- ') || l.startsWith('• ') || l.startsWith('* '));
        if (isBulletList) {
            html += '<ul style="margin: 15px 0; padding-right: 25px; list-style-type: disc;">\n';
            lines.forEach(l => {
                let item = l.replace(/^[-•*]\s*/, '').trim();
                item = item.replace(/\{([^}]+)\}/g, '<span class="quran-verse" style="color: #BB1111; font-family: \'Traditional Arabic\', serif;">﴿ $1 ﴾</span>');
                item = item.replace(/\[c\](.*?)\[\/c\]/g, '<span style="color: #8B4513; font-weight: bold;">$1</span>');
                html += `  <li style="margin-bottom: 6px;">${item}</li>\n`;
            });
            html += '</ul>\n\n';
            return;
        }

        // معالجة الفقرة العادية
        // معالجة الآيات
        let processed = p.replace(/\{([^}]+)\}/g, '<span class="quran-verse" style="color: #BB1111; font-family: \'Traditional Arabic\', serif;">﴿ $1 ﴾</span>');
        // معالجة النصوص الفرعية الملونة
        processed = processed.replace(/\[c\](.*?)\[\/c\]/g, '<span style="color: #8B4513; font-weight: bold;">$1</span>');
        processed = processed.replace(/\n/g, '<br />\n');

        // توسيط البسملة والتصديق والحمدلة
        if (/^(بسم الله الرحمن الرحيم|صدق الله العلي العظيم|والحمد لله رب العالمين|والحمدلله رب العالمين)$/.test(p.trim())) {
            html += `<p style="text-align: center; font-weight: bold; margin: 15px 0;">${processed}</p>\n\n`;
        } else {
            html += `<p style="line-height: 2; text-align: justify; margin-bottom: 16px;">${processed}</p>\n\n`;
        }
    });
    document.getElementById('outTextHtml').value = html.trim();
}

function showTextMode() {
    currentMode = 'text';
    document.getElementById('outText').style.display = '';
    document.getElementById('outTextHtml').style.display = 'none';
    document.getElementById('btn-mode-text').className = 'px-3 py-1 bg-white rounded-lg shadow-sm font-bold text-emerald-900 transition';
    document.getElementById('btn-mode-html').className = 'px-3 py-1 text-slate-600 rounded-lg hover:bg-white/60 transition';
}

function showHtmlMode() {
    currentMode = 'html';
    document.getElementById('outText').style.display = 'none';
    document.getElementById('outTextHtml').style.display = '';
    document.getElementById('btn-mode-html').className = 'px-3 py-1 bg-emerald-800 text-white rounded-lg shadow-sm font-bold transition';
    document.getElementById('btn-mode-text').className = 'px-3 py-1 text-slate-600 rounded-lg hover:bg-white/60 transition';
}

function copyCleanText() {
    const target = currentMode === 'text' ? document.getElementById('outText') : document.getElementById('outTextHtml');
    if (!target.value.trim()) {
        alert('لا يوجد نص لنسخه');
        return;
    }
    navigator.clipboard.writeText(target.value).then(() => {
        const btn = document.getElementById('copy-btn');
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check"></i> تم النسخ بنجاح!';
        setTimeout(() => { btn.innerHTML = orig; }, 2000);
    });
}
</script>
@endsection

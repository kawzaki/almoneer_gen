@extends('layouts.admin')

@section('title', 'أداة تنقيح وتنسيق النصوص (المخمة)')

@push('styles')
<style>
    /* Styling for the live website preview */
    .vacum-preview-box {
        font-family: 'Traditional Arabic', 'Amiri', 'IBM Plex Sans Arabic', serif;
    }
    .vacum-preview-box .quran-verse {
        font-family: 'Traditional Arabic', 'Amiri', serif !important;
        color: #BB1111 !important;
        font-size: 1.15em;
    }
    .vacum-preview-box .lecture-heading {
        color: #990000 !important;
        font-weight: bold !important;
        font-size: 1.55rem !important;
        margin-top: 2rem !important;
        margin-bottom: 0.85rem !important;
        font-family: 'Traditional Arabic', 'Amiri', serif !important;
        line-height: 1.4 !important;
    }
    .vacum-preview-box .lecture-subheading {
        color: #8B4513 !important;
        font-weight: bold !important;
        font-size: 1.35rem !important;
        margin-top: 1.6rem !important;
        margin-bottom: 0.65rem !important;
        font-family: 'Traditional Arabic', 'Amiri', serif !important;
        line-height: 1.4 !important;
    }
    .vacum-preview-box .lecture-bullets {
        list-style-type: disc !important;
        padding-right: 2.25rem !important;
        margin: 1.25rem 0 !important;
    }
    .vacum-preview-box .lecture-bullet-item {
        font-weight: bold !important;
        color: #1e293b !important;
        line-height: 2.1 !important;
        margin-bottom: 0.5rem !important;
    }
    .vacum-preview-box .prophet-symbol {
        font-family: 'Traditional Arabic', 'Amiri', serif !important;
        font-size: 1.2em !important;
        font-weight: bold !important;
        color: #0f172a !important;
    }
    .vacum-preview-box p {
        line-height: 2.2 !important;
        text-align: justify;
        margin-bottom: 1.25rem;
        color: #1e293b;
    }
    /* Placeholder for contenteditable */
    [contenteditable=true]:empty:before {
        content: attr(data-placeholder);
        color: #94a3b8;
        font-style: italic;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="p-6 rounded-3xl bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 text-white shadow-xl flex flex-wrap items-center justify-between gap-4 border border-gold-500/30">
        <div class="space-y-1 max-w-2xl">
            <span class="text-xs text-gold-300 font-semibold flex items-center gap-1.5">
                <i class="fa-solid fa-broom text-gold-400"></i>
                <span>أدوات إدارة المحتوى والتحرير المركزي</span>
            </span>
            <h2 class="text-2xl font-bold font-scholarly text-white">أداة «المخمة» لتنقيح وتنسيق نصوص المحاضرات والمقالات</h2>
            <p class="text-xs text-slate-300 leading-relaxed">انسخ النص من ملف Word والصقه مباشرة هنا؛ ستقوم الأداة بتهذيب النص، تحويل الأرقام، ضبط الآيات القرآنية بالخط العثماني، تمييز العناوين باللون الأحمر والفروع بالبني، وإدراج رمز النبي ﷺ، لتجهيزه للنشر الفوري على الموقع.</p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="processVacum()" id="btn-main-run" class="px-5 py-3 rounded-xl bg-gold-500 hover:bg-gold-400 text-emerald-950 font-bold text-xs shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>خم وتنسيق النص الآن</span>
            </button>
        </div>
    </div>

    <!-- Main Workspace Panels (2 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Left/Start Panel: Raw / Word Text Input -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span>مساحة اللصق والإدخال (من Word أو المسودة):</span>
                    </h3>
                    
                    <!-- Input Mode Tabs -->
                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs">
                        <button type="button" onclick="setInputMode('word')" id="tab-in-word" class="px-3 py-1 bg-white rounded-lg shadow-sm font-bold text-emerald-900 transition flex items-center gap-1">
                            <i class="fa-regular fa-file-word text-blue-600"></i>
                            <span>لصق من Word (منسق)</span>
                        </button>
                        <button type="button" onclick="setInputMode('raw')" id="tab-in-raw" class="px-3 py-1 text-slate-600 rounded-lg hover:bg-white/60 transition flex items-center gap-1">
                            <i class="fa-solid fa-code text-slate-500"></i>
                            <span>نص خام / كود HTML</span>
                        </button>
                    </div>
                </div>

                <!-- Rich Input Container for Word Paste (Preserves HTML Headings & Bullets) -->
                <div id="wrapper-input-word" class="space-y-1">
                    <div id="inRichText" contenteditable="true" data-placeholder="الصق هنا النص المنسوخ من ملف Word مباشرة (Ctrl + V)... سيتم التقاط العناوين والآيات والقوائم بكامل تنسيقاتها." class="w-full min-h-[380px] max-h-[520px] overflow-y-auto text-xs rounded-2xl border border-slate-200 p-4 bg-slate-50 font-sans leading-relaxed focus:bg-white focus:border-emerald-700 focus:outline-none transition"></div>
                    <p class="text-[11px] text-slate-400">نصيحة: انسخ النص مباشرة من Microsoft Word والصقه هنا، وستحتفظ الأداة بهيكل العناوين والفقرات والقوائم.</p>
                </div>

                <!-- Raw Input Container for Plain Text or Raw HTML -->
                <div id="wrapper-input-raw" class="space-y-1 hidden">
                    <textarea id="inRawText" rows="16" placeholder="الصق هنا النص الخام أو كود HTML المصدر..." class="w-full text-xs rounded-2xl border border-slate-200 p-4 bg-slate-50 font-mono leading-relaxed focus:bg-white focus:border-emerald-700 transition resize-y"></textarea>
                    <p class="text-[11px] text-slate-400">يمكنك استخدام علامات @@عنوان@@ للعناوين الكبرى، أو %%نص فرعي%% للعناوين الفرعية، أو {الآية} للآيات.</p>
                </div>
            </div>

            <div class="pt-3 flex items-center justify-between border-t border-slate-100 flex-wrap gap-2">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="clearInput()" class="text-xs text-slate-400 hover:text-red-600 transition flex items-center gap-1">
                        <i class="fa-solid fa-trash-can"></i>
                        <span>مسح المدخلات</span>
                    </button>
                    <span class="text-[11px] text-slate-400" id="inTextStats">0 حرف • 0 كلمة</span>
                </div>
                
                <button type="button" onclick="processVacum()" id="btn-sub-run" class="px-5 py-2 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs shadow transition flex items-center gap-1.5">
                    <i class="fa-solid fa-broom"></i>
                    <span>خم وتنسيق النص</span>
                </button>
            </div>
        </div>

        <!-- Right/End Panel: Formatted Output & Live Preview -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span>مخرجات التنسيق والنشر للموقع:</span>
                    </h3>

                    <!-- Output Mode Tabs -->
                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs">
                        <button type="button" onclick="setOutputMode('preview')" id="tab-out-preview" class="px-3 py-1 bg-white rounded-lg shadow-sm font-bold text-emerald-900 transition flex items-center gap-1">
                            <i class="fa-regular fa-eye text-emerald-700"></i>
                            <span>معاينة حية للموقع</span>
                        </button>
                        <button type="button" onclick="setOutputMode('html')" id="tab-out-html" class="px-3 py-1 text-slate-600 rounded-lg hover:bg-white/60 transition flex items-center gap-1">
                            <i class="fa-solid fa-code text-slate-500"></i>
                            <span>كود HTML للنشر</span>
                        </button>
                        <button type="button" onclick="setOutputMode('text')" id="tab-out-text" class="px-3 py-1 text-slate-600 rounded-lg hover:bg-white/60 transition flex items-center gap-1">
                            <i class="fa-regular fa-file-lines text-slate-500"></i>
                            <span>نص منقح</span>
                        </button>
                    </div>
                </div>

                <!-- 1. Live Scholarly Website Preview Box -->
                <div id="wrapper-out-preview" class="space-y-1">
                    <div id="outPreview" class="vacum-preview-box w-full min-h-[380px] max-h-[520px] overflow-y-auto rounded-2xl border border-amber-200/70 p-6 bg-gradient-to-br from-sand-50/40 via-white to-amber-50/10 text-slate-900 text-sm sm:text-base leading-loose transition">
                        <div class="h-full flex flex-col items-center justify-center text-slate-400 py-16 space-y-2 text-center">
                            <i class="fa-solid fa-wand-sparkles text-3xl text-slate-300"></i>
                            <p class="text-xs">ستظهر هنا المعاينة الحية المطابقة لخطوط وألوان وتنسيقات موقع المنير فور الضغط على «خم وتنسيق النص».</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Clean HTML Code Box -->
                <div id="wrapper-out-html" class="space-y-1 hidden">
                    <textarea id="outHtml" rows="16" readonly placeholder="سيظهر هنا كود HTML النظيف الجاهز للنسخ ولصقه في المحرر..." class="w-full text-xs rounded-2xl border border-slate-200 p-4 bg-slate-900 text-emerald-400 font-mono leading-relaxed transition resize-y" dir="ltr"></textarea>
                </div>

                <!-- 3. Clean Text with Markers Box -->
                <div id="wrapper-out-text" class="space-y-1 hidden">
                    <textarea id="outText" rows="16" readonly placeholder="سيظهر هنا النص المنقح بنظام العلامات..." class="w-full text-xs rounded-2xl border border-slate-200 p-4 bg-emerald-50/30 text-slate-900 font-mono leading-relaxed transition resize-y"></textarea>
                </div>
            </div>

            <div class="pt-3 flex items-center justify-between border-t border-slate-100 flex-wrap gap-2">
                <span class="text-[11px] text-slate-500 font-medium" id="outTextStats">0 حرف • 0 كلمة</span>
                
                <div class="flex items-center gap-2">
                    <!-- Copy Formatted Rich Text Button -->
                    <button type="button" onclick="copyRichText()" id="copy-rich-btn" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs shadow-xs transition flex items-center gap-1.5" title="نسخ بتنسيق الألوان والخطوط للصق المباشر في Word أو أي محرر مرئي">
                        <i class="fa-solid fa-palette text-amber-700"></i>
                        <span>نسخ كنص منسق</span>
                    </button>

                    <!-- Copy Clean HTML Button -->
                    <button type="button" onclick="copyCleanHtml()" id="copy-html-btn" class="px-4 py-2 rounded-xl bg-gold-500 hover:bg-gold-400 text-emerald-950 font-bold text-xs shadow transition flex items-center gap-1.5" title="نسخ كود HTML الجاهز للنشر">
                        <i class="fa-regular fa-copy"></i>
                        <span>نسخ كود HTML للنشر</span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Rules & Quick Guide -->
    <div class="p-6 rounded-2xl bg-slate-100/80 border border-slate-200 text-xs space-y-3">
        <h4 class="font-bold text-slate-800 flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-emerald-800"></i>
            <span>قواعد التنسيق الشاملة المعتمدة لموقع المنير والمطبقة في «المخمة»:</span>
        </h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-slate-600 leading-relaxed">
            <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 space-y-1">
                <strong class="text-red-900 block font-bold flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-red-700"></span>
                    <span>العناوين الرئيسية (#990000):</span>
                </strong>
                <span>تنسيق المحاور والفصول والمنطلقات وعناوين H1-H3 باللون الأحمر الكلاسيكي وحجم 1.55rem مع خط الرقعة/الأميري.</span>
            </div>
            <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 space-y-1">
                <strong class="text-amber-900 block font-bold flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-amber-800"></span>
                    <span>العناوين الفرعية (#8B4513):</span>
                </strong>
                <span>تمييز الصفات، المصاديق، الخطوات، والشواهد الفرعية باللون البني الذهبي الداكن وحجم 1.35rem.</span>
            </div>
            <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 space-y-1">
                <strong class="text-emerald-900 block font-bold flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-700"></span>
                    <span>الآيات والمراجع والصلوات:</span>
                </strong>
                <span>الآيات بالخط العثماني باللون الأحمر (#BB1111) بين أقواس ﴿ ﴾، مراجع السور باللون الذهبي، ورمز النبي المصحفي الشريف ﷺ.</span>
            </div>
            <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 space-y-1">
                <strong class="text-slate-800 block font-bold flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-slate-700"></span>
                    <span>الأرقام والقوائم والترقيم:</span>
                </strong>
                <span>تحويل الأرقام الهندية (٠-٩) إلى القياسية (0-9)، ضبط مسافات الفواصل والنقاط، وتنسيق القوائم النقطية لتطابق مقالات الموقع.</span>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    let inputMode = 'word';
    let outputMode = 'preview';
    let lastFormattedHtml = '';
    let lastCleanText = '';

    // Switch Input Modes (Word Rich vs Raw Text)
    function setInputMode(mode) {
        inputMode = mode;
        const wordWrap = document.getElementById('wrapper-input-word');
        const rawWrap = document.getElementById('wrapper-input-raw');
        const tabWord = document.getElementById('tab-in-word');
        const tabRaw = document.getElementById('tab-in-raw');

        if (mode === 'word') {
            wordWrap.classList.remove('hidden');
            rawWrap.classList.add('hidden');
            tabWord.className = 'px-3 py-1 bg-white rounded-lg shadow-sm font-bold text-emerald-900 transition flex items-center gap-1';
            tabRaw.className = 'px-3 py-1 text-slate-600 rounded-lg hover:bg-white/60 transition flex items-center gap-1';
            // Sync content from raw to rich if available
            if (!document.getElementById('inRichText').innerHTML.trim() && document.getElementById('inRawText').value.trim()) {
                document.getElementById('inRichText').innerText = document.getElementById('inRawText').value;
            }
        } else {
            rawWrap.classList.remove('hidden');
            wordWrap.classList.add('hidden');
            tabRaw.className = 'px-3 py-1 bg-white rounded-lg shadow-sm font-bold text-emerald-900 transition flex items-center gap-1';
            tabWord.className = 'px-3 py-1 text-slate-600 rounded-lg hover:bg-white/60 transition flex items-center gap-1';
            // Sync content from rich to raw if available
            if (!document.getElementById('inRawText').value.trim() && document.getElementById('inRichText').innerHTML.trim()) {
                document.getElementById('inRawText').value = document.getElementById('inRichText').innerHTML;
            }
        }
        updateInputStats();
    }

    // Switch Output Modes (Preview vs HTML vs Text)
    function setOutputMode(mode) {
        outputMode = mode;
        const previewWrap = document.getElementById('wrapper-out-preview');
        const htmlWrap = document.getElementById('wrapper-out-html');
        const textWrap = document.getElementById('wrapper-out-text');

        const tabPreview = document.getElementById('tab-out-preview');
        const tabHtml = document.getElementById('tab-out-html');
        const tabText = document.getElementById('tab-out-text');

        // Reset all tabs
        [tabPreview, tabHtml, tabText].forEach(t => {
            t.className = 'px-3 py-1 text-slate-600 rounded-lg hover:bg-white/60 transition flex items-center gap-1';
        });
        [previewWrap, htmlWrap, textWrap].forEach(w => w.classList.add('hidden'));

        if (mode === 'preview') {
            previewWrap.classList.remove('hidden');
            tabPreview.className = 'px-3 py-1 bg-white rounded-lg shadow-sm font-bold text-emerald-900 transition flex items-center gap-1';
        } else if (mode === 'html') {
            htmlWrap.classList.remove('hidden');
            tabHtml.className = 'px-3 py-1 bg-slate-900 text-emerald-400 rounded-lg shadow-sm font-bold transition flex items-center gap-1';
        } else {
            textWrap.classList.remove('hidden');
            tabText.className = 'px-3 py-1 bg-emerald-800 text-white rounded-lg shadow-sm font-bold transition flex items-center gap-1';
        }
    }

    function updateInputStats() {
        let text = inputMode === 'word' 
            ? document.getElementById('inRichText').innerText.trim()
            : document.getElementById('inRawText').value.trim();
        const chars = text.length;
        const words = text ? text.split(/\s+/).length : 0;
        document.getElementById('inTextStats').innerText = `${chars} حرف • ${words} كلمة`;
    }

    document.getElementById('inRichText').addEventListener('input', updateInputStats);
    document.getElementById('inRawText').addEventListener('input', updateInputStats);

    function clearInput() {
        document.getElementById('inRichText').innerHTML = '';
        document.getElementById('inRawText').value = '';
        document.getElementById('outPreview').innerHTML = `
            <div class="h-full flex flex-col items-center justify-center text-slate-400 py-16 space-y-2 text-center">
                <i class="fa-solid fa-wand-sparkles text-3xl text-slate-300"></i>
                <p class="text-xs">ستظهر هنا المعاينة الحية المطابقة لخطوط وألوان وتنسيقات موقع المنير فور الضغط على «خم وتنسيق النص».</p>
            </div>
        `;
        document.getElementById('outHtml').value = '';
        document.getElementById('outText').value = '';
        lastFormattedHtml = '';
        lastCleanText = '';
        updateInputStats();
        document.getElementById('outTextStats').innerText = '0 حرف • 0 كلمة';
    }

    // Main Processing Pipeline (Backend Fetch with Fallback)
    async function processVacum() {
        let content = '';
        if (inputMode === 'word') {
            // Check if there is rich HTML in the contenteditable div
            content = document.getElementById('inRichText').innerHTML;
        } else {
            content = document.getElementById('inRawText').value;
        }

        if (!content || !content.trim() || content.trim() === '<br>') {
            alert('يرجى وضع أو لصق النص في مساحة الإدخال أولاً');
            return;
        }

        const btnMain = document.getElementById('btn-main-run');
        const btnSub = document.getElementById('btn-sub-run');
        const origMain = btnMain.innerHTML;
        const origSub = btnSub.innerHTML;

        btnMain.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>جارٍ التنقيح والتنسيق...</span>';
        btnSub.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>جارٍ المعالجة...</span>';
        btnMain.disabled = true;
        btnSub.disabled = true;

        try {
            const response = await fetch('{{ route("admin.tools.vacum.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ content: content })
            });

            const data = await response.json();

            if (data.success) {
                lastFormattedHtml = data.html;
                lastCleanText = data.clean_text;

                // Update Preview
                document.getElementById('outPreview').innerHTML = data.html;
                // Update HTML Box
                document.getElementById('outHtml').value = data.html;
                // Update Clean Text Box
                document.getElementById('outText').value = data.clean_text;

                // Update Stats
                const s = data.stats || {};
                document.getElementById('outTextStats').innerText = 
                    `${s.words || 0} كلمة • ${s.chars || 0} حرف • ${s.verses || 0} آية كريمة • ${s.headings || 0} عنوان منسق`;

                // Success flash
                btnMain.innerHTML = '<i class="fa-solid fa-check"></i> <span>تم التنسيق بنجاح!</span>';
                btnSub.innerHTML = '<i class="fa-solid fa-check"></i> <span>تم!</span>';
                setTimeout(() => {
                    btnMain.innerHTML = origMain;
                    btnSub.innerHTML = origSub;
                    btnMain.disabled = false;
                    btnSub.disabled = false;
                }, 1800);
            } else {
                alert(data.message || 'حدث خطأ أثناء معالجة النص.');
                btnMain.innerHTML = origMain;
                btnSub.innerHTML = origSub;
                btnMain.disabled = false;
                btnSub.disabled = false;
            }
        } catch (err) {
            console.error('Vacum fetch error:', err);
            alert('حدث خطأ في الاتصال بالخادم، يرجى المحاولة مجدداً.');
            btnMain.innerHTML = origMain;
            btnSub.innerHTML = origSub;
            btnMain.disabled = false;
            btnSub.disabled = false;
        }
    }

    // Copy Clean HTML Code
    function copyCleanHtml() {
        const textToCopy = lastFormattedHtml || document.getElementById('outHtml').value;
        if (!textToCopy.trim()) {
            alert('لا يوجد كود منسق لنسخه، يرجى تنقيح النص أولاً.');
            return;
        }

        navigator.clipboard.writeText(textToCopy).then(() => {
            const btn = document.getElementById('copy-html-btn');
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check text-emerald-950"></i> <span>تم نسخ كود HTML!</span>';
            setTimeout(() => { btn.innerHTML = orig; }, 2000);
        });
    }

    // Copy Formatted Rich Text (Preserves Colors, Headings, and Verses for pasting into Word or WYSIWYG)
    async function copyRichText() {
        if (!lastFormattedHtml.trim()) {
            alert('لا يوجد نص منسق لنسخه، يرجى تنقيح النص أولاً.');
            return;
        }

        const btn = document.getElementById('copy-rich-btn');
        const orig = btn.innerHTML;

        try {
            const blobHtml = new Blob([lastFormattedHtml], { type: 'text/html' });
            const blobText = new Blob([document.getElementById('outPreview').innerText], { type: 'text/plain' });
            const item = new ClipboardItem({
                'text/html': blobHtml,
                'text/plain': blobText
            });

            await navigator.clipboard.write([item]);
            btn.innerHTML = '<i class="fa-solid fa-check text-emerald-700"></i> <span>تم نسخ النص المنسق!</span>';
            setTimeout(() => { btn.innerHTML = orig; }, 2000);
        } catch (err) {
            // Fallback: select and execCommand copy on preview container
            const container = document.getElementById('outPreview');
            const range = document.createRange();
            range.selectNodeContents(container);
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
            document.execCommand('copy');
            sel.removeAllRanges();

            btn.innerHTML = '<i class="fa-solid fa-check text-emerald-700"></i> <span>تم نسخ النص المنسق!</span>';
            setTimeout(() => { btn.innerHTML = orig; }, 2000);
        }
    }
</script>
@endpush
@endsection

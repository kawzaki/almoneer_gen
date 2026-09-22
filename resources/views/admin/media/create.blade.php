@extends('layouts.admin')

@section('title', 'إضافة مادة إعلامية جديدة')

@push('styles')
<!-- Quill WYSIWYG Editor CSS -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<style>
    .ql-editor {
        direction: rtl !important;
        text-align: right !important;
        font-family: 'IBM Plex Sans Arabic', sans-serif !important;
        font-size: 0.875rem !important;
        line-height: 1.8 !important;
    }
    #quill-editor .ql-editor {
        min-height: 160px !important;
    }
    #quill-transcript .ql-editor {
        min-height: 340px !important;
    }
    .ql-toolbar.ql-snow {
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
        border-color: #e2e8f0;
        background-color: #f8fafc;
        direction: ltr !important;
        text-align: left !important;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
        border-color: #e2e8f0;
        background-color: #ffffff;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">إضافة مادة صوتية أو مرئية</h2>
        <a href="{{ route('admin.media.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← العودة للقائمة</a>
    </div>

    <form id="media-form" action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان المادة الإعلامية:</label>
            <input type="text" name="title" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">النوع:</label>
                <select name="type" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="audio">محاضرة صوتية (MP3)</option>
                    <option value="video">محاضرة مرئية (يوتيوب)</option>
                    <option value="short">ريلز / شورتس قصير</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">التصنيف:</label>
                <select name="category_id" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="">بدون تصنيف</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">الموسم / السنة:</label>
                <input type="text" name="season_year" placeholder="مثال: عاشوراء 1447هـ" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                    <span>رقم الليلة / المحاضرة:</span>
                    <span class="text-slate-400 font-normal text-[10px]">(اختياري)</span>
                </label>
                <input type="number" name="lecture_number" min="1" max="999" placeholder="مثال: 1 أو 19" value="{{ old('lecture_number') }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 font-mono text-center">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    رابط الفيديو (يوتيوب):
                    <span class="text-slate-400 font-normal text-[11px]">(اختياري - في حال عدم توفر الفيديو بعد)</span>
                </label>
                <input type="text" name="media_url" placeholder="https://www.youtube.com/watch?v=..." class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">المدة (مثال: 45:10):</label>
                <input type="text" name="duration" placeholder="45:10" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 text-center font-mono">
            </div>
        </div>

        <!-- Audio Format Options Box (SoundCloud & MP3 Upload) -->
        <div class="p-4 sm:p-5 rounded-2xl bg-amber-50/40 border border-amber-200/80 space-y-4">
            <div class="flex items-center gap-2 border-b border-amber-200/60 pb-2.5">
                <span class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center text-sm shadow-xs">
                    <i class="fa-solid fa-headphones"></i>
                </span>
                <div>
                    <h3 class="text-xs font-bold text-slate-800">التسجيل الصوتي للمحاضرة</h3>
                    <p class="text-[11px] text-slate-500">أضف رابط ساوندكلاود أو ارفع ملف صوتي MP3 لتمكين الزوار من الاستماع المباشر واستخدام المشغل العائم.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- SoundCloud URL -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-brands fa-soundcloud text-orange-500 text-sm"></i>
                            <span>رابط ساوندكلاود (SoundCloud URL):</span>
                        </span>
                        <span class="text-slate-400 font-normal text-[10px]">(اختياري)</span>
                    </label>
                    <input type="url" name="soundcloud_url" value="{{ old('soundcloud_url') }}" placeholder="https://soundcloud.com/..." class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white shadow-2xs focus:ring-1 focus:ring-amber-500 focus:border-amber-500" dir="ltr">
                    <p class="text-[10px] text-slate-400 mt-1">يُمكّن مشغل SoundCloud المدمج والمشغل العائم أثناء تصفح الموقع.</p>
                </div>

                <!-- MP3 Audio File Upload -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-file-audio text-emerald-600 text-sm"></i>
                            <span>رفع ملف صوتي (MP3 / M4A):</span>
                        </span>
                        <span class="text-slate-400 font-normal text-[10px]">(اختياري)</span>
                    </label>
                    <input type="file" name="audio_file" accept="audio/*,.mp3,.m4a,.wav,.ogg" class="w-full text-xs rounded-xl border border-slate-200 p-2 bg-white shadow-2xs cursor-pointer file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-800 file:text-white hover:file:bg-emerald-900">
                </div>
            </div>
        </div>

        <!-- PDF File Attachment -->
        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <span class="w-7 h-7 rounded-lg bg-red-600 text-white flex items-center justify-center text-xs shadow-xs shrink-0">
                    <i class="fa-solid fa-file-pdf"></i>
                </span>
                <div>
                    <label class="block text-xs font-bold text-slate-800">الملف النصي الموثق (PDF):</label>
                    <p class="text-[10px] text-slate-500">ملف PDF لتحميل التفريغ النصي للمحاضرة عبر زر التحميل في صفحة المحاضرة.</p>
                </div>
            </div>
            <div>
                <input type="file" name="pdf_file" accept=".pdf" class="text-xs rounded-xl border border-slate-200 p-1.5 bg-white shadow-2xs cursor-pointer file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-slate-700 file:text-white hover:file:bg-slate-800">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">الكلمات الدلالية والوسوم (Tags):</label>
            <input type="text" name="tags" placeholder="مثال: عاشوراء، العقيدة، الفلسفة، العدل الإلهي، الأخلاق" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            <p class="text-[11px] text-slate-400 mt-1">افصل بين الكلمات الدلالية بفواصل (، أو ,) لتسهيل الفلترة والبحث للمستخدمين.</p>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-semibold text-slate-700">الوصف أو الملخص:</label>
                <button type="button" onclick="toggleRawHtmlMode()" id="toggle-html-btn" class="text-[11px] text-slate-500 hover:text-emerald-800 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-code"></i>
                    <span>تبديل لكود HTML المصدر</span>
                </button>
            </div>
            
            <!-- Hidden input that carries formatted HTML content -->
            <textarea name="description" id="media-description" class="hidden">{{ old('description') }}</textarea>

            <!-- Raw HTML textarea (toggled on demand) -->
            <textarea id="raw-html-editor" rows="6" class="hidden w-full text-xs font-mono rounded-xl border-slate-200 p-3 bg-slate-900 text-slate-100" oninput="syncFromRawHtml(this.value)">{{ old('description') }}</textarea>

            <!-- Quill Editor Container -->
            <div id="quill-editor-wrapper">
                <div id="quill-editor">{!! old('description') !!}</div>
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-semibold text-slate-700">تفريغ المحاضرة:</label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="toggleRawTranscriptMode()" id="toggle-transcript-html-btn" class="text-[11px] text-slate-500 hover:text-emerald-800 font-semibold flex items-center gap-1">
                        <i class="fa-solid fa-code"></i>
                        <span>تبديل لكود HTML المصدر</span>
                    </button>
                    <button type="button" onclick="cleanTranscriptWithVacum()" class="px-3 py-1 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-900 text-xs font-bold transition flex items-center gap-1.5 shadow-sm border border-amber-300/60">
                        <i class="fa-solid fa-broom text-amber-700"></i>
                        <span>تنظيف وتنسيق النص (المخمة)</span>
                    </button>
                </div>
            </div>

            <!-- Hidden input that carries formatted HTML transcript -->
            <textarea name="transcript" id="media-transcript" class="hidden">{{ old('transcript') }}</textarea>

            <!-- Raw HTML textarea (toggled on demand) -->
            <textarea id="raw-transcript-editor" rows="12" class="hidden w-full text-xs font-mono rounded-xl border-slate-200 p-3 bg-slate-900 text-slate-100" oninput="syncFromRawTranscript(this.value)">{{ old('transcript') }}</textarea>

            <!-- Quill Transcript Editor Container -->
            <div id="quill-transcript-wrapper">
                <div id="quill-transcript">{!! old('transcript') !!}</div>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">يمكنك لصق النص المنسوخ من Word وتنسيقه بحرية أو الضغط على زر "تنظيف وتنسيق النص (المخمة)" لضبط علامات الترقيم والأقواس والأرقام والآيات آلياً.</p>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" class="rounded text-emerald-800">
                <span>إبراز في الصفحة الرئيسية</span>
            </label>
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="rounded text-emerald-800">
                <span>مفعل ونشط</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow">حفظ المادة الإعلامية وتحديث الكاش</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- Quill WYSIWYG Editor JS -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    var toolbarOptions = [
        [{ 'header': [2, 3, 4, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'align': [] }],
        ['blockquote', 'link'],
        ['clean']
    ];

    // 1. Initialize Quill Editor for Description
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'اكتب وصف المحاضرة أو الصق النص المنسق هنا...',
        modules: { toolbar: toolbarOptions }
    });

    // 2. Initialize Quill Editor for Transcript
    var quillTranscript = new Quill('#quill-transcript', {
        theme: 'snow',
        placeholder: 'اكتب أو الصق تفريغ المحاضرة هنا مع التنسيقات...',
        modules: { toolbar: toolbarOptions }
    });

    // 3. Form elements & submit sync
    var form = document.getElementById('media-form');
    var descInput = document.getElementById('media-description');
    var rawHtmlEditor = document.getElementById('raw-html-editor');
    var isRawMode = false;
    var quillWrapper = document.getElementById('quill-editor-wrapper');
    var toggleBtn = document.getElementById('toggle-html-btn');

    var transcriptInput = document.getElementById('media-transcript');
    var rawTranscriptEditor = document.getElementById('raw-transcript-editor');
    var isRawTranscriptMode = false;
    var quillTranscriptWrapper = document.getElementById('quill-transcript-wrapper');
    var toggleTranscriptBtn = document.getElementById('toggle-transcript-html-btn');

    form.onsubmit = function() {
        if (!isRawMode) {
            descInput.value = quill.root.innerHTML;
        } else {
            descInput.value = rawHtmlEditor.value;
        }

        if (!isRawTranscriptMode) {
            transcriptInput.value = quillTranscript.root.innerHTML;
        } else {
            transcriptInput.value = rawTranscriptEditor.value;
        }
    };

    // Toggle description raw mode
    function toggleRawHtmlMode() {
        isRawMode = !isRawMode;
        if (isRawMode) {
            rawHtmlEditor.value = quill.root.innerHTML;
            quillWrapper.classList.add('hidden');
            rawHtmlEditor.classList.remove('hidden');
            toggleBtn.innerHTML = '<i class="fa-solid fa-pen-nib"></i> <span>العودة للمحرر المرئي</span>';
        } else {
            quill.root.innerHTML = rawHtmlEditor.value;
            rawHtmlEditor.classList.add('hidden');
            quillWrapper.classList.remove('hidden');
            toggleBtn.innerHTML = '<i class="fa-solid fa-code"></i> <span>تبديل لكود HTML المصدر</span>';
        }
    }

    function syncFromRawHtml(val) {
        descInput.value = val;
    }

    // Toggle transcript raw mode
    function toggleRawTranscriptMode() {
        isRawTranscriptMode = !isRawTranscriptMode;
        if (isRawTranscriptMode) {
            rawTranscriptEditor.value = quillTranscript.root.innerHTML;
            quillTranscriptWrapper.classList.add('hidden');
            rawTranscriptEditor.classList.remove('hidden');
            toggleTranscriptBtn.innerHTML = '<i class="fa-solid fa-pen-nib"></i> <span>العودة للمحرر المرئي</span>';
        } else {
            quillTranscript.root.innerHTML = rawTranscriptEditor.value;
            rawTranscriptEditor.classList.add('hidden');
            quillTranscriptWrapper.classList.remove('hidden');
            toggleTranscriptBtn.innerHTML = '<i class="fa-solid fa-code"></i> <span>تبديل لكود HTML المصدر</span>';
        }
    }

    function syncFromRawTranscript(val) {
        transcriptInput.value = val;
    }

    // 4. Vacum Rules for Transcript Cleaning
    function applyVacumString(text) {
        return text.replace(/,/g, "،")
                   .replace(/\،\s*/g, "، ")
                   .replace(/\.\s*/g, ". ")
                   .replace(/:\s*/g, ": ")
                   .replace(/؛\s*/g, "؛ ")
                   .replace(/؟\s*/g, "؟ ")
                   .replace(/!\s*/g, "! ")
                   .replace(/\(\(/g, "«")
                   .replace(/\)\)/g, "»")
                   .replace(/«\s*/g, " «")
                   .replace(/\s*»/g, "» ")
                   .replace(/"([^"]+?)"/g, " ”$1“ ")
                   .replace(/﴿/g, "{")
                   .replace(/﴾/g, "}")
                   .replace(/–/g, "-")
                   .replace(/(?<!\w)-(?!\w)/g, " - ")
                   .replace(/[٠-٩]/g, d => "٠١٢٣٤٥٦٧٨٩".indexOf(d))
                   .replace(/[\t]+/g, " ")
                   .replace(/[ ]{2,}/g, " ")
                   .replace(/ـ/g, "")
                   .replace(/\sه\s/g, " هـ ")
                   .replace(/«صلى الله عليه وآله وسلم»/g, "(ص)")
                   .replace(/"صلى الله عليه وآله وسلم"/g, "(ص)")
                   .replace(/صلى الله عليه وآله وسلم/g, "(ص)")
                   .replace(/«صلى الله عليه وآله»/g, "(ص)")
                   .replace(/صلى الله عليه وآله/g, "(ص)")
                   .replace(/«عليه السلام»/g, "(ع)")
                   .replace(/"عليه السلام"/g, "(ع)")
                   .replace(/عليه السلام/g, "(ع)")
                   .replace(/«عليهم السلام»/g, "(عع)")
                   .replace(/عليهم السلام/g, "(عع)")
                   .replace(/«عليها السلام»/g, "(عه)")
                   .replace(/عليها السلام/g, "(عه)")
                   .replace(/\. \./g, "..")
                   .replace(/\، \،/g, "،،")
                   .replace(/\s+،/g, "،")
                   .replace(/\s+\./g, ".")
                   .replace(/\s+:/g, ":")
                   .replace(/«\s+/g, "«")
                   .replace(/\s+»/g, "»")
                   .replace(/\{\s+/g, "{")
                   .replace(/\s+\}/g, "}")
                   .replace(/\sو\s/g, " و")
                   .replace(/\nو\s/g, "\nو")
                   .replace(/^و\s/gm, "و")
                   .replace(/\[\s*(\d+)\s*\]/g, "($1)")
                   .replace(/(\d+\.)\s+(\d+)/g, "$1$2");
    }

    function cleanTranscriptWithVacum() {
        if (isRawTranscriptMode) {
            let text = rawTranscriptEditor.value;
            if (!text.trim()) {
                alert('يرجى كتابة أو لصق النص أولاً');
                return;
            }
            text = applyVacumString(text);
            text = text.replace(/^[ \t]+|[ \t]+$/gm, "").replace(/\n{3,}/g, "\n\n");
            rawTranscriptEditor.value = text.trim();
            transcriptInput.value = text.trim();
            alert('تم تنظيف وتنسيق النص بنجاح وفق قواعد المخمة!');
            return;
        }

        if (!quillTranscript.getText().trim()) {
            alert('يرجى كتابة أو لصق النص أولاً');
            return;
        }

        // Clean text nodes in Quill while preserving HTML tags/formatting
        var walker = document.createTreeWalker(quillTranscript.root, NodeFilter.SHOW_TEXT, null, false);
        var node;
        var textNodes = [];
        while (node = walker.nextNode()) {
            textNodes.push(node);
        }

        textNodes.forEach(function(n) {
            n.nodeValue = applyVacumString(n.nodeValue);
        });

        transcriptInput.value = quillTranscript.root.innerHTML;
        alert('تم تنظيف وتنسيق النص بنجاح وفق قواعد المخمة!');
    }
</script>
@endpush

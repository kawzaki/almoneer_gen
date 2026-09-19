@extends('layouts.admin')

@section('title', 'تعديل المادة الإعلامية')

@push('styles')
<!-- Quill WYSIWYG Editor CSS -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<style>
    .ql-editor {
        direction: rtl !important;
        text-align: right !important;
        font-family: 'IBM Plex Sans Arabic', sans-serif !important;
        min-height: 180px !important;
        font-size: 0.875rem !important;
        line-height: 1.8 !important;
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
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">تعديل: {{ $item->title }}</h2>
            <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-xs text-slate-500 mt-1.5">
                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-800 px-2.5 py-1 rounded-lg border border-emerald-200/60">
                    <i class="fa-regular fa-calendar-plus text-emerald-600"></i>
                    <span>تاريخ الإضافة:</span>
                    <strong class="font-mono text-slate-800">{{ $item->created_at ? $item->created_at->format('Y-m-d H:i') : 'غير محدد' }}</strong>
                    @if($item->created_at)
                    <span class="text-[10px] text-emerald-700">({{ $item->created_at->diffForHumans() }})</span>
                    @endif
                </span>
                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-900 px-2.5 py-1 rounded-lg border border-amber-200/60">
                    <i class="fa-regular fa-clock text-amber-600"></i>
                    <span>آخر تعديل:</span>
                    <strong class="font-mono text-slate-800">{{ $item->updated_at ? $item->updated_at->format('Y-m-d H:i') : 'غير محدد' }}</strong>
                    @if($item->updated_at)
                    <span class="text-[10px] text-amber-800">({{ $item->updated_at->diffForHumans() }})</span>
                    @endif
                </span>
            </div>
        </div>
        <a href="{{ route('admin.media.index') }}" class="text-xs text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3.5 py-2 rounded-xl shadow-xs transition">← العودة للقائمة</a>
    </div>

    <form id="media-form" action="{{ route('admin.media.update', $item->id) }}" method="POST" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان المادة الإعلامية:</label>
            <input type="text" name="title" value="{{ $item->title }}" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">النوع:</label>
                <select name="type" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="audio" {{ $item->type === 'audio' ? 'selected' : '' }}>محاضرة صوتية (MP3)</option>
                    <option value="video" {{ $item->type === 'video' ? 'selected' : '' }}>محاضرة مرئية (يوتيوب)</option>
                    <option value="short" {{ $item->type === 'short' ? 'selected' : '' }}>ريلز / شورتس قصير</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">التصنيف:</label>
                <select name="category_id" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="">بدون تصنيف</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">الموسم / السنة:</label>
                <input type="text" name="season_year" value="{{ $item->season_year }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    رابط الميديا (يوتيوب أو صوتي):
                    <span class="text-slate-400 font-normal text-[11px]">(اختياري - في حال عدم توفر الفيديو بعد)</span>
                </label>
                <input type="text" name="media_url" value="{{ $item->media_url }}" placeholder="https://..." class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">المدة:</label>
                <input type="text" name="duration" value="{{ $item->duration }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 text-center font-mono">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">الكلمات الدلالية والوسوم (Tags):</label>
            <input type="text" name="tags" value="{{ old('tags', $item->tags) }}" placeholder="مثال: عاشوراء، العقيدة، الفلسفة، العدل الإلهي، الأخلاق" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            <p class="text-[11px] text-slate-400 mt-1">افصل بين الكلمات الدلالية بفواصل (، أو ,) لتسهيل الفلترة والبحث للمستخدمين.</p>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-semibold text-slate-700">الوصف (محرر مرئي منسق WYSIWYG):</label>
                <button type="button" onclick="toggleRawHtmlMode()" id="toggle-html-btn" class="text-[11px] text-slate-500 hover:text-emerald-800 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-code"></i>
                    <span>تبديل لكود HTML المصدر</span>
                </button>
            </div>
            
            <!-- Hidden input that carries formatted HTML content -->
            <textarea name="description" id="media-description" class="hidden">{{ old('description', $item->description) }}</textarea>

            <!-- Raw HTML textarea (toggled on demand) -->
            <textarea id="raw-html-editor" rows="6" class="hidden w-full text-xs font-mono rounded-xl border-slate-200 p-3 bg-slate-900 text-slate-100" oninput="syncFromRawHtml(this.value)">{{ old('description', $item->description) }}</textarea>

            <!-- Quill Editor Container -->
            <div id="quill-editor-wrapper">
                <div id="quill-editor">{!! old('description', $item->description) !!}</div>
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-semibold text-slate-700">تفريغ المحاضرة (Transcript):</label>
                <button type="button" onclick="cleanTranscriptWithVacum()" class="px-3 py-1 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-900 text-xs font-bold transition flex items-center gap-1.5 shadow-sm border border-amber-300/60">
                    <i class="fa-solid fa-broom text-amber-700"></i>
                    <span>تنظيف وتنسيق النص (المخمة)</span>
                </button>
            </div>
            <textarea name="transcript" id="transcript-input" rows="8" class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 font-mono leading-relaxed">{{ $item->transcript }}</textarea>
            <p class="text-[11px] text-slate-400 mt-1">يمكنك لصق النص المنسوخ من Word والضغط على زر "تنظيف وتنسيق النص (المخمة)" لضبط علامات الترقيم والأقواس والأرقام والآيات آلياً.</p>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ $item->is_featured ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>إبراز في الصفحة الرئيسية</span>
            </label>
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>مفعل ونشط</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow">تحديث المادة وتفريغ الكاش</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- Quill WYSIWYG Editor JS -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    // 1. Initialize Quill Editor for Description
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'اكتب وصف المحاضرة أو الصق النص المنسق هنا...',
        modules: {
            toolbar: [
                [{ 'header': [2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['blockquote', 'link'],
                ['clean']
            ]
        }
    });

    // 2. Sync Quill content to hidden textarea on submit
    var form = document.getElementById('media-form');
    var descInput = document.getElementById('media-description');
    var rawHtmlEditor = document.getElementById('raw-html-editor');
    var isRawMode = false;
    var quillWrapper = document.getElementById('quill-editor-wrapper');
    var toggleBtn = document.getElementById('toggle-html-btn');

    form.onsubmit = function() {
        if (!isRawMode) {
            descInput.value = quill.root.innerHTML;
        } else {
            descInput.value = rawHtmlEditor.value;
        }
    };

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
</script>

<script>
function cleanTranscriptWithVacum() {
    const el = document.getElementById('transcript-input');
    let text = el.value;
    if (!text.trim()) {
        alert('يرجى كتابة أو لصق النص أولاً');
        return;
    }
    text = text.replace(/,/g, "،")
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
               .replace(/^[ \t]+|[ \t]+$/gm, "")
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
               .replace(/(\d+\.)\s+(\d+)/g, "$1$2")
               .replace(/\n{3,}/g, "\n\n");

    el.value = text.trim();
    alert('تم تنظيف وتنسيق النص بنجاح وفق قواعد المخمة!');
}
</script>
@endpush

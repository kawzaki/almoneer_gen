@extends('layouts.admin')

@section('title', 'إضافة قصيدة جديدة للديوان')

@push('styles')
<!-- Quill WYSIWYG Editor CSS -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<style>
    .ql-editor {
        direction: rtl !important;
        text-align: right !important;
        font-family: 'IBM Plex Sans Arabic', sans-serif !important;
        min-height: 160px !important;
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
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">إضافة قصيدة جديدة</h2>
        <a href="{{ route('admin.poems.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← العودة للديوان</a>
    </div>

    <form id="poem-form" action="{{ route('admin.poems.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان القصيدة:</label>
            <input type="text" name="title" value="{{ old('title') }}" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">المناسبة:</label>
                <input type="text" name="occasion" value="{{ old('occasion') }}" placeholder="مثال: في رثاء سيد الشهداء (ع)" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">تاريخ القصيدة / المناسبة:</label>
                <input type="text" name="poem_date" value="{{ old('poem_date') }}" placeholder="مثال: 10 محرم 1445هـ أو 2024م" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">بحر الشعر (اختياري):</label>
                <input type="text" name="meter" value="{{ old('meter') }}" placeholder="مثال: بحر البسيط / الطويل" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
        </div>

        <!-- Poem Image (Displays ONLY inside poem page) -->
        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
            <label class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-image text-emerald-800"></i>
                    <span>صورة مخصصة للقصيدة (تُعرض فقط داخل صفحة القصيدة):</span>
                </span>
                <span class="text-slate-400 font-normal text-[10px]">(اختياري)</span>
            </label>
            <input type="file" name="image" accept="image/*" class="w-full text-xs rounded-xl border border-slate-200 p-2 bg-white cursor-pointer file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-800 file:text-white hover:file:bg-emerald-900">
            <p class="text-[10px] text-slate-400 mt-1">تظهر هذه الصورة كغلاف فني داخل موضوع القصيدة فقط، ولا تظهر في شبكة بطاقات الديوان العامة للحفاظ على رونق النص.</p>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">أبيات القصيدة (كل بيت في سطر، مع الفصل بين الصدر والعجز بالرمز | ):</label>
            <textarea name="verses" rows="10" required placeholder="قف بالطفوف وجُد بالدمع منسكبا | والثم تراباً به نبل الهدى انسكبا" class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 font-scholarly text-sm leading-loose">{{ old('verses') }}</textarea>
            <p class="text-[11px] text-slate-400 mt-1">ملاحظة: استخدام علامة الشارطة الرأسية | يفصل تلقائياً بين الصدر والعجز متجاوباً مع الجوال.</p>
        </div>

        <!-- WYSIWYG Editor: وصف المناسبة والخلفية -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-xs font-semibold text-slate-700">الوصف والمناسبة (محرر منسق):</label>
                <button type="button" onclick="toggleRawHtmlMode()" id="toggle-html-btn" class="text-[11px] text-slate-500 hover:text-emerald-800 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-code"></i>
                    <span>تبديل لكود HTML المصدر</span>
                </button>
            </div>

            <!-- Hidden input that carries formatted HTML content -->
            <textarea name="description" id="poem-description" class="hidden">{{ old('description') }}</textarea>

            <!-- Raw HTML textarea (toggled on demand) -->
            <textarea id="raw-html-editor" rows="6" class="hidden w-full text-xs font-mono rounded-xl border-slate-200 p-3 bg-slate-900 text-slate-100" oninput="syncFromRawHtml(this.value)">{{ old('description') }}</textarea>

            <!-- Quill Editor Container -->
            <div id="quill-editor-wrapper">
                <div id="quill-editor">{!! old('description') !!}</div>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">يمكنك تنسيق النص بحرية، وإضافة اقتباسات، وتلوين الخط، وإضافة روابط أو فواصل لتظهر بأناقة في صفحة القصيدة.</p>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>عرض في الصفحة الرئيسية</span>
            </label>
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>مفعلة ونشطة</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow">حفظ القصيدة في الديوان</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- Quill WYSIWYG Editor JS -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    // 1. Initialize Quill Editor
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'اكتب نبذة عن مناسبة النظم وخلفيتها مع التنسيقات...',
        modules: {
            toolbar: [
                [{ 'header': [2, 3, 4, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['blockquote', 'link'],
                ['clean']
            ]
        }
    });

    // 2. Form submission sync
    var form = document.getElementById('poem-form');
    var descInput = document.getElementById('poem-description');
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

    // 3. Toggle Raw HTML Mode
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
@endpush

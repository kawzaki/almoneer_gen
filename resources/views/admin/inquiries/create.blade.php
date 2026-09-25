@extends('layouts.admin')

@section('title', 'إضافة استفسار وجواب جديد')

@push('styles')
<!-- Quill WYSIWYG Editor CSS -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<style>
    .ql-editor {
        direction: rtl !important;
        text-align: right !important;
        font-family: 'IBM Plex Sans Arabic', sans-serif !important;
        min-height: 100% !important;
        font-size: 0.925rem !important;
        line-height: 1.85 !important;
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
        resize: vertical !important;
        overflow-y: auto !important;
        min-height: 220px;
        height: 300px;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">إضافة استفسار وجواب جديد</h2>
            <p class="text-xs text-slate-500 mt-0.5">تسجيل سؤال وجواب واردين عبر البريد الإلكتروني أو مصادر خارجية لإدراجهما في الأرشيف.</p>
        </div>
        <a href="{{ route('admin.inquiries.index') }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold">← العودة للاستفسارات</a>
    </div>

    <form id="inquiry-form" action="{{ route('admin.inquiries.store') }}" method="POST" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-5">
        @csrf

        <!-- Inquirer Info -->
        <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200 space-y-3">
            <h4 class="font-bold text-xs text-slate-700 flex items-center gap-1.5">
                <i class="fa-solid fa-user-tag text-emerald-800"></i>
                <span>بيانات السائل:</span>
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">اسم السائل / اللقب: <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="مثال: أبو محمد / وارد عبر البريد" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white focus:border-emerald-800 transition">
                    @error('name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">البريد الإلكتروني (اختياري):</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="user@example.com" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white focus:border-emerald-800 transition" dir="ltr">
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">الدولة أو المدينة (اختياري):</label>
                    <input type="text" name="country" value="{{ old('country') }}" placeholder="مثال: القطيف / الكويت / لندن" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white focus:border-emerald-800 transition">
                </div>
            </div>
        </div>

        <!-- Question -->
        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <label for="question" class="block text-xs font-bold text-slate-700">نص السؤال أو المسألة: <span class="text-red-500">*</span></label>
                <span class="text-[11px] text-slate-400">يمكن تصحيح الأخطاء الإملائية وتنسيق السؤال</span>
            </div>
            <textarea id="question" name="question" rows="4" required class="w-full text-xs sm:text-sm font-semibold text-slate-900 rounded-xl border-slate-200 p-3.5 bg-slate-50 focus:bg-white focus:border-emerald-600 transition leading-relaxed" placeholder="اكتب نص السؤال هنا...">{{ old('question') }}</textarea>
            @error('question')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Meta: Status, Category, Responder -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-slate-100">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">حالة الاستفسار: <span class="text-red-500">*</span></label>
                <select name="status" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="answered" {{ old('status', 'answered') === 'answered' ? 'selected' : '' }}>تمت الإجابة والاعتماد</option>
                    <option value="in_review" {{ old('status') === 'in_review' ? 'selected' : '' }}>قيد المراجعة لدى اللجنة</option>
                    <option value="new" {{ old('status') === 'new' ? 'selected' : '' }}>جديد</option>
                </select>
                @error('status')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">التصنيف الموضوعي:</label>
                <select name="category_id" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="">بدون تصنيف</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">مصدر الجواب (صادر عن):</label>
                <select name="responder_title" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 font-semibold text-emerald-950">
                    <option value="إدارة الموقع" {{ old('responder_title', 'إدارة الموقع') === 'إدارة الموقع' ? 'selected' : '' }}>إدارة الموقع</option>
                    <option value="السيد منير الخباز" {{ old('responder_title') === 'السيد منير الخباز' ? 'selected' : '' }}>السيد منير الخباز</option>
                    <option value="لجنة المسائل الشرعية" {{ old('responder_title') === 'لجنة المسائل الشرعية' ? 'selected' : '' }}>لجنة المسائل الشرعية</option>
                </select>
                @error('responder_title')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Answer (Quill WYSIWYG Editor) -->
        <div class="space-y-1.5 pt-2">
            <div class="flex items-center justify-between">
                <label class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                    <i class="fa-solid fa-pen-nib text-emerald-800"></i>
                    <span>نص الجواب المعتمد (محرر مرئي منسق WYSIWYG):</span>
                </label>
                <button type="button" onclick="toggleRawHtmlMode()" id="toggle-html-btn" class="text-[11px] text-slate-500 hover:text-emerald-800 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-code"></i>
                    <span>تبديل لكود HTML المصدر</span>
                </button>
            </div>

            <!-- Hidden input that carries formatted HTML content -->
            <textarea name="answer" id="inquiry-answer" class="hidden">{{ old('answer') }}</textarea>

            <!-- Raw HTML textarea (toggled on demand) -->
            <textarea id="raw-html-editor" rows="10" class="hidden w-full text-xs font-mono rounded-xl border-slate-200 p-3 bg-slate-900 text-slate-100" oninput="syncFromRawHtml(this.value)">{{ old('answer') }}</textarea>

            <!-- Quill Editor Container -->
            <div id="quill-editor-wrapper">
                <div id="quill-editor">{!! old('answer') !!}</div>
            </div>
            @error('answer')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Publish Option -->
        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>نشر هذا السؤال والجواب في بنك الفتاوى والاستفسارات العام</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <a href="{{ route('admin.inquiries.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                إلغاء
            </a>
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>حفظ واعتماد السؤال والجواب</span>
            </button>
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
        placeholder: 'اكتب الجواب الشرعي أو الفكري المعتمد هنا...',
        modules: {
            toolbar: [
                [{ 'header': [2, 3, 4, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['blockquote'],
                ['link'],
                ['clean']
            ]
        }
    });

    // 2. Sync Quill content to hidden textarea on submit
    var form = document.getElementById('inquiry-form');
    var answerInput = document.getElementById('inquiry-answer');
    var rawHtmlEditor = document.getElementById('raw-html-editor');

    form.onsubmit = function() {
        if (!isRawMode) {
            answerInput.value = quill.root.innerHTML;
        } else {
            answerInput.value = rawHtmlEditor.value;
        }
    };

    // 3. Toggle Raw HTML Mode
    var isRawMode = false;
    var quillWrapper = document.getElementById('quill-editor-wrapper');
    var toggleBtn = document.getElementById('toggle-html-btn');

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
        answerInput.value = val;
    }
</script>
@endpush

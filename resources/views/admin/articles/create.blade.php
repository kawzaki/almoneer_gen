@extends('layouts.admin')

@section('title', 'إضافة خبر أو نشاط جديد')

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
        min-height: 280px;
        height: 350px;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">إضافة خبر أو نشاط جديد</h2>
            <p class="text-xs text-slate-500 mt-0.5">نشر تغطية إخبارية أو بيان أو نشاط لسماحة السيد</p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="px-3 py-1.5 rounded-xl text-xs text-slate-600 hover:text-slate-900 transition">
            ← العودة للقائمة
        </a>
    </div>

    <form id="article-form" action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-4 sm:p-6 md:p-8 border border-slate-200 shadow-sm space-y-6">
        @csrf

        <!-- Title -->
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">عنوان الخبر / النشاط:</label>
            <input type="text" name="title" value="{{ old('title') }}" required placeholder="أدخل عنوان الخبر البارز..." class="w-full text-xs sm:text-sm rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:border-emerald-800 focus:ring-1 focus:ring-emerald-800/20 transition">
        </div>

        <!-- Type, Category and Published Date -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">النوع:</label>
                <select name="type" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white transition">
                    <option value="news" {{ old('type') === 'news' ? 'selected' : '' }}>خبر عام</option>
                    <option value="activity" {{ old('type') === 'activity' ? 'selected' : '' }}>نشاط / جولة تبليغية</option>
                    <option value="bio" {{ old('type') === 'bio' ? 'selected' : '' }}>سيرة ذاتية</option>
                    <option value="article" {{ old('type') === 'article' ? 'selected' : '' }}>مقال فكري</option>
                </select>
            </div>
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-slate-700">التصنيف:</label>
                    <a href="{{ route('admin.categories.index', ['module' => 'article']) }}" target="_blank" class="text-[11px] text-emerald-800 hover:text-emerald-950 font-semibold flex items-center gap-1" title="إدارة أو إضافة تصنيفات جديدة">
                        <i class="fa-solid fa-folder-tree text-[10px]"></i>
                        <span>إدارة التصنيفات</span>
                    </a>
                </div>
                <select name="category_id" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white transition">
                    <option value="">بدون تصنيف</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-slate-700 flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar-days text-emerald-800"></i>
                        <span>تاريخ النشر:</span>
                    </label>
                    <span class="text-[10px] text-slate-400">يدعم الأخبار السابقة</span>
                </div>
                <div class="relative">
                    <input type="text" name="published_at" id="published_at" value="{{ old('published_at', date('Y-m-d')) }}" 
                           class="admin-datepicker w-full text-xs rounded-xl border-slate-200 p-2.5 pr-9 bg-slate-50 focus:bg-white focus:border-emerald-800 focus:ring-1 focus:ring-emerald-800/20 transition text-slate-700 font-semibold"
                           placeholder="يوم / شهر / سنة (DD / MM / YYYY)">
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 text-xs">
                        <i class="fa-regular fa-calendar"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tags Input -->
        <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200 space-y-2">
            <div class="flex items-center justify-between">
                <label class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                    <i class="fa-solid fa-tags text-emerald-800"></i>
                    <span>الكلمات الدلالية والوسوم (Tags):</span>
                </label>
                <span class="text-[11px] text-slate-400">افصل بين الوسوم بفواصل (، أو ,)</span>
            </div>
            <input type="text" 
                   name="tags" 
                   id="tags-input" 
                   value="{{ old('tags') }}" 
                   placeholder="مثال: أخبار، نشاطات، جولة تبليغية، لندن، مؤتمر، حوار الأديان" 
                   class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white focus:border-emerald-800 transition">
        </div>

        <!-- Image Uploader Section -->
        <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200 space-y-3">
            <label class="block text-xs font-bold text-slate-800 flex items-center gap-1.5">
                <i class="fa-solid fa-image text-gold-600"></i>
                <span>الصورة البارزة للخبر (Featured Cover Image):</span>
            </label>

            <!-- Image Upload Box -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-center">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">رفع صورة من جهازك:</label>
                    <input type="file" 
                           name="image_file" 
                           id="image-file-input" 
                           accept="image/*"
                           onchange="previewSelectedImage(this)" 
                           class="w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-800 file:text-white hover:file:bg-emerald-900 border border-slate-200 rounded-xl p-1.5 bg-white">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">أو إدخال مسار / رابط مباشر للصورة:</label>
                    <input type="text" 
                           name="image" 
                           value="{{ old('image') }}" 
                           placeholder="https://example.com/photo.jpg أو /images/..." 
                           class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white focus:border-emerald-800 transition">
                </div>
            </div>

            <!-- New Image Live Preview Container -->
            <div id="new-image-preview-container" class="hidden p-3 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center gap-3">
                <img id="new-image-preview" src="#" alt="معاينة الصورة المختارة" class="w-24 h-16 object-cover rounded-lg shadow-sm border border-emerald-300">
                <div>
                    <p class="text-xs font-bold text-emerald-900">معاينة الصورة المختارة</p>
                    <p class="text-[11px] text-emerald-700">سيتم رفع الصورة وحفظها تلقائياً مع الخبر.</p>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">ملخص مختصر (يظهر في البطاقات وقوائم الأخبار):</label>
            <textarea name="summary" rows="2" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white focus:border-emerald-800 transition" placeholder="نبذة موجزة تلخص مضمون الخبر...">{{ old('summary') }}</textarea>
        </div>

        <!-- Full Content (WYSIWYG Quill Editor) -->
        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <label class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                    <i class="fa-solid fa-pen-nib text-emerald-800"></i>
                    <span>المحتوى الكامل (محرر مرئي منسق WYSIWYG):</span>
                </label>
                <button type="button" onclick="toggleRawHtmlMode()" id="toggle-html-btn" class="text-[11px] text-slate-500 hover:text-emerald-800 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-code"></i>
                    <span>تبديل لكود HTML المصدر</span>
                </button>
            </div>

            <!-- Hidden input that carries formatted HTML content -->
            <textarea name="content" id="article-content" class="hidden">{{ old('content') }}</textarea>

            <!-- Raw HTML textarea (toggled on demand) -->
            <textarea id="raw-html-editor" rows="12" class="hidden w-full text-xs font-mono rounded-xl border-slate-200 p-3 bg-slate-900 text-slate-100" oninput="syncFromRawHtml(this.value)">{{ old('content') }}</textarea>

            <!-- Quill Editor Container -->
            <div id="quill-editor-wrapper">
                <div id="quill-editor">{!! old('content') !!}</div>
            </div>
        </div>

        <!-- Options -->
        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded text-emerald-800 focus:ring-emerald-800">
                <span>تثبيت في البانر الرئيسي للموقع</span>
            </label>
            <label class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded text-emerald-800 focus:ring-emerald-800">
                <span>مفعل ونشط في الموقع</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <a href="{{ route('admin.articles.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                إلغاء
            </a>
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>حفظ ونشر الخبر</span>
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
        placeholder: 'اكتب نص وتفاصيل الخبر أو الصق التنسيق هنا...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['blockquote', 'code-block'],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // 2. Sync Quill content to hidden textarea on submit
    var form = document.getElementById('article-form');
    var contentInput = document.getElementById('article-content');
    var rawHtmlEditor = document.getElementById('raw-html-editor');

    form.onsubmit = function() {
        if (!isRawMode) {
            contentInput.value = quill.root.innerHTML;
        } else {
            contentInput.value = rawHtmlEditor.value;
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
        contentInput.value = val;
    }

    // 4. Live Image Preview
    function previewSelectedImage(input) {
        var container = document.getElementById('new-image-preview-container');
        var preview = document.getElementById('new-image-preview');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            container.classList.add('hidden');
        }
    }
</script>
@endpush

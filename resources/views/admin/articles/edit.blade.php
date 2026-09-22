@extends('layouts.admin')

@section('title', 'تعديل الخبر: ' . $article->title)

@push('styles')
<!-- Quill WYSIWYG Editor CSS -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<style>
    .ql-editor {
        direction: rtl !important;
        text-align: right !important;
        font-family: 'IBM Plex Sans Arabic', sans-serif !important;
        min-height: 320px !important;
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
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">تعديل الخبر / النشاط</h2>
            <p class="text-xs text-slate-500 mt-0.5">{{ $article->title }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('news.show', $article->slug) }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                <span>معاينة في الموقع</span>
            </a>
            <a href="{{ route('admin.articles.index') }}" class="px-3 py-1.5 rounded-xl text-xs text-slate-600 hover:text-slate-900 transition">
                ← العودة للقائمة
            </a>
        </div>
    </div>

    <form id="article-form" action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-4 sm:p-6 md:p-8 border border-slate-200 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">عنوان الخبر / النشاط:</label>
            <input type="text" name="title" value="{{ old('title', $article->title) }}" required class="w-full text-xs sm:text-sm rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:border-emerald-800 focus:ring-1 focus:ring-emerald-800/20 transition">
        </div>

        <!-- Type, Category and Published Date -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">النوع:</label>
                <select name="type" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white transition">
                    <option value="news" {{ old('type', $article->type) === 'news' ? 'selected' : '' }}>خبر عام</option>
                    <option value="activity" {{ old('type', $article->type) === 'activity' ? 'selected' : '' }}>نشاط / جولة تبليغية</option>
                    <option value="bio" {{ old('type', $article->type) === 'bio' ? 'selected' : '' }}>سيرة ذاتية</option>
                    <option value="article" {{ old('type', $article->type) === 'article' ? 'selected' : '' }}>مقال فكري</option>
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
                    <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
                <input type="date" name="published_at" 
                       value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d') : ($article->created_at ? $article->created_at->format('Y-m-d') : date('Y-m-d'))) }}" 
                       class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white focus:border-emerald-800 focus:ring-1 focus:ring-emerald-800/20 transition text-slate-700 font-medium dir-ltr">
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
                   value="{{ old('tags', $article->tags) }}" 
                   placeholder="مثال: أخبار، نشاطات، جولة تبليغية، لندن، مؤتمر، حوار الأديان" 
                   class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white focus:border-emerald-800 transition">
            
            @if(!empty($article->tags_list))
            <div class="flex flex-wrap items-center gap-1.5 pt-1">
                <span class="text-[11px] text-slate-400 font-semibold">الوسوم المحفوظة:</span>
                @foreach($article->tags_list as $tag)
                <span class="px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] font-medium flex items-center gap-1">
                    <i class="fa-solid fa-tag text-[9px]"></i>
                    <span>{{ $tag }}</span>
                </span>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Image Uploader Section -->
        <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200 space-y-3">
            <label class="block text-xs font-bold text-slate-800 flex items-center gap-1.5">
                <i class="fa-solid fa-image text-gold-600"></i>
                <span>الصورة البارزة للخبر (Featured Cover Image):</span>
            </label>

            <!-- Current Image Preview (if exists) -->
            @if($article->image)
            <div id="current-image-box" class="flex items-center gap-4 p-3 bg-white rounded-xl border border-slate-200">
                <img src="{{ str_starts_with($article->image, 'http') ? $article->image : asset($article->image) }}" 
                     alt="{{ $article->title }}" 
                     class="w-24 h-16 object-cover rounded-lg shadow-sm border border-slate-200">
                <div class="flex-grow space-y-1">
                    <p class="text-xs font-semibold text-slate-700 truncate">{{ basename($article->image) }}</p>
                    <label class="inline-flex items-center gap-1.5 text-xs text-red-600 cursor-pointer hover:text-red-800">
                        <input type="checkbox" name="remove_image" value="1" class="rounded text-red-600">
                        <span>حذف هذه الصورة عند الحفظ</span>
                    </label>
                </div>
            </div>
            @endif

            <!-- Image Upload Box -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-center">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">رفع صورة جديدة من جهازك:</label>
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
                           value="{{ old('image', $article->image) }}" 
                           placeholder="https://example.com/photo.jpg أو /images/..." 
                           class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white focus:border-emerald-800 transition">
                </div>
            </div>

            <!-- New Image Live Preview Container -->
            <div id="new-image-preview-container" class="hidden p-3 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center gap-3">
                <img id="new-image-preview" src="#" alt="معاينة الصورة الجديدة" class="w-24 h-16 object-cover rounded-lg shadow-sm border border-emerald-300">
                <div>
                    <p class="text-xs font-bold text-emerald-900">معاينة الصورة الجديدة المختارة</p>
                    <p class="text-[11px] text-emerald-700">سيتم حفظها ورفعها تلقائياً عند النقر على تحديث الخبر.</p>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">ملخص مختصر (يظهر في البطاقات وقوائم الأخبار):</label>
            <textarea name="summary" rows="2" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white focus:border-emerald-800 transition">{{ old('summary', $article->summary) }}</textarea>
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
            <textarea name="content" id="article-content" class="hidden">{{ old('content', $article->content) }}</textarea>

            <!-- Raw HTML textarea (toggled on demand) -->
            <textarea id="raw-html-editor" rows="12" class="hidden w-full text-xs font-mono rounded-xl border-slate-200 p-3 bg-slate-900 text-slate-100" oninput="syncFromRawHtml(this.value)">{{ old('content', $article->content) }}</textarea>

            <!-- Quill Editor Container -->
            <div id="quill-editor-wrapper">
                <div id="quill-editor">{!! old('content', $article->content) !!}</div>
            </div>
        </div>

        <!-- Options -->
        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $article->is_featured) ? 'checked' : '' }} class="rounded text-emerald-800 focus:ring-emerald-800">
                <span>تثبيت في البانر الرئيسي للموقع</span>
            </label>
            <label class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $article->is_active) ? 'checked' : '' }} class="rounded text-emerald-800 focus:ring-emerald-800">
                <span>مفعل ونشط في الموقع</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <a href="{{ route('admin.articles.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                إلغاء
            </a>
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-check"></i>
                <span>تحديث الخبر وحفظ التعديلات</span>
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

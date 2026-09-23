@extends('layouts.admin')

@section('title', 'تعديل بيانات الكتاب')

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
    #quill-summary .ql-editor {
        min-height: 160px !important;
    }
    #quill-toc .ql-editor {
        min-height: 220px !important;
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
            <h2 class="text-xl font-bold text-slate-800">تعديل: {{ $book->title }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">تحديث بيانات الكتاب، صورة الغلاف، الملفات والتصنيف.</p>
        </div>
        <a href="{{ route('admin.books.index') }}" class="text-xs text-slate-500 hover:text-slate-800 flex items-center gap-1">
            <i class="fa-solid fa-arrow-right"></i>
            <span>العودة للمكتبة</span>
        </a>
    </div>

    @if (isset($errors) && $errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs space-y-1">
            <div class="font-bold">يرجى تصحيح الأخطاء التالية:</div>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="book-form" action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-5">
        @csrf 
        @method('PUT')

        <!-- Title, Author, Category -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان الكتاب: <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $book->title) }}" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-emerald-700">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">تصنيف الكتاب:</label>
                <select name="category_id" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white">
                    <option value="">-- بدون تصنيف --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">المؤلف: <span class="text-red-500">*</span></label>
                <input type="text" name="author" value="{{ old('author', $book->author) }}" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">دار النشر:</label>
                <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">سنة النشر:</label>
                <input type="text" name="publication_year" value="{{ old('publication_year', $book->publication_year) }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">عدد الصفحات:</label>
                <input type="number" name="pages_count" value="{{ old('pages_count', $book->pages_count) }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white">
            </div>
        </div>

        <!-- Media Permit & ISBN Info (Optional) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-stamp text-amber-600"></i>
                        <span>رقم الإيداع (الفسح الإعلامي):</span>
                    </span>
                    <span class="text-[10px] text-slate-400 font-normal">اختياري</span>
                </label>
                <input type="text" name="deposit_number" value="{{ old('deposit_number', $book->deposit_number) }}" placeholder="مثال: 1440/3571" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white focus:ring-1 focus:ring-emerald-700">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-barcode text-slate-600"></i>
                        <span>الترقيم الدولي (ردمك / ISBN):</span>
                    </span>
                    <span class="text-[10px] text-slate-400 font-normal">اختياري</span>
                </label>
                <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" placeholder="مثال: 978-603-8255-64-3" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white focus:ring-1 focus:ring-emerald-700 font-mono" dir="ltr">
            </div>
        </div>

        <!-- Book Cover Management Section -->
        <div class="p-5 rounded-2xl bg-gradient-to-br from-slate-50 to-emerald-50/20 border border-slate-200/80 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-image text-emerald-700 text-sm"></i>
                    <h3 class="text-xs font-bold text-slate-800">غلاف الكتاب (صورة الغلاف)</h3>
                </div>
                <span class="text-[11px] text-slate-500">يدعم صيغ JPG, PNG, WebP</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
                <!-- Cover Preview Box -->
                <div class="md:col-span-4 flex flex-col items-center justify-center p-3 bg-white rounded-xl border border-slate-200 shadow-xs text-center">
                    <div id="cover_preview_wrapper" class="w-32 h-44 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden relative shadow-inner">
                        @php
                            $hasCover = !empty($book->cover_image);
                            $coverUrl = $hasCover ? (str_starts_with($book->cover_image, 'http') ? $book->cover_image : asset($book->cover_image)) : '';
                        @endphp
                        
                        <img id="cover_preview_img" 
                             src="{{ $coverUrl }}" 
                             alt="غلاف الكتاب" 
                             class="w-full h-full object-cover {{ $hasCover ? '' : 'hidden' }}">

                        <div id="cover_placeholder" class="text-center p-2 text-slate-400 {{ $hasCover ? 'hidden' : '' }}">
                            <i class="fa-solid fa-book-open text-3xl mb-1 block text-slate-300"></i>
                            <span class="text-[11px]">لا يوجد غلاف محدد</span>
                        </div>
                    </div>

                    <div class="mt-2 text-center w-full">
                        <span id="cover_path_display" class="text-[10px] text-slate-500 truncate block px-2 dir-ltr" title="{{ $book->cover_image }}">
                            {{ $book->cover_image ? basename($book->cover_image) : 'بدون صورة' }}
                        </span>
                        
                        @if($hasCover)
                        <label class="inline-flex items-center gap-1.5 mt-2 text-[11px] text-red-600 hover:text-red-700 cursor-pointer">
                            <input type="checkbox" name="remove_cover" value="1" id="remove_cover_cb" onchange="toggleRemoveCover(this)" class="rounded text-red-600 focus:ring-red-500">
                            <span>حذف صورة الغلاف</span>
                        </label>
                        @endif
                    </div>
                </div>

                <!-- Cover Selection Options -->
                <div class="md:col-span-8 space-y-3.5">
                    <!-- Option 1: Choose from uploaded library -->
                    <div class="p-3 bg-white rounded-xl border border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div>
                            <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                <i class="fa-solid fa-photo-film text-emerald-700"></i>
                                <span>اختيار من الصور المرفوعة مسبقاً</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">تصفح صور الأغلفة والوسائط المرفوعة في الموقع واختيار أحدها بضغطة واحدة.</p>
                        </div>
                        <button type="button" onclick="openCoverPickerModal()" class="px-4 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-2 flex-shrink-0">
                            <i class="fa-solid fa-images text-amber-300 text-sm"></i>
                            <span class="text-white font-bold">تصفح واختيار صورة</span>
                            <span class="px-1.5 py-0.5 rounded-md bg-emerald-950/70 text-amber-300 text-[10px] font-bold">({{ count($availableCovers) }})</span>
                        </button>
                    </div>

                    <!-- Option 2: Upload new cover file from computer -->
                    <div class="p-3 bg-white rounded-xl border border-slate-200 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-800 flex items-center gap-1.5">
                            <i class="fa-solid fa-upload text-blue-600"></i>
                            <span>أو رفع صورة غلاف جديدة من جهازك:</span>
                        </label>
                        <input type="file" name="cover_file" id="cover_file_input" accept="image/*" onchange="previewUploadedCoverFile(event)" 
                               class="block w-full text-xs text-slate-500 file:mr-0 file:ml-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-800 hover:file:bg-emerald-100 cursor-pointer border border-slate-200 rounded-xl p-1 bg-slate-50">
                        <p class="text-[10px] text-slate-400">سيتم حفظ الصورة المرفوعة في مجلد uploads/books واعتمادها كغلاف للكتاب فوراً.</p>
                    </div>

                    <!-- Option 3: Manual path or external URL -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">أو مسار الصورة المباشر / رابط URL خارجي:</label>
                        <div class="flex gap-2">
                            <input type="text" name="cover_image" id="cover_image_input" value="{{ old('cover_image', $book->cover_image) }}" 
                                   placeholder="uploads/books/example.jpg أو رابط https://" 
                                   class="w-full text-xs rounded-xl border-slate-200 p-2 bg-white focus:ring-1 focus:ring-emerald-700" 
                                   dir="ltr" oninput="previewManualCoverPath(this.value)">
                            <button type="button" onclick="clearCoverSelection()" title="تفريغ الحقل" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs rounded-xl transition">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary & Table of Contents (WYSIWYG Editors) -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-xs font-semibold text-slate-700">ملخص عام عن محتوى الكتاب (محرر منسق):</label>
                <button type="button" onclick="toggleRawSummaryMode()" id="toggle-summary-html-btn" class="text-[11px] text-slate-500 hover:text-emerald-800 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-code"></i>
                    <span>تبديل لكود HTML المصدر</span>
                </button>
            </div>

            <!-- Hidden input that carries formatted HTML content -->
            <textarea name="summary" id="book-summary" class="hidden">{{ old('summary', $book->summary) }}</textarea>

            <!-- Raw HTML textarea (toggled on demand) -->
            <textarea id="raw-summary-editor" rows="6" class="hidden w-full text-xs font-mono rounded-xl border-slate-200 p-3 bg-slate-900 text-slate-100" oninput="syncFromRawSummary(this.value)">{{ old('summary', $book->summary) }}</textarea>

            <!-- Quill Editor Container for Summary -->
            <div id="quill-summary-wrapper">
                <div id="quill-summary">{!! old('summary', $book->summary) !!}</div>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">اكتب نبذة أو مقدمة عن الكتاب مع إمكانية التنسيق، التلوين، وإضافة اقتباسات وقوائم.</p>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-xs font-semibold text-slate-700">فهرس الموضوعات والأبواب (محرر منسق):</label>
                <button type="button" onclick="toggleRawTocMode()" id="toggle-toc-html-btn" class="text-[11px] text-slate-500 hover:text-emerald-800 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-code"></i>
                    <span>تبديل لكود HTML المصدر</span>
                </button>
            </div>

            <!-- Hidden input that carries formatted HTML content -->
            <textarea name="table_of_contents" id="book-toc" class="hidden">{{ old('table_of_contents', $book->table_of_contents) }}</textarea>

            <!-- Raw HTML textarea (toggled on demand) -->
            <textarea id="raw-toc-editor" rows="8" class="hidden w-full text-xs font-mono rounded-xl border-slate-200 p-3 bg-slate-900 text-slate-100" oninput="syncFromRawToc(this.value)">{{ old('table_of_contents', $book->table_of_contents) }}</textarea>

            <!-- Quill Editor Container for Table of Contents -->
            <div id="quill-toc-wrapper">
                <div id="quill-toc">{!! old('table_of_contents', $book->table_of_contents) !!}</div>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">اكتب أو الصق فهرس الأبواب والفصول، مع دعم القوائم النقطية أو المرقمة والعناوين الفرعية.</p>
        </div>

        <!-- PDF & Buy URL Section -->
        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf text-red-600 text-sm"></i>
                    <h3 class="text-xs font-bold text-slate-800">ملف الكتاب الـ PDF ورابط الشراء</h3>
                </div>
                @if($book->pdf_file)
                    <div class="flex items-center gap-3">
                        <a href="{{ asset($book->pdf_file) }}" target="_blank" class="text-[11px] font-bold text-emerald-800 hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            <span>معاينة الملف الحالي</span>
                        </a>
                        <label class="inline-flex items-center gap-1 text-[11px] text-red-600 cursor-pointer">
                            <input type="checkbox" name="remove_pdf" value="1" class="rounded text-red-600">
                            <span>حذف ملف PDF</span>
                        </label>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-700">رفع ملف PDF جديد من جهازك:</label>
                    <input type="file" name="pdf_upload" accept=".pdf" 
                           class="block w-full text-xs text-slate-500 file:mr-0 file:ml-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer border border-slate-200 rounded-xl p-1 bg-white">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-700">أو مسار ملف الـ PDF المباشر:</label>
                    <input type="text" name="pdf_file" value="{{ old('pdf_file', $book->pdf_file) }}" 
                           placeholder="uploads/books/my_book.pdf" 
                           class="w-full text-xs rounded-xl border-slate-200 p-2 bg-white" dir="ltr">
                </div>
            </div>

            <div class="pt-2">
                <label class="block text-xs font-semibold text-slate-700 mb-1">رابط الشراء أو الاقتناء (اختياري):</label>
                <input type="text" name="buy_url" value="{{ old('buy_url', $book->buy_url) }}" placeholder="https://..." class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white" dir="ltr">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">ترتيب العرض:</label>
                <input type="number" name="order" value="{{ old('order', $book->order ?? 0) }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div class="sm:col-span-2 flex items-center gap-6 pt-5">
                <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $book->is_featured) ? 'checked' : '' }} class="rounded text-emerald-800 focus:ring-emerald-700">
                    <span>إبراز في الصفحة الرئيسية</span>
                </label>
                <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $book->is_active) ? 'checked' : '' }} class="rounded text-emerald-800 focus:ring-emerald-700">
                    <span>مفعل ونشط</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow transition">
                <i class="fa-solid fa-floppy-disk ml-1.5"></i>
                <span>تحديث الكتاب وتفريغ الكاش</span>
            </button>
        </div>
    </form>
</div>

<!-- Modal: اختيار الغلاف من الصور المرفوعة -->
<div id="coverPickerModal" class="fixed inset-0 z-50 bg-black/75 hidden items-center justify-center p-4 backdrop-blur-xs">
    <div class="bg-white rounded-3xl max-w-4xl w-full p-6 space-y-4 shadow-2xl border border-slate-100 max-h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-photo-film text-emerald-700"></i>
                    <span>اختيار غلاف الكتاب من الصور المرفوعة</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">انقر على أي صورة لاعتمادها مباشرة كغلاف لهذا الكتاب.</p>
            </div>
            <button type="button" onclick="closeCoverPickerModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Search & Filter Controls -->
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-grow w-full">
                <i class="fa-solid fa-magnifying-glass absolute right-3 top-3 text-slate-400 text-xs"></i>
                <input type="text" id="coverSearchInput" oninput="filterCoverImages()" 
                       placeholder="بحث في الصور حسب الاسم أو المجلد..." 
                       class="w-full pr-8 pl-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-emerald-700">
            </div>
            <div class="flex items-center gap-1.5 overflow-x-auto w-full sm:w-auto pb-1 sm:pb-0" id="filterPillsContainer">
                <button type="button" onclick="filterByFolder('all', this)" class="folder-pill px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-800 text-white transition flex-shrink-0">
                    الكل
                </button>
                <button type="button" onclick="filterByFolder('أغلفة الكتب', this)" class="folder-pill px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition flex-shrink-0">
                    أغلفة الكتب
                </button>
                <button type="button" onclick="filterByFolder('معرض الصور', this)" class="folder-pill px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition flex-shrink-0">
                    معرض الصور
                </button>
                <button type="button" onclick="filterByFolder('الأخبار والمقالات', this)" class="folder-pill px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition flex-shrink-0">
                    الأخبار
                </button>
                <button type="button" onclick="filterByFolder('صور الموقع', this)" class="folder-pill px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition flex-shrink-0">
                    الموقع
                </button>
            </div>
        </div>

        <!-- Images Grid -->
        <div class="overflow-y-auto flex-grow pr-1 custom-scrollbar">
            @if(count($availableCovers) > 0)
                <div id="modalImagesGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 p-1">
                    @foreach($availableCovers as $item)
                        @php
                            $isCur = ($book->cover_image === $item['path']) || ($book->cover_image === $item['url']) || (str_ends_with($book->cover_image ?? '', $item['name']));
                        @endphp
                        <div class="image-card relative group bg-slate-50 rounded-xl overflow-hidden border-2 {{ $isCur ? 'border-emerald-600 ring-2 ring-emerald-500/30' : 'border-slate-200 hover:border-emerald-500' }} transition cursor-pointer p-1.5 flex flex-col justify-between"
                             data-name="{{ strtolower($item['name']) }}"
                             data-folder="{{ $item['folder'] }}"
                             onclick="selectImageAsCover('{{ $item['path'] }}', '{{ $item['url'] }}', this)">
                            
                            <div class="h-28 rounded-lg overflow-hidden bg-white relative flex items-center justify-center">
                                <img src="{{ $item['url'] }}" alt="{{ $item['name'] }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @if($isCur)
                                    <span class="absolute top-1.5 right-1.5 bg-emerald-700 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] shadow">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                @endif
                            </div>

                            <div class="pt-2 px-1 text-center">
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-slate-200 text-slate-700 font-semibold truncate inline-block mb-1">
                                    {{ $item['folder'] }}
                                </span>
                                <p class="text-[10px] text-slate-600 font-medium truncate dir-ltr" title="{{ $item['name'] }}">
                                    {{ $item['name'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div id="noImagesFound" class="hidden text-center py-12 text-slate-400 text-xs">
                    لا توجد صور مطابقة لنتائج البحث.
                </div>
            @else
                <div class="text-center py-12 text-slate-400 text-xs">
                    لم يتم العثور على أي صور مرفوعة مسبقاً. يمكنك رفع صورة جديدة من جهازك مباشرة.
                </div>
            @endif
        </div>

        <!-- Modal Footer -->
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
            <span class="text-[11px] text-slate-400">إجمالي الصور المتاحة: {{ count($availableCovers) }}</span>
            <button type="button" onclick="closeCoverPickerModal()" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                إغلاق
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Quill WYSIWYG Editor JS -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    var quillToolbarOptions = [
        [{ 'header': [2, 3, 4, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'align': [] }],
        ['blockquote', 'link'],
        ['clean']
    ];

    // Initialize Quill for Summary
    var quillSummary = new Quill('#quill-summary', {
        theme: 'snow',
        placeholder: 'اكتب ملخصاً أو نبذة عن الكتاب مع التنسيقات...',
        modules: { toolbar: quillToolbarOptions }
    });

    // Initialize Quill for Table of Contents
    var quillToc = new Quill('#quill-toc', {
        theme: 'snow',
        placeholder: 'اكتب أو الصق فهرس الأبواب والموضوعات هنا مع التنسيقات...',
        modules: { toolbar: quillToolbarOptions }
    });

    // Sync to hidden textareas on form submit
    var bookForm = document.getElementById('book-form');
    var summaryInput = document.getElementById('book-summary');
    var tocInput = document.getElementById('book-toc');
    var rawSummaryEditor = document.getElementById('raw-summary-editor');
    var rawTocEditor = document.getElementById('raw-toc-editor');
    var isRawSummaryMode = false;
    var isRawTocMode = false;

    if (bookForm) {
        bookForm.onsubmit = function() {
            if (!isRawSummaryMode) {
                summaryInput.value = quillSummary.root.innerHTML;
            } else {
                summaryInput.value = rawSummaryEditor.value;
            }

            if (!isRawTocMode) {
                tocInput.value = quillToc.root.innerHTML;
            } else {
                tocInput.value = rawTocEditor.value;
            }
        };
    }

    // Toggle Raw HTML for Summary
    function toggleRawSummaryMode() {
        isRawSummaryMode = !isRawSummaryMode;
        var wrapper = document.getElementById('quill-summary-wrapper');
        var btn = document.getElementById('toggle-summary-html-btn');
        if (isRawSummaryMode) {
            rawSummaryEditor.value = quillSummary.root.innerHTML;
            wrapper.classList.add('hidden');
            rawSummaryEditor.classList.remove('hidden');
            btn.innerHTML = '<i class="fa-solid fa-pen-nib"></i> <span>العودة للمحرر المرئي</span>';
        } else {
            quillSummary.root.innerHTML = rawSummaryEditor.value;
            rawSummaryEditor.classList.add('hidden');
            wrapper.classList.remove('hidden');
            btn.innerHTML = '<i class="fa-solid fa-code"></i> <span>تبديل لكود HTML المصدر</span>';
        }
    }

    function syncFromRawSummary(val) {
        summaryInput.value = val;
    }

    // Toggle Raw HTML for TOC
    function toggleRawTocMode() {
        isRawTocMode = !isRawTocMode;
        var wrapper = document.getElementById('quill-toc-wrapper');
        var btn = document.getElementById('toggle-toc-html-btn');
        if (isRawTocMode) {
            rawTocEditor.value = quillToc.root.innerHTML;
            wrapper.classList.add('hidden');
            rawTocEditor.classList.remove('hidden');
            btn.innerHTML = '<i class="fa-solid fa-pen-nib"></i> <span>العودة للمحرر المرئي</span>';
        } else {
            quillToc.root.innerHTML = rawTocEditor.value;
            rawTocEditor.classList.add('hidden');
            wrapper.classList.remove('hidden');
            btn.innerHTML = '<i class="fa-solid fa-code"></i> <span>تبديل لكود HTML المصدر</span>';
        }
    }

    function syncFromRawToc(val) {
        tocInput.value = val;
    }

    let currentFolderFilter = 'all';

    function openCoverPickerModal() {
        const modal = document.getElementById('coverPickerModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('coverSearchInput').value = '';
        filterCoverImages();
    }

    function closeCoverPickerModal() {
        const modal = document.getElementById('coverPickerModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeCoverPickerModal();
    });

    // Select image from modal
    function selectImageAsCover(path, url, cardElement) {
        document.getElementById('cover_image_input').value = path;
        
        // Update preview image
        const previewImg = document.getElementById('cover_preview_img');
        const placeholder = document.getElementById('cover_placeholder');
        const displayLabel = document.getElementById('cover_path_display');
        
        previewImg.src = url;
        previewImg.classList.remove('hidden');
        placeholder.classList.add('hidden');
        displayLabel.textContent = path.split('/').pop();
        displayLabel.title = path;

        // Reset file input
        const fileInput = document.getElementById('cover_file_input');
        if (fileInput) fileInput.value = '';

        // Uncheck remove checkbox if present
        const removeCb = document.getElementById('remove_cover_cb');
        if (removeCb) removeCb.checked = false;

        // Highlight selected card
        document.querySelectorAll('.image-card').forEach(card => {
            card.classList.remove('border-emerald-600', 'ring-2', 'ring-emerald-500/30');
            card.classList.add('border-slate-200');
        });
        if (cardElement) {
            cardElement.classList.add('border-emerald-600', 'ring-2', 'ring-emerald-500/30');
            cardElement.classList.remove('border-slate-200');
        }

        closeCoverPickerModal();
    }

    // Preview newly selected local file
    function previewUploadedCoverFile(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('cover_preview_img');
            const placeholder = document.getElementById('cover_placeholder');
            const displayLabel = document.getElementById('cover_path_display');

            previewImg.src = e.target.result;
            previewImg.classList.remove('hidden');
            placeholder.classList.add('hidden');
            displayLabel.textContent = file.name + ' (جاهز للرفع)';

            // Clear manual text path
            document.getElementById('cover_image_input').value = '';

            // Uncheck remove checkbox
            const removeCb = document.getElementById('remove_cover_cb');
            if (removeCb) removeCb.checked = false;
        };
        reader.readAsDataURL(file);
    }

    // Preview manual input path
    function previewManualCoverPath(val) {
        const previewImg = document.getElementById('cover_preview_img');
        const placeholder = document.getElementById('cover_placeholder');
        const displayLabel = document.getElementById('cover_path_display');

        if (!val || val.trim() === '') {
            previewImg.classList.add('hidden');
            placeholder.classList.remove('hidden');
            displayLabel.textContent = 'بدون صورة';
            return;
        }

        const trimmed = val.trim();
        const url = (trimmed.startsWith('http://') || trimmed.startsWith('https://')) 
            ? trimmed 
            : ('/' + trimmed.replace(/^\/+/, ''));

        previewImg.src = url;
        previewImg.classList.remove('hidden');
        placeholder.classList.add('hidden');
        displayLabel.textContent = trimmed.split('/').pop();
    }

    // Clear selection
    function clearCoverSelection() {
        document.getElementById('cover_image_input').value = '';
        const fileInput = document.getElementById('cover_file_input');
        if (fileInput) fileInput.value = '';

        const previewImg = document.getElementById('cover_preview_img');
        const placeholder = document.getElementById('cover_placeholder');
        const displayLabel = document.getElementById('cover_path_display');

        previewImg.classList.add('hidden');
        placeholder.classList.remove('hidden');
        displayLabel.textContent = 'بدون صورة';
    }

    // Handle remove checkbox
    function toggleRemoveCover(cb) {
        if (cb.checked) {
            clearCoverSelection();
        }
    }

    // Filter modal images by search text and folder
    function filterCoverImages() {
        const query = (document.getElementById('coverSearchInput').value || '').toLowerCase().trim();
        const cards = document.querySelectorAll('.image-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const folder = card.getAttribute('data-folder') || '';

            const matchesText = !query || name.includes(query) || folder.toLowerCase().includes(query);
            const matchesFolder = (currentFolderFilter === 'all') || (folder === currentFolderFilter);

            if (matchesText && matchesFolder) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        const noFound = document.getElementById('noImagesFound');
        if (noFound) {
            if (visibleCount === 0) {
                noFound.classList.remove('hidden');
            } else {
                noFound.classList.add('hidden');
            }
        }
    }

    function filterByFolder(folder, btn) {
        currentFolderFilter = folder;
        document.querySelectorAll('.folder-pill').forEach(p => {
            p.classList.remove('bg-emerald-800', 'text-white');
            p.classList.add('bg-slate-100', 'text-slate-600');
        });
        btn.classList.add('bg-emerald-800', 'text-white');
        btn.classList.remove('bg-slate-100', 'text-slate-600');
        filterCoverImages();
    }
</script>
@endpush

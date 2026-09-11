@extends('layouts.admin')

@section('title', 'إضافة ألبوم جديد')

@push('styles')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    <style>
        /* FilePond Grid Layout for neat multi-image upload */
        .filepond--root {
            font-family: inherit;
        }
        .filepond--panel-root {
            background-color: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 1rem;
        }
        .filepond--drop-label {
            color: #475569;
            cursor: pointer;
            min-height: 120px;
        }
        .filepond--drop-label label {
            font-size: 0.95rem;
            cursor: pointer;
        }
        .filepond--label-action {
            text-decoration: underline;
            color: #065f46;
            font-weight: 700;
        }
        .filepond--item {
            width: calc(20% - 0.5em);
        }
        @media (max-width: 1024px) {
            .filepond--item {
                width: calc(33.33% - 0.5em);
            }
        }
        @media (max-width: 640px) {
            .filepond--item {
                width: calc(50% - 0.5em);
            }
        }
    </style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <h2 class="text-xl font-bold text-slate-800">إضافة ألبوم جديد</h2>
        <a href="{{ route('admin.gallery.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
            ← العودة للألبومات
        </a>
    </div>

    @if (isset($errors) && $errors->any())
        <div class="p-4 bg-red-50 text-red-700 rounded-xl text-xs border border-red-200 space-y-1">
            <div class="font-bold">يرجى تصحيح الأخطاء التالية:</div>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.gallery.store') }}" method="POST" id="albumCreateForm" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- عنوان الألبوم -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">عنوان الألبوم <span class="text-red-500">*</span></label>
                <input type="text" name="title" required value="{{ old('title') }}" 
                    class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 transition"
                    placeholder="مثال: مجلس الليلة الأولى من محرم 1447هـ">
            </div>

            <!-- التصنيف الأب (اختياري) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">التصنيف الأب (اختياري)</label>
                <select name="pid" class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 transition">
                    <option value="0">ألبوم رئيسي</option>
                    @foreach($parentAlbums as $parent)
                        <option value="{{ $parent->id }}" {{ old('pid') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- تاريخ المناسبة -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">تاريخ المناسبة (اختياري)</label>
                <input type="date" name="event_date" value="{{ old('event_date') }}"
                    class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 transition">
            </div>

            <!-- صورة الغلاف -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">صورة الغلاف للألبوم</label>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 flex items-center gap-2.5">
                    <i class="fa-solid fa-wand-magic-sparkles text-emerald-700 text-base"></i>
                    <div>
                        <span class="font-bold text-slate-800">تحديد تلقائي:</span>
                        <span class="text-slate-500">سيتم اعتماد أول صورة تقوم برفعها كغلاف للألبوم تلقائياً، مع إمكانية اختيار أي صورة أخرى بنقرة واحدة لاحقاً.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- وصف مختصر -->
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">وصف مختصر</label>
            <textarea name="brief" rows="3" 
                class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 transition"
                placeholder="نبذة عن صور الألبوم ومكان وتاريخ الفعالية...">{{ old('brief') }}</textarea>
        </div>

        <!-- رفع الصور للألبوم (سحب وإفلات) -->
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <label class="block text-xs font-bold text-slate-800">
                    <i class="fa-solid fa-cloud-arrow-up text-emerald-700 ml-1"></i>
                    رفع الصور للألبوم (سحب وإفلات)
                </label>
                <span class="text-[11px] text-slate-400">يدعم حتى 50 صورة دفعة واحدة (JPEG, PNG, WEBP)</span>
            </div>

            <input type="file" class="filepond" name="file" multiple 
                data-allow-reorder="true"
                data-max-file-size="10MB"
                data-max-files="50"
                data-image-preview-height="120">

            <!-- Container for hidden inputs with uploaded file paths -->
            <div id="uploaded-paths"></div>
        </div>

        <!-- الترتيب وحالة الظهور -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-center pt-2">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">الترتيب</label>
                <input type="number" name="ord" value="{{ old('ord', 0) }}" min="0"
                    class="w-full sm:w-40 text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 transition">
            </div>

            <div class="pt-2 sm:pt-6">
                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="isdisabled" value="1" {{ old('isdisabled') ? 'checked' : '' }}
                        class="w-4 h-4 text-emerald-700 rounded border-slate-300 focus:ring-emerald-600">
                    <span class="text-xs font-bold text-slate-700">مخفي من الموقع</span>
                </label>
            </div>
        </div>

        <!-- أزرار الإجراءات -->
        <div class="pt-6 border-t border-slate-100 flex items-center justify-start gap-3">
            <button type="submit" id="submitBtn" class="px-7 py-3 bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-check"></i>
                <span>حفظ الألبوم والصور</span>
            </button>
            <a href="{{ route('admin.gallery.index') }}" class="px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-bold text-xs rounded-xl transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

    <script>
        FilePond.registerPlugin(
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType,
            FilePondPluginImagePreview
        );

        const inputElement = document.querySelector('.filepond');
        const uploadedPathsContainer = document.getElementById('uploaded-paths');

        const pond = FilePond.create(inputElement, {
            acceptedFileTypes: ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
            labelIdle: 'اسحب وأفلت الصور هنا أو <span class="filepond--label-action">استعرض من جهازك</span>',
            labelFileProcessing: 'جارٍ الرفع...',
            labelFileProcessingComplete: 'تم الرفع بنجاح',
            labelFileProcessingError: 'خطأ أثناء الرفع',
            labelTapToCancel: 'انقر للإلغاء',
            labelTapToRetry: 'انقر لإعادة المحاولة',
            labelTapToRemove: 'انقر للإزالة',
            server: {
                process: {
                    url: '{{ route("admin.gallery.upload") }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                revert: {
                    url: '{{ route("admin.gallery.upload.revert") }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            }
        });

        // When a file is successfully processed, add a hidden input
        pond.on('processfile', (error, file) => {
            if (error) return;
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'photos[]';
            input.value = file.serverId;
            input.dataset.fileId = file.id;
            uploadedPathsContainer.appendChild(input);
        });

        // When a file is removed from FilePond, remove corresponding hidden input
        pond.on('removefile', (error, file) => {
            if (error) return;
            const input = uploadedPathsContainer.querySelector(`input[data-file-id="${file.id}"]`);
            if (input) {
                input.remove();
            }
        });
    </script>
@endpush

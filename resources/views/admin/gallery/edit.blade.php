@extends('layouts.admin')

@section('title', 'تعديل الألبوم: ' . $album->title)

@push('styles')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    <style>
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
            min-height: 100px;
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
        .cover-active-card {
            border-color: #059669 !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.25) !important;
        }
    </style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div>
            <h2 class="text-xl font-bold text-slate-800">تعديل الألبوم: {{ $album->title }}</h2>
            <p class="text-xs text-slate-500 mt-1">تعديل بيانات الألبوم، تحديد صورة الغلاف بنقرة واحدة، ورفع صور جديدة.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('gallery.show', $album->slug) }}" target="_blank" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>معاينة في الموقع</span>
            </a>
            <a href="{{ route('admin.gallery.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                ← العودة للألبومات
            </a>
        </div>
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

    <!-- تنبيه نجاح سريع (Toast Notification) -->
    <div id="quickToast" class="hidden fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-emerald-800 text-white text-xs font-bold px-5 py-3 rounded-2xl shadow-xl border border-emerald-600 flex items-center gap-2 transition-all duration-300">
        <i class="fa-solid fa-circle-check text-gold-400 text-sm"></i>
        <span id="quickToastMsg">تم التحديث بنجاح</span>
    </div>

    <form action="{{ route('admin.gallery.update', $album->id) }}" method="POST" enctype="multipart/form-data" id="albumEditForm" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-8">
        @csrf
        @method('PUT')

        <!-- بيانات الألبوم الأساسية -->
        <div class="space-y-6">
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-2">
                <i class="fa-solid fa-circle-info text-emerald-700"></i>
                <span>1. معلومات الألبوم الأساسية</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- عنوان الألبوم -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">عنوان الألبوم <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required value="{{ old('title', $album->title) }}" 
                        class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 transition">
                </div>

                <!-- التصنيف الأب (اختياري) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">التصنيف الأب (اختياري)</label>
                    <select name="pid" class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 transition">
                        <option value="0">ألبوم رئيسي</option>
                        @foreach($parentAlbums as $parent)
                            <option value="{{ $parent->id }}" {{ (old('pid', $album->parent_id) == $parent->id) ? 'selected' : '' }}>
                                {{ $parent->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- تاريخ المناسبة -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">تاريخ المناسبة</label>
                    <input type="date" name="event_date" value="{{ old('event_date', $album->event_date ? $album->event_date->format('Y-m-d') : '') }}"
                        class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 transition">
                </div>

                <!-- خيار صورة الغلاف المرئي بالكامل (بدون إدخال مسارات) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                        <span>صورة غلاف الألبوم الرئيسية</span>
                        <span id="cover_status_badge" class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ $album->cover_image ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ $album->cover_image ? 'تم تحديد الغلاف' : 'تلقائي (أول صورة)' }}
                        </span>
                    </label>

                    <div class="flex items-center gap-3.5 p-3 bg-slate-50 border border-slate-200 rounded-2xl">
                        <!-- معاينة مصغرة للغلاف -->
                        <div class="w-20 h-16 rounded-xl overflow-hidden bg-slate-200 border border-slate-300 flex-shrink-0 relative shadow-xs">
                            <img id="current_cover_preview" src="{{ $album->cover_url ?: asset('images/default-image.jpg') }}" alt="غلاف الألبوم" class="w-full h-full object-cover transition-all duration-300">
                            <span class="absolute top-1 right-1 px-1.5 py-0.2 rounded text-[9px] font-bold bg-black/70 text-gold-300">
                                الغلاف
                            </span>
                        </div>

                        <!-- أزرار الاختيار والتحكم -->
                        <div class="space-y-1.5 flex-grow">
                            <p class="text-[11px] text-slate-600 font-medium truncate" id="cover_desc_text">
                                {{ $album->cover_image ? basename($album->cover_image) : 'سيتم اعتماد أول صورة في الألبوم تلقائياً' }}
                            </p>

                            <div class="flex flex-wrap items-center gap-2">
                                @if($items->count() > 0)
                                <button type="button" onclick="openCoverModal()" class="px-3 py-1.5 bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-xs">
                                    <i class="fa-solid fa-images"></i>
                                    <span>اختر من صور الألبوم</span>
                                </button>
                                @endif

                                <button type="button" onclick="document.getElementById('cover_file_input').click()" class="px-2.5 py-1.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 text-xs font-bold rounded-xl transition flex items-center gap-1">
                                    <i class="fa-solid fa-arrow-up-from-bracket text-[11px]"></i>
                                    <span>رفع غلاف مستقل</span>
                                </button>
                            </div>

                            <!-- حقول الإسناد المخفية -->
                            <input type="file" id="cover_file_input" name="cover_file" accept="image/*" class="hidden" onchange="previewNewCoverFile(this)">
                            <input type="hidden" name="cover_image" id="cover_image_input" value="{{ old('cover_image', $album->cover_image) }}">
                            <input type="hidden" name="selected_cover" id="selected_cover" value="{{ old('selected_cover', $album->cover_image) }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- وصف مختصر -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">وصف مختصر</label>
                <textarea name="brief" rows="3" 
                    class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 transition">{{ old('brief', $album->description) }}</textarea>
            </div>

            <!-- الترتيب وحالة الظهور -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-center">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">الترتيب</label>
                    <input type="number" name="ord" value="{{ old('ord', $album->order) }}" min="0"
                        class="w-full sm:w-40 text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 transition">
                </div>

                <div class="pt-2 sm:pt-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="isdisabled" value="1" {{ old('isdisabled', !$album->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-emerald-700 rounded border-slate-300 focus:ring-emerald-600">
                        <span class="text-xs font-bold text-slate-700">مخفي من الموقع</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- معرض الصور الحالية في الألبوم واختيار الغلاف -->
        <div class="space-y-4 pt-4 border-t border-slate-100" id="albumPhotosSection">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-images text-emerald-700"></i>
                    <span>2. صور الألبوم الحالية ({{ $items->count() }} صورة)</span>
                </h3>
                <span class="text-[11px] text-slate-500">انقر على «تعيين كغلاف» أو على أي صورة لاختيارها كغلاف رئيسي للألبوم</span>
            </div>

            @if($items->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                    @foreach($items as $item)
                        @php
                            $isCurrentCover = ($album->cover_image === $item->image_path) || ($album->cover_image === asset($item->image_path)) || (str_ends_with($album->cover_image ?? '', basename($item->image_path)));
                        @endphp
                        <div class="relative group bg-white rounded-xl overflow-hidden border-2 {{ $isCurrentCover ? 'cover-active-card' : 'border-slate-200 hover:border-emerald-400' }} transition-all flex flex-col justify-between shadow-xs cover-picker-item" 
                            data-path="{{ $item->image_path }}" data-url="{{ $item->url }}">
                            
                            <div class="h-28 bg-slate-100 overflow-hidden cursor-pointer relative" onclick="selectAsCover('{{ $item->image_path }}', '{{ $item->url }}', this)">
                                <img src="{{ $item->url }}" alt="{{ $item->caption }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/25 transition flex items-center justify-center">
                                    <span class="px-2 py-1 rounded bg-black/70 text-white text-[10px] font-bold opacity-0 group-hover:opacity-100 transition">
                                        اختيار كغلاف
                                    </span>
                                </div>
                            </div>

                            <!-- الشارة والإجراءات -->
                            <div class="p-1.5 bg-white flex items-center justify-between text-[10px] border-t border-slate-100">
                                <button type="button" onclick="selectAsCover('{{ $item->image_path }}', '{{ $item->url }}', this)" 
                                        class="cover-badge font-bold px-2 py-0.5 rounded transition {{ $isCurrentCover ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600 hover:bg-emerald-50 hover:text-emerald-800' }}">
                                    {{ $isCurrentCover ? '★ الغلاف' : 'تعيين كغلاف' }}
                                </button>
                                
                                <button type="button" onclick="deletePhoto({{ $item->id }}, this)" class="text-red-500 hover:text-red-700 p-1 hover:bg-red-50 rounded transition" title="حذف الصورة">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-300 text-slate-400 text-xs">
                    لا توجد صور في هذا الألبوم بعد. استخدم النموذج أدناه لرفع صور جديدة.
                </div>
            @endif
        </div>

        <!-- رفع صور إضافية بواسطة FilePond -->
        <div class="space-y-3 pt-4 border-t border-slate-100">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up text-emerald-700"></i>
                    <span>3. إضافة صور جديدة للألبوم (سحب وإفلات)</span>
                </h3>
                <span class="text-[11px] text-slate-400">يدعم حتى 50 صورة دفعة واحدة</span>
            </div>

            <input type="file" class="filepond" name="file" multiple 
                data-allow-reorder="true"
                data-max-file-size="10MB"
                data-max-files="50"
                data-image-preview-height="120">

            <div id="uploaded-paths"></div>
        </div>

        <!-- أزرار الحفظ -->
        <div class="pt-6 border-t border-slate-100 flex items-center justify-start gap-3">
            <button type="submit" class="px-7 py-3 bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-check"></i>
                <span>تحديث الألبوم والصور</span>
            </button>
            <a href="{{ route('admin.gallery.index') }}" class="px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-bold text-xs rounded-xl transition">
                إلغاء
            </a>
        </div>
    </form>
</div>

<!-- نافذة اختيار الغلاف المنبثقة (Cover Picker Modal) -->
<div id="coverPickerModal" class="fixed inset-0 z-50 bg-black/75 hidden items-center justify-center p-4 backdrop-blur-xs">
    <div class="bg-white rounded-3xl max-w-3xl w-full p-6 space-y-4 shadow-2xl border border-slate-100 max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-images text-emerald-700"></i>
                    <span>اختر صورة الغلاف من صور الألبوم</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">انقر على أي صورة لاعتمادها فوراً كغلاف رئيسي للألبوم.</p>
            </div>
            <button type="button" onclick="closeCoverModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="overflow-y-auto flex-grow pr-1">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 p-1">
                @foreach($items as $item)
                @php
                    $isCur = ($album->cover_image === $item->image_path) || ($album->cover_image === asset($item->image_path)) || (str_ends_with($album->cover_image ?? '', basename($item->image_path)));
                @endphp
                <div class="relative group bg-slate-50 rounded-xl overflow-hidden border-2 {{ $isCur ? 'border-emerald-600 ring-2 ring-emerald-500/30' : 'border-slate-200 hover:border-emerald-500' }} transition cursor-pointer p-1"
                     onclick="pickCoverFromModal('{{ $item->image_path }}', '{{ $item->url }}', this)">
                    <div class="h-28 rounded-lg overflow-hidden bg-white">
                        <img src="{{ $item->url }}" alt="{{ $item->caption }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <div class="p-1.5 text-center">
                        <span class="text-[10px] font-bold {{ $isCur ? 'text-emerald-700' : 'text-slate-500' }}">
                            {{ $isCur ? '★ الغلاف الحالي' : 'اختر هذا الغلاف' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex justify-end">
            <button type="button" onclick="closeCoverModal()" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                إغلاق
            </button>
        </div>
    </div>
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
            labelIdle: 'اسحب وأفلت صوراً جديدة هنا أو <span class="filepond--label-action">استعرض من جهازك</span>',
            labelFileProcessing: 'جارٍ الرفع...',
            labelFileProcessingComplete: 'تم الرفع بنجاح',
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

        pond.on('processfile', (error, file) => {
            if (error) return;
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'photos[]';
            input.value = file.serverId;
            input.dataset.fileId = file.id;
            uploadedPathsContainer.appendChild(input);
        });

        pond.on('removefile', (error, file) => {
            if (error) return;
            const input = uploadedPathsContainer.querySelector(`input[data-file-id="${file.id}"]`);
            if (input) input.remove();
        });

        // Open/Close Cover Modal
        function openCoverModal() {
            const modal = document.getElementById('coverPickerModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeCoverModal() {
            const modal = document.getElementById('coverPickerModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function showToast(msg) {
            const toast = document.getElementById('quickToast');
            const toastMsg = document.getElementById('quickToastMsg');
            toastMsg.textContent = msg;
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        // Apply selected cover across the UI & save to inputs
        function selectAsCover(imagePath, imageUrl, element) {
            // Update hidden inputs
            document.getElementById('selected_cover').value = imagePath;
            document.getElementById('cover_image_input').value = imagePath;

            // Update top preview image
            const preview = document.getElementById('current_cover_preview');
            preview.src = imageUrl || imagePath;
            preview.style.transform = 'scale(1.1)';
            setTimeout(() => { preview.style.transform = 'scale(1)'; }, 250);

            // Update name and status text
            document.getElementById('cover_desc_text').textContent = imagePath.split('/').pop();
            const badge = document.getElementById('cover_status_badge');
            badge.textContent = 'تم تحديد الغلاف';
            badge.className = 'text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800';

            // Update border states on cards in section 2
            document.querySelectorAll('.cover-picker-item').forEach(el => {
                el.classList.remove('cover-active-card');
                el.classList.add('border-slate-200');
                const b = el.querySelector('.cover-badge');
                if (b) {
                    b.textContent = 'تعيين كغلاف';
                    b.className = 'cover-badge font-bold px-2 py-0.5 rounded transition bg-slate-100 text-slate-600 hover:bg-emerald-50 hover:text-emerald-800';
                }
            });

            const card = element.closest('.cover-picker-item');
            if (card) {
                card.classList.remove('border-slate-200');
                card.classList.add('cover-active-card');
                const b = card.querySelector('.cover-badge');
                if (b) {
                    b.textContent = '★ الغلاف';
                    b.className = 'cover-badge font-bold px-2 py-0.5 rounded transition bg-emerald-100 text-emerald-800';
                }
            }

            // Also silently save via AJAX so changes persist immediately
            saveCoverToServer(imagePath);
        }

        function pickCoverFromModal(imagePath, imageUrl, cardElement) {
            selectAsCover(imagePath, imageUrl, cardElement);
            closeCoverModal();
            showToast('تم تعيين صورة الغلاف بنجاح!');
        }

        function saveCoverToServer(imagePath) {
            fetch(`{{ route('admin.gallery.cover.set', $album->id) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ cover_image: imagePath })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('تم حفظ وتحديث صورة الغلاف بنجاح!');
                }
            })
            .catch(err => {
                console.log('Cover set locally for form submit');
            });
        }

        function previewNewCoverFile(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('current_cover_preview').src = e.target.result;
                    document.getElementById('cover_desc_text').textContent = input.files[0].name + ' (سيتم رفعها كغلاف جديد عند الحفظ)';
                    const badge = document.getElementById('cover_status_badge');
                    badge.textContent = 'غلاف جديد محلي';
                    badge.className = 'text-[11px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Delete photo item via AJAX
        function deletePhoto(itemId, btn) {
            if (!confirm('هل أنت متأكد من حذف هذه الصورة نهائياً؟')) return;

            const card = btn.closest('.cover-picker-item');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

            fetch(`{{ url('admin/gallery/items') }}/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (card) {
                        card.style.opacity = '0';
                        setTimeout(() => card.remove(), 250);
                    }
                    showToast('تم حذف الصورة بنجاح');
                } else {
                    alert('تعذر حذف الصورة');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-trash-can"></i>';
                }
            })
            .catch(err => {
                console.error(err);
                alert('حدث خطأ أثناء الحذف');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-trash-can"></i>';
            });
        }
    </script>
@endpush

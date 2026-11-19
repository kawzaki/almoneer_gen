@extends('layouts.admin')

@section('title', 'إدارة ألبوم الصور')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">إدارة ألبوم الصور والمناسبات</h2>
            <p class="text-xs text-slate-500 mt-1">إنشاء ألبومات الصور، رفع متعدد للصور بالسحب والإفلات، وتوثيق المناسبات والمجالس والجولات.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.gallery.create') }}" class="px-5 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>إضافة ألبوم جديد</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 text-emerald-800 rounded-xl text-xs border border-emerald-200 flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- تصفية بحسب الألبوم الأب إذا كان هناك ألبومات رئيسية -->
    @if(isset($parents) && $parents->count() > 0)
    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
        <span class="text-slate-500 font-bold ml-1">تصفية:</span>
        <a href="{{ route('admin.gallery.index') }}" 
           class="px-3 py-1.5 rounded-lg border transition {{ empty($parentId) ? 'bg-emerald-800 text-white border-emerald-800 font-bold' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
            الكل ({{ $albums->total() }})
        </a>
        <a href="{{ route('admin.gallery.index', ['parent_id' => '0']) }}" 
           class="px-3 py-1.5 rounded-lg border transition {{ $parentId === '0' ? 'bg-emerald-800 text-white border-emerald-800 font-bold' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
            الألبومات الرئيسية فقط
        </a>
        @foreach($parents as $parent)
            <a href="{{ route('admin.gallery.index', ['parent_id' => $parent->id]) }}" 
               class="px-3 py-1.5 rounded-lg border transition {{ $parentId == $parent->id ? 'bg-emerald-800 text-white border-emerald-800 font-bold' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                {{ $parent->title }}
            </a>
        @endforeach
    </div>
    @endif

    <!-- شريط العمليات الجماعية للألبومات المحددة -->
    <div id="bulkAlbumsBar" class="hidden p-3.5 bg-red-50 border-2 border-red-200 rounded-2xl flex flex-wrap items-center justify-between gap-3 shadow-sm transition-all duration-300">
        <div class="flex items-center gap-2.5 text-xs text-red-900 font-bold">
            <span class="w-7 h-7 rounded-lg bg-red-600 text-white flex items-center justify-center text-xs shadow-xs">
                <i class="fa-solid fa-check"></i>
            </span>
            <span>تم تحديد <span id="selectedAlbumsCount" class="text-sm font-extrabold text-red-700">0</span> ألبوم</span>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="selectAllAlbums(false)" class="px-3 py-1.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-xl text-xs font-semibold transition">
                إلغاء التحديد
            </button>
            <button type="button" onclick="executeBulkDeleteAlbums()" id="btnBulkDeleteAlbums" class="px-4 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-1.5">
                <i class="fa-solid fa-trash-can"></i>
                <span>حذف الألبومات المحددة نهائياً</span>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200 whitespace-nowrap">
                    <tr>
                        <th class="p-3.5 sm:p-4 w-10 text-center">
                            <input type="checkbox" id="masterAlbumCheckbox" onchange="selectAllAlbums(this.checked)" class="rounded text-emerald-800 focus:ring-emerald-800 w-4 h-4 cursor-pointer" title="تحديد / إلغاء تحديد الكل">
                        </th>
                        <th class="p-3.5 sm:p-4 font-bold w-20">الغلاف</th>
                        <th class="p-3.5 sm:p-4 font-bold">عنوان الألبوم</th>
                        <th class="p-3.5 sm:p-4 font-bold">التصنيف الأب</th>
                        <th class="p-3.5 sm:p-4 font-bold">تاريخ المناسبة</th>
                        <th class="p-3.5 sm:p-4 font-bold">عدد الصور</th>
                        <th class="p-3.5 sm:p-4 font-bold">الحالة</th>
                        <th class="p-3.5 sm:p-4 font-bold text-center w-28">الإجراءات</th>
                    </tr>
                </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($albums as $album)
                <tr class="hover:bg-slate-50 transition" id="album-row-{{ $album->id }}">
                    <td class="p-3 text-center">
                        <input type="checkbox" value="{{ $album->id }}" class="album-checkbox rounded text-emerald-800 focus:ring-emerald-800 w-4 h-4 cursor-pointer" onchange="onAlbumCheckboxChange(this, {{ $album->id }})">
                    </td>
                    <td class="p-3">
                        <div class="w-14 h-10 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                            @if($album->cover_url)
                                <img src="{{ $album->cover_url }}" alt="{{ $album->title }}" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-images text-sm text-slate-300"></i>
                            @endif
                        </div>
                    </td>
                    <td class="p-4 font-bold text-slate-800">
                        <div class="font-bold text-slate-900">{{ $album->title }}</div>
                        @if($album->description)
                            <div class="text-[11px] text-slate-400 font-normal line-clamp-1 mt-0.5">{{ $album->description }}</div>
                        @endif
                    </td>
                    <td class="p-4 text-slate-600">
                        @if($album->parent)
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md">
                                <i class="fa-solid fa-folder text-[9px]"></i>
                                {{ $album->parent->title }}
                            </span>
                        @else
                            <span class="text-slate-400 text-[11px]">ألبوم رئيسي</span>
                        @endif
                    </td>
                    <td class="p-4 text-slate-500 whitespace-nowrap">
                        @if($album->event_date)
                            <span class="inline-flex items-center gap-0.5" dir="rtl">
                                <span>{{ $album->event_date->format('d') }}</span>/<span>{{ $album->event_date->format('m') }}</span>/<span>{{ $album->event_date->format('Y') }}</span>
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="p-4 whitespace-nowrap">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-bold text-[11px]">
                            <i class="fa-regular fa-image text-slate-400 text-[10px]"></i>
                            {{ $album->items_count }} صورة
                        </span>
                    </td>
                    <td class="p-4 whitespace-nowrap">
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $album->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ $album->is_active ? 'نشط' : 'مخفي' }}
                        </span>
                    </td>
                    <td class="p-4 text-center whitespace-nowrap">
                        <div class="inline-flex items-center gap-1">
                            <a href="{{ route('gallery.show', $album->slug) }}" target="_blank" class="p-2 text-slate-500 hover:text-emerald-800 hover:bg-slate-100 rounded-lg transition" title="عرض في الموقع">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                            <a href="{{ route('admin.gallery.edit', $album->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="تعديل الألبوم والصور">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.gallery.destroy', $album->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الألبوم وكافة صوره؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="حذف الألبوم">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-8 text-center text-slate-400">
                        <div class="space-y-2">
                            <i class="fa-solid fa-images text-2xl text-slate-300"></i>
                            <p>لا توجد ألبومات صور مطابقة.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div>{{ $albums->links() }}</div>
</div>
@endsection

@push('scripts')
<script>
    function onAlbumCheckboxChange(cb, albumId) {
        const row = document.getElementById('album-row-' + albumId);
        if (cb.checked) {
            row?.classList.add('bg-red-50/50');
        } else {
            row?.classList.remove('bg-red-50/50');
        }
        updateAlbumBulkBar();
    }

    function selectAllAlbums(checked) {
        const master = document.getElementById('masterAlbumCheckbox');
        if (master) master.checked = checked;

        document.querySelectorAll('.album-checkbox').forEach(cb => {
            cb.checked = checked;
            const albumId = cb.value;
            const row = document.getElementById('album-row-' + albumId);
            if (checked) {
                row?.classList.add('bg-red-50/50');
            } else {
                row?.classList.remove('bg-red-50/50');
            }
        });
        updateAlbumBulkBar();
    }

    function updateAlbumBulkBar() {
        const checked = document.querySelectorAll('.album-checkbox:checked');
        const count = checked.length;
        const bar = document.getElementById('bulkAlbumsBar');
        const countSpan = document.getElementById('selectedAlbumsCount');
        if (countSpan) countSpan.textContent = count;
        if (bar) {
            if (count > 0) {
                bar.classList.remove('hidden');
            } else {
                bar.classList.add('hidden');
            }
        }
    }

    async function executeBulkDeleteAlbums() {
        const checkedBoxes = Array.from(document.querySelectorAll('.album-checkbox:checked'));
        const ids = checkedBoxes.map(cb => parseInt(cb.value)).filter(id => !isNaN(id));
        if (ids.length === 0) {
            alert('يرجى تحديد ألبوم واحد على الأقل للحذف');
            return;
        }

        if (!confirm(`هل أنت متأكد من حذف ${ids.length} ألبوم نهائياً مع كافة الصور التابعة لها؟ لا يمكن التراجع عن هذا الإجراء.`)) {
            return;
        }

        const btn = document.getElementById('btnBulkDeleteAlbums');
        const origHtml = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>جارٍ الحذف...</span>';
        }

        try {
            const response = await fetch('{{ route("admin.gallery.bulk-delete") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: ids })
            });

            const data = await response.json();
            if (data.success) {
                ids.forEach(id => {
                    const row = document.getElementById('album-row-' + id);
                    if (row) {
                        row.style.opacity = '0';
                        setTimeout(() => row.remove(), 250);
                    }
                });
                alert(data.message || `تم حذف ${ids.length} ألبوم بنجاح`);
                setTimeout(() => updateAlbumBulkBar(), 300);
            } else {
                alert(data.message || 'حدث خطأ أثناء حذف الألبومات');
            }
        } catch (err) {
            console.error('Bulk delete error:', err);
            alert('تعذر الاتصال بالخادم لحذف الألبومات');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = origHtml;
            }
        }
    }
</script>
@endpush

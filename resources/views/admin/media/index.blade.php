@extends('layouts.admin')

@section('title', 'إدارة الصوتيات والمرئيات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800">إدارة الصوتيات والمرئيات والريلز</h2>
            <p class="text-xs text-slate-500 mt-0.5 sm:mt-1">إضافة محاضرات صوتية ومرئيات يوتيوب والريلز مع إمكانية التصفية بالموسم والسنة والبحث السريع والحذف المتعدد.</p>
        </div>
        <a href="{{ route('admin.media.create') }}" class="self-start sm:self-auto px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>إضافة مادة إعلامية</span>
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.media.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <!-- Search Title -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1">بحث في العنوان:</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن عنوان المحاضرة أو الموضوع..." class="w-full text-xs rounded-xl border-slate-200 pr-9 pl-3 py-2 bg-slate-50 focus:bg-white focus:border-emerald-600 transition">
                </div>
            </div>

            <!-- Hijri Year Filter -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">السنة الهجرية:</label>
                <select name="hijri_year" class="w-full text-xs rounded-xl border-slate-200 py-2 px-3 bg-slate-50 focus:bg-white focus:border-emerald-600 transition">
                    <option value="">جميع السنوات</option>
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ request('hijri_year') == $year ? 'selected' : '' }}>{{ $year }} هـ</option>
                    @endforeach
                </select>
            </div>

            <!-- Season Filter -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">الموسم:</label>
                <select name="season_slug" class="w-full text-xs rounded-xl border-slate-200 py-2 px-3 bg-slate-50 focus:bg-white focus:border-emerald-600 transition">
                    <option value="">جميع المواسم</option>
                    @foreach($availableSeasons as $slug => $label)
                        <option value="{{ $slug }}" {{ request('season_slug') == $slug ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Type Filter & Action Buttons -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">النوع:</label>
                <div class="flex items-center gap-2">
                    <select name="type" class="w-full text-xs rounded-xl border-slate-200 py-2 px-2 bg-slate-50 focus:bg-white focus:border-emerald-600 transition">
                        <option value="">الكل</option>
                        <option value="audio" {{ request('type') == 'audio' ? 'selected' : '' }}>صوتي</option>
                        <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>مرئي</option>
                        <option value="short" {{ request('type') == 'short' ? 'selected' : '' }}>ريلز</option>
                    </select>
                    <button type="submit" class="px-3.5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold rounded-xl transition shrink-0" title="تطبيق التصفية">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    @if(request()->anyFilled(['search', 'hijri_year', 'season_slug', 'type']))
                        <a href="{{ route('admin.media.index') }}" class="px-2.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs rounded-xl transition shrink-0" title="إلغاء التصفية">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Toolbar (Appears when items are selected) -->
    <div id="bulkActionsBar" class="hidden bg-emerald-950 text-white rounded-2xl p-4 shadow-xl border border-emerald-700/60 flex flex-wrap items-center justify-between gap-4 transition-all animate-fadeIn">
        <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-xl bg-gold-500 text-emerald-950 font-black flex items-center justify-center text-sm shadow">
                <span id="selectedCount">0</span>
            </span>
            <div class="text-xs">
                <span class="font-bold">عناصر محددة في الصفحة</span>
                <span class="text-slate-300 block text-[11px]">يمكنك تنفيذ إجراء جماعي عليها مباشرة</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" id="deselectAllBtn" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-semibold transition">
                إلغاء التحديد
            </button>
            <form id="bulkDeleteForm" action="{{ route('admin.media.bulk-delete', request()->query()) }}" method="POST" onsubmit="return confirmBulkDelete()">
                @csrf
                <div id="bulkHiddenInputs"></div>
                <button type="submit" class="px-4 py-1.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-xs">
                    <i class="fa-solid fa-trash"></i>
                    <span>حذف العناصر المحددة</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Media Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200 whitespace-nowrap">
                    <tr>
                        <th class="p-3.5 sm:p-4 w-10 text-center">
                            <input type="checkbox" id="selectAllCheckbox" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600 cursor-pointer">
                        </th>
                        <th class="p-3.5 sm:p-4 font-bold">العنوان</th>
                        <th class="p-3.5 sm:p-4 font-bold">النوع</th>
                        <th class="p-3.5 sm:p-4 font-bold">الموسم / الليلة</th>
                        <th class="p-3.5 sm:p-4 font-bold">المدة</th>
                        <th class="p-3.5 sm:p-4 font-bold">المشاهدات</th>
                        <th class="p-3.5 sm:p-4 font-bold text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $m)
                    <tr class="hover:bg-slate-50 transition row-item" data-id="{{ $m->id }}">
                        <td class="p-3.5 sm:p-4 text-center">
                            <input type="checkbox" class="item-checkbox rounded border-slate-300 text-emerald-700 focus:ring-emerald-600 cursor-pointer" value="{{ $m->id }}">
                        </td>
                        <td class="p-3.5 sm:p-4 font-bold text-slate-800 min-w-[200px] max-w-sm">
                            <div class="truncate" title="{{ $m->title }}">{{ $m->title }}</div>
                            @if($m->hijri_year)
                                <span class="text-[10px] text-slate-400 font-normal">{{ $m->hijri_year }} هـ</span>
                            @endif
                        </td>
                        <td class="p-3.5 sm:p-4 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded font-bold {{ $m->type === 'audio' ? 'bg-emerald-100 text-emerald-800' : ($m->type === 'short' ? 'bg-purple-100 text-purple-800' : 'bg-red-100 text-red-800') }}">
                                {{ $m->type === 'audio' ? 'صوتي' : ($m->type === 'short' ? 'ريلز قصير' : 'مرئي') }}
                            </span>
                        </td>
                        <td class="p-3.5 sm:p-4 text-slate-600 whitespace-nowrap">
                            <div>{{ $m->season_year ?? '-' }}</div>
                            @if($m->night_label)
                                <span class="inline-block mt-0.5 text-[10px] font-bold px-1.5 py-0.5 rounded bg-amber-100 text-amber-900 border border-amber-200">
                                    {{ $m->night_label }}
                                </span>
                            @endif
                        </td>
                        <td class="p-3.5 sm:p-4 text-slate-500 font-mono whitespace-nowrap">
                            @if(!empty($m->duration))
                                <span class="inline-flex items-center gap-1 bg-slate-100 px-2 py-0.5 rounded">
                                    <i class="fa-regular fa-clock text-[10px] text-slate-400"></i>
                                    {{ $m->duration }}
                                </span>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="p-3.5 sm:p-4 text-slate-500 whitespace-nowrap">{{ number_format($m->views_count) }}</td>
                        <td class="p-3.5 sm:p-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ $m->live_url }}" target="_blank" rel="noopener noreferrer" class="p-1.5 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition" title="فتح المحاضرة في الموقع">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                                <a href="{{ route('admin.media.edit', $m->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="تعديل"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.media.destroy', $m->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="حذف"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">
                            لا توجد مواد إعلامية تطابق شروط البحث أو التصفية الحالية.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $items->links() }}</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const bulkBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    const deselectBtn = document.getElementById('deselectAllBtn');
    const hiddenInputs = document.getElementById('bulkHiddenInputs');

    function updateBulkState() {
        const checked = document.querySelectorAll('.item-checkbox:checked');
        const count = checked.length;
        if (selectedCount) selectedCount.textContent = count;

        if (count > 0) {
            bulkBar.classList.remove('hidden');
        } else {
            bulkBar.classList.add('hidden');
        }

        if (selectAll) {
            selectAll.checked = (checkboxes.length > 0 && count === checkboxes.length);
        }

        // populate hidden inputs for form submission
        if (hiddenInputs) {
            hiddenInputs.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                hiddenInputs.appendChild(input);
            });
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateBulkState();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkState);
    });

    if (deselectBtn) {
        deselectBtn.addEventListener('click', function () {
            checkboxes.forEach(cb => cb.checked = false);
            if (selectAll) selectAll.checked = false;
            updateBulkState();
        });
    }

    window.confirmBulkDelete = function () {
        const count = document.querySelectorAll('.item-checkbox:checked').length;
        if (count === 0) return false;
        return confirm(`هل أنت متأكد من حذف ${count} مادة إعلامية محددة بصورة نهائية؟`);
    };
});
</script>
@endsection

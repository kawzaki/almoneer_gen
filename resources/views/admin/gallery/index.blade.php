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

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-right text-xs">
            <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                <tr>
                    <th class="p-4 font-bold w-20">الغلاف</th>
                    <th class="p-4 font-bold">عنوان الألبوم</th>
                    <th class="p-4 font-bold">التصنيف الأب</th>
                    <th class="p-4 font-bold">تاريخ المناسبة</th>
                    <th class="p-4 font-bold">عدد الصور</th>
                    <th class="p-4 font-bold">الحالة</th>
                    <th class="p-4 font-bold text-center w-28">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($albums as $album)
                <tr class="hover:bg-slate-50 transition">
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
                    <td class="p-4 text-slate-500 whitespace-nowrap">{{ $album->event_date ? $album->event_date->format('Y-m-d') : '-' }}</td>
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
                    <td colspan="7" class="p-8 text-center text-slate-400">
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

    <div>{{ $albums->links() }}</div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'إدارة ألبوم الصور')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">إدارة ألبوم الصور والمناسبات</h2>
            <p class="text-xs text-slate-500 mt-1">إنشاء ألبومات الصور وتوثيق المناسبات والمجالس والجولات.</p>
        </div>
        <a href="{{ route('admin.gallery.create') }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>إنشاء ألبوم جديد</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-right text-xs">
            <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                <tr>
                    <th class="p-4 font-bold">عنوان الألبوم</th>
                    <th class="p-4 font-bold">تاريخ المناسبة</th>
                    <th class="p-4 font-bold">عدد الصور</th>
                    <th class="p-4 font-bold">الحالة</th>
                    <th class="p-4 font-bold text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($albums as $album)
                <tr class="hover:bg-slate-50 transition">
                    <td class="p-4 font-bold text-slate-800">{{ $album->title }}</td>
                    <td class="p-4 text-slate-500">{{ $album->event_date ? $album->event_date->format('Y-m-d') : '-' }}</td>
                    <td class="p-4 text-slate-500">{{ $album->items_count }} صورة</td>
                    <td class="p-4">
                        <span class="px-2 py-0.5 rounded {{ $album->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ $album->is_active ? 'نشط' : 'معطل' }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.gallery.edit', $album->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('admin.gallery.destroy', $album->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-6 text-center text-slate-400">لا توجد ألبومات صور مضافة.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $albums->links() }}</div>
</div>
@endsection

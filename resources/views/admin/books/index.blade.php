@extends('layouts.admin')

@section('title', 'إدارة الكتب والمؤلفات')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800">إدارة الكتب والمؤلفات</h2>
            <p class="text-xs text-slate-500 mt-0.5 sm:mt-1">إضافة وإدارة مؤلفات وكتب سماحة السيد وملفات الـ PDF وفهارس الموضوعات.</p>
        </div>
        <a href="{{ route('admin.books.create') }}" class="self-start sm:self-auto px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>إضافة كتاب جديد</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200 whitespace-nowrap">
                    <tr>
                        <th class="p-3.5 sm:p-4 font-bold">عنوان الكتاب</th>
                        <th class="p-3.5 sm:p-4 font-bold">المؤلف</th>
                        <th class="p-3.5 sm:p-4 font-bold">سنة النشر</th>
                        <th class="p-3.5 sm:p-4 font-bold">الصفحات</th>
                        <th class="p-3.5 sm:p-4 font-bold">التحميلات</th>
                        <th class="p-3.5 sm:p-4 font-bold text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($books as $b)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-3.5 sm:p-4 font-bold text-slate-800 min-w-[220px]">
                            <div class="flex items-center gap-3">
                                @if($b->cover_image)
                                    <img src="{{ str_starts_with($b->cover_image, 'http') ? $b->cover_image : asset($b->cover_image) }}" class="w-9 h-12 object-cover rounded-lg shadow-xs flex-shrink-0 border border-slate-200" alt="">
                                @else
                                    <div class="w-9 h-12 rounded-lg bg-emerald-950 text-gold-300 flex items-center justify-center flex-shrink-0 text-xs border border-emerald-800/40">
                                        <i class="fa-solid fa-book"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-slate-800 leading-snug">{{ $b->title }}</div>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        @if($b->category)
                                            <span class="text-[10px] text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md font-semibold">{{ $b->category->name }}</span>
                                        @endif
                                        @if($b->is_featured)
                                            <span class="text-[10px] text-amber-800 bg-amber-50 px-1.5 py-0.5 rounded-md font-semibold">مميز</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="p-3.5 sm:p-4 text-slate-500 whitespace-nowrap">{{ $b->author }}</td>
                        <td class="p-3.5 sm:p-4 text-slate-500 whitespace-nowrap">{{ $b->publication_year ?? '-' }}</td>
                        <td class="p-3.5 sm:p-4 text-slate-500 whitespace-nowrap">{{ $b->pages_count ? $b->pages_count . ' ص' : '-' }}</td>
                        <td class="p-3.5 sm:p-4 text-slate-500 whitespace-nowrap">{{ $b->download_count }}</td>
                        <td class="p-3.5 sm:p-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.books.edit', $b->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.books.destroy', $b->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-6 text-center text-slate-400">لا توجد كتب مضافة حالياً.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $books->links() }}</div>
</div>
@endsection

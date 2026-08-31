@extends('layouts.admin')

@section('title', 'إدارة الكتب والمؤلفات')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">إدارة الكتب والمؤلفات</h2>
            <p class="text-xs text-slate-500 mt-1">إضافة وإدارة مؤلفات وكتب سماحة السيد وملفات الـ PDF وفهارس الموضوعات.</p>
        </div>
        <a href="{{ route('admin.books.create') }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>إضافة كتاب جديد</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-right text-xs">
            <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                <tr>
                    <th class="p-4 font-bold">عنوان الكتاب</th>
                    <th class="p-4 font-bold">المؤلف</th>
                    <th class="p-4 font-bold">سنة النشر</th>
                    <th class="p-4 font-bold">الصفحات</th>
                    <th class="p-4 font-bold">التحميلات</th>
                    <th class="p-4 font-bold text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($books as $b)
                <tr class="hover:bg-slate-50 transition">
                    <td class="p-4 font-bold text-slate-800">{{ $b->title }}</td>
                    <td class="p-4 text-slate-500">{{ $b->author }}</td>
                    <td class="p-4 text-slate-500">{{ $b->publication_year ?? '-' }}</td>
                    <td class="p-4 text-slate-500">{{ $b->pages_count ?? '-' }}</td>
                    <td class="p-4 text-slate-500">{{ $b->download_count }}</td>
                    <td class="p-4 text-center">
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

    <div>{{ $books->links() }}</div>
</div>
@endsection

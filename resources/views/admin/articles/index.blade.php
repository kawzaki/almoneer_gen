@extends('layouts.admin')

@section('title', 'إدارة الأخبار والنشاطات')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">إدارة الأخبار والنشاطات والبيانات</h2>
            <p class="text-xs text-slate-500 mt-1">إضافة وتحرير الأخبار وتغطيات المؤتمرات والجولات التبليغية.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.categories.index', ['module' => 'article']) }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center gap-2 border border-slate-200">
                <i class="fa-solid fa-folder-tree text-gold-600"></i>
                <span>إدارة التصنيفات</span>
            </a>
            <a href="{{ route('admin.articles.create') }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>إضافة خبر جديد</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-right text-xs">
            <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                <tr>
                    <th class="p-4 font-bold">العنوان</th>
                    <th class="p-4 font-bold">النوع</th>
                    <th class="p-4 font-bold">التصنيف</th>
                    <th class="p-4 font-bold">المشاهدات</th>
                    <th class="p-4 font-bold">التاريخ</th>
                    <th class="p-4 font-bold text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($articles as $art)
                <tr class="hover:bg-slate-50 transition">
                    <td class="p-4 font-bold text-slate-800 max-w-xs truncate">{{ $art->title }}</td>
                    <td class="p-4"><span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800">{{ $art->type }}</span></td>
                    <td class="p-4 text-slate-500">{{ $art->category->name ?? '-' }}</td>
                    <td class="p-4 text-slate-500">{{ $art->views_count }}</td>
                    <td class="p-4 text-slate-400">{{ $art->created_at ? $art->created_at->format('Y-m-d') : '' }}</td>
                    <td class="p-4 text-center">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.articles.edit', $art->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="تعديل"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('admin.articles.destroy', $art->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg" title="حذف"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-6 text-center text-slate-400">لا توجد مقالات مضافة.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $articles->links() }}</div>
</div>
@endsection

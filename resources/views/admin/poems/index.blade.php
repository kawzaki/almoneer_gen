@extends('layouts.admin')

@section('title', 'إدارة ديوان الشعر')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">إدارة ديوان الشعر والقصائد</h2>
            <p class="text-xs text-slate-500 mt-1">إضافة وتحرير القصائد الولائية والوجدانية المنظومة بقلم سماحة السيد.</p>
        </div>
        <a href="{{ route('admin.poems.create') }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>إضافة قصيدة جديدة</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-right text-xs">
            <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                <tr>
                    <th class="p-4 font-bold">عنوان القصيدة</th>
                    <th class="p-4 font-bold">المناسبة</th>
                    <th class="p-4 font-bold">بحر الشعر</th>
                    <th class="p-4 font-bold">عدد الأبيات</th>
                    <th class="p-4 font-bold text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($poems as $p)
                <tr class="hover:bg-slate-50 transition">
                    <td class="p-4 font-bold text-slate-800">{{ $p->title }}</td>
                    <td class="p-4 text-slate-500">{{ $p->occasion ?? '-' }}</td>
                    <td class="p-4 text-slate-500">{{ $p->meter ?? '-' }}</td>
                    <td class="p-4 text-slate-500">{{ count($p->couplets) }}</td>
                    <td class="p-4 text-center">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.poems.edit', $p->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('admin.poems.destroy', $p->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-6 text-center text-slate-400">لا توجد قصائد في الديوان حالياً.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $poems->links() }}</div>
</div>
@endsection

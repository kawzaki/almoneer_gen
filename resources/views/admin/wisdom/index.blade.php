@extends('layouts.admin')

@section('title', 'إدارة كلمة الأسبوع والحكم')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">إدارة كلمة الأسبوع وقبسات الحكم</h2>
            <p class="text-xs text-slate-500 mt-1">تعديل الاقتباس الأسبوعي المعروض في الهيدر والصفحة الرئيسية.</p>
        </div>
        <a href="{{ route('admin.wisdom.create') }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>إضافة كلمة أسبوع جديدة</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-right text-xs">
            <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                <tr>
                    <th class="p-4 font-bold">العنوان</th>
                    <th class="p-4 font-bold">نص الحكمة / الاقتباس</th>
                    <th class="p-4 font-bold">المصدر</th>
                    <th class="p-4 font-bold">الحالة</th>
                    <th class="p-4 font-bold text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($wisdoms as $w)
                <tr class="hover:bg-slate-50 transition">
                    <td class="p-4 font-bold text-slate-800">{{ $w->title ?? 'كلمة الأسبوع' }}</td>
                    <td class="p-4 font-semibold text-slate-700 max-w-sm truncate">"{{ $w->quote }}"</td>
                    <td class="p-4 text-slate-500">{{ $w->source }}</td>
                    <td class="p-4">
                        @if($w->is_active)
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold">معروضة حالياً</span>
                        @else
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500">أرشيف</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.wisdom.edit', $w->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('admin.wisdom.destroy', $w->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-6 text-center text-slate-400">لا توجد حكم مسجلة.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

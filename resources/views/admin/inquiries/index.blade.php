@extends('layouts.admin')

@section('title', 'إدارة الاستفسارات والفتاوى')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">إدارة الاستفسارات والفتاوى</h2>
            <p class="text-xs text-slate-500 mt-1">فرز الأسئلة الواردة، تسجيل الإجابة المعتمدة، وتحديد النشر في بنك الفتاوى العام.</p>
        </div>
        
        <!-- Filter status -->
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.inquiries.index') }}" class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $status === 'all' ? 'bg-emerald-800 text-gold-300' : 'bg-white border border-slate-200 text-slate-600' }}">الكل</a>
            <a href="{{ route('admin.inquiries.index', ['status' => 'new']) }}" class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $status === 'new' ? 'bg-amber-600 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">جديد</a>
            <a href="{{ route('admin.inquiries.index', ['status' => 'answered']) }}" class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $status === 'answered' ? 'bg-emerald-800 text-gold-300' : 'bg-white border border-slate-200 text-slate-600' }}">تمت الإجابة</a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-right text-xs">
            <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                <tr>
                    <th class="p-4 font-bold">رقم التتبع</th>
                    <th class="p-4 font-bold">السائل</th>
                    <th class="p-4 font-bold">السؤال</th>
                    <th class="p-4 font-bold">الحالة</th>
                    <th class="p-4 font-bold">النشر العام</th>
                    <th class="p-4 font-bold text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($inquiries as $inq)
                <tr class="hover:bg-slate-50 transition">
                    <td class="p-4 font-mono font-bold text-slate-700">{{ $inq->tracking_code }}</td>
                    <td class="p-4">{{ $inq->name }} <span class="text-slate-400">({{ $inq->country ?? '-' }})</span></td>
                    <td class="p-4 font-semibold text-slate-800 max-w-xs truncate">{{ $inq->question }}</td>
                    <td class="p-4">
                        <span class="px-2 py-0.5 rounded font-bold {{ $inq->status === 'answered' ? 'bg-emerald-100 text-emerald-800' : ($inq->status === 'new' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ $inq->status === 'answered' ? 'تم الرد' : ($inq->status === 'new' ? 'جديد' : 'قيد المراجعة') }}
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="px-2 py-0.5 rounded {{ $inq->is_published ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ $inq->is_published ? 'منشور للعامة' : 'خاص بالسائل' }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.inquiries.edit', $inq->id) }}" class="px-3 py-1 rounded-lg bg-emerald-800 text-gold-300 font-bold hover:bg-emerald-900">
                                <i class="fa-solid fa-pen-to-square"></i> مراجعة ورد
                            </a>
                            <form action="{{ route('admin.inquiries.destroy', $inq->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-6 text-center text-slate-400">لا توجد استفسارات مطابقة.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $inquiries->links() }}</div>
</div>
@endsection

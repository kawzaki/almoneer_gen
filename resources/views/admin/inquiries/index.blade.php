@extends('layouts.admin')

@section('title', 'إدارة الاستفسارات والفتاوى')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800">إدارة الاستفسارات والفتاوى</h2>
            <p class="text-xs text-slate-500 mt-0.5 sm:mt-1">فرز الأسئلة الواردة، تسجيل الإجابة المعتمدة، وتحديد النشر في بنك الفتاوى العام.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.inquiries.create') }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>إضافة استفسار وجواب جديد</span>
            </a>
        </div>
    </div>

    <!-- Toolbar: Search and Status Filters -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-white p-3.5 rounded-2xl border border-slate-200 shadow-2xs">
        <!-- Search input -->
        <form action="{{ route('admin.inquiries.index') }}" method="GET" class="flex items-center gap-2 flex-grow max-w-md">
            @if(!empty($status) && $status !== 'all')
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <div class="relative flex-grow">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="بحث برقم التتبع (INQ-...) أو السؤال أو السائل..." class="w-full text-xs rounded-xl border-slate-200 pr-9 pl-3 py-2 bg-slate-50 focus:bg-white focus:border-emerald-800 transition">
                <span class="absolute right-3 top-2.5 text-slate-400 text-xs pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
            </div>
            <button type="submit" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition">
                بحث
            </button>
            @if(!empty($search))
                <a href="{{ route('admin.inquiries.index', ['status' => $status]) }}" class="px-2 py-2 text-slate-400 hover:text-red-600 text-xs transition" title="إلغاء البحث">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>

        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto text-xs font-semibold">
            <a href="{{ route('admin.inquiries.index', array_filter(['status' => 'all', 'search' => $search ?? null])) }}" class="px-3 py-1.5 rounded-xl transition {{ ($status === 'all' || empty($status)) ? 'bg-emerald-800 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">الكل</a>
            <a href="{{ route('admin.inquiries.index', array_filter(['status' => 'new', 'search' => $search ?? null])) }}" class="px-3 py-1.5 rounded-xl transition {{ $status === 'new' ? 'bg-amber-600 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">جديد</a>
            <a href="{{ route('admin.inquiries.index', array_filter(['status' => 'answered', 'search' => $search ?? null])) }}" class="px-3 py-1.5 rounded-xl transition {{ $status === 'answered' ? 'bg-emerald-800 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">تمت الإجابة</a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200 whitespace-nowrap">
                    <tr>
                        <th class="p-3.5 sm:p-4 font-bold">رقم التتبع</th>
                        <th class="p-3.5 sm:p-4 font-bold">السائل</th>
                        <th class="p-3.5 sm:p-4 font-bold">السؤال</th>
                        <th class="p-3.5 sm:p-4 font-bold">الحالة ومصدر الجواب</th>
                        <th class="p-3.5 sm:p-4 font-bold">النشر العام</th>
                        <th class="p-3.5 sm:p-4 font-bold text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inquiries as $inq)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-3.5 sm:p-4 whitespace-nowrap">
                            <span dir="ltr" class="font-mono font-bold text-slate-800 select-all" style="font-family: monospace, sans-serif !important; font-variant-numeric: tabular-nums lining-nums !important; unicode-bidi: isolate;">{{ $inq->tracking_code }}</span>
                        </td>
                        <td class="p-3.5 sm:p-4 whitespace-nowrap">
                            <span class="font-semibold text-slate-800 block">{{ $inq->name }}</span>
                            <span class="text-[11px] text-slate-400 block">{{ $inq->email }}</span>
                        </td>
                        <td class="p-3.5 sm:p-4 font-semibold text-slate-800 min-w-[220px] max-w-sm">
                            <p class="line-clamp-2 leading-relaxed" title="{{ $inq->question }}">{{ $inq->question }}</p>
                        </td>
                        <td class="p-3.5 sm:p-4 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded font-bold inline-block {{ $inq->status === 'answered' ? 'bg-emerald-100 text-emerald-800' : ($inq->status === 'new' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ $inq->status === 'answered' ? 'تم الرد' : ($inq->status === 'new' ? 'جديد' : 'قيد المراجعة') }}
                            </span>
                            @if($inq->responder_title)
                                <span class="block text-[11px] text-slate-500 font-medium mt-1">
                                    <i class="fa-solid fa-signature text-[10px] text-gold-600"></i> {{ $inq->responder_title }}
                                </span>
                            @endif
                        </td>
                        <td class="p-3.5 sm:p-4 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded {{ $inq->is_published ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                {{ $inq->is_published ? 'منشور للعامة' : 'خاص بالسائل' }}
                            </span>
                        </td>
                        <td class="p-3.5 sm:p-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.inquiries.edit', $inq->id) }}" class="px-3 py-1 rounded-lg bg-emerald-800 text-white font-bold hover:bg-emerald-900 transition flex items-center gap-1">
                                    <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                                    <span>مراجعة ورد</span>
                                </a>
                                <form action="{{ route('admin.inquiries.destroy', $inq->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الاستفسار نهائياً؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="حذف">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">
                            <div class="space-y-1">
                                <i class="fa-solid fa-inbox text-2xl text-slate-300"></i>
                                <p class="text-xs">لا توجد استفسارات مطابقة لبحثكم.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $inquiries->links() }}</div>
</div>
@endsection

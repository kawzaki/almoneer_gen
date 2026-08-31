@extends('layouts.admin')

@section('title', 'الرئيسية والإحصائيات')

@section('content')
<div class="space-y-8">

    <!-- Welcome & Top Alert Banner -->
    <div class="p-6 rounded-3xl bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 text-white shadow-xl flex flex-wrap items-center justify-between gap-6 border border-gold-500/30">
        <div class="space-y-1">
            <span class="text-xs text-gold-300 font-semibold">أهلاً بك في لوحة الإدارة</span>
            <h2 class="text-2xl font-bold font-scholarly text-white">إدارة شبكة سماحة العلامة السيد منير الخباز</h2>
            <p class="text-xs text-slate-300">منظومة إدارة المحتوى للموقع العام والفكري — نظام الكاش التلقائي مفعل ونشط.</p>
        </div>

        <div class="flex items-center gap-3">
            <form action="{{ route('admin.cache.flush') }}" method="POST">
                @csrf
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-gold-500 hover:bg-gold-400 text-emerald-950 font-bold text-xs shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-bolt"></i>
                    <span>تفريغ الكاش وتحديث الموقع فوراً</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Stat Cards (6 Cards) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400">
                <span>الأخبار والنشاطات</span>
                <i class="fa-solid fa-newspaper text-emerald-800"></i>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['articles_count'] }}</p>
            <a href="{{ route('admin.articles.index') }}" class="text-[11px] text-emerald-800 font-semibold hover:underline block">إدارة الأخبار ←</a>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400">
                <span>المكتبة الإعلامية</span>
                <i class="fa-solid fa-photo-film text-emerald-800"></i>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['media_count'] }}</p>
            <a href="{{ route('admin.media.index') }}" class="text-[11px] text-emerald-800 font-semibold hover:underline block">إدارة الميديا ←</a>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400">
                <span>الكتب والمؤلفات</span>
                <i class="fa-solid fa-book-bookmark text-emerald-800"></i>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['books_count'] }}</p>
            <a href="{{ route('admin.books.index') }}" class="text-[11px] text-emerald-800 font-semibold hover:underline block">إدارة الكتب ←</a>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400">
                <span>ديوان الشعر</span>
                <i class="fa-solid fa-feather text-emerald-800"></i>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['poems_count'] }}</p>
            <a href="{{ route('admin.poems.index') }}" class="text-[11px] text-emerald-800 font-semibold hover:underline block">إدارة القصائد ←</a>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400">
                <span>استفسارات جديدة</span>
                <i class="fa-solid fa-circle-question text-amber-600"></i>
            </div>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['new_inquiries'] }}</p>
            <a href="{{ route('admin.inquiries.index') }}" class="text-[11px] text-amber-600 font-semibold hover:underline block">معالجة الأسئلة ←</a>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400">
                <span>رسائل التواصل</span>
                <i class="fa-solid fa-envelope text-blue-600"></i>
            </div>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['unread_messages'] }}</p>
            <span class="text-[11px] text-slate-400 block">الوارد</span>
        </div>

    </div>

    <!-- Dual Sections Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left 6 Cols: Recent Inquiries -->
        <div class="lg:col-span-6 bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-circle-question text-amber-500"></i>
                    <span>أحدث الاستفسارات الواردة</span>
                </h3>
                <a href="{{ route('admin.inquiries.index') }}" class="text-xs text-emerald-800 hover:underline">عرض الكل ←</a>
            </div>

            <div class="space-y-3">
                @forelse($recentInquiries as $inq)
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ $inq->question }}</p>
                        <p class="text-[11px] text-slate-400">{{ $inq->name }} ({{ $inq->tracking_code }})</p>
                    </div>
                    <a href="{{ route('admin.inquiries.edit', $inq->id) }}" class="px-3 py-1.5 rounded-lg bg-emerald-800 text-gold-300 text-xs font-semibold flex-shrink-0 hover:bg-emerald-900">
                        إجابة
                    </a>
                </div>
                @empty
                <p class="text-xs text-slate-400 text-center py-6">لا توجد استفسارات جديدة بانتظار الرد.</p>
                @endforelse
            </div>
        </div>

        <!-- Right 6 Cols: Audit Log Stream -->
        <div class="lg:col-span-6 bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-emerald-800"></i>
                    <span>سجل النشاطات والعمليات الرقابية (Audit Log)</span>
                </h3>
                <a href="{{ route('admin.audit.index') }}" class="text-xs text-emerald-800 hover:underline">عرض السجل ←</a>
            </div>

            <div class="space-y-2 text-xs">
                @forelse($recentAudits as $log)
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-slate-600">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $log->action === 'create' ? 'bg-emerald-100 text-emerald-800' : ($log->action === 'update' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                            {{ $log->action }}
                        </span>
                        <span class="font-semibold text-slate-800">{{ $log->module }}</span>
                    </div>
                    <span class="text-[11px] text-slate-400">{{ $log->created_at ? $log->created_at->diffForHumans() : '' }}</span>
                </div>
                @empty
                <p class="text-xs text-slate-400 text-center py-6">لا توجد حركات مسجلة حالياً.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

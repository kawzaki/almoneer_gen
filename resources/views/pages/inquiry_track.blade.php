@extends('layouts.app')

@section('title', 'متابعة حالة الاستفسار | شبكة العلامة المنير')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 py-12 space-y-8">

    <div class="text-center space-y-2">
        <h1 class="text-2xl font-bold font-scholarly text-slate-900">متابعة حالة الاستفسار والمسألة</h1>
        <p class="text-xs text-slate-500">أدخل رقم التتبع الذي تم تزويدك به عند إرسال السؤال.</p>
    </div>

    <!-- Track Form -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
        <form action="{{ route('inquiries.track') }}" method="GET" class="flex gap-2">
            <input type="text" name="code" value="{{ $code }}" required placeholder="مثال: INQ-1447-001" class="flex-grow text-xs rounded-xl border-slate-200 p-3 bg-slate-50 uppercase text-center font-mono">
            <button type="submit" class="px-5 py-3 bg-emerald-800 hover:bg-emerald-900 text-gold-300 font-bold text-xs rounded-xl shadow transition">
                استعلام
            </button>
        </form>
    </div>

    @if(!empty($code))
        @if($inquiry)
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-md space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <span class="text-xs text-slate-400 font-mono">{{ $inquiry->tracking_code }}</span>
                @if($inquiry->status === 'answered')
                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>تمت الإجابة</span>
                </span>
                @else
                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold flex items-center gap-1.5">
                    <i class="fa-solid fa-hourglass-half"></i>
                    <span>قيد المراجعة لدى اللجنة</span>
                </span>
                @endif
            </div>

            <div>
                <h4 class="font-bold text-xs text-slate-400 mb-1">نص السؤال المرسل:</h4>
                <p class="text-sm font-semibold text-slate-800 leading-relaxed">{{ $inquiry->question }}</p>
            </div>

            @if($inquiry->answer)
            <div class="p-5 rounded-2xl bg-sand-50 border-r-4 border-emerald-800 space-y-2">
                <h4 class="font-bold text-xs text-emerald-950">نص الإجابة المعتمدة:</h4>
                <p class="text-sm text-slate-700 leading-relaxed font-light">{!! nl2br(e($inquiry->answer)) !!}</p>
                <p class="text-[10px] text-slate-400 pt-2 flex items-center gap-1">
                    <span>تاريخ الإجابة:</span>
                    @if($inquiry->answered_at)
                        <span class="inline-flex items-center gap-0.5" dir="rtl">
                            <span>{{ $inquiry->answered_at->format('d') }}</span>/<span>{{ $inquiry->answered_at->format('m') }}</span>/<span>{{ $inquiry->answered_at->format('Y') }}</span>
                        </span>
                    @endif
                </p>
            </div>
            @else
            <div class="p-4 rounded-xl bg-slate-50 text-slate-500 text-xs text-center">
                طلبكم قيد العرض والمراجعة، يرجى إعادة التحقق لاحقاً.
            </div>
            @endif
        </div>
        @else
        <div class="p-6 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-center text-xs">
            لم يتم العثور على أي استفسار مسجل برقم التتبع المدخل ({{ $code }}). يرجى التأكد من الرمز.
        </div>
        @endif
    @endif

</div>
@endsection

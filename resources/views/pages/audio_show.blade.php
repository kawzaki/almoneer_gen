@extends('layouts.app')

@section('title', $audio->title . ' | شبكة العلامة المنير')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 space-y-8">

    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-emerald-800">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('audios.index') }}" class="hover:text-emerald-800">المكتبة الصوتية</a>
        <span>/</span>
        <span class="text-emerald-950 font-bold truncate">{{ $audio->title }}</span>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold">
                {{ $audio->season_year ?? 'محاضرة عامة' }}
            </span>
            <div class="flex items-center gap-4 text-xs text-slate-400">
                <span><i class="fa-regular fa-clock"></i> المدة: {{ $audio->duration ?? '40:00' }}</span>
                <span><i class="fa-solid fa-headphones"></i> الاستماعات: {{ $audio->views_count }}</span>
            </div>
        </div>

        <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900 leading-tight">
            {{ $audio->title }}
        </h1>

        @if($audio->description)
        <p class="text-sm text-slate-600 leading-relaxed font-light">
            {{ $audio->description }}
        </p>
        @endif

        <!-- Direct Action Bar -->
        <div class="p-6 rounded-2xl bg-emerald-950 text-white flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <button onclick="playGlobalAudio('{{ $audio->media_url }}', '{{ addslashes($audio->title) }}')" class="w-12 h-12 rounded-full bg-gold-500 hover:bg-gold-400 text-emerald-950 flex items-center justify-center text-xl shadow-lg transition">
                    <i class="fa-solid fa-play"></i>
                </button>
                <div>
                    <h4 class="font-bold text-sm text-gold-300">الاستماع المباشر في المشغل المستمر</h4>
                    <p class="text-[11px] text-slate-300">يمكنك مواصلة الاستماع أثناء تصفح بقية صفحات الموقع</p>
                </div>
            </div>

            <a href="{{ $audio->media_url }}" download class="px-4 py-2 bg-emerald-900 hover:bg-emerald-800 text-gold-300 border border-gold-500/30 rounded-xl text-xs font-bold transition flex items-center gap-2">
                <i class="fa-solid fa-download"></i>
                <span>تنزيل الملف (MP3)</span>
            </a>
        </div>

        @if($audio->transcript)
        <!-- Transcript -->
        <div class="pt-6 border-t border-slate-100 space-y-4">
            <h3 class="font-bold text-base text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-align-right text-emerald-800"></i>
                <span>مكتوب / تفريغ المحاضرة</span>
            </h3>
            <div class="prose prose-slate max-w-none text-sm leading-relaxed text-slate-700">
                {!! nl2br(e($audio->transcript)) !!}
            </div>
        </div>
        @endif
    </div>

</div>
@endsection

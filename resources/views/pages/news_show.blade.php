@extends('layouts.app')

@section('title', $article->title . ' | شبكة العلامة المنير')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 space-y-8">

    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-emerald-800">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('news.index') }}" class="hover:text-emerald-800">الأخبار والنشاطات</a>
        <span>/</span>
        <span class="text-emerald-950 font-bold truncate">{{ $article->title }}</span>
    </div>

    <article class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-sm space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-slate-100 text-xs text-slate-400">
            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 font-bold">
                {{ $article->type === 'activity' ? 'نشاط وتبليغ' : 'خبر رسمي' }}
            </span>
            <div class="flex items-center gap-4">
                <span><i class="fa-regular fa-calendar"></i> {{ $article->published_at ? $article->published_at->format('Y-m-d') : '' }}</span>
                <span><i class="fa-solid fa-eye"></i> {{ $article->views_count }} قراءة</span>
            </div>
        </div>

        <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900 leading-tight">
            {{ $article->title }}
        </h1>

        @if($article->summary)
        <div class="p-4 rounded-2xl bg-sand-50 border-r-4 border-gold-500 text-slate-700 text-sm leading-relaxed font-light">
            {{ $article->summary }}
        </div>
        @endif

        <div class="prose prose-slate max-w-none text-sm sm:text-base leading-relaxed text-slate-800 space-y-4 pt-4">
            {!! $article->content !!}
        </div>

        <div class="pt-6 border-t border-slate-100 flex items-center justify-between text-xs">
            <a href="{{ route('news.index') }}" class="text-emerald-800 font-bold hover:underline">← العودة لجميع الأخبار</a>
            <button onclick="navigator.clipboard.writeText(window.location.href); alert('تم نسخ رابط الخبر!')" class="text-slate-500 hover:text-emerald-800 flex items-center gap-1.5">
                <i class="fa-solid fa-share-nodes"></i>
                <span>مشاركة الرابط</span>
            </button>
        </div>
    </article>

</div>
@endsection

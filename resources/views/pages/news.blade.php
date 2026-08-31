@extends('layouts.app')

@section('title', 'أخبار ونشاطات سماحة العلامة السيد منير الخباز')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="flex flex-wrap items-end justify-between gap-4 pb-6 border-b border-slate-200 mb-8">
        <div>
            <span class="text-xs font-bold text-gold-600 uppercase tracking-wider">المركز الإخباري</span>
            <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900 mt-1">أخبار ونشاطات وجولات سماحة السيد</h1>
        </div>
    </div>

    <!-- News Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse($articles as $article)
        <article class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
            <div class="p-6 space-y-3">
                <span class="text-[11px] font-bold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                    {{ $article->type === 'activity' ? 'نشاط وتبليغ' : 'بيان رسمي' }}
                </span>
                <h3 class="font-bold text-lg text-slate-800 leading-snug hover:text-emerald-800 transition">
                    <a href="{{ route('news.show', $article->slug) }}">{{ $article->title }}</a>
                </h3>
                <p class="text-xs text-slate-500 font-light leading-relaxed line-clamp-3">
                    {{ $article->summary }}
                </p>
            </div>
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <span><i class="fa-regular fa-calendar"></i> {{ $article->published_at ? $article->published_at->format('Y-m-d') : '' }}</span>
                <a href="{{ route('news.show', $article->slug) }}" class="font-bold text-emerald-800 hover:text-emerald-950">قراءة المزيد ←</a>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-slate-200 text-slate-500">
            لا توجد أخبار منشورة حالياً.
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $articles->links() }}
    </div>

</div>
@endsection

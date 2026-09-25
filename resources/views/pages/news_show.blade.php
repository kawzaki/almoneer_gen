@extends('layouts.app')

@section('title', $article->title . ' | شبكة العلامة المنير')
@section('description', $article->summary ?? Str::limit(strip_tags($article->content), 160))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 sm:py-12 space-y-8">

    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-emerald-800 transition">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('news.index') }}" class="hover:text-emerald-800 transition">الأخبار والنشاطات</a>
        <span>/</span>
        <span class="text-emerald-950 font-bold truncate">{{ $article->title }}</span>
    </div>

    <!-- Main Article Card -->
    <article class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm space-y-6">
        
        <!-- Meta Bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-slate-100 text-xs text-slate-500">
            <span class="px-3.5 py-1 rounded-full bg-emerald-50 text-emerald-800 font-bold border border-emerald-200">
                {{ $article->type_name }}
            </span>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5">
                    <i class="fa-regular fa-calendar text-emerald-700"></i>
                    @if($article->published_at)
                        <span class="inline-flex items-center gap-0.5" dir="rtl">
                            <span>{{ $article->published_at->format('d') }}</span>/<span>{{ $article->published_at->format('m') }}</span>/<span>{{ $article->published_at->format('Y') }}</span>
                        </span>
                    @endif
                </span>
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-eye text-emerald-700"></i>
                    <span>{{ number_format($article->views_count) }} قراءة</span>
                </span>
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold font-scholarly text-slate-900 leading-tight">
            {{ $article->title }}
        </h1>

        <!-- Featured Image -->
        @if($article->image)
        <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm max-h-[440px] bg-slate-100">
            <img src="{{ str_starts_with($article->image, 'http') ? $article->image : asset($article->image) }}" 
                 alt="{{ $article->title }}" 
                 class="w-full h-full object-cover">
        </div>
        @endif

        <!-- Summary Quote -->
        @if($article->summary)
        <div class="p-5 rounded-2xl bg-sand-50 border-r-4 border-gold-500 text-slate-700 text-sm sm:text-base leading-relaxed font-light shadow-xs">
            {{ strip_tags($article->summary) }}
        </div>
        @endif

        <!-- Full Rich HTML Content (WYSIWYG generated) -->
        <div class="prose prose-slate max-w-none text-sm sm:text-base leading-loose text-slate-800 space-y-4 pt-2 [&>p]:leading-relaxed [&>ul]:list-disc [&>ul]:pr-6 [&>ol]:list-decimal [&>ol]:pr-6 [&>blockquote]:border-r-4 [&>blockquote]:border-gold-500 [&>blockquote]:pr-4 [&>blockquote]:italic [&>img]:rounded-2xl [&>img]:my-4">
            {!! $article->content !!}
        </div>

        <!-- Article Tags Section -->
        @if(!empty($article->tags_list))
        <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center gap-2">
            <span class="text-xs font-bold text-slate-600 flex items-center gap-1.5 ml-2">
                <i class="fa-solid fa-tags text-emerald-800"></i>
                <span>الكلمات الدلالية:</span>
            </span>
            @foreach($article->tags_list as $tag)
            <a href="{{ route('news.index', ['tag' => $tag]) }}" 
               class="px-3 py-1 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-900 border border-emerald-200 text-xs font-medium transition flex items-center gap-1 shadow-2xs"
               title="عرض جميع الأخبار الموسومة بـ #{{ $tag }}">
                <i class="fa-solid fa-tag text-[9px] text-emerald-700"></i>
                <span>#{{ $tag }}</span>
            </a>
            @endforeach
        </div>
        @endif

        <!-- Bottom Actions & Share -->
        <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4 text-xs">
            <a href="{{ route('news.index') }}" class="text-emerald-800 font-bold hover:text-emerald-950 flex items-center gap-1.5 transition">
                <span>← العودة لجميع الأخبار والنشاطات</span>
            </a>
            <div class="flex items-center gap-3">
                <button onclick="navigator.clipboard.writeText(window.location.href); alert('تم نسخ رابط الخبر بنجاح!')" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-link"></i>
                    <span>نسخ الرابط</span>
                </button>
            </div>
        </div>
    </article>

    <!-- Recent News Section -->
    @if(isset($recentNews) && $recentNews->count() > 0)
    <div class="space-y-4 pt-4">
        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-newspaper text-gold-500"></i>
            <span>أخبار ونشاطات أخرى:</span>
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($recentNews as $recent)
            <a href="{{ route('news.show', $recent->slug) }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-700 hover:shadow-sm transition flex gap-3 group">
                @if($recent->image)
                <img src="{{ str_starts_with($recent->image, 'http') ? $recent->image : asset($recent->image) }}" alt="{{ $recent->title }}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                @endif
                <div class="space-y-1 overflow-hidden">
                    <h4 class="font-bold text-xs sm:text-sm text-slate-800 group-hover:text-emerald-800 transition truncate">{{ $recent->title }}</h4>
                    @if($recent->published_at)
                        <p class="text-[11px] text-slate-400">
                            <span class="inline-flex items-center gap-0.5" dir="rtl">
                                <span>{{ $recent->published_at->format('d') }}</span>/<span>{{ $recent->published_at->format('m') }}</span>/<span>{{ $recent->published_at->format('Y') }}</span>
                            </span>
                        </p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection

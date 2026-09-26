@extends('layouts.app')

@section('title', !empty($selectedTag) ? 'أخبار موسومة بـ #' . $selectedTag . ' | شبكة العلامة المنير' : 'أخبار ونشاطات وجولات سماحة السيد منير الخباز')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12 space-y-8">
    
    <!-- Header -->
    <div class="flex flex-wrap items-end justify-between gap-4 pb-6 border-b border-slate-200">
        <div>
            <span class="text-xs font-bold text-gold-600 uppercase tracking-wider flex items-center gap-1.5">
                <i class="fa-solid fa-newspaper"></i>
                <span>المركز الإعلامي والإخباري</span>
            </span>
            <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900 mt-1">
                أخبار ونشاطات وجولات سماحة السيد
            </h1>
        </div>
        <p class="text-xs text-slate-500 font-light max-w-md">
            تغطية حصرية لكافة البيانات الرسمية، والجولات التبليغية، والمؤتمرات الفكرية لسماحة العلامة السيد منير الخباز.
        </p>
    </div>

    <!-- Categories Filter Bar -->
    @if(isset($categories) && $categories->count() > 0)
    <div class="flex flex-wrap items-center gap-2 p-3 bg-white rounded-2xl border border-slate-200 shadow-2xs">
        <span class="text-xs font-bold text-slate-700 ml-2 flex items-center gap-1.5">
            <i class="fa-solid fa-folder-tree text-gold-600"></i>
            <span>التصنيفات:</span>
        </span>
        <a href="{{ route('news.index', array_filter(['tag' => $selectedTag])) }}" 
           class="px-3.5 py-1.5 rounded-full text-xs font-bold transition flex items-center gap-1.5 {{ empty($activeCategory) ? 'bg-emerald-800 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            <span>الكل</span>
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('news.index', array_filter(['category' => $cat->slug, 'tag' => $selectedTag])) }}" 
           class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition flex items-center gap-1.5 {{ isset($activeCategory) && $activeCategory->id === $cat->id ? 'bg-emerald-800 text-white font-bold shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 border border-transparent hover:border-emerald-200' }}">
            <span>{{ $cat->name }}</span>
        </a>
        @endforeach
    </div>
    @endif

    <!-- Active Filter Indicators -->
    @if(!empty($selectedTag) || !empty($activeCategory))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex flex-wrap items-center justify-between gap-3 shadow-xs">
        <div class="flex items-center gap-3">
            <span class="w-10 h-10 rounded-xl bg-emerald-800 text-white flex items-center justify-center text-base shadow-sm">
                <i class="fa-solid fa-filter"></i>
            </span>
            <div>
                <p class="text-xs text-emerald-700">تصفية الأخبار النشطة:</p>
                <div class="flex flex-wrap items-center gap-2 mt-0.5">
                    @if(!empty($activeCategory))
                    <span class="text-xs font-bold text-emerald-950 bg-white px-2.5 py-0.5 rounded-lg border border-emerald-300">
                        التصنيف: {{ $activeCategory->name }}
                    </span>
                    @endif
                    @if(!empty($selectedTag))
                    <span class="text-xs font-bold text-emerald-950 bg-white px-2.5 py-0.5 rounded-lg border border-emerald-300">
                        الوسم: #{{ $selectedTag }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <a href="{{ route('news.index') }}" class="px-4 py-2 rounded-xl bg-white text-emerald-800 hover:bg-emerald-100 border border-emerald-300 text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
            <i class="fa-solid fa-xmark"></i>
            <span>إلغاء الفلترة وعرض كافة الأخبار</span>
        </a>
    </div>
    @endif

    <!-- Tags Cloud Bar -->
    @if(!empty($tagsCloud))
    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-3">
        <div class="flex items-center justify-between text-xs text-slate-500 font-semibold">
            <span class="flex items-center gap-2 text-slate-800 font-bold">
                <i class="fa-solid fa-tags text-gold-500"></i>
                <span>استكشف الأخبار بحسب المواضيع والوسوم:</span>
            </span>
            <span class="text-[11px] text-slate-400">{{ count($tagsCloud) }} وسم</span>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('news.index') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ empty($selectedTag) ? 'bg-emerald-800 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                كافة الأخبار
            </a>
            @foreach($tagsCloud as $tag => $count)
            <a href="{{ route('news.index', ['tag' => $tag]) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-medium transition flex items-center gap-1.5 {{ $selectedTag === $tag ? 'bg-emerald-800 text-white font-bold shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 border border-transparent hover:border-emerald-200' }}">
                <span>#{{ $tag }}</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $selectedTag === $tag ? 'bg-emerald-950 text-gold-300' : 'bg-slate-200 text-slate-600' }}">{{ $count }}</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- News Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($articles as $article)
        <article class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
            <div>
                <!-- Featured Image (if available) -->
                @if($article->image)
                <div class="h-48 overflow-hidden relative bg-slate-100">
                    <img src="{{ str_starts_with($article->image, 'http') ? $article->image : asset($article->image) }}" 
                         alt="{{ $article->title }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-3 right-3 text-[11px] font-bold text-white bg-emerald-950/80 backdrop-blur-sm px-3 py-1 rounded-full border border-white/20">
                        {{ $article->category->name ?? $article->type_name }}
                    </span>
                </div>
                @endif

                <div class="p-6 space-y-3">
                    @if(!$article->image)
                    <span class="inline-block text-[11px] font-bold text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                        {{ $article->category->name ?? $article->type_name }}
                    </span>
                    @endif

                    <h3 class="font-bold text-lg text-slate-900 leading-snug group-hover:text-emerald-800 transition">
                        <a href="{{ route('news.show', $article->slug) }}">{{ $article->title }}</a>
                    </h3>

                    <p class="text-xs text-slate-500 font-light leading-relaxed line-clamp-3">
                        {{ strip_tags($article->summary) }}
                    </p>

                    <!-- Article Tag Badges -->
                    @if(!empty($article->tags_list))
                    <div class="flex flex-wrap items-center gap-1.5 pt-2">
                        @foreach(array_slice($article->tags_list, 0, 4) as $t)
                        <a href="{{ route('news.index', ['tag' => $t]) }}" class="px-2 py-0.5 rounded-md bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-800 border border-slate-200 hover:border-emerald-200 text-[11px] transition">
                            #{{ $t }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <span class="flex items-center gap-1.5">
                    <i class="fa-regular fa-calendar"></i>
                    @if($article->published_at)
                        <span class="inline-flex items-center gap-0.5" dir="rtl">
                            <span>{{ $article->published_at->format('d') }}</span>/<span>{{ $article->published_at->format('m') }}</span>/<span>{{ $article->published_at->format('Y') }}</span>
                        </span>
                    @endif
                </span>
                <a href="{{ route('news.show', $article->slug) }}" class="font-bold text-emerald-800 hover:text-emerald-950 flex items-center gap-1">
                    <span>قراءة الخبر</span>
                    <span>←</span>
                </a>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-slate-200 p-8 space-y-3">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto">
                <i class="fa-regular fa-newspaper"></i>
            </div>
            <h3 class="font-bold text-base text-slate-700">لا توجد أخبار تطابق البحث أو الوسم المحدد حالياً.</h3>
            @if(!empty($selectedTag))
            <p class="text-xs text-slate-400">يمكنك إلغاء الفلترة لعرض كافة الأخبار والنشاطات المنشورة.</p>
            <a href="{{ route('news.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-800 text-white rounded-xl text-xs font-bold hover:bg-emerald-900 transition mt-2">
                <span>عرض كافة الأخبار</span>
            </a>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $articles->links() }}
    </div>

</div>
@endsection

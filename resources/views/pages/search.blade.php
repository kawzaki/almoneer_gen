@extends('layouts.app')

@section('title', 'نتائج البحث' . ($term ? ': ' . $term : '') . ' | ' . ($siteSettings['site.title'] ?? 'سماحة السيد منير الخباز'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-b from-[#06373f] to-[#04242a] text-white py-10 px-4 sm:px-6 lg:px-8 border-b border-gold-500/20 relative">
    <div class="max-w-7xl mx-auto">
        <div class="max-w-3xl mx-auto text-center space-y-4">
            <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-gold-300">
                <i class="fa-solid fa-magnifying-glass ml-2 text-gold-400"></i>
                <span>البحث في الموقع</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-300">
                ابحث في مكتبة المحاضرات، المرئيات، الكتب والمؤلفات، المقالات الفكرية، وديوان الشعر.
            </p>

            <!-- Search Form -->
            <form action="{{ route('search') }}" method="GET" class="relative max-w-xl mx-auto pt-2">
                <input type="text" 
                       name="q" 
                       value="{{ $term }}" 
                       placeholder="اكتب كلمة البحث هنا..." 
                       class="w-full pl-12 pr-5 py-3 text-sm rounded-2xl bg-[#031d22] border-2 border-gold-500/50 text-white placeholder-slate-400 focus:outline-none focus:border-gold-400 shadow-xl transition"
                       autofocus>
                <button type="submit" class="absolute left-3 top-5 text-gold-400 hover:text-gold-200 text-lg transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if(empty($term))
        <div class="text-center py-16 text-slate-500">
            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4 text-2xl text-slate-400">
                <i class="fa-solid fa-keyboard"></i>
            </div>
            <p class="text-base font-semibold">يرجى كتابة كلمة البحث في الحقل أعلاه</p>
            <p class="text-xs text-slate-400 mt-1">يمكنك البحث بالعنوان، الموضوع، أو الكلمات المفتاحية.</p>
        </div>
    @elseif($totalResults === 0)
        <div class="text-center py-16 text-slate-500">
            <div class="w-16 h-16 rounded-full bg-amber-50 flex items-center justify-center mx-auto mb-4 text-2xl text-amber-500">
                <i class="fa-solid fa-circle-question"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700">لم يتم العثور على نتائج لـ «{{ $term }}»</h3>
            <p class="text-xs text-slate-400 mt-2">يرجى التأكد من صحة الكلمات أو تجربة عبارات بحث عامة.</p>
        </div>
    @else
        <!-- Results Header -->
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    نتائج البحث عن: <span class="text-emerald-800">«{{ $term }}»</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">تم العثور على {{ $totalResults }} نتيجة</p>
            </div>
        </div>

        <div class="space-y-12">
            <!-- 1. Books -->
            @if($books->count() > 0)
            <div>
                <h3 class="text-base font-bold font-scholarly text-emerald-950 flex items-center gap-2 mb-4 border-r-4 border-gold-500 pr-2.5">
                    <i class="fa-solid fa-book-open text-gold-600"></i>
                    <span>الكتب والمؤلفات ({{ $books->count() }})</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($books as $book)
                    <a href="{{ route('books.show', $book->slug) }}" class="bg-white rounded-xl border border-slate-200 hover:border-gold-400 p-4 transition shadow-sm hover:shadow-md flex flex-col justify-between group">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full font-bold">كتاب</span>
                                @if($book->publication_year)
                                    <span class="text-[10px] text-slate-400">{{ $book->publication_year }}م</span>
                                @endif
                            </div>
                            <div class="flex gap-3 items-start">
                                @if($book->cover_image)
                                    <img src="{{ str_starts_with($book->cover_image, 'http') ? $book->cover_image : asset($book->cover_image) }}" 
                                         alt="{{ $book->title }}" 
                                         class="w-12 h-16 rounded-md object-cover flex-shrink-0 border border-slate-200 shadow-xs">
                                @endif
                                <div class="min-w-0 flex-grow">
                                    <h4 class="font-scholarly font-bold text-slate-800 group-hover:text-emerald-800 text-sm leading-snug line-clamp-2">{{ $book->title }}</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $book->author }}</p>
                                </div>
                            </div>
                            @if($book->summary)
                            <p class="text-xs text-slate-500 line-clamp-2 font-light leading-relaxed">{{ strip_tags($book->summary) }}</p>
                            @endif
                        </div>
                        <div class="pt-3 border-t border-slate-100 mt-3 text-[11px] text-gold-600 font-semibold flex items-center justify-between">
                            <span>عرض وتنزيل الكتاب</span>
                            <i class="fa-solid fa-arrow-left text-[9px] group-hover:-translate-x-1 transition-transform"></i>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- 2. Audios -->
            @if($audios->count() > 0)
            <div>
                <h3 class="text-base font-bold font-scholarly text-emerald-950 flex items-center gap-2 mb-4 border-r-4 border-gold-500 pr-2.5">
                    <i class="fa-solid fa-microphone text-gold-600"></i>
                    <span>المحاضرات الصوتية ({{ $audios->count() }})</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($audios as $audio)
                    <div class="bg-white rounded-xl border border-slate-200 p-4 transition shadow-sm hover:shadow-md flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-[11px] text-slate-400">
                                <span><i class="fa-regular fa-clock ml-1"></i>{{ $audio->duration ?? '45:00' }}</span>
                                <span class="text-gold-600 font-bold bg-gold-50 px-2 py-0.5 rounded-full text-[10px]">صوتي</span>
                            </div>
                            <h4 class="font-scholarly font-bold text-slate-800 text-sm leading-snug">{{ $audio->title }}</h4>
                            @if($audio->description)
                            <p class="text-xs text-slate-500 line-clamp-2">{{ $audio->description }}</p>
                            @endif
                        </div>
                        <div class="pt-3 border-t border-slate-100 mt-3 flex items-center justify-between">
                            <button onclick="playGlobalAudio('{{ $audio->media_url }}', '{{ addslashes($audio->title) }}')" class="text-xs text-emerald-800 font-bold hover:text-gold-600 flex items-center gap-1.5 transition">
                                <i class="fa-solid fa-circle-play text-base text-gold-500"></i>
                                <span>استماع الآن</span>
                            </button>
                            <a href="{{ route('audios.show', $audio->slug) }}" class="text-xs text-slate-400 hover:text-slate-700">التفاصيل</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- 3. Videos -->
            @if($videos->count() > 0)
            <div>
                <h3 class="text-base font-bold font-scholarly text-emerald-950 flex items-center gap-2 mb-4 border-r-4 border-gold-500 pr-2.5">
                    <i class="fa-solid fa-video text-gold-600"></i>
                    <span>المحاضرات والمرئيات ({{ $videos->count() }})</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($videos as $video)
                    <a href="{{ route('videos.show', $video->slug) }}" class="bg-white rounded-xl border border-slate-200 hover:border-gold-400 p-4 transition shadow-sm hover:shadow-md flex flex-col justify-between group">
                        <div class="space-y-2">
                            <span class="text-[10px] text-red-700 bg-red-50 px-2 py-0.5 rounded-full font-bold">مرئي</span>
                            <h4 class="font-scholarly font-bold text-slate-800 group-hover:text-emerald-800 text-sm leading-snug line-clamp-2">{{ $video->title }}</h4>
                        </div>
                        <div class="pt-3 border-t border-slate-100 mt-3 text-[11px] text-gold-600 font-semibold flex items-center justify-between">
                            <span>مشاهدة المقطع</span>
                            <i class="fa-solid fa-arrow-left text-[9px] group-hover:-translate-x-1 transition-transform"></i>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- 4. News & Activities -->
            @if($news->count() > 0)
            <div>
                <h3 class="text-base font-bold font-scholarly text-emerald-950 flex items-center gap-2 mb-4 border-r-4 border-gold-500 pr-2.5">
                    <i class="fa-solid fa-newspaper text-gold-600"></i>
                    <span>الأخبار والنشاطات ({{ $news->count() }})</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($news as $item)
                    <a href="{{ route('news.show', $item->slug) }}" class="bg-white rounded-xl border border-slate-200 hover:border-gold-400 p-4 transition shadow-sm hover:shadow-md flex flex-col justify-between group">
                        <div class="space-y-2">
                            <span class="text-[10px] text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full font-bold">خبر</span>
                            <h4 class="font-scholarly font-bold text-slate-800 group-hover:text-emerald-800 text-sm leading-snug line-clamp-2">{{ $item->title }}</h4>
                            @if($item->summary)
                            <p class="text-xs text-slate-500 line-clamp-2">{{ strip_tags($item->summary) }}</p>
                            @endif
                        </div>
                        <div class="pt-3 border-t border-slate-100 mt-3 text-[11px] text-gold-600 font-semibold flex items-center justify-between">
                            <span>قراءة الخبر</span>
                            <i class="fa-solid fa-arrow-left text-[9px] group-hover:-translate-x-1 transition-transform"></i>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- 5. Poems -->
            @if($poems->count() > 0)
            <div>
                <h3 class="text-base font-bold font-scholarly text-emerald-950 flex items-center gap-2 mb-4 border-r-4 border-gold-500 pr-2.5">
                    <i class="fa-solid fa-feather text-gold-600"></i>
                    <span>ديوان الشعر ({{ $poems->count() }})</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($poems as $poem)
                    <a href="{{ route('poems.show', $poem->slug) }}" class="bg-white rounded-xl border border-slate-200 hover:border-gold-400 p-4 transition shadow-sm hover:shadow-md flex flex-col justify-between group">
                        <div class="space-y-2">
                            <span class="text-[10px] text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full font-bold">شعر</span>
                            <h4 class="font-scholarly font-bold text-slate-800 group-hover:text-emerald-800 text-sm leading-snug line-clamp-2">{{ $poem->title }}</h4>
                        </div>
                        <div class="pt-3 border-t border-slate-100 mt-3 text-[11px] text-gold-600 font-semibold flex items-center justify-between">
                            <span>قراءة القصيدة</span>
                            <i class="fa-solid fa-arrow-left text-[9px] group-hover:-translate-x-1 transition-transform"></i>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    @endif
</div>
@endsection

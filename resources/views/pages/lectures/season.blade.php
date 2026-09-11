@extends('layouts.app')

@section('title', 'موسم ' . $seasonInfo['name'] . ' ' . $year . ' هـ | شبكة العلامة المنير')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-emerald-800 transition">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('lectures.index') }}" class="hover:text-emerald-800 transition">المحاضرات</a>
        <span>/</span>
        <a href="{{ route('lectures.year', ['year' => $year]) }}" class="hover:text-emerald-800 transition">{{ $year }} هـ</a>
        <span>/</span>
        <span class="text-emerald-950 font-bold">موسم {{ $seasonInfo['short'] ?? $season }}</span>
    </nav>

    <!-- Season Header -->
    <div class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-slate-900 text-white rounded-3xl p-8 sm:p-10 shadow-xl border border-gold-500/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-3">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full bg-gold-500/20 text-gold-300 text-xs font-bold border border-gold-500/30">
                    {{ $year }} هـ
                </span>
                <span class="px-3 py-1 rounded-full bg-emerald-800 text-slate-200 text-xs">
                    {{ $totalLectures }} مجالس ومحاضرات
                </span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-bold font-scholarly text-gold-300">
                موسم {{ $seasonInfo['name'] ?? $season }} {{ $year }} هـ
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 font-light max-w-2xl leading-relaxed">
                سلسلة المحاضرات الفكرية والعقائدية لسماحة العلامة السيد منير الخباز التي ألقيت خلال هذا الموسم المبارك.
            </p>
        </div>

        <!-- Sibling Seasons Pill Switcher -->
        @if($siblingSeasons->count() > 1)
        <div class="bg-emerald-900/80 p-3 rounded-2xl border border-emerald-800 shrink-0 space-y-2">
            <div class="text-[11px] text-gold-300 font-bold px-1">مواسم أخرى في سنة {{ $year }} هـ:</div>
            <div class="flex flex-wrap gap-1.5">
                @foreach($siblingSeasons as $sib)
                <a href="{{ route('lectures.season', ['year' => $year, 'season' => $sib->season_slug]) }}" 
                   class="px-2.5 py-1 rounded-lg text-xs {{ $sib->season_slug == $season ? 'bg-gold-500 text-emerald-950 font-bold' : 'bg-emerald-950/60 text-slate-300 hover:bg-emerald-800' }} transition">
                    {{ $sib->season }}
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Toolbar: Search & Display View Switcher -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm flex flex-wrap items-center justify-between gap-4">
        
        <!-- Search within season -->
        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2 max-w-md w-full">
            @if($selectedTag)
            <input type="hidden" name="tag" value="{{ $selectedTag }}">
            @endif
            <div class="relative flex-1">
                <input type="text" name="q" value="{{ $search }}" placeholder="ابحث في عناوين ومواضيع هذا الموسم..." 
                       class="w-full text-xs rounded-xl bg-slate-50 border border-slate-200 text-slate-900 p-2.5 pr-9 focus:bg-white focus:border-emerald-700 outline-none">
                <i class="fa-solid fa-magnifying-glass absolute right-3 top-3 text-slate-400 text-xs"></i>
            </div>
            @if($search)
            <a href="{{ route('lectures.season', array_filter(['year' => $year, 'season' => $season, 'tag' => $selectedTag, 'view' => $viewMode])) }}" class="text-xs text-red-600 hover:underline px-2">إلغاء</a>
            @endif
        </form>

        <!-- View Switcher -->
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-400">طريقة العرض:</span>
            <div class="inline-flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200">
                <button type="button" onclick="setViewMode('grid')" id="view-btn-grid" 
                        class="p-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $viewMode === 'grid' ? 'bg-white text-emerald-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}" 
                        title="عرض شبكي (بطاقات)">
                    <i class="fa-solid fa-table-cells-large text-sm"></i>
                </button>
                <button type="button" onclick="setViewMode('list')" id="view-btn-list" 
                        class="p-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $viewMode === 'list' ? 'bg-white text-emerald-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}" 
                        title="عرض قائمة تفصيلية">
                    <i class="fa-solid fa-bars-staggered text-sm"></i>
                </button>
            </div>
        </div>

    </div>

    <!-- Season Interactive Tags Filter Bar (Chips) -->
    @if(!empty($availableTags) && count($availableTags) > 0)
    <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-2xs space-y-3">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <span class="text-xs font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-tags text-gold-500"></i>
                <span>فلترة بحسب الكلمات الدلالية والموضوع:</span>
            </span>
            @if($selectedTag)
            <a href="{{ route('lectures.season', array_filter(['year' => $year, 'season' => $season, 'q' => $search, 'view' => $viewMode])) }}" 
               class="text-xs text-red-600 hover:text-red-700 font-bold flex items-center gap-1 bg-red-50 px-2.5 py-1 rounded-lg transition">
                <i class="fa-solid fa-xmark text-[10px]"></i>
                <span>إلغاء فلترة الوسم (عرض كل مواضيع الموسم)</span>
            </a>
            @endif
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('lectures.season', array_filter(['year' => $year, 'season' => $season, 'q' => $search, 'view' => $viewMode])) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ empty($selectedTag) ? 'bg-emerald-900 text-gold-300 shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                <span>كافة المواضيع</span>
                <span class="text-[10px] opacity-75">({{ $totalLectures }})</span>
            </a>
            @foreach($availableTags as $tag => $count)
            <a href="{{ route('lectures.season', array_filter(['year' => $year, 'season' => $season, 'tag' => $tag, 'q' => $search, 'view' => $viewMode])) }}" 
               class="px-3 py-1.5 rounded-xl text-xs transition flex items-center gap-1.5 {{ $selectedTag === $tag ? 'bg-emerald-800 text-gold-300 font-bold shadow-xs' : 'bg-slate-50 hover:bg-emerald-50 text-slate-700 hover:text-emerald-900 border border-slate-200 hover:border-emerald-300' }}">
                <span class="text-gold-600 font-bold">#</span>
                <span>{{ $tag }}</span>
                <span class="text-[10px] opacity-60">({{ $count }})</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- 1. GRID VIEW -->
    <div id="lectures-grid-container" class="{{ $viewMode === 'grid' ? 'grid' : 'hidden' }} grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($lectures as $lec)
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
            <div>
                <div class="relative bg-slate-900 aspect-video overflow-hidden">
                    <img src="{{ $lec->display_thumbnail }}" 
                         alt="{{ $lec->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <a href="{{ route('lectures.show', ['year' => $year, 'season' => $lec->season_slug ?? $season, 'slug' => $lec->slug]) }}" 
                       class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition flex items-center justify-center">
                        <div class="w-12 h-12 rounded-full {{ $lec->hasVideo() ? 'bg-emerald-800 text-gold-300' : 'bg-gold-500 text-emerald-950' }} flex items-center justify-center text-lg shadow-lg group-hover:scale-110 transition">
                            <i class="fa-solid {{ $lec->hasVideo() ? 'fa-play' : 'fa-arrow-left' }}"></i>
                        </div>
                    </a>
                    <span class="absolute bottom-2 left-2 px-2 py-0.5 rounded bg-black/80 text-white text-[11px] font-mono">
                        {{ $lec->duration ?? '50 د' }}
                    </span>
                    @if($lec->lecture_number)
                    <span class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-gold-500 text-emerald-950 text-[11px] font-bold shadow">
                        الليلة {{ $lec->lecture_number }}
                    </span>
                    @endif
                    @if(!$lec->hasVideo())
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full bg-slate-900/80 text-amber-300 text-[10px] font-semibold border border-amber-400/40">
                        مرئي قريباً
                    </span>
                    @endif
                </div>

                <div class="p-5 space-y-3">
                    <h3 class="font-bold text-sm sm:text-base text-slate-800 leading-snug group-hover:text-emerald-900 transition line-clamp-2">
                        <a href="{{ route('lectures.show', ['year' => $year, 'season' => $lec->season_slug ?? $season, 'slug' => $lec->slug]) }}">
                            {{ $lec->title }}
                        </a>
                    </h3>

                    @if(!empty($lec->description))
                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed font-light">
                        {{ $lec->description }}
                    </p>
                    @endif

                    @if(!empty($lec->tags_list))
                    <div class="flex flex-wrap gap-1 pt-1">
                        @foreach(array_slice($lec->tags_list, 0, 3) as $t)
                        <a href="{{ route('lectures.season', array_filter(['year' => $year, 'season' => $season, 'tag' => $t, 'view' => $viewMode])) }}" 
                           class="text-[10px] text-slate-600 hover:text-emerald-900 bg-slate-100 hover:bg-emerald-50 px-2 py-0.5 rounded-md transition">
                            #{{ $t }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                <div class="flex items-center gap-1.5">
                    @if($lec->hasVideo())
                    <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 text-[10px] font-bold" title="مرئي"><i class="fa-solid fa-video"></i> مرئي</span>
                    @else
                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px]" title="المرئي قيد المونتاج"><i class="fa-solid fa-video-slash"></i> مرئي قريباً</span>
                    @endif

                    @if($lec->hasAudio())
                    <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-bold" title="صوتي"><i class="fa-solid fa-headphones"></i> صوتي</span>
                    @endif

                    @if($lec->hasTranscript())
                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold" title="مكتوب"><i class="fa-solid fa-file-lines"></i> مكتوب</span>
                    @endif
                </div>

                <a href="{{ route('lectures.show', ['year' => $year, 'season' => $lec->season_slug ?? $season, 'slug' => $lec->slug]) }}" 
                   class="font-bold text-emerald-800 hover:text-emerald-950 flex items-center gap-1">
                    <span>فتح المحاضرة</span>
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-3xl border border-slate-200 space-y-2">
            <i class="fa-solid fa-magnifying-glass text-2xl text-slate-300"></i>
            <p>لا توجد محاضرات مطابقة لبحثك في هذا الموسم.</p>
            @if($selectedTag || $search)
            <a href="{{ route('lectures.season', ['year' => $year, 'season' => $season, 'view' => $viewMode]) }}" class="inline-block text-xs text-emerald-800 font-bold hover:underline">
                إعادة ضبط البحث وعرض كافة المحاضرات ←
            </a>
            @endif
        </div>
        @endforelse
    </div>

    <!-- 2. LIST VIEW -->
    <div id="lectures-list-container" class="{{ $viewMode === 'list' ? 'space-y-4' : 'hidden' }} space-y-4">
        @forelse($lectures as $lec)
        <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-sm hover:shadow-md transition flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 group">
            <div class="flex items-start sm:items-center gap-4 w-full sm:w-auto">
                <div class="relative w-28 sm:w-36 aspect-video rounded-xl overflow-hidden bg-slate-900 shrink-0">
                    <img src="{{ $lec->display_thumbnail }}" 
                         alt="{{ $lec->title }}" class="w-full h-full object-cover">
                    <span class="absolute bottom-1 left-1 px-1.5 py-0.5 rounded bg-black/80 text-white text-[9px] font-mono">
                        {{ $lec->duration ?? '50 د' }}
                    </span>
                    @if(!$lec->hasVideo())
                    <span class="absolute top-1 left-1 px-1.5 py-0.2 rounded bg-slate-900/90 text-amber-300 text-[8px] font-semibold">
                        قريباً
                    </span>
                    @endif
                </div>

                <div class="space-y-1.5 flex-1 min-w-0">
                    <div class="flex items-center gap-2 text-xs">
                        @if($lec->lecture_number)
                        <span class="px-2 py-0.5 rounded-full bg-gold-500 text-emerald-950 font-bold text-[10px]">
                            الليلة {{ $lec->lecture_number }}
                        </span>
                        @endif
                        <span class="text-slate-400 text-[11px]"><i class="fa-solid fa-eye ml-1"></i> {{ number_format($lec->views_count) }}</span>
                    </div>

                    <h3 class="font-bold text-sm sm:text-base text-slate-900 group-hover:text-emerald-800 transition line-clamp-1">
                        <a href="{{ route('lectures.show', ['year' => $year, 'season' => $lec->season_slug ?? $season, 'slug' => $lec->slug]) }}">
                            {{ $lec->title }}
                        </a>
                    </h3>

                    <p class="text-xs text-slate-500 line-clamp-1 font-light hidden sm:block">
                        {{ $lec->description ?? 'محاضرة فكرية وعقائدية متكاملة.' }}
                    </p>

                    @if(!empty($lec->tags_list))
                    <div class="flex flex-wrap gap-1 pt-0.5">
                        @foreach(array_slice($lec->tags_list, 0, 4) as $t)
                        <span class="text-[10px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">#{{ $t }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                <div class="flex items-center gap-1.5">
                    @if($lec->hasVideo())
                    <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 text-[10px] font-bold" title="مرئي"><i class="fa-solid fa-video"></i> مرئي</span>
                    @else
                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px]"><i class="fa-solid fa-video-slash"></i> مرئي قريباً</span>
                    @endif

                    @if($lec->hasAudio())
                    <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-bold" title="صوتي"><i class="fa-solid fa-headphones"></i> صوتي</span>
                    @endif

                    @if($lec->hasTranscript())
                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold" title="مكتوب"><i class="fa-solid fa-file-lines"></i> مكتوب</span>
                    @endif
                </div>

                <a href="{{ route('lectures.show', ['year' => $year, 'season' => $lec->season_slug ?? $season, 'slug' => $lec->slug]) }}" 
                   class="px-3.5 py-1.5 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-gold-300 font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                    <span>فتح المحاضرة</span>
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="py-12 text-center text-slate-400 bg-white rounded-3xl border border-slate-200">
            لا توجد محاضرات مطابقة لبحثك في هذا الموسم.
        </div>
        @endforelse
    <!-- Pagination -->
    <div class="pt-6">
        {{ $lectures->links() }}
    </div>

</div>

@push('scripts')
<script>
    function setViewMode(mode) {
        const grid = document.getElementById('lectures-grid-container');
        const list = document.getElementById('lectures-list-container');
        const btnGrid = document.getElementById('view-btn-grid');
        const btnList = document.getElementById('view-btn-list');

        if (mode === 'grid') {
            grid.classList.remove('hidden');
            grid.classList.add('grid');
            list.classList.add('hidden');
            btnGrid.className = 'p-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 bg-white text-emerald-900 shadow-sm';
            btnList.className = 'p-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 text-slate-500 hover:text-slate-800';
            localStorage.setItem('lectures_view_mode', 'grid');
        } else {
            grid.classList.add('hidden');
            grid.classList.remove('grid');
            list.classList.remove('hidden');
            btnList.className = 'p-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 bg-white text-emerald-900 shadow-sm';
            btnGrid.className = 'p-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 text-slate-500 hover:text-slate-800';
            localStorage.setItem('lectures_view_mode', 'list');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem('lectures_view_mode');
        if (saved === 'list' || saved === 'grid') {
            setViewMode(saved);
        }
    });
</script>
@endpush
@endsection

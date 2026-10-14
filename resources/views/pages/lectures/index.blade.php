@extends('layouts.app')

@section('title', (!empty($selectedTag) ? 'محاضرات وسم #' . $selectedTag . ' | ' : '') . ($browseMode === 'topics' ? 'فهرس المواضيع والوسوم | ' : '') . 'المحاضرات والمواسم السنوية | شبكة العلامة المنير')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

        <!-- Breadcrumb (Adaptive: Seasonal vs Thematic) -->
        <nav class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-emerald-800 transition">الرئيسية</a>
            <span>/</span>
            @if(!empty($selectedTag))
                <a href="{{ route('lectures.index', ['browse' => 'topics']) }}" class="hover:text-emerald-800 transition">فهرس المواضيع والوسوم</a>
                <span>/</span>
                <span class="text-emerald-950 font-bold flex items-center gap-1.5 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                    <i class="fa-solid fa-tag text-gold-500 text-[10px]"></i>
                    <span>موضوع: #{{ $selectedTag }}</span>
                </span>
            @elseif($browseMode === 'topics')
                <a href="{{ route('lectures.index') }}" class="hover:text-emerald-800 transition">المحاضرات</a>
                <span>/</span>
                <span class="text-emerald-950 font-bold">فهرس المواضيع والوسوم</span>
            @else
                <span class="text-emerald-950 font-bold">المحاضرات والمواسم السنوية</span>
            @endif
        </nav>

        <!-- Page Header & Introduction -->
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200/60 text-emerald-800 text-xs font-bold">
                <i class="fa-solid fa-layer-group text-gold-500"></i>
                <span>الأرشيف الفكري والدعوي المتكامل</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold font-scholarly text-slate-900">المحاضرات والدروس العامة</h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-light">
                استعرض النتاج الفكري والعقائدي لسماحة العلامة السيد منير الخباز بالتسلسل الزمني للمواسم السنوية، أو تصفح المحاضرات حسب الفهرس الموضوعي والكلمات الدلالية.
            </p>
        </div>

        <!-- Dual Browsing Mode Segmented Switcher (المواسم والسنوات vs المواضيع والوسوم) -->
        <div class="bg-slate-100/90 p-1.5 rounded-2xl border border-slate-200/80 max-w-xl mx-auto flex items-center shadow-inner">
            <!-- Mode 1: Chronological Seasons -->
            <button type="button" onclick="switchBrowseMode('seasons')" id="tab-btn-seasons"
                class="flex-1 py-3 px-3 sm:px-5 rounded-xl text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 {{ $browseMode === 'seasons' ? 'bg-emerald-900 text-gold-300 shadow-md ring-1 ring-gold-500/30' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-calendar-days text-sm"></i>
                <span>المواسم والسنوات</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full {{ $browseMode === 'seasons' ? 'bg-emerald-950 text-gold-400' : 'bg-slate-200 text-slate-600' }}">
                    {{ arabic_years($allYearsWithCounts->count()) }}
                </span>
            </button>

            <!-- Mode 2: Thematic Topics & Tags -->
            <button type="button" onclick="switchBrowseMode('topics')" id="tab-btn-topics"
                class="flex-1 py-3 px-3 sm:px-5 rounded-xl text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 {{ $browseMode === 'topics' ? 'bg-emerald-900 text-gold-300 shadow-md ring-1 ring-gold-500/30' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-tags text-sm"></i>
                <span>فهرس المواضيع والوسوم</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full {{ $browseMode === 'topics' ? 'bg-emerald-950 text-gold-400' : 'bg-slate-200 text-slate-600' }}">
                    {{ arabic_tags(count($allTagsWithCounts)) }}
                </span>
            </button>
        </div>

        <!-- ========================================================== -->
        <!-- VIEW 1: CHRONOLOGICAL SEASONS EXPLORER                     -->
        <!-- ========================================================== -->
        <div id="browse-seasons-container" class="{{ $browseMode === 'seasons' ? 'space-y-6' : 'hidden' }}">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold font-scholarly text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-calendar-days text-gold-500"></i>
                        <span>استعراض المواسم بحسب السنوات الهجرية</span>
                    </h2>
                    <p class="text-xs text-slate-500">المواسم المتكررة: عاشوراء، صفر، رمضان، الفاطمية، والمناسبات العامة.</p>
                </div>

                <!-- Year Selector Dropdown -->
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-600 flex items-center gap-1.5 whitespace-nowrap">
                        <i class="fa-solid fa-clock-rotate-left text-emerald-800"></i>
                        <span class="hidden sm:inline">أرشيف الأعوام:</span>
                    </label>
                    <select onchange="if(this.value) window.location.href=this.value" 
                            class="text-xs font-bold rounded-xl border border-slate-200 py-2 pr-3 pl-8 bg-white text-emerald-950 focus:border-emerald-800 focus:ring-1 focus:ring-emerald-800 shadow-xs cursor-pointer">
                        <option value="">-- اختر سنة لعرض مواسمها ({{ arabic_years($allYearsWithCounts->count()) }}) ▾ --</option>
                        @foreach($allYearsWithCounts as $yItem)
                            <option value="{{ route('lectures.year', ['year' => $yItem->hijri_year]) }}" {{ ($selectedYear == $yItem->hijri_year) ? 'selected' : '' }}>
                                مواسم سنة {{ $yItem->hijri_year }} هـ ({{ arabic_lectures($yItem->total) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- 3 Featured Recent Years Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($yearsData as $yearNum => $seasons)
                    <div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-slate-950 text-white rounded-3xl p-6 shadow-md border border-emerald-800/40 relative overflow-hidden flex flex-col justify-between group">
                        <div class="space-y-4 relative z-10">
                            <div class="flex items-center justify-between border-b border-emerald-800/60 pb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-xl bg-gold-500 text-emerald-950 font-bold flex items-center justify-center text-sm shadow">
                                        <i class="fa-solid fa-moon"></i>
                                    </span>
                                    <span class="text-xl font-bold font-scholarly text-gold-300">مواسم سنة {{ $yearNum }} هـ</span>
                                </div>
                                <span class="text-[11px] bg-emerald-800/80 px-2.5 py-0.5 rounded-full text-slate-300 font-mono">
                                    {{ arabic_lectures($seasons->sum('count')) }}
                                </span>
                            </div>

                            <!-- Seasons List in this Year -->
                            <div class="space-y-2">
                                @foreach($seasons as $s)
                                    @if(!empty($s->season_slug))
                                        <a href="{{ route('lectures.season', ['year' => $yearNum, 'season' => $s->season_slug ?? 'general']) }}"
                                            class="flex items-center justify-between p-2.5 rounded-xl bg-emerald-900/60 hover:bg-gold-500 hover:text-emerald-950 transition group/season border border-emerald-800/40 text-xs">
                                            <span class="font-bold flex items-center gap-2">
                                                <i class="fa-solid fa-chevron-left text-[10px] text-gold-400 group-hover/season:text-emerald-950 transition"></i>
                                                <span>موسم {{ $s->season }}</span>
                                            </span>
                                            <span class="text-[11px] text-slate-400 group-hover/season:text-emerald-900 font-mono">
                                                {{ $s->count }} مادة
                                            </span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-4 mt-4 border-t border-emerald-800/40 relative z-10">
                            <a href="{{ route('lectures.year', ['year' => $yearNum]) }}"
                                class="w-full py-2 bg-emerald-800 hover:bg-gold-500 hover:text-emerald-950 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-2">
                                <span>كافة مواسم ومحاضرات {{ $yearNum }} هـ</span>
                                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ========================================================== -->
        <!-- VIEW 2: THEMATIC TOPICS & TAGS EXPLORER                     -->
        <!-- ========================================================== -->
        <div id="browse-topics-container" class="{{ $browseMode === 'topics' ? 'space-y-8' : 'hidden' }}">

            <!-- Topics Search & Filter Bar -->
            <div class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl border border-gold-500/30 space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1.5">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/20 text-gold-300 text-xs font-bold border border-gold-500/30">
                            <i class="fa-solid fa-tags text-gold-400"></i>
                            <span>التصنيف الموضوعي والمفاهيمي</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold font-scholarly text-gold-200">
                            فهرس الكلمات الدلالية ومواضيع المحاضرات
                        </h2>
                        <p class="text-xs text-slate-300 max-w-2xl font-light">
                            تصفح كافة المحاضرات بحسب المحاور الفكرية والعقائدية والأخلاقية عبر السنين دون التقيد بالموسم.
                        </p>
                    </div>

                    <div class="text-left shrink-0">
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-emerald-800/80 text-xs font-mono text-gold-300 border border-emerald-700/60">
                            <i class="fa-solid fa-cubes"></i>
                            <span>{{ arabic_topics(count($allTagsWithCounts)) }} مفهرسة</span>
                        </span>
                    </div>
                </div>

                <!-- Instant Topic Search Field -->
                <div class="relative max-w-2xl">
                    <input type="text" id="topic-tags-search-input" oninput="filterTopicTags(this.value)"
                           placeholder="ابحث فورياً في قائمة الوسوم والمواضيع (مثال: العقيدة، الفلسفة، الذكاء الاصطناعي، عاشوراء)..." 
                           class="w-full text-xs sm:text-sm rounded-2xl bg-white/10 hover:bg-white/15 focus:bg-white text-slate-100 focus:text-slate-900 placeholder:text-slate-400 p-3.5 pr-11 border border-white/20 focus:border-gold-400 outline-none transition shadow-inner">
                    <i class="fa-solid fa-magnifying-glass absolute right-4 top-4 text-gold-400 text-sm"></i>
                    <button type="button" onclick="clearTopicSearch()" id="topic-search-clear-btn" 
                            class="hidden absolute left-3 top-3 text-xs bg-white/20 hover:bg-white/30 text-white rounded-lg px-2.5 py-1 transition">
                        مسح
                    </button>
                </div>
            </div>

            <!-- Active Tag Filter Highlight Card (When a tag is selected) -->
            @if(!empty($selectedTag))
            <div class="bg-gradient-to-r from-gold-500/15 via-emerald-50 to-emerald-100/40 rounded-3xl p-5 border border-gold-500/40 shadow-xs flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-900 text-gold-300 flex items-center justify-center text-base shadow">
                        <i class="fa-solid fa-tag"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-500 font-semibold">الموضوع المحدد حالياً:</span>
                            <h3 class="text-base font-bold text-emerald-950">#{{ $selectedTag }}</h3>
                        </div>
                        <p class="text-xs text-slate-600">
                            عرض المحاضرات والدروس المطابقة لهذا الوسم من مختلف المواسم والسنوات.
                        </p>
                    </div>
                </div>

                <a href="{{ route('lectures.index', ['browse' => 'topics']) }}" 
                   class="px-4 py-2 rounded-xl bg-white hover:bg-red-50 text-red-600 hover:text-red-700 text-xs font-bold border border-red-200 shadow-2xs flex items-center gap-2 transition">
                    <i class="fa-solid fa-xmark"></i>
                    <span>إلغاء التصفية (عرض كل المواضيع)</span>
                </a>
            </div>
            @endif

            <!-- 1. Thematic Curated Clusters (المحاور العلمية والفكرية الكبرى) -->
            <div class="space-y-4 pt-6 sm:pt-8">
                <div class="flex items-center justify-between">
                    <h3 class="text-base sm:text-lg font-bold font-scholarly text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-compass text-gold-500"></i>
                        <span>المحاور العلمية والفكرية الكبرى</span>
                    </h3>
                    <span class="text-xs text-slate-400">تقسيم موضوعي منهجي</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($thematicClusters as $clusterKey => $cluster)
                        @if(count($cluster['tags']) > 0)
                        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition space-y-3.5 group thematic-cluster-card">
                            <div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-sm shadow-2xs group-hover:bg-emerald-900 group-hover:text-gold-300 transition">
                                        <i class="fa-solid {{ $cluster['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm sm:text-base text-slate-900 group-hover:text-emerald-900 transition">
                                            {{ $cluster['name'] }}
                                        </h4>
                                        <p class="text-[11px] text-slate-400 font-light">{{ $cluster['description'] }}</p>
                                    </div>
                                </div>
                                <span class="text-[11px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-mono shrink-0">
                                    {{ arabic_tags(count($cluster['tags'])) }}
                                </span>
                            </div>

                            <!-- Cluster Tag Pills -->
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($cluster['tags'] as $cTag => $cCount)
                                    <a href="{{ route('lectures.index', ['browse' => 'topics', 'tag' => $cTag]) }}"
                                       class="topic-tag-chip px-2.5 py-1 rounded-xl text-xs transition flex items-center gap-1 {{ $selectedTag === $cTag ? 'bg-emerald-800 text-gold-300 font-bold shadow-xs' : 'bg-slate-50 hover:bg-emerald-50 text-slate-700 hover:text-emerald-900 border border-slate-200 hover:border-emerald-300' }}"
                                       data-tag-name="{{ $cTag }}">
                                        <span class="text-gold-600 font-bold">#</span>
                                        <span>{{ $cTag }}</span>
                                        <span class="text-[10px] opacity-60 font-mono">({{ $cCount }})</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>

        <!-- ========================================================== -->
        <!-- LECTURES TOOLBAR & RESULTS SECTION                         -->
        <!-- ========================================================== -->
        <div class="space-y-6 pt-4 border-t border-slate-200/80">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <h3 class="text-lg font-bold font-scholarly text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-film text-gold-500"></i>
                    @if(!empty($selectedTag))
                        <span>محاضرات ودروس موضوع: #{{ $selectedTag }} ({{ arabic_lectures($lectures->total()) }})</span>
                    @elseif(!empty($selectedYear))
                        <span>محاضرات عام {{ $selectedYear }} هـ ({{ arabic_lectures($lectures->total()) }})</span>
                    @else
                        <span>أحدث المحاضرات والدروس المتاحة</span>
                    @endif
                </h3>
            </div>

            <!-- Filter Bar: Formats, Year, Search & View Switcher -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-4">

                    <!-- Format & Year Filters -->
                    <div class="flex flex-wrap items-center gap-3 text-xs">
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-500 font-bold ml-1">الصيغة:</span>
                            <a href="{{ route('lectures.index', array_merge(request()->query(), ['format' => 'all'])) }}"
                                class="px-3 py-1 rounded-full font-semibold transition {{ $selectedFormat === 'all' ? 'bg-emerald-800 text-gold-300' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                الكل
                            </a>
                            <a href="{{ route('lectures.index', array_merge(request()->query(), ['format' => 'video'])) }}"
                                class="px-3 py-1 rounded-full font-semibold transition flex items-center gap-1.5 {{ $selectedFormat === 'video' ? 'bg-red-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                <i class="fa-solid fa-video text-xs"></i>
                                <span>مرئية</span>
                            </a>
                            <a href="{{ route('lectures.index', array_merge(request()->query(), ['format' => 'audio'])) }}"
                                class="px-3 py-1 rounded-full font-semibold transition flex items-center gap-1.5 {{ $selectedFormat === 'audio' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                <i class="fa-solid fa-headphones text-xs"></i>
                                <span>صوتية</span>
                            </a>
                            <a href="{{ route('lectures.index', array_merge(request()->query(), ['format' => 'text'])) }}"
                                class="px-3 py-1 rounded-full font-semibold transition flex items-center gap-1.5 {{ $selectedFormat === 'text' ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                <i class="fa-solid fa-file-lines text-xs"></i>
                                <span>مكتوبة</span>
                            </a>
                        </div>

                        <!-- Year Filter Select -->
                        <div class="flex items-center gap-1.5 border-r border-slate-200 pr-3 mr-1">
                            <span class="text-slate-500 font-bold">السنة:</span>
                            <select onchange="window.location.href=this.value" class="text-xs font-semibold rounded-xl border border-slate-200 py-1 px-2.5 bg-slate-50 text-slate-700 focus:bg-white focus:ring-1 focus:ring-emerald-800 cursor-pointer">
                                <option value="{{ route('lectures.index', array_merge(request()->query(), ['year' => ''])) }}">كافة السنوات</option>
                                @foreach($allYearsWithCounts as $y)
                                    <option value="{{ route('lectures.index', array_merge(request()->query(), ['year' => $y->hijri_year])) }}" {{ $selectedYear == $y->hijri_year ? 'selected' : '' }}>
                                        {{ $y->hijri_year }} هـ ({{ $y->total }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- View Switcher (Grid vs List Icons) -->
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

                @if(!empty($selectedTag))
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold">
                            <i class="fa-solid fa-tag text-gold-500"></i>
                            <span>تصفية بحسب الوسم: #{{ $selectedTag }}</span>
                        </div>
                        <a href="{{ route('lectures.index', array_merge(request()->except('tag'), ['browse' => 'topics'])) }}"
                            class="text-xs text-red-600 hover:text-red-700 font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-xmark text-xs"></i>
                            <span>إلغاء تصفية الوسم (عرض كافة المحاضرات)</span>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Lectures Display Container (Grid & List View) -->

            <!-- 1. GRID VIEW -->
            <div id="lectures-grid-container"
                class="{{ $viewMode === 'grid' ? 'grid' : 'hidden' }} grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($lectures as $lec)
                    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                        <div>
                            <!-- Thumbnail with Play Overlay -->
                            <div class="relative bg-slate-900 aspect-video overflow-hidden">
                                <img src="{{ $lec->display_thumbnail }}" alt="{{ $lec->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">

                                <a href="{{ route('lectures.show', ['year' => $lec->hijri_year ?? '1448', 'season' => $lec->season_slug ?? 'general', 'slug' => $lec->slug]) }}"
                                    class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition flex items-center justify-center">
                                    <div class="w-12 h-12 rounded-full {{ $lec->hasVideo() ? 'bg-emerald-800 text-gold-300' : 'bg-gold-500 text-emerald-950' }} flex items-center justify-center text-lg shadow-lg group-hover:scale-110 transition">
                                        <i class="fa-solid {{ $lec->hasVideo() ? 'fa-play' : 'fa-arrow-left' }}"></i>
                                    </div>
                                </a>

                                <!-- Duration Badge -->
                                <span class="absolute bottom-2 left-2 px-2 py-0.5 rounded bg-black/80 text-white text-[11px] font-mono">
                                    {{ $lec->duration ?? '50 د' }}
                                </span>

                                <!-- Season Pill Badge -->
                                <span class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-emerald-950/80 text-gold-300 text-[11px] font-bold border border-gold-400/20 backdrop-blur-sm">
                                    {{ $lec->season ?? 'محاضرة عامة' }} {{ $lec->hijri_year ? $lec->hijri_year . 'هـ' : '' }}
                                </span>

                                @if(!$lec->hasVideo())
                                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full bg-slate-900/80 text-amber-300 text-[10px] font-semibold border border-amber-400/40">
                                        مرئي قريباً
                                    </span>
                                @endif
                            </div>

                            <!-- Info Area -->
                            <div class="p-5 space-y-3">
                                <div class="flex items-center justify-between text-xs text-slate-400">
                                    <span class="text-emerald-800 font-semibold flex items-center gap-1">
                                        <i class="fa-solid fa-calendar text-[10px]"></i>
                                        <span>{{ $lec->season_year ?? ($lec->hijri_year ? 'موسم ' . $lec->hijri_year . 'هـ' : 'الموسم العام') }}</span>
                                    </span>
                                    <span><i class="fa-solid fa-eye ml-1 text-slate-400"></i>
                                        {{ number_format($lec->views_count) }}</span>
                                </div>

                                <h3 class="font-bold text-sm sm:text-base text-slate-800 leading-snug group-hover:text-emerald-900 transition line-clamp-2">
                                    <a href="{{ route('lectures.show', ['year' => $lec->hijri_year ?? '1448', 'season' => $lec->season_slug ?? 'general', 'slug' => $lec->slug]) }}">
                                        {{ $lec->title }}
                                    </a>
                                </h3>

                                @if(!empty($lec->description))
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed font-light">
                                        {{ strip_tags($lec->description) }}
                                    </p>
                                @endif

                                @if(!empty($lec->tags_list))
                                    <div class="flex flex-wrap gap-1 pt-1">
                                        @foreach(array_slice($lec->tags_list, 0, 3) as $t)
                                            <a href="{{ route('lectures.index', ['browse' => 'topics', 'tag' => $t]) }}"
                                                class="text-[10px] text-slate-600 hover:text-emerald-900 bg-slate-100 hover:bg-emerald-50 px-2 py-0.5 rounded-md transition">
                                                #{{ $t }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Actions & Media Badges -->
                        <div class="px-5 pb-5 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-1.5">
                                @if($lec->hasVideo())
                                    <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 text-[10px] font-bold" title="مرئي"><i
                                            class="fa-solid fa-video"></i> مرئي</span>
                                @endif

                                @if($lec->hasAudio())
                                    <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-bold" title="صوتي"><i
                                            class="fa-solid fa-headphones"></i> صوتي</span>
                                @endif

                                @if($lec->hasTranscript())
                                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold"
                                        title="مكتوب"><i class="fa-solid fa-file-lines"></i> مكتوب</span>
                                @endif
                            </div>

                            <a href="{{ route('lectures.show', ['year' => $lec->hijri_year ?? '1448', 'season' => $lec->season_slug ?? 'general', 'slug' => $lec->slug]) }}"
                                class="text-xs font-bold text-emerald-800 hover:text-emerald-950 flex items-center gap-1 group-hover:translate-x-[-2px] transition">
                                <span>التفاصيل</span>
                                <i class="fa-solid fa-chevron-left text-[9px]"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center text-slate-400 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                        <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300"></i>
                        <p>لا توجد محاضرات مطابقة لخيارات البحث أو الفلترة المحددة.</p>
                        @if(!empty($selectedTag))
                            <a href="{{ route('lectures.index', ['browse' => 'topics']) }}" class="mt-3 inline-block text-xs text-emerald-800 font-bold hover:underline">
                                العودة لعرض كافة المواضيع
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- 2. LIST VIEW -->
            <div id="lectures-list-container"
                class="{{ $viewMode === 'list' ? 'flex' : 'hidden' }} flex-col gap-4">
                @forelse($lectures as $lec)
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs hover:shadow-md transition flex flex-col sm:flex-row items-center justify-between gap-4 group">
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <!-- Thumbnail Small -->
                            <div class="relative bg-slate-900 w-28 h-20 rounded-xl overflow-hidden shrink-0">
                                <img src="{{ $lec->display_thumbnail }}" alt="{{ $lec->title }}" class="w-full h-full object-cover">
                                <a href="{{ route('lectures.show', ['year' => $lec->hijri_year ?? '1448', 'season' => $lec->season_slug ?? 'general', 'slug' => $lec->slug]) }}"
                                    class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition flex items-center justify-center">
                                    <div class="w-7 h-7 rounded-full {{ $lec->hasVideo() ? 'bg-emerald-800 text-gold-300' : 'bg-gold-500 text-emerald-950' }} flex items-center justify-center text-xs shadow">
                                        <i class="fa-solid {{ $lec->hasVideo() ? 'fa-play' : 'fa-arrow-left' }}"></i>
                                    </div>
                                </a>
                            </div>

                            <div class="space-y-1">
                                <div class="flex items-center gap-2 text-xs text-slate-400">
                                    <span class="text-emerald-800 font-semibold">{{ $lec->season_year ?? ($lec->hijri_year ? 'موسم ' . $lec->hijri_year . 'هـ' : 'الموسم العام') }}</span>
                                    <span>•</span>
                                    <span><i class="fa-solid fa-eye ml-1 text-slate-400"></i>{{ number_format($lec->views_count) }}</span>
                                </div>
                                <h4 class="font-bold text-sm text-slate-800 group-hover:text-emerald-900 transition">
                                    <a href="{{ route('lectures.show', ['year' => $lec->hijri_year ?? '1448', 'season' => $lec->season_slug ?? 'general', 'slug' => $lec->slug]) }}">
                                        {{ $lec->title }}
                                    </a>
                                </h4>
                                @if(!empty($lec->tags_list))
                                    <div class="flex flex-wrap gap-1 pt-0.5">
                                        @foreach(array_slice($lec->tags_list, 0, 4) as $t)
                                            <a href="{{ route('lectures.index', ['browse' => 'topics', 'tag' => $t]) }}"
                                                class="text-[10px] text-slate-600 hover:text-emerald-900 bg-slate-100 hover:bg-emerald-50 px-2 py-0.5 rounded-md transition">
                                                #{{ $t }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3 shrink-0 w-full sm:w-auto justify-between sm:justify-end">
                            <div class="flex items-center gap-1.5">
                                @if($lec->hasVideo())
                                    <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 text-[10px] font-bold" title="مرئي"><i
                                            class="fa-solid fa-video"></i> مرئي</span>
                                @endif
                                @if($lec->hasAudio())
                                    <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-bold" title="صوتي"><i
                                            class="fa-solid fa-headphones"></i> صوتي</span>
                                @endif
                                @if($lec->hasTranscript())
                                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold"
                                        title="مكتوب"><i class="fa-solid fa-file-lines"></i> مكتوب</span>
                                @endif
                            </div>

                            <a href="{{ route('lectures.show', ['year' => $lec->hijri_year ?? '1448', 'season' => $lec->season_slug ?? 'general', 'slug' => $lec->slug]) }}"
                                class="px-3.5 py-1.5 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-gold-300 font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                                <span>فتح المحاضرة</span>
                                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center text-slate-400 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                        <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300"></i>
                        <p>لا توجد محاضرات مطابقة لخيارات البحث.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="pt-6">
                {{ $lectures->links() }}
            </div>

        </div>

    </div>

    @push('scripts')
        <script>
            // 1. Dual Mode Switcher (Seasons vs Topics)
            function switchBrowseMode(mode) {
                const seasonsContainer = document.getElementById('browse-seasons-container');
                const topicsContainer = document.getElementById('browse-topics-container');
                const btnSeasons = document.getElementById('tab-btn-seasons');
                const btnTopics = document.getElementById('tab-btn-topics');

                if (mode === 'topics') {
                    seasonsContainer.classList.add('hidden');
                    topicsContainer.classList.remove('hidden');

                    btnTopics.className = 'flex-1 py-3 px-3 sm:px-5 rounded-xl text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 bg-emerald-900 text-gold-300 shadow-md ring-1 ring-gold-500/30';
                    btnSeasons.className = 'flex-1 py-3 px-3 sm:px-5 rounded-xl text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900';

                    // Update URL without reload
                    const url = new URL(window.location);
                    url.searchParams.set('browse', 'topics');
                    window.history.replaceState({}, '', url);
                } else {
                    topicsContainer.classList.add('hidden');
                    seasonsContainer.classList.remove('hidden');

                    btnSeasons.className = 'flex-1 py-3 px-3 sm:px-5 rounded-xl text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 bg-emerald-900 text-gold-300 shadow-md ring-1 ring-gold-500/30';
                    btnTopics.className = 'flex-1 py-3 px-3 sm:px-5 rounded-xl text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900';

                    const url = new URL(window.location);
                    url.searchParams.delete('browse');
                    window.history.replaceState({}, '', url);
                }
            }

            // 2. Instant Tag Search Filter
            function filterTopicTags(query) {
                const q = query.trim().toLowerCase();
                const chips = document.querySelectorAll('.topic-tag-chip');
                const clearBtn = document.getElementById('topic-search-clear-btn');
                let matchCount = 0;

                if (clearBtn) {
                    if (q) {
                        clearBtn.classList.remove('hidden');
                    } else {
                        clearBtn.classList.add('hidden');
                    }
                }

                chips.forEach(chip => {
                    const tagText = (chip.getAttribute('data-tag-name') || '').toLowerCase();
                    if (!q || tagText.includes(q)) {
                        chip.style.display = 'inline-flex';
                        matchCount++;
                    } else {
                        chip.style.display = 'none';
                    }
                });

                // Toggle thematic cluster cards based on visible chips
                document.querySelectorAll('.thematic-cluster-card').forEach(card => {
                    if (!q) {
                        card.style.display = '';
                    } else {
                        const visibleChips = Array.from(card.querySelectorAll('.topic-tag-chip')).filter(c => c.style.display !== 'none');
                        card.style.display = visibleChips.length > 0 ? '' : 'none';
                    }
                });

                const countBadge = document.getElementById('filtered-tags-count');
                if (countBadge) {
                    countBadge.innerText = matchCount;
                }
            }

            function clearTopicSearch() {
                const input = document.getElementById('topic-tags-search-input');
                if (input) {
                    input.value = '';
                    filterTopicTags('');
                    input.focus();
                }
            }

            // 3. Grid vs List View Switcher
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

            // Auto-restore view mode preference
            document.addEventListener('DOMContentLoaded', () => {
                const savedView = localStorage.getItem('lectures_view_mode');
                if (savedView === 'list' || savedView === 'grid') {
                    setViewMode(savedView);
                }
            });
        </script>
    @endpush
@endsection
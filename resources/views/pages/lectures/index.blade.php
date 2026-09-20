@extends('layouts.app')

@section('title', 'المحاضرات والمواسم السنوية | شبكة العلامة المنير')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-emerald-800 transition">الرئيسية</a>
            <span>/</span>
            <span class="text-emerald-950 font-bold">المحاضرات والمواسم</span>
        </nav>

        <!-- Page Header & Introduction -->
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200/60 text-emerald-800 text-xs font-bold">
                <i class="fa-solid fa-layer-group text-gold-500"></i>
                <span>الأرشيف الفكري والدعوي المتكامل</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold font-scholarly text-slate-900">المحاضرات والمواسم السنوية</h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-light">
                تصفح المحاضرات الدينية والفكرية لسماحة السيد منير الخباز بحسب المواسم السنوية الهجرية المتكررة، متوفرة بثلاث
                صيغ متكاملة: البث المرئي، التسجيل الصوتي، والتفريغ النصي الموثق.
            </p>
        </div>

        <!-- Seasonal Hijri Years Explorer Cards -->
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold font-scholarly text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-calendar-days text-gold-500"></i>
                        <span>استعراض المواسم بحسب السنوات الهجرية</span>
                    </h2>
                </div>

                <!-- Year Selector Dropdown -->
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-600 flex items-center gap-1.5 whitespace-nowrap">
                        <i class="fa-solid fa-clock-rotate-left text-emerald-800"></i>
                        <span class="hidden sm:inline">أرشيف الأعوام:</span>
                    </label>
                    <select onchange="if(this.value) window.location.href=this.value" 
                            class="text-xs font-bold rounded-xl border border-slate-200 py-2 pr-3 pl-8 bg-white text-emerald-950 focus:border-emerald-800 focus:ring-1 focus:ring-emerald-800 shadow-xs cursor-pointer">
                        <option value="">-- اختر سنة لعرض مواسمها ({{ $allYearsWithCounts->count() }} عام) ▾ --</option>
                        @foreach($allYearsWithCounts as $yItem)
                            <option value="{{ route('lectures.year', ['year' => $yItem->hijri_year]) }}" {{ ($selectedYear == $yItem->hijri_year) ? 'selected' : '' }}>
                                مواسم سنة {{ $yItem->hijri_year }} هـ ({{ $yItem->total }} محاضرة)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>


            <!-- 3 Featured Recent Years Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($yearsData as $yearNum => $seasons)
                    <div
                        class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-slate-950 text-white rounded-3xl p-6 shadow-md border border-emerald-800/40 relative overflow-hidden flex flex-col justify-between group">
                        <div class="space-y-4 relative z-10">
                            <div class="flex items-center justify-between border-b border-emerald-800/60 pb-3">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-8 h-8 rounded-xl bg-gold-500 text-emerald-950 font-bold flex items-center justify-center text-sm shadow">
                                        <i class="fa-solid fa-moon"></i>
                                    </span>
                                    <span class="text-xl font-bold font-scholarly text-gold-300">مواسم سنة {{ $yearNum }}
                                        هـ</span>
                                </div>
                                <span class="text-[11px] bg-emerald-800/80 px-2.5 py-0.5 rounded-full text-slate-300 font-mono">
                                    {{ $seasons->sum('count') }} محاضرة
                                </span>
                            </div>

                            <!-- Seasons List in this Year -->
                            <div class="space-y-2">
                                @foreach($seasons as $s)
                                    @if(!empty($s->season_slug))
                                        <a href="{{ route('lectures.season', ['year' => $yearNum, 'season' => $s->season_slug ?? 'general']) }}"
                                            class="flex items-center justify-between p-2.5 rounded-xl bg-emerald-900/60 hover:bg-gold-500 hover:text-emerald-950 transition group/season border border-emerald-800/40 text-xs">
                                            <span class="font-bold flex items-center gap-2">
                                                <i
                                                    class="fa-solid fa-chevron-left text-[10px] text-gold-400 group-hover/season:text-emerald-950 transition"></i>
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

        <!-- Filter Bar & Display View Switcher -->
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
                    <div
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold">
                        <i class="fa-solid fa-tag text-gold-500"></i>
                        <span>تصفية بحسب الوسم: #{{ $selectedTag }}</span>
                    </div>
                    <a href="{{ route('lectures.index', request()->except('tag')) }}"
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
                <div
                    class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                    <div>
                        <!-- Thumbnail with Play Overlay -->
                        <div class="relative bg-slate-900 aspect-video overflow-hidden">
                            <img src="{{ $lec->display_thumbnail }}" alt="{{ $lec->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">

                            <a href="{{ route('lectures.show', ['year' => $lec->hijri_year ?? '1448', 'season' => $lec->season_slug ?? 'general', 'slug' => $lec->slug]) }}"
                                class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition flex items-center justify-center">
                                <div
                                    class="w-12 h-12 rounded-full {{ $lec->hasVideo() ? 'bg-emerald-800 text-gold-300' : 'bg-gold-500 text-emerald-950' }} flex items-center justify-center text-lg shadow-lg group-hover:scale-110 transition">
                                    <i class="fa-solid {{ $lec->hasVideo() ? 'fa-play' : 'fa-arrow-left' }}"></i>
                                </div>
                            </a>

                            <!-- Duration Badge -->
                            <span
                                class="absolute bottom-2 left-2 px-2 py-0.5 rounded bg-black/80 text-white text-[11px] font-mono">
                                {{ $lec->duration ?? '50 د' }}
                            </span>

                            <!-- Season Pill Badge -->
                            <span
                                class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-emerald-950/80 text-gold-300 text-[11px] font-bold border border-gold-400/20 backdrop-blur-sm">
                                {{ $lec->season ?? 'محاضرة عامة' }} {{ $lec->hijri_year ? $lec->hijri_year . 'هـ' : '' }}
                            </span>

                            @if(!$lec->hasVideo())
                                <span
                                    class="absolute top-2 left-2 px-2 py-0.5 rounded-full bg-slate-900/80 text-amber-300 text-[10px] font-semibold border border-amber-400/40">
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

                            <h3
                                class="font-bold text-sm sm:text-base text-slate-800 leading-snug group-hover:text-emerald-900 transition line-clamp-2">
                                <a
                                    href="{{ route('lectures.show', ['year' => $lec->hijri_year ?? '1448', 'season' => $lec->season_slug ?? 'general', 'slug' => $lec->slug]) }}">
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
                                        <a href="{{ route('lectures.index', ['tag' => $t]) }}"
                                            class="text-[10px] text-slate-600 hover:text-emerald-900 bg-slate-100 hover:bg-emerald-50 px-2 py-0.5 rounded-md transition">
                                            #{{ $t }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Footer with 3 Format Badges -->
                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            @if($lec->hasVideo())
                                <span
                                    class="px-2 py-0.5 rounded bg-red-100 text-red-700 text-[10px] font-bold flex items-center gap-1"
                                    title="متوفر مرئياً"><i class="fa-solid fa-video"></i> مرئي</span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px] flex items-center gap-1"
                                    title="المرئي قريباً"><i class="fa-solid fa-video-slash"></i> مرئي قريباً</span>
                            @endif

                            @if($lec->hasAudio())
                                <span
                                    class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-bold flex items-center gap-1"
                                    title="متوفر صوتياً"><i class="fa-solid fa-headphones"></i> صوتي</span>
                            @endif

                            @if($lec->hasTranscript())
                                <span
                                    class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold flex items-center gap-1"
                                    title="متوفر نصياً"><i class="fa-solid fa-file-lines"></i> مكتوب</span>
                            @endif
                        </div>

                        <a href="{{ route('lectures.show', ['year' => $lec->hijri_year ?? '1448', 'season' => $lec->season_slug ?? 'general', 'slug' => $lec->slug]) }}"
                            class="font-bold text-emerald-800 hover:text-emerald-950 flex items-center gap-1">
                            <span>فتح المحاضرة</span>
                            <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-slate-400 space-y-2">
                    <i class="fa-solid fa-inbox text-4xl"></i>
                    <p class="text-sm">لا توجد محاضرات مطابقة لخيارات البحث المحددة.</p>
                    @if(!empty($selectedTag) || !empty($search))
                        <a href="{{ route('lectures.index') }}" class="text-xs text-emerald-800 font-bold hover:underline">
                            عرض كافة المحاضرات ←
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- 2. LIST VIEW -->
        <div id="lectures-list-container" class="{{ $viewMode === 'list' ? 'space-y-4' : 'hidden' }} space-y-4">
            @forelse($lectures as $lec)
                <div
                    class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-sm hover:shadow-md transition flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 group">

                    <div class="flex items-start sm:items-center gap-4 w-full sm:w-auto">
                        <!-- Thumbnail -->
                        <div
                            class="relative w-28 sm:w-36 aspect-video rounded-xl overflow-hidden bg-slate-900 shrink-0 shadow-sm">
                            <img src="{{ $lec->display_thumbnail }}" alt="{{ $lec->title }}" class="w-full h-full object-cover">
                            <span
                                class="absolute bottom-1 left-1 px-1.5 py-0.5 rounded bg-black/80 text-white text-[9px] font-mono">
                                {{ $lec->duration ?? '50 د' }}
                            </span>
                            @if(!$lec->hasVideo())
                                <span
                                    class="absolute top-1 left-1 px-1.5 py-0.2 rounded bg-slate-900/90 text-amber-300 text-[8px] font-semibold">
                                    قريباً
                                </span>
                            @endif
                        </div>

                        <div class="space-y-1.5 flex-1 min-w-0">
                            <div class="flex items-center gap-2 text-xs">
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-900 font-bold text-[10px]">
                                    {{ $lec->season ?? 'عام' }} {{ $lec->hijri_year ? $lec->hijri_year . 'هـ' : '' }}
                                </span>
                                <span class="text-slate-400 text-[11px]"><i class="fa-solid fa-eye ml-1"></i>
                                    {{ number_format($lec->views_count) }}</span>
                            </div>

                            <h3
                                class="font-bold text-sm sm:text-base text-slate-900 group-hover:text-emerald-800 transition line-clamp-1">
                                <a
                                    href="{{ route('lectures.show', ['year' => $lec->hijri_year ?? '1448', 'season' => $lec->season_slug ?? 'general', 'slug' => $lec->slug]) }}">
                                    {{ $lec->title }}
                                </a>
                            </h3>

                            <p class="text-xs text-slate-500 line-clamp-1 font-light hidden sm:block">
                                {{ !empty($lec->description) ? strip_tags($lec->description) : 'محاضرة فكرية وعقائدية متكاملة.' }}
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

                    <div
                        class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                        <div class="flex items-center gap-1.5">
                            @if($lec->hasVideo())
                                <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 text-[10px] font-bold" title="مرئي"><i
                                        class="fa-solid fa-video"></i> مرئي</span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px]"><i
                                        class="fa-solid fa-video-slash"></i> مرئي قريباً</span>
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
                <div class="py-12 text-center text-slate-400">لا توجد محاضرات مطابقة لخيارات البحث.</div>
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

                // Auto-restore persisted view preference
                document.addEventListener('DOMContentLoaded', () => {
                    const saved = localStorage.getItem('lectures_view_mode');
                    if (saved === 'list' || saved === 'grid') {
                        setViewMode(saved);
                    }
                });
            </script>
        @endpush
@endsection
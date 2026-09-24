@extends('layouts.app')

@section('title', 'مواسم ومحاضرات سنة ' . $year . ' هـ | شبكة العلامة المنير')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-emerald-800 transition">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('lectures.index') }}" class="hover:text-emerald-800 transition">المحاضرات والمواسم</a>
        <span>/</span>
        <span class="text-emerald-950 font-bold">سنة {{ $year }} هـ</span>
    </nav>

    <!-- Year Header -->
    <div class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-slate-900 text-white rounded-3xl p-8 sm:p-10 shadow-lg border border-gold-500/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/20 text-gold-300 text-xs font-bold border border-gold-500/30">
                <i class="fa-solid fa-calendar text-gold-400"></i>
                <span>السنة الهجرية {{ $year }}</span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-bold font-scholarly text-white">مواسم ومحاضرات سنة {{ $year }} هـ</h1>
            <p class="text-xs sm:text-sm text-slate-300 font-light max-w-2xl">
                استعراض مواسم هذه السنة ومحاضراتها الدينية والفكرية بصيغها المرئية والصوتية والمكتوبة.
            </p>
        </div>

        <div class="flex items-center gap-3 bg-emerald-900/60 p-4 rounded-2xl border border-emerald-800 shrink-0">
            <div class="text-center px-4 border-l border-emerald-800">
                <div class="text-2xl font-bold font-scholarly text-gold-300">{{ $seasons->count() }}</div>
                <div class="text-[11px] text-slate-400">مواسم</div>
            </div>
            <div class="text-center px-4">
                <div class="text-2xl font-bold font-scholarly text-gold-300">{{ $lectures->total() }}</div>
                <div class="text-[11px] text-slate-400">محاضرة</div>
            </div>
        </div>
    </div>

    <!-- Seasons in this Year -->
    <div class="space-y-4">
        <h2 class="text-xl font-bold font-scholarly text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-calendar-check text-gold-500"></i>
            <span>المواسم المنعقدة في سنة {{ $year }} هـ</span>
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($seasons as $s)
            <a href="{{ route('lectures.season', ['year' => $year, 'season' => $s->season_slug ?? 'general']) }}" 
               class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-md hover:border-gold-400 transition flex items-center justify-between group">
                <div class="space-y-1">
                    <div class="text-xs text-slate-400 font-bold">موسم سنوي</div>
                    <h3 class="text-lg font-bold font-scholarly text-slate-900 group-hover:text-emerald-800 transition">
                        {{ $s->season }}
                    </h3>
                    <p class="text-xs text-slate-500">{{ $s->lectures_count }} محاضرة ومجلس</p>
                </div>

                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-800 group-hover:bg-gold-500 group-hover:text-emerald-950 flex items-center justify-center text-lg transition shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </div>
            </a>
            @empty
            <div class="col-span-full p-8 text-center text-slate-400 bg-white rounded-3xl">
                لا توجد مواسم مسجلة لهذه السنة حتى الآن.
            </div>
            @endforelse
        </div>
    </div>

    <!-- All Lectures in this Year -->
    <div class="space-y-6 pt-4 border-t border-slate-200">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold font-scholarly text-slate-900">كافة محاضرات سنة {{ $year }} هـ</h2>
                <p class="text-xs text-slate-400">مرتبة بحسب تسلسل الليالي والمواسم</p>
            </div>

            <!-- View Switcher -->
            <div class="inline-flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200">
                <button type="button" onclick="setViewMode('grid')" id="view-btn-grid" 
                        class="p-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $viewMode === 'grid' ? 'bg-white text-emerald-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}" 
                        title="عرض شبكي">
                    <i class="fa-solid fa-table-cells-large text-sm"></i>
                </button>
                <button type="button" onclick="setViewMode('list')" id="view-btn-list" 
                        class="p-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $viewMode === 'list' ? 'bg-white text-emerald-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}" 
                        title="عرض قائمة">
                    <i class="fa-solid fa-bars-staggered text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Grid View -->
        <div id="lectures-grid-container" class="{{ $viewMode === 'grid' ? 'grid' : 'hidden' }} grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($lectures as $lec)
            <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                <div>
                    <div class="relative bg-slate-900 aspect-video overflow-hidden">
                        <img src="{{ $lec->thumbnail ?? ($lec->youtube_id ? 'https://img.youtube.com/vi/' . $lec->youtube_id . '/hqdefault.jpg' : asset('images/default-image.jpg')) }}" 
                             alt="{{ $lec->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        <a href="{{ route('lectures.show', ['year' => $year, 'season' => $lec->season_slug ?? 'general', 'slug' => $lec->slug]) }}" 
                           class="absolute inset-0 bg-black/35 group-hover:bg-black/15 transition flex items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-emerald-800 text-gold-300 flex items-center justify-center text-lg shadow-lg group-hover:scale-110 transition">
                                <i class="fa-solid fa-play"></i>
                            </div>
                        </a>
                        <span class="absolute bottom-2 left-2 px-2 py-0.5 rounded bg-black/80 text-white text-[11px] font-mono">
                            {{ $lec->duration ?? '50 د' }}
                        </span>
                        <span class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-emerald-950/80 text-gold-300 text-[11px] font-bold shadow">
                            {{ (!empty($lec->season_year) && $lec->season_year !== 'الموسم العام') ? $lec->season_year : $lec->season }}
                        </span>
                    </div>

                    <div class="p-5 space-y-3">
                        <h3 class="font-bold text-sm sm:text-base text-slate-800 leading-snug group-hover:text-emerald-900 transition line-clamp-2">
                            <a href="{{ route('lectures.show', ['year' => $year, 'season' => $lec->season_slug ?? 'general', 'slug' => $lec->slug]) }}">
                                {{ $lec->title }}
                            </a>
                        </h3>

                        @if(!empty($lec->recording_date))
                        <div class="text-[11px] text-slate-500 flex items-center gap-1.5 pt-1">
                            <i class="fa-regular fa-calendar-days text-slate-400"></i>
                            <span>{{ $lec->recording_date }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 text-[10px] font-bold"><i class="fa-solid fa-video"></i> مرئي</span>
                        <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-bold"><i class="fa-solid fa-headphones"></i> صوتي</span>
                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold"><i class="fa-solid fa-file-lines"></i> مكتوب</span>
                    </div>
                    <a href="{{ route('lectures.show', ['year' => $year, 'season' => $lec->season_slug ?? 'general', 'slug' => $lec->slug]) }}" class="font-bold text-emerald-800 hover:text-emerald-950">فتح المحاضرة ←</a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- List View -->
        <div id="lectures-list-container" class="{{ $viewMode === 'list' ? 'space-y-4' : 'hidden' }} space-y-4">
            @foreach($lectures as $lec)
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-sm hover:shadow-md transition flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 group">
                <div class="flex items-start sm:items-center gap-4 w-full sm:w-auto">
                    <div class="relative w-28 sm:w-36 aspect-video rounded-xl overflow-hidden bg-slate-900 shrink-0">
                        <img src="{{ $lec->thumbnail ?? ($lec->youtube_id ? 'https://img.youtube.com/vi/' . $lec->youtube_id . '/mqdefault.jpg' : asset('images/default-image.jpg')) }}" 
                             alt="{{ $lec->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="space-y-1.5 flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            @if($lec->lecture_number)
                            <span class="px-2 py-0.5 rounded-full bg-gold-500/20 text-gold-600 font-bold text-[10px]">
                                الليلة {{ $lec->lecture_number }}
                            </span>
                            @endif
                            <span class="text-emerald-800 font-bold text-[11px]">{{ (!empty($lec->season_year) && $lec->season_year !== 'الموسم العام') ? $lec->season_year : $lec->season }}</span>
                            @if(!empty($lec->recording_date))
                            <span class="text-slate-400 text-[11px] flex items-center gap-1">
                                <i class="fa-regular fa-calendar-days text-[10px]"></i>
                                <span>{{ $lec->recording_date }}</span>
                            </span>
                            @endif
                        </div>
                        <h3 class="font-bold text-sm sm:text-base text-slate-900 group-hover:text-emerald-800 transition line-clamp-1">
                            <a href="{{ route('lectures.show', ['year' => $year, 'season' => $lec->season_slug ?? 'general', 'slug' => $lec->slug]) }}">
                                {{ $lec->title }}
                            </a>
                        </h3>
                    </div>
                </div>

                <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                    <a href="{{ route('lectures.show', ['year' => $year, 'season' => $lec->season_slug ?? 'general', 'slug' => $lec->slug]) }}" 
                       class="px-4 py-2 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs transition flex items-center gap-1.5">
                        <span>فتح المحاضرة</span>
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pt-6">
            {{ $lectures->links() }}
        </div>
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

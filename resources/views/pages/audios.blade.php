@extends('layouts.app')

@section('title', 'المكتبة الصوتية ومحاضرات المواسم | شبكة العلامة المنير')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <!-- Page Header -->
    <div class="flex flex-wrap items-end justify-between gap-4 pb-6 border-b border-slate-200 mb-8">
        <div>
            <span class="text-xs font-bold text-gold-600 uppercase tracking-wider">المكتبة الصوتية</span>
            <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900 mt-1">محاضرات وخطب سماحة السيد</h1>
        </div>

        <!-- Seasons filter -->
        <div class="flex items-center gap-2 overflow-x-auto">
            <a href="{{ route('audios.index') }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold {{ !request('season') ? 'bg-emerald-800 text-gold-300' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                الكل
            </a>
            @foreach($seasons as $s)
            <a href="{{ route('audios.index', ['season' => $s]) }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold {{ request('season') == $s ? 'bg-emerald-800 text-gold-300' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                {{ $s }}
            </a>
            @endforeach
        </div>
    </div>

    <!-- Audios Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($audios as $audio)
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                    <span class="px-2.5 py-0.5 rounded bg-emerald-50 text-emerald-800 font-semibold">{{ $audio->season_year ?? 'محاضرة عامة' }}</span>
                    <span><i class="fa-regular fa-clock"></i> {{ $audio->duration ?? '40:00' }}</span>
                </div>
                <h3 class="font-bold text-base text-slate-800 leading-snug hover:text-emerald-800 transition line-clamp-2">
                    <a href="{{ route('audios.show', $audio->slug) }}">{{ $audio->title }}</a>
                </h3>
                @if($audio->description)
                <p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed font-light">
                    {{ $audio->description }}
                </p>
                @endif
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <button onclick="playGlobalAudio('{{ $audio->media_url }}', '{{ addslashes($audio->title) }}')" class="px-4 py-2 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-gold-300 text-xs font-bold flex items-center gap-2 shadow-sm transition">
                    <i class="fa-solid fa-circle-play text-sm"></i>
                    <span>تشغيل في المشغل</span>
                </button>
                <a href="{{ route('audios.show', $audio->slug) }}" class="text-xs text-slate-400 hover:text-slate-700">التفاصيل والتحميل ←</a>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-slate-200 text-slate-500">
            لا توجد تسجيلات صوتية مطابقة للتصنيف المحدد.
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $audios->links() }}
    </div>

</div>
@endsection

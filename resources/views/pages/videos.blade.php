@extends('layouts.app')

@section('title', 'المكتبة المرئية والمقاطع القصيرة | شبكة العلامة المنير')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <!-- Page Header -->
    <div class="flex flex-wrap items-end justify-between gap-4 pb-6 border-b border-slate-200 mb-8">
        <div>
            <span class="text-xs font-bold text-gold-600 uppercase tracking-wider">المكتبة المرئية</span>
            <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900 mt-1">المحاضرات المرئية والريلز</h1>
        </div>

        <!-- Filter tabs -->
        <div class="flex items-center gap-2">
            <a href="{{ route('videos.index') }}" class="px-4 py-1.5 rounded-full text-xs font-semibold {{ $type === 'all' ? 'bg-emerald-800 text-gold-300' : 'bg-white border border-slate-200 text-slate-600' }}">
                الكل
            </a>
            <a href="{{ route('videos.index', ['type' => 'video']) }}" class="px-4 py-1.5 rounded-full text-xs font-semibold {{ $type === 'video' ? 'bg-emerald-800 text-gold-300' : 'bg-white border border-slate-200 text-slate-600' }}">
                المحاضرات الكاملة
            </a>
            <a href="{{ route('videos.index', ['type' => 'short']) }}" class="px-4 py-1.5 rounded-full text-xs font-semibold {{ $type === 'short' ? 'bg-emerald-800 text-gold-300' : 'bg-white border border-slate-200 text-slate-600' }}">
                قبسات قصيرة (Shorts & Reels)
            </a>
        </div>
    </div>

    <!-- Videos Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($videos as $video)
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
            <div>
                <!-- Video Thumbnail Container -->
                <div class="relative bg-black aspect-video flex items-center justify-center group overflow-hidden">
                    @if($video->youtube_id)
                        <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/hqdefault.jpg" alt="{{ $video->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full bg-emerald-950 flex items-center justify-center text-gold-400 text-4xl">
                            <i class="fa-solid fa-play"></i>
                        </div>
                    @endif
                    <a href="{{ route('videos.show', $video->slug) }}" class="absolute inset-0 flex items-center justify-center bg-black/40 group-hover:bg-black/20 transition">
                        <div class="w-12 h-12 rounded-full bg-red-600/90 text-white flex items-center justify-center text-xl shadow-lg transform group-hover:scale-110 transition">
                            <i class="fa-solid fa-play"></i>
                        </div>
                    </a>
                    <span class="absolute bottom-2 left-2 px-2 py-0.5 rounded bg-black/70 text-white text-[11px] font-mono">
                        {{ $video->duration ?? 'Video' }}
                    </span>
                </div>

                <div class="p-5 space-y-3">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-[11px] font-bold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                            {{ $video->type === 'short' ? 'ريلز قصير' : ($video->season_year ?? 'محاضرة مرئية') }}
                        </span>
                        @if($video->soundcloud_url || true)
                        <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200/60" title="تسجيل صوتي متوفر">
                            <i class="fa-solid fa-headphones text-amber-600"></i> صـوت
                        </span>
                        @endif
                        @if($video->pdf_file || true)
                        <span class="text-[10px] font-bold text-emerald-900 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60" title="تفريغ نصي متوفر">
                            <i class="fa-solid fa-file-lines text-emerald-700"></i> تفريغ نصي
                        </span>
                        @endif
                    </div>

                    <h3 class="font-bold text-base text-slate-800 leading-snug hover:text-emerald-800 transition line-clamp-2">
                        <a href="{{ route('videos.show', $video->slug) }}">{{ $video->title }}</a>
                    </h3>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <span><i class="fa-solid fa-eye"></i> {{ $video->views_count }} مشاهدة</span>
                <a href="{{ route('videos.show', $video->slug) }}" class="font-bold text-emerald-800 hover:text-emerald-950">مشاهدة المقطع ←</a>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-slate-200 text-slate-500">
            لا توجد مقاطع مرئية متاحة حالياً.
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $videos->links() }}
    </div>

</div>
@endsection

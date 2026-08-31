@extends('layouts.app')

@section('title', $video->title . ' | شبكة العلامة المنير')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-12 space-y-8">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-emerald-800">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('videos.index') }}" class="hover:text-emerald-800">المكتبة المرئية والمحاضرات</a>
        <span>/</span>
        <span class="text-emerald-950 font-bold truncate">{{ $video->title }}</span>
    </div>

    <!-- Main Video Frame -->
    <div class="bg-black rounded-3xl overflow-hidden shadow-2xl aspect-video relative border border-slate-800">
        @if($video->youtube_id)
            <iframe src="https://www.youtube.com/embed/{{ $video->youtube_id }}?autoplay=1&rel=0" title="{{ $video->title }}" class="w-full h-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        @else
            <video src="{{ $video->media_url }}" controls class="w-full h-full"></video>
        @endif
    </div>

    <!-- Metadata & Available Formats Hub -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">

        <!-- Top Badges & Actions -->
        <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-100 text-xs">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 font-bold">
                    {{ $video->season_year ?? 'محاضرة عقائدية وفكرية' }}
                </span>
                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 font-medium">
                    <i class="fa-regular fa-clock text-slate-400 ml-1"></i> {{ $video->duration ?? '50 دقيقة' }}
                </span>
            </div>

            <div class="flex items-center gap-3 text-slate-500">
                <span><i class="fa-solid fa-eye text-slate-400 ml-1"></i> {{ number_format($video->views_count) }} مشاهدة</span>
            </div>
        </div>

        <!-- Title & Description -->
        <div class="space-y-3">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold font-scholarly text-slate-900 leading-snug">
                {{ $video->title }}
            </h1>

            @if($video->description)
            <p class="text-slate-600 text-sm leading-relaxed font-light">
                {{ $video->description }}
            </p>
            @endif
        </div>

        <!-- Available Formats Hub (وسائط المحاضرة المتوفرة) -->
        <div class="pt-4 border-t border-slate-100 space-y-4">
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-cubes text-emerald-700"></i>
                <span>وسائط المحاضرة المتوفرة (مرئيات • صوتيات • تفريغ نصي):</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                
                <!-- 1. Video Stream -->
                <div class="p-4 rounded-2xl bg-red-50/70 border border-red-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center text-lg shadow-sm shrink-0">
                        <i class="fa-solid fa-video"></i>
                    </div>
                    <div>
                        <div class="font-bold text-xs text-slate-900">البث المرئي</div>
                        <div class="text-[11px] text-red-700">شاهد بدقة عالية</div>
                    </div>
                </div>

                <!-- 2. Audio Recording -->
                <a href="{{ $video->soundcloud_url ?? 'https://soundcloud.com/almoneerorg' }}" target="_blank" class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 hover:bg-amber-100/70 transition flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg shadow-sm shrink-0 group-hover:scale-105 transition">
                        <i class="fa-solid fa-headphones"></i>
                    </div>
                    <div>
                        <div class="font-bold text-xs text-slate-900 group-hover:text-amber-900 transition">التسجيل الصوتي</div>
                        <div class="text-[11px] text-amber-800">استمع للتسجيل الصوتي ↗</div>
                    </div>
                </a>

                <!-- 3. Transcribed Document -->
                @if($video->pdf_file || true)
                <a href="{{ asset($video->pdf_file ?? 'transcripts/1448-01-01-human-nature.pdf') }}" target="_blank" download class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200/80 hover:bg-emerald-100/80 transition flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-800 text-gold-300 flex items-center justify-center text-lg shadow-sm shrink-0 group-hover:scale-105 transition">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div>
                        <div class="font-bold text-xs text-slate-900 group-hover:text-emerald-950 transition">تفريغ المحاضرة</div>
                        <div class="text-[11px] text-emerald-800">تحميل التفريغ النصي 📥</div>
                    </div>
                </a>
                @endif

            </div>
        </div>

    </div>

    <!-- Full Transcribed Text (التفريغ النصي الكامل للمحاضرة) -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-800 text-gold-300 flex items-center justify-center text-lg font-bold shadow-sm">
                    <i class="fa-solid fa-align-right"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold font-scholarly text-slate-900">التفريغ النصي الكامل للمحاضرة</h2>
                    <p class="text-xs text-slate-500">تم تفريغ المادة وتوثيقها كتابياً</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ asset($video->pdf_file ?? 'transcripts/1448-01-01-human-nature.pdf') }}" target="_blank" download class="px-4 py-2 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-gold-300 text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>تحميل الملف النصي 📥</span>
                </a>
            </div>
        </div>

        <!-- Transcript Content Area -->
        <div class="prose prose-slate max-w-none text-slate-800 text-sm sm:text-base leading-loose font-scholarly bg-amber-50/30 p-6 sm:p-8 rounded-2xl border border-amber-100/60 space-y-4">
            @if($video->transcript)
                {!! nl2br(e($video->transcript)) !!}
            @else
                <p>
                    بسم الله الرحمن الرحيم<br>
                    الحمد لله رب العالمين والصلاة والسلام على أشرف الأنبياء والمرسلين سيدنا محمد وآله الطاهرين.
                </p>
            @endif
        </div>
    </div>

    <!-- Related Lectures -->
    @if($relatedVideos->count() > 0)
    <div class="space-y-4 pt-6">
        <h3 class="text-lg font-bold font-scholarly text-slate-900">محاضرات ذات صلة</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($relatedVideos as $rel)
            <a href="{{ route('videos.show', $rel->slug) }}" class="bg-white rounded-2xl p-4 border border-slate-200 hover:border-emerald-500 hover:shadow-md transition space-y-2 block group">
                <div class="aspect-video bg-slate-900 rounded-xl overflow-hidden relative">
                    @if($rel->youtube_id)
                        <img src="https://img.youtube.com/vi/{{ $rel->youtube_id }}/hqdefault.jpg" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-500">
                            <i class="fa-solid fa-play"></i>
                        </div>
                    @endif
                </div>
                <div class="text-xs font-bold text-slate-800 line-clamp-2 group-hover:text-emerald-800 transition">
                    {{ $rel->title }}
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection

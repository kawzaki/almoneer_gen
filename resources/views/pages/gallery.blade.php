@extends('layouts.app')

@section('title', 'ألبوم وصور سماحة العلامة السيد منير الخباز')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="flex flex-wrap items-end justify-between gap-4 pb-6 border-b border-slate-200 mb-8">
        <div>
            <span class="text-xs font-bold text-gold-600 uppercase tracking-wider">معرض الصور</span>
            <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900 mt-1">ألبومات المناسبات والمجالس</h1>
        </div>
    </div>

    <!-- Albums Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($albums as $album)
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
            <div>
                <div class="h-48 bg-emerald-950 flex items-center justify-center text-gold-400 text-4xl relative">
                    <i class="fa-solid fa-images"></i>
                    <span class="absolute bottom-3 left-3 px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-mono">
                        {{ $album->items->count() }} صورة
                    </span>
                </div>

                <div class="p-6 space-y-2">
                    <span class="text-xs text-slate-400"><i class="fa-regular fa-calendar"></i> {{ $album->event_date ? $album->event_date->format('Y-m-d') : '' }}</span>
                    <h3 class="font-bold text-base text-slate-800 leading-snug hover:text-emerald-800 transition">
                        <a href="{{ route('gallery.show', $album->slug) }}">{{ $album->title }}</a>
                    </h3>
                    <p class="text-xs text-slate-500 font-light line-clamp-2 leading-relaxed">
                        {{ $album->description }}
                    </p>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                <a href="{{ route('gallery.show', $album->slug) }}" class="font-bold text-emerald-800 hover:text-emerald-950">استعراض الصور ←</a>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-slate-200 text-slate-500">
            لا توجد ألبومات صور منشورة حالياً.
        </div>
        @endforelse
    </div>

</div>
@endsection

@extends('layouts.app')

@section('title', 'ألبوم وصور سماحة العلامة السيد منير الخباز')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="flex flex-wrap items-end justify-between gap-4 pb-6 border-b border-slate-200 mb-8">
        <div>
            <span class="text-xs font-bold text-gold-600 uppercase tracking-wider">معرض الصور والمناسبات</span>
            <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900 mt-1">ألبومات الصور والمجالس والنشاطات</h1>
        </div>
        <div class="text-xs text-slate-500">
            إجمالي الألبومات: <span class="font-bold text-emerald-800">{{ $albums->total() }}</span>
        </div>
    </div>

    <!-- Albums Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($albums as $album)
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
            <div>
                <!-- الغلاف -->
                <div class="h-52 bg-slate-900 overflow-hidden relative">
                    @if($album->cover_url)
                        <img src="{{ $album->cover_url }}" alt="{{ $album->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gold-400 text-4xl bg-emerald-950">
                            <i class="fa-solid fa-images"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent"></div>
                    
                    <span class="absolute bottom-3 left-3 px-3 py-1 rounded-full bg-black/60 backdrop-blur-xs text-white text-[11px] font-mono flex items-center gap-1.5">
                        <i class="fa-regular fa-image text-gold-400"></i>
                        <span>{{ $album->items->count() }} صورة</span>
                    </span>

                    @if($album->parent)
                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-emerald-800/90 backdrop-blur-xs text-white text-[10px] font-bold shadow-xs">
                            <i class="fa-solid fa-folder text-[9px] ml-1"></i>
                            {{ $album->parent->title }}
                        </span>
                    @endif
                </div>

                <div class="p-6 space-y-2.5">
                    @if($album->event_date)
                        <span class="text-xs text-slate-400 flex items-center gap-1.5">
                            <i class="fa-regular fa-calendar text-gold-600"></i>
                            <span>{{ $album->event_date->format('Y-m-d') }}</span>
                        </span>
                    @endif

                    <h3 class="font-bold text-base text-slate-800 leading-snug group-hover:text-emerald-800 transition">
                        <a href="{{ route('gallery.show', $album->slug) }}">{{ $album->title }}</a>
                    </h3>

                    @if($album->description)
                        <p class="text-xs text-slate-500 font-light line-clamp-2 leading-relaxed">
                            {{ $album->description }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                <a href="{{ route('gallery.show', $album->slug) }}" class="font-bold text-emerald-800 hover:text-emerald-950 flex items-center gap-1.5">
                    <span>استعراض الألبوم والصور</span>
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-slate-200 text-slate-500 space-y-3">
            <i class="fa-solid fa-images text-3xl text-slate-300"></i>
            <p>لا توجد ألبومات صور منشورة حالياً.</p>
        </div>
        @endforelse
    </div>

    @if($albums->hasPages())
        <div class="mt-10">{{ $albums->links() }}</div>
    @endif

</div>
@endsection

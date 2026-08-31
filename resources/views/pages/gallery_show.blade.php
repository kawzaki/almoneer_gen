@extends('layouts.app')

@section('title', $album->title . ' | ألبوم الصور')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-emerald-800">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('gallery.index') }}" class="hover:text-emerald-800">ألبوم الصور</a>
        <span>/</span>
        <span class="text-emerald-950 font-bold truncate">{{ $album->title }}</span>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-4">
        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold">
            {{ $album->event_date ? $album->event_date->format('Y-m-d') : 'مناسبة رسمية' }}
        </span>
        <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900 leading-tight">
            {{ $album->title }}
        </h1>
        @if($album->description)
        <p class="text-sm text-slate-600 leading-relaxed font-light">
            {{ $album->description }}
        </p>
        @endif
    </div>

    <!-- Images Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($album->items as $item)
        <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm group">
            <div class="h-64 bg-slate-100 flex items-center justify-center text-slate-400 overflow-hidden">
                <i class="fa-regular fa-image text-3xl"></i>
            </div>
            @if($item->caption)
            <div class="p-3 text-xs text-slate-600 bg-slate-50 border-t border-slate-100">
                {{ $item->caption }}
            </div>
            @endif
        </div>
        @empty
        <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-slate-200 text-slate-500">
            سيتم إضافة صور هذا الألبوم قريباً.
        </div>
        @endforelse
    </div>

</div>
@endsection

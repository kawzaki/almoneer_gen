@extends('layouts.app')

@section('title', $poem->title . ' | ديوان العلامة المنير')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 space-y-8">

    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-emerald-800">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('poems.index') }}" class="hover:text-emerald-800">ديوان الشعر</a>
        <span>/</span>
        <span class="text-emerald-950 font-bold truncate">{{ $poem->title }}</span>
    </div>

    <!-- Poem Card -->
    <div class="bg-white rounded-3xl p-8 sm:p-12 border border-gold-500/40 shadow-sm space-y-8 text-center">
        
        <div class="space-y-2 pb-6 border-b border-slate-100">
            @if(!empty($poem->occasion))
                <span class="px-3 py-1 rounded-full bg-gold-50 text-gold-700 text-xs font-bold border border-gold-200 inline-block mb-2">
                    {{ $poem->occasion }}
                </span>
            @endif
            <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-emerald-950 pt-2">
                {{ $poem->title }}
            </h1>
            <p class="text-xs text-slate-400">
                نظم: سماحة العلامة السيد منير الخباز@if(!empty($poem->meter)) — البحر: {{ $poem->meter }}@endif
            </p>
        </div>

        @if($poem->description)
        <p class="text-sm text-slate-600 max-w-xl mx-auto font-light leading-relaxed">
            {{ $poem->description }}
        </p>
        @endif

        <!-- Couplets Full List -->
        <div class="space-y-4 py-6 max-w-2xl mx-auto text-base sm:text-lg font-scholarly leading-loose">
            @foreach($poem->couplets as $index => $c)
            <div class="couplet-line">
                <span class="text-slate-900 font-medium">{{ $c['first'] }}</span>
                <span class="text-gold-500 text-sm hidden sm:inline">✤</span>
                <span class="text-slate-800">{{ $c['second'] }}</span>
            </div>
            @endforeach
        </div>

        <div class="pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
            <a href="{{ route('poems.index') }}" class="text-emerald-800 font-bold hover:underline">← العودة لديوان الشعر</a>
            <span>عدد الأبيات: {{ count($poem->couplets) }}</span>
        </div>

    </div>

</div>
@endsection

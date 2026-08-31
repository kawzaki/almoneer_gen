@extends('layouts.app')

@section('title', $book->title . ' | مكتبة العلامة المنير')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 space-y-8">

    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-emerald-800">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('books.index') }}" class="hover:text-emerald-800">مكتبة الكتب</a>
        <span>/</span>
        <span class="text-emerald-950 font-bold truncate">{{ $book->title }}</span>
    </div>

    <!-- Main Book Presentation -->
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-sm space-y-8">
        
        <div class="flex flex-col sm:flex-row gap-8 items-center sm:items-start text-center sm:text-right pb-8 border-b border-slate-100">
            <div class="w-36 h-52 rounded-2xl bg-gradient-to-tr from-emerald-950 to-emerald-800 text-gold-300 flex items-center justify-center text-5xl font-scholarly flex-shrink-0 shadow-2xl border border-gold-500/40">
                <i class="fa-solid fa-book"></i>
            </div>

            <div class="space-y-3 flex-grow">
                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold">
                    إصدارات الفكر الإسلامي
                </span>
                <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900 leading-tight">
                    {{ $book->title }}
                </h1>
                <p class="text-xs text-slate-500">المؤلف: {{ $book->author }}</p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2 text-xs text-slate-600">
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] text-slate-400 block">سنة النشر:</span>
                        <span class="font-bold text-slate-800">{{ $book->publication_year ?? '2024' }}م</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] text-slate-400 block">عدد الصفحات:</span>
                        <span class="font-bold text-slate-800">{{ $book->pages_count ?? '320' }}</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] text-slate-400 block">دار النشر:</span>
                        <span class="font-bold text-slate-800 truncate block">{{ $book->publisher ?? 'دار المحجة البيضاء' }}</span>
                    </div>
                </div>

                <!-- Download / Read actions -->
                <div class="flex flex-wrap items-center gap-3 pt-4 justify-center sm:justify-start">
                    <a href="{{ route('books.download', $book->slug) }}" class="px-6 py-3 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-gold-300 font-bold text-xs shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-down text-sm"></i>
                        <span>تحميل الكتاب بصيغة PDF</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="space-y-3">
            <h3 class="font-bold text-base text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-align-left text-emerald-800"></i>
                <span>عن الكتاب ونبذة عامة:</span>
            </h3>
            <p class="text-sm text-slate-600 leading-relaxed font-light">
                {{ $book->summary }}
            </p>
        </div>

        @if($book->table_of_contents)
        <!-- Table of contents -->
        <div class="space-y-3 pt-4 border-t border-slate-100">
            <h3 class="font-bold text-base text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-emerald-800"></i>
                <span>فهرس الموضوعات والأبواب:</span>
            </h3>
            <div class="p-4 rounded-2xl bg-sand-50 border border-slate-200 text-xs sm:text-sm text-slate-700 leading-relaxed">
                {!! nl2br(e($book->table_of_contents)) !!}
            </div>
        </div>
        @endif

    </div>

</div>
@endsection

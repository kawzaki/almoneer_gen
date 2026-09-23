@extends('layouts.app')

@section('title', $book->title . ' | مكتبة العلامة المنير')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 space-y-8">

    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-emerald-800 transition">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('books.index') }}" class="hover:text-emerald-800 transition">مكتبة الكتب</a>
        <span>/</span>
        <span class="text-emerald-950 font-bold truncate">{{ $book->title }}</span>
    </div>

    <!-- Main Book Presentation -->
    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-sm space-y-8">
        
        <div class="flex flex-col sm:flex-row gap-8 items-center sm:items-start text-center sm:text-right pb-8 border-b border-slate-100">
            @if($book->cover_image)
                <!-- Interactive Cover with Zoom Lightbox Trigger -->
                <div class="relative group/cover cursor-pointer flex-shrink-0" onclick="openBookCoverModal()" title="انقر لتكبير صورة الغلاف">
                    <div class="w-40 h-56 sm:w-44 sm:h-64 rounded-2xl overflow-hidden shadow-2xl border-2 border-slate-100 bg-white transition duration-300 transform group-hover/cover:scale-102 group-hover/cover:shadow-emerald-900/10">
                        <img id="book-cover-image" 
                             src="{{ str_starts_with($book->cover_image, 'http') ? $book->cover_image : asset($book->cover_image) }}"
                             alt="{{ $book->title }}"
                             class="w-full h-full object-cover">
                    </div>
                    <!-- Zoom badge overlay -->
                    <div class="absolute inset-0 rounded-2xl bg-black/40 opacity-0 group-hover/cover:opacity-100 transition duration-200 flex flex-col items-center justify-center text-white gap-1.5 backdrop-blur-[2px]">
                        <span class="w-10 h-10 rounded-full bg-white/20 border border-white/40 flex items-center justify-center text-base">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                        </span>
                        <span class="text-xs font-semibold">تكبير الغلاف</span>
                    </div>
                </div>
            @else
                <div class="w-40 h-56 sm:w-44 sm:h-64 rounded-2xl bg-gradient-to-tr from-emerald-950 to-emerald-800 text-gold-300 flex items-center justify-center text-5xl font-scholarly flex-shrink-0 shadow-2xl border border-gold-500/40">
                    <i class="fa-solid fa-book"></i>
                </div>
            @endif

            <div class="space-y-4 flex-grow w-full">
                <div>
                    <span class="px-3.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold inline-block mb-2">
                        {{ $book->category->name ?? 'إصدارات ومؤلفات' }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900 leading-tight">
                        {{ $book->title }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">المؤلف: <span class="text-slate-800 font-semibold">{{ $book->author }}</span></p>
                </div>

                <!-- Technical & Publishing Specs (Only if provided) -->
                @php
                    $hasMeta = $book->publication_year || $book->pages_count || $book->publisher || $book->deposit_number || $book->isbn;
                @endphp

                @if($hasMeta)
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 pt-1 text-xs text-slate-600">
                    @if($book->publication_year)
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] text-slate-400 block font-normal">سنة النشر:</span>
                        <span class="font-bold text-slate-800">{{ $book->publication_year }}م</span>
                    </div>
                    @endif

                    @if($book->pages_count)
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] text-slate-400 block font-normal">عدد الصفحات:</span>
                        <span class="font-bold text-slate-800">{{ $book->pages_count }} صفحة</span>
                    </div>
                    @endif

                    @if($book->publisher)
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] text-slate-400 block font-normal">دار النشر:</span>
                        <span class="font-bold text-slate-800 truncate block" title="{{ $book->publisher }}">{{ $book->publisher }}</span>
                    </div>
                    @endif

                    @if($book->deposit_number)
                    <div class="p-2.5 rounded-xl bg-amber-50/60 border border-amber-200/70">
                        <span class="text-[10px] text-amber-700 block font-normal flex items-center gap-1">
                            <i class="fa-solid fa-stamp text-[10px]"></i>
                            <span>رقم الإيداع:</span>
                        </span>
                        <span class="font-bold text-slate-800">{{ $book->deposit_number }}</span>
                    </div>
                    @endif

                    @if($book->isbn)
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 sm:col-span-2">
                        <span class="text-[10px] text-slate-400 block font-normal flex items-center gap-1">
                            <i class="fa-solid fa-barcode text-[10px]"></i>
                            <span>الترقيم الدولي (ردمك / ISBN):</span>
                        </span>
                        <span class="font-bold text-slate-800 font-mono text-[11px] dir-ltr block text-right sm:text-left">{{ $book->isbn }}</span>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Download / Read actions -->
                <div class="flex flex-wrap items-center gap-3 pt-3 justify-center sm:justify-start">
                    @if($book->pdf_file)
                    <a href="{{ route('books.download', $book->slug) }}" class="px-6 py-3 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-gold-300 font-bold text-xs sm:text-sm shadow-md transition flex items-center gap-2 transform active:scale-98">
                        <i class="fa-solid fa-cloud-arrow-down text-base"></i>
                        <span>تحميل الكتاب بصيغة PDF</span>
                    </a>
                    @endif
                    @if($book->buy_url)
                    <a href="{{ $book->buy_url }}" target="_blank" rel="noopener noreferrer" class="px-5 py-3 rounded-xl bg-gold-50 hover:bg-gold-100 text-gold-900 border border-gold-300 font-bold text-xs sm:text-sm transition flex items-center gap-2">
                        <i class="fa-solid fa-cart-shopping text-base text-gold-700"></i>
                        <span>طلب واقتناء الكتاب</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        @if($book->summary)
        <!-- Book Summary (Styled Elegantly) -->
        <div class="space-y-3">
            <div class="flex items-center gap-2 text-slate-800 pb-1">
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-sm shadow-xs border border-emerald-100">
                    <i class="fa-solid fa-book-open-reader"></i>
                </span>
                <h3 class="font-bold text-base text-slate-900">نبذة عن الكتاب:</h3>
            </div>
            
            <div class="relative p-6 sm:p-8 rounded-3xl bg-gradient-to-br from-sand-50/70 via-white to-amber-50/20 border border-amber-100 shadow-xs">
                <div class="text-sm sm:text-base text-slate-700 leading-loose font-normal whitespace-pre-line text-justify">
                    {{ $book->summary }}
                </div>
            </div>
        </div>
        @endif

        @if($book->table_of_contents)
        <!-- Table of contents -->
        <div class="space-y-3 pt-4 border-t border-slate-100">
            <div class="flex items-center gap-2 text-slate-800 pb-1">
                <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-sm shadow-xs border border-slate-200">
                    <i class="fa-solid fa-list-check"></i>
                </span>
                <h3 class="font-bold text-base text-slate-900">فهرس الموضوعات والأبواب:</h3>
            </div>
            <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line font-light">
                {{ $book->table_of_contents }}
            </div>
        </div>
        @endif

    </div>

</div>

@if($book->cover_image)
<!-- Modal: Lightbox Cover Zoom -->
<div id="bookCoverModal" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-sm hidden items-center justify-center p-4 transition-all duration-300" onclick="handleCoverModalBackdrop(event)">
    <div class="relative max-w-2xl max-h-[90vh] flex flex-col items-center">
        <!-- Close Button -->
        <button type="button" onclick="closeBookCoverModal()" class="absolute -top-12 left-0 sm:left-auto sm:-right-12 w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition shadow-lg focus:outline-none" title="إغلاق">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <!-- High-res Image -->
        <div class="rounded-2xl overflow-hidden shadow-2xl border-2 border-white/20 bg-slate-950 max-h-[80vh] flex items-center justify-center">
            <img src="{{ str_starts_with($book->cover_image, 'http') ? $book->cover_image : asset($book->cover_image) }}" 
                 alt="{{ $book->title }}" 
                 class="max-h-[80vh] max-w-full object-contain">
        </div>

        <p class="text-white/80 text-xs sm:text-sm font-semibold mt-3 text-center px-4">
            {{ $book->title }}
        </p>
    </div>
</div>

<script>
    function openBookCoverModal() {
        const modal = document.getElementById('bookCoverModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeBookCoverModal() {
        const modal = document.getElementById('bookCoverModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }

    function handleCoverModalBackdrop(e) {
        if (e.target.id === 'bookCoverModal') {
            closeBookCoverModal();
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeBookCoverModal();
        }
    });
</script>
@endif
@endsection

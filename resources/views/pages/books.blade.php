@extends('layouts.app')

@section('title', 'كتب ومؤلفات سماحة السيد منير الخباز')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="flex flex-wrap items-end justify-between gap-4 pb-6 border-b border-slate-200 mb-8">
            <div>
                <span class="text-xs font-bold text-gold-600 uppercase tracking-wider">مكتبة المؤلفات</span>
                <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900 mt-1">مؤلفات سماحة السيد منير الخباز
                </h1>
            </div>

            <!-- Categories filter -->
            <div class="flex items-center gap-2 overflow-x-auto">
                <a href="{{ route('books.index') }}"
                    class="px-3.5 py-1.5 rounded-full text-xs font-semibold {{ !request('category') ? 'bg-emerald-800 text-gold-300' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    كافة المؤلفات
                </a>
                @if(isset($categories))
                    @foreach($categories as $cat)
                        <a href="{{ route('books.index', ['category' => $cat->slug]) }}"
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold {{ request('category') == $cat->slug ? 'bg-emerald-800 text-gold-300' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                @endif
                <a href="{{ route('poems.index') }}"
                    class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-gold-50 border border-gold-300 text-gold-800 hover:bg-gold-100 flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-feather-pointed text-[11px] text-gold-600"></i>
                    <span>ديوان القصائد المنظومة</span>
                </a>
            </div>
        </div>

        <!-- Books Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($books as $book)
                <div
                    class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-6">
                    <div class="flex gap-5">
                        <!-- Book Cover / Mockup -->
                        <a href="{{ route('books.show', $book->slug) }}" class="flex-shrink-0 block group/cover">
                            @if($book->cover_image)
                                <img src="{{ str_starts_with($book->cover_image, 'http') ? $book->cover_image : asset($book->cover_image) }}"
                                    alt="{{ $book->title }}"
                                    class="w-20 h-28 rounded-xl object-cover shadow-lg border border-slate-200 group-hover/cover:scale-105 transition duration-300">
                            @else
                                <div
                                    class="w-20 h-28 rounded-xl bg-gradient-to-tr from-emerald-950 to-emerald-800 text-gold-300 flex items-center justify-center text-3xl font-scholarly shadow-lg border border-gold-500/30 group-hover/cover:scale-105 transition duration-300">
                                    <i class="fa-solid fa-book-open"></i>
                                </div>
                            @endif
                        </a>

                        <div class="space-y-1 min-w-0">
                            <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 text-[10px] font-bold">
                                {{ $book->publication_year ?? '2024' }}م
                            </span>
                            <h3
                                class="font-bold text-base text-slate-900 leading-snug hover:text-emerald-800 transition line-clamp-2">
                                <a href="{{ route('books.show', $book->slug) }}">{{ $book->title }}</a>
                            </h3>
                            <p class="text-xs text-slate-400">{{ $book->publisher ?? 'دار المحجة البيضاء' }}</p>
                            @if($book->pages_count)
                                <p class="text-[11px] text-slate-400">{{ $book->pages_count }} صفحة</p>
                            @endif
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed font-light">
                        {{ $book->summary }}
                    </p>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('books.show', $book->slug) }}"
                            class="px-4 py-2 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-gold-300 font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                            <i class="fa-solid fa-book-open-reader"></i>
                            <span>تصفح وتحميل</span>
                        </a>
                        <span class="text-xs text-slate-400"><i class="fa-solid fa-download"></i> {{ $book->download_count }}
                            تنزيل</span>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-slate-200 text-slate-500">
                    لا توجد كتب مضافة في المكتبة حالياً.
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $books->links() }}
        </div>

    </div>
@endsection
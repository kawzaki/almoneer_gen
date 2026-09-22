@extends('layouts.app')

@section('title', 'سماحة السيد منير الخباز | الموقع الرسمي')

@section('content')

    <!-- 1. Hero Showcase Section -->
    <section class="relative islamic-pattern text-white overflow-hidden py-12 md:py-20 border-b border-gold-500/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">

                <!-- Hero Content (7 Cols) -->
                <div class="lg:col-span-7 space-y-6 text-right">

                    <div
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-950/80 border border-gold-400/40 text-gold-300 text-xs font-medium backdrop-blur-sm shadow-md">
                        <span class="w-2 h-2 rounded-full bg-gold-400 animate-pulse"></span>
                        <span>الموسم العاشورائي ١٤٤٧هـ - المنظومة الأخلاقية</span>
                    </div>

                    <h1
                        class="text-xl sm:text-2xl md:text-3xl font-bold font-scholarly leading-snug tracking-wide text-white">
                        @if($featuredArticle)
                            {{ $featuredArticle->title }}
                        @else
                            سماحة السيد منير الخباز
                        @endif
                    </h1>

                    <p class="text-sm md:text-base text-slate-200 leading-relaxed max-w-2xl font-light">
                        @if($featuredArticle && $featuredArticle->summary)
                            {{ $featuredArticle->summary }}
                        @else
                            المنصة العامة والفكرية المخصصة للمحاضرات العامة، مؤلفات الفكر الإسلامي، ديوان الشعر، الفتاوى
                            والاستفسارات، والأنشطة التبليغية في العالم.
                        @endif
                    </p>

                    <!-- Hero Actions -->
                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        @if($featuredArticle)
                            <a href="{{ route('news.show', $featuredArticle->slug) }}"
                                class="px-6 py-3 rounded-xl bg-gradient-to-r from-gold-400 via-gold-500 to-gold-600 hover:from-gold-300 hover:to-gold-500 text-emerald-950 font-bold text-sm shadow-xl hover:shadow-gold-500/20 transition-all transform active:scale-95 flex items-center gap-2">
                                <i class="fa-solid fa-newspaper"></i>
                                <span>قراءة تفاصيل الخبر</span>
                            </a>
                        @endif
                    </div>

                </div>

                <!-- Hero Portrait Card (5 Cols) -->
                <div class="lg:col-span-5 flex justify-center">
                    <div class="relative w-full max-w-sm">
                        <!-- Glowing Gold/Emerald Background Ambient Glow -->
                        <div
                            class="absolute -inset-2 bg-gradient-to-r from-gold-500/40 via-emerald-500/30 to-gold-600/40 rounded-[2.5rem] blur-xl opacity-60">
                        </div>

                        <div
                            class="relative bg-gradient-to-b from-[#083b45] to-[#041f25] rounded-[2rem] p-7 border-2 border-gold-500/50 shadow-2xl text-center space-y-5 backdrop-blur-md">

                            <!-- High-resolution dignified portrait of Sayyid Muneer -->
                            <div class="relative mx-auto w-44 h-44 sm:w-52 sm:h-52">
                                <div
                                    class="absolute -inset-1.5 bg-gradient-to-tr from-gold-400 via-gold-500 to-emerald-500 rounded-full blur opacity-75 animate-pulse">
                                </div>
                                <div
                                    class="relative w-full h-full rounded-full p-1.5 bg-gradient-to-tr from-gold-300 via-gold-500 to-emerald-800 shadow-2xl overflow-hidden border-2 border-gold-400">
                                    <div
                                        class="w-full h-full rounded-full overflow-hidden bg-gradient-to-b from-[#083b45] to-[#041f25]">
                                        <img src="{{ asset('images/sayyid-muneer-portrait.png') }}"
                                            alt="سماحة العلامة السيد منير الخباز"
                                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <h3 class="text-xl sm:text-2xl font-bold font-scholarly text-gold-300 drop-shadow">سماحة
                                    العلامة السيد منير الخباز</h3>
                                <p class="text-xs text-slate-200 font-light">أستاذ البحث الخارج في الحوزة العلمية </p>
                            </div>

                            <div class="pt-2">
                                <a href="{{ route('bio') }}"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gold-500/15 hover:bg-gold-500/25 border border-gold-500/40 text-gold-300 hover:text-white text-xs font-semibold transition shadow-sm hover:shadow-gold-500/10">
                                    <span>استعراض السيرة الذاتية المفصلة</span>
                                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. Wisdom of the Week & Hawza Portal Cross-Banner -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

            <!-- Weekly Wisdom Card (7 cols) -->
            <div class="md:col-span-7 bg-white rounded-2xl p-6 border border-slate-200 shadow-lg flex items-center gap-5">
                <div
                    class="w-14 h-14 rounded-2xl bg-gold-50 text-gold-600 flex items-center justify-center text-2xl flex-shrink-0 border border-gold-200">
                    <i class="fa-solid fa-quote-right"></i>
                </div>
                <div class="space-y-1 flex-grow">
                    <span
                        class="text-[11px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                        {{ $weeklyWisdom->title ?? 'كلمة الأسبوع' }}
                    </span>
                    <p class="text-sm sm:text-base font-bold font-scholarly text-slate-800 leading-relaxed pt-1">
                        "{{ $weeklyWisdom->quote ?? 'عليك بالرضا في الشدة والرخاء' }}"
                    </p>
                    <p class="text-xs text-slate-500">
                        — {{ $weeklyWisdom->source ?? 'أمير المؤمنين الإمام علي (ع)' }}
                    </p>
                </div>
            </div>

            <!-- Hawza Academic Gateway Card (5 cols) -->
            <div
                class="md:col-span-5 bg-gradient-to-br from-emerald-950 to-emerald-900 rounded-2xl p-6 border border-gold-500/40 shadow-lg text-white flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span
                        class="px-2.5 py-0.5 rounded-full bg-gold-500/20 text-gold-300 text-[11px] font-bold border border-gold-500/30">
                        منظومة الدراسات التخصصية
                    </span>
                    <i class="fa-solid fa-graduation-cap text-gold-400 text-xl"></i>
                </div>
                <div class="py-2">
                    <h4 class="font-scholarly text-lg font-bold text-white">بوابة الدروس الحوزوية والبحث الخارج</h4>
                    <p class="text-xs text-slate-300 mt-0.5">أبحاث الفقه والأصول والتفسير، المقررات، وجداول الحلقات العلمية.
                    </p>
                </div>
                <a href="{{ $hawzaPortalUrl }}" target="_blank"
                    class="text-xs text-gold-300 font-bold hover:underline flex items-center gap-1">
                    <span>الدخول إلى بوابة الدروس المتخصصة</span>
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                </a>
            </div>

        </div>
    </section>

    <!-- 3. Audio & Video Library Section (Latest YouTube Uploads from Alm0neer1) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900">جديد المحاضرات</h2>
            </div>

            <div class="flex items-center gap-2">

                <a href="{{ route('lectures.index') }}"
                    class="px-4 py-2 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-layer-group text-gold-400"></i>
                    <span>أرشيف المحاضرات والمواسم</span>
                </a>
            </div>
        </div>

        <!-- YouTube Latest Videos Grid -->
        @if(!empty($latestYouTubeVideos) && count($latestYouTubeVideos) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach($latestYouTubeVideos as $yt)
                    <div
                        class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                        <div>
                            <!-- Video Thumbnail Container -->
                            <div class="relative bg-black aspect-video flex items-center justify-center overflow-hidden">
                                <img src="{{ $yt->thumbnail }}" alt="{{ $yt->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <a href="{{ $yt->url }}" target="_blank"
                                    class="absolute inset-0 flex items-center justify-center bg-black/40 group-hover:bg-black/20 transition">
                                    <div
                                        class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center text-xl shadow-lg transform group-hover:scale-110 transition">
                                        <i class="fa-solid fa-play"></i>
                                    </div>
                                </a>
                                <span
                                    class="absolute bottom-2 left-2 px-2 py-0.5 rounded bg-black/80 text-white text-[11px] font-mono flex items-center gap-1">
                                    <i class="fa-brands fa-youtube text-red-500"></i>
                                    <span>YouTube</span>
                                </span>
                            </div>

                            <div class="p-5 space-y-2">
                                <div class="flex items-center justify-between text-xs text-slate-400">
                                    <span class="text-emerald-800 font-semibold flex items-center gap-1">
                                        <i class="fa-regular fa-clock"></i>
                                        <span>{{ $yt->published_at ? $yt->published_at->diffForHumans() : 'مؤخراً' }}</span>
                                    </span>
                                </div>
                                <h3
                                    class="font-bold text-sm sm:text-base text-slate-800 leading-snug hover:text-red-600 transition line-clamp-2">
                                    <a href="{{ $yt->url }}" target="_blank">{{ $yt->title }}</a>
                                </h3>
                                @if(!empty($yt->description))
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed font-light">
                                        {{ strip_tags($yt->description) }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                            <a href="{{ $yt->url }}" target="_blank"
                                class="font-bold text-red-600 hover:text-red-700 flex items-center gap-1">
                                <span>مشاهدة على يوتيوب</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </section>

    <!-- 4. Books & Poetry Dual Showcase -->
    <section class="bg-sand-100 border-y border-slate-200 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

                <!-- Books Showcase (7 Cols) -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-gold-600 uppercase tracking-wider">مكتبة المؤلفات</span>
                            <h2 class="text-2xl font-bold font-scholarly text-slate-900">كتب وإصدارات سماحة العلامة</h2>
                        </div>
                        <a href="{{ route('books.index') }}" class="text-xs font-bold text-emerald-800 hover:underline">عرض
                            كل الكتب ←</a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($featuredBooks as $book)
                            <div
                                class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex gap-4 hover:shadow-md transition">
                                <!-- Book Icon / Cover placeholder -->
                                @if($book->cover_image)
                                    <a href="{{ route('books.show', $book->slug) }}" class="flex-shrink-0">
                                        <img src="{{ str_starts_with($book->cover_image, 'http') ? $book->cover_image : asset($book->cover_image) }}"
                                            alt="{{ $book->title }}"
                                            class="w-16 h-24 rounded-lg object-cover shadow border border-slate-200 hover:scale-105 transition duration-200">
                                    </a>
                                @else
                                    <div
                                        class="w-16 h-24 rounded-lg bg-emerald-900 text-gold-300 flex items-center justify-center flex-shrink-0 shadow text-2xl font-scholarly">
                                        <i class="fa-solid fa-book"></i>
                                    </div>
                                @endif
                                <div class="flex flex-col justify-between min-w-0">
                                    <div>
                                        <h4 class="font-bold text-xs sm:text-sm text-slate-800 truncate"
                                            title="{{ $book->title }}">{{ $book->title }}</h4>
                                        <p class="text-[11px] text-slate-400 mt-0.5">{{ $book->publisher ?? 'دار النشر' }}
                                            ({{ $book->publication_year ?? '2024' }})</p>
                                        <p class="text-xs text-slate-500 line-clamp-2 mt-1 font-light leading-relaxed">
                                            {{ $book->summary }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 pt-2">
                                        <a href="{{ route('books.show', $book->slug) }}"
                                            class="text-[11px] font-bold text-emerald-800 hover:underline">قراءة وتنزيل ←</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-500">لا توجد مؤلفات معروضة حالياً.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Poetry Diwan Showcase (5 Cols) -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-gold-600 uppercase tracking-wider">ديوان الشعر</span>
                            <h2 class="text-2xl font-bold font-scholarly text-slate-900">قصائد ولائية ووجدانية</h2>
                        </div>
                        <a href="{{ route('poems.index') }}" class="text-xs font-bold text-emerald-800 hover:underline">عرض
                            الديوان ←</a>
                    </div>

                    @if($featuredPoem)
                        <div class="bg-white rounded-2xl p-6 border border-gold-500/30 shadow-md space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                @if(!empty($featuredPoem->occasion))
                                    <span
                                        class="text-xs font-semibold px-2.5 py-0.5 rounded bg-gold-50 text-gold-700">{{ $featuredPoem->occasion }}</span>
                                @else
                                    <span></span>
                                @endif
                                @if(!empty($featuredPoem->meter))
                                    <span class="text-[11px] text-slate-400">{{ $featuredPoem->meter }}</span>
                                @endif
                            </div>

                            <h3 class="text-lg font-bold font-scholarly text-emerald-950 text-center">
                                <a href="{{ route('poems.show', $featuredPoem->slug) }}">{{ $featuredPoem->title }}</a>
                            </h3>

                            <!-- Couplets formatting -->
                            <div class="space-y-2 py-2 text-sm font-scholarly leading-loose">
                                @foreach(array_slice($featuredPoem->couplets, 0, 3) as $c)
                                    <div class="couplet-line">
                                        <span class="text-slate-800">{{ $c['first'] }}</span>
                                        <span class="text-gold-500 text-xs hidden sm:inline">✤</span>
                                        <span class="text-slate-700">{{ $c['second'] }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="pt-2 text-center">
                                <a href="{{ route('poems.show', $featuredPoem->slug) }}"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-800 hover:text-emerald-950">
                                    <span>قراءة القصيدة كاملة في الديوان</span>
                                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    <!-- 5. News & Activities Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <span class="text-xs font-bold text-gold-600 uppercase tracking-wider">المركز الإخباري</span>
                <h2 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900">أخبار سماحة السيد والنشاط التبليغي
                </h2>
            </div>
            <a href="{{ route('news.index') }}" class="text-xs font-bold text-emerald-800 hover:underline">أرشيف الأخبار
                ←</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($recentNews as $news)
                <article
                    class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div class="p-6 space-y-3">
                        <span class="text-[11px] font-bold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                            {{ $news->type === 'activity' ? 'نشاط وجولة' : 'خبر عام' }}
                        </span>
                        <h3 class="font-bold text-base text-slate-800 leading-snug hover:text-emerald-800 transition">
                            <a href="{{ route('news.show', $news->slug) }}">{{ $news->title }}</a>
                        </h3>
                        <p class="text-xs text-slate-500 font-light leading-relaxed line-clamp-3">
                            {{ $news->summary }}
                        </p>
                    </div>
                    <div
                        class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                        <span><i class="fa-regular fa-calendar"></i>
                            {{ $news->published_at ? $news->published_at->format('Y-m-d') : '' }}</span>
                        <a href="{{ route('news.show', $news->slug) }}"
                            class="font-semibold text-emerald-800 hover:text-emerald-950">التفاصيل ←</a>
                    </div>
                </article>
            @empty
                <p class="col-span-3 text-xs text-slate-500 text-center">لا توجد أخبار منشورة حالياً.</p>
            @endforelse
        </div>
    </section>

    <!-- 6. Inquiries & Q&A Banner -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div
            class="bg-gradient-to-r from-emerald-900 to-emerald-950 rounded-3xl p-8 sm:p-12 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-8 border border-gold-500/30">
            <div class="space-y-3 max-w-xl text-right">
                <span
                    class="px-3 py-1 rounded-full bg-gold-500/20 text-gold-300 text-xs font-bold border border-gold-500/30">
                    نافذة الاستفسارات والفتاوى
                </span>
                <h2 class="text-2xl sm:text-3xl font-bold font-scholarly text-white">هل لديك استفسار فكري أو مسألة فقهية؟
                </h2>
                <p class="text-xs sm:text-sm text-slate-300 font-light leading-relaxed">
                    يمكنكم إرسال استفساراتكم ومسائلكم الشرعية والفكرية ليتولى مكتب سماحة السيد الإجابة عنها، مع إمكانية
                    متابعة حالة السؤال برقم التتبع الخاص.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('inquiries.index') }}"
                    class="px-6 py-3.5 rounded-xl bg-gold-500 hover:bg-gold-400 text-emerald-950 font-bold text-xs shadow-lg transition flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>طرح استفسار جديد</span>
                </a>
                <a href="{{ route('inquiries.track') }}"
                    class="px-5 py-3.5 rounded-xl bg-emerald-800/80 hover:bg-emerald-800 text-slate-200 border border-slate-600 text-xs font-semibold transition flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>متابعة حالة استفسار</span>
                </a>
            </div>
        </div>
    </section>

@endsection
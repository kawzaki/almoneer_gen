@extends('layouts.app')

@section('title', $video->title . ' | شبكة العلامة المنير')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10 space-y-8">

        <!-- Seasonal Breadcrumbs -->
        <nav class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-emerald-800 transition">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('lectures.index') }}" class="hover:text-emerald-800 transition">المحاضرات</a>
            <span>/</span>
            <a href="{{ route('lectures.year', ['year' => $resolvedYear]) }}"
                class="hover:text-emerald-800 transition">{{ $resolvedYear }} هـ</a>
            <span>/</span>
            <a href="{{ route('lectures.season', ['year' => $resolvedYear, 'season' => $resolvedSeasonSlug]) }}"
                class="hover:text-emerald-800 transition">موسم {{ $resolvedSeasonName }}</a>
            <span>/</span>
            <span class="text-emerald-950 font-bold truncate max-w-xs sm:max-w-md">{{ $video->title }}</span>
        </nav>

        <!-- Main Video Frame / Pending Placeholder -->
        <div class="bg-black rounded-3xl overflow-hidden shadow-2xl aspect-video relative border border-slate-800">
            @if($video->hasVideo() && $video->youtube_id)
                <iframe src="https://www.youtube.com/embed/{{ $video->youtube_id }}?autoplay=1&rel=0"
                    title="{{ $video->title }}" class="w-full h-full border-0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            @elseif($video->hasVideo() && filter_var($video->media_url, FILTER_VALIDATE_URL))
                <video src="{{ $video->media_url }}" controls class="w-full h-full"></video>
            @else
                <!-- Placeholder for pending video -->
                <div class="relative w-full h-full flex items-center justify-center bg-slate-950">
                    <img src="{{ $video->thumbnail ? asset($video->thumbnail) : asset('images/video-pending-placeholder.svg') }}"
                        alt="التسجيل المرئي قيد المعالجة" class="w-full h-full object-cover opacity-60">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/60 to-black/30 flex flex-col justify-end p-6 sm:p-10 text-white space-y-3">
                        <div
                            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-gold-500/20 text-gold-300 text-xs font-bold border border-gold-500/40 w-fit backdrop-blur-md">
                            <i class="fa-solid fa-hourglass-half text-gold-400"></i>
                            <span>التسجيل المرئي قيد المونتاج والإدراج قريباً</span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-300 max-w-xl leading-relaxed">
                            التسجيل المرئي قيد المعالجة والمونتاج. يمكنك الاستماع مباشرة للتسجيل الصوتي المدمج أدناه أو فصله
                            لمشغل عائم للتصفح.
                        </p>
                        @if($video->hasAudio())
                            <div class="pt-1 flex flex-wrap items-center gap-2.5">
                                <button type="button" onclick="playLectureInlineAudio()"
                                    class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-emerald-950 font-bold text-xs sm:text-sm flex items-center gap-2 shadow-lg transition transform hover:scale-102 active:scale-98">
                                    <i class="fa-solid fa-circle-play text-base"></i>
                                    <span>استمع للتسجيل الصوتي الآن</span>
                                </button>
                                <button type="button" onclick="detachLectureAudio()"
                                    class="px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-medium flex items-center gap-2 backdrop-blur-md border border-white/20 transition">
                                    <i class="fa-solid fa-clone text-amber-300"></i>
                                    <span>فصل المشغل (مشغل عائم للتصفح)</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Embedded In-Page Audio Player Card (Inline & Detachable) -->
        @if($video->hasAudio())
            <div id="lecture-audio-box"
                class="bg-gradient-to-r from-[#072b2f] via-slate-900 to-[#072b2f] rounded-3xl p-5 sm:p-6 text-white border border-gold-500/30 shadow-xl space-y-4 transition-all duration-300">
                <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-white/10">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-2xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-headphones"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm sm:text-base text-gold-200">التسجيل الصوتي للمحاضرة</span>
                                @if($video->is_soundcloud)
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-orange-500/20 text-orange-300 border border-orange-500/30 text-[10px] font-bold">SoundCloud</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400">استمع مباشرة على الموقع بدون مغادرة الصفحة</p>
                        </div>
                    </div>

                    <!-- Player Quick Actions -->
                    <div class="flex items-center gap-2">
                        <!-- Detach to Floating Bottom Bar -->
                        <button type="button" onclick="detachLectureAudio()"
                            class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-xs text-gold-300 border border-white/15 flex items-center gap-1.5 transition"
                            title="فصل المشغل في شريط عائم سفلي أثناء تصفح الموقع">
                            <i class="fa-solid fa-clone text-[11px]"></i>
                            <span>فصل المشغل (مشغل عائم)</span>
                        </button>

                        <!-- Pop-out Mini Window -->
                        <button type="button" onclick="openLecturePopup()"
                            class="px-3 py-1.5 rounded-xl bg-gold-500/20 hover:bg-gold-500/30 text-xs text-gold-300 border border-gold-500/40 flex items-center gap-1.5 transition"
                            title="فتح نافذة مستقلة مصغرة تستمر أثناء تصفح أي صفحة">
                            <i class="fa-solid fa-up-right-from-square text-[11px]"></i>
                            <span class="hidden sm:inline">نافذة مستقلة</span>
                        </button>

                        @if($video->soundcloud_url)
                            <a href="{{ $video->soundcloud_url }}" target="_blank" rel="noopener noreferrer"
                                class="p-2 rounded-xl bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition"
                                title="فتح في ساوندكلاود">
                                <i class="fa-brands fa-soundcloud"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Player Frame / Widget -->
                <div id="inline-audio-player-frame" class="rounded-2xl overflow-hidden bg-black/40 border border-white/10 p-1">
                    @if($video->is_soundcloud)
                        <iframe id="sc-inline-widget" width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay"
                            src="{{ $video->soundcloud_embed_url }}" class="w-full rounded-xl"></iframe>
                    @else
                        <div class="p-4 flex flex-col sm:flex-row items-center gap-4">
                            <audio id="local-lecture-audio" controls class="w-full">
                                <source src="{{ $video->effective_audio_url }}">
                                متصفحك لا يدعم تشغيل الصوت المباشر.
                            </audio>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Metadata & Available Formats Hub -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">

            <!-- Top Badges & Actions -->
            <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-100 text-xs">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('lectures.season', ['year' => $resolvedYear, 'season' => $resolvedSeasonSlug]) }}"
                        class="px-3 py-1 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-900 font-bold transition flex items-center gap-1.5">
                        <i class="fa-solid fa-calendar-day text-gold-500"></i>
                        <span>موسم {{ $resolvedSeasonName }} {{ $resolvedYear }} هـ</span>
                    </a>

                    @if($video->lecture_number)
                        <span class="px-3 py-1 rounded-full bg-gold-500 text-emerald-950 font-bold">
                            الليلة {{ $video->lecture_number }}
                        </span>
                    @endif

                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 font-medium">
                        <i class="fa-regular fa-clock text-slate-400 ml-1"></i> {{ $video->duration ?? '50 دقيقة' }}
                    </span>
                </div>

                <div class="flex items-center gap-3 text-slate-500">
                    <span><i class="fa-solid fa-eye text-slate-400 ml-1"></i> {{ number_format($video->views_count) }}
                        مشاهدة</span>
                </div>
            </div>

            <!-- Title & Description -->
            <div class="space-y-3">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold font-scholarly text-slate-900 leading-snug">
                    {{ $video->title }}
                </h1>

                @if($video->description)
                    <div class="prose prose-slate max-w-none text-slate-600 text-sm leading-relaxed font-light">
                        {!! $video->description !!}
                    </div>
                @endif

                <!-- Lecture Tags Section (Clickable) -->
                @if(!empty($video->tags_list) && count($video->tags_list) > 0)
                    <div class="pt-3 flex flex-wrap items-center gap-2">
                        <span class="text-xs font-bold text-slate-500 flex items-center gap-1.5 ml-1">
                            <i class="fa-solid fa-tags text-gold-500"></i>
                            <span>الكلمات الدلالية:</span>
                        </span>
                        @foreach($video->tags_list as $tag)
                            <a href="{{ route('lectures.index', ['tag' => $tag]) }}"
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-slate-50 hover:bg-emerald-50 text-slate-700 hover:text-emerald-900 text-xs border border-slate-200 hover:border-emerald-300 transition duration-150 group shadow-2xs"
                                title="البحث عن محاضرات موسومة بـ {{ $tag }}">
                                <span class="text-emerald-700/60 group-hover:text-emerald-700 font-bold">#</span>
                                <span>{{ $tag }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Available Formats Hub (وسائط المحاضرة المتوفرة: مرئيات • صوتيات • تفريغ نصي) -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-cubes text-emerald-700"></i>
                    <span>وسائط المحاضرة المتوفرة:</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                    <!-- 1. Video Stream -->
                    @if($video->hasVideo())
                        <div class="p-4 rounded-2xl bg-red-50/70 border border-red-100 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center text-lg shadow-sm shrink-0">
                                <i class="fa-solid fa-video"></i>
                            </div>
                            <div>
                                <div class="font-bold text-xs text-slate-900">البث المرئي</div>
                                <div class="text-[11px] text-red-700">شاهد أعلاه بدقة عالية</div>
                            </div>
                        </div>
                    @else
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center gap-3 opacity-75">
                            <div
                                class="w-10 h-10 rounded-xl bg-slate-200 text-slate-500 flex items-center justify-center text-lg shadow-sm shrink-0">
                                <i class="fa-solid fa-video-slash"></i>
                            </div>
                            <div>
                                <div class="font-bold text-xs text-slate-700">البث المرئي (قريباً)</div>
                                <div class="text-[11px] text-slate-500">قيد المونتاج والإدراج</div>
                            </div>
                        </div>
                    @endif

                    <!-- 2. Audio Recording (Inline Embedded & Detachable) -->
                    @if($video->hasAudio())
                        <div
                            class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200/90 flex flex-col justify-between gap-3 group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg shadow-sm shrink-0 group-hover:scale-105 transition">
                                        <i class="fa-solid fa-headphones"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-xs text-slate-900 group-hover:text-amber-900 transition">
                                            التسجيل الصوتي</div>
                                        <div class="text-[11px] text-amber-800">مشغل مدمج بالموقع</div>
                                    </div>
                                </div>
                                <span
                                    class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">متوفر</span>
                            </div>
                            <div class="flex items-center gap-2 pt-2 border-t border-amber-200/60 text-xs">
                                <button type="button" onclick="playLectureInlineAudio()"
                                    class="flex-1 py-1.5 px-2.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-bold transition flex items-center justify-center gap-1.5 shadow-2xs">
                                    <i class="fa-solid fa-play text-[10px]"></i>
                                    <span>تشغيل بالموقع</span>
                                </button>
                                <button type="button" onclick="detachLectureAudio()" title="فصل المشغل في شريط عائم للتصفح"
                                    class="py-1.5 px-2.5 rounded-lg bg-white hover:bg-amber-100 border border-amber-300 text-amber-900 font-medium transition flex items-center gap-1">
                                    <i class="fa-solid fa-clone text-[10px]"></i>
                                    <span>مشغل عائم</span>
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center gap-3 opacity-75">
                            <div
                                class="w-10 h-10 rounded-xl bg-slate-200 text-slate-500 flex items-center justify-center text-lg shadow-sm shrink-0">
                                <i class="fa-solid fa-headphones-simple"></i>
                            </div>
                            <div>
                                <div class="font-bold text-xs text-slate-700">التسجيل الصوتي (غير متوفر)</div>
                                <div class="text-[11px] text-slate-500">جاري توفير التسجيل</div>
                            </div>
                        </div>
                    @endif

                    <!-- 3. Transcribed Document -->
                    <a href="#transcript-section"
                        class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200/80 hover:bg-emerald-100/80 transition flex items-center gap-3 group">
                        <div
                            class="w-10 h-10 rounded-xl bg-emerald-800 text-gold-300 flex items-center justify-center text-lg shadow-sm shrink-0 group-hover:scale-105 transition">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                        <div>
                            <div class="font-bold text-xs text-slate-900 group-hover:text-emerald-950 transition">التفريغ
                                النصي</div>
                            <div class="text-[11px] text-emerald-800">قراءة النص بالرسم العثماني ↓</div>
                        </div>
                    </a>

                </div>
            </div>

            <!-- Next / Previous Lectures in Season Navigation -->
            @if($previousLecture || $nextLecture)
                <div class="pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    @if($previousLecture)
                        <a href="{{ route('lectures.show', ['year' => $resolvedYear, 'season' => $resolvedSeasonSlug, 'slug' => $previousLecture->slug]) }}"
                            class="p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 transition flex items-center gap-3 group">
                            <i class="fa-solid fa-arrow-right text-slate-400 group-hover:text-emerald-800"></i>
                            <div class="min-w-0">
                                <span class="text-[10px] text-slate-400 block">المحاضرة السابقة (الليلة
                                    {{ $previousLecture->lecture_number }})</span>
                                <span
                                    class="font-bold text-slate-800 group-hover:text-emerald-950 truncate block">{{ $previousLecture->title }}</span>
                            </div>
                        </a>
                    @else
                        <div></div>
                    @endif

                    @if($nextLecture)
                        <a href="{{ route('lectures.show', ['year' => $resolvedYear, 'season' => $resolvedSeasonSlug, 'slug' => $nextLecture->slug]) }}"
                            class="p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 transition flex items-center justify-end text-left gap-3 group">
                            <div class="min-w-0">
                                <span class="text-[10px] text-slate-400 block text-right">المحاضرة التالية (الليلة
                                    {{ $nextLecture->lecture_number }})</span>
                                <span
                                    class="font-bold text-slate-800 group-hover:text-emerald-950 truncate block text-right">{{ $nextLecture->title }}</span>
                            </div>
                            <i class="fa-solid fa-arrow-left text-slate-400 group-hover:text-emerald-800"></i>
                        </a>
                    @endif
                </div>
            @endif

        </div>

        <!-- Full Transcribed Text (التفريغ النصي الكامل للمحاضرة) -->
        <div id="transcript-section" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-2xl bg-emerald-800 text-gold-300 flex items-center justify-center text-lg font-bold shadow-sm">
                        <i class="fa-solid fa-align-right"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold font-scholarly text-slate-900">التفريغ النصي للمحاضرة</h2>
                        <p class="text-xs text-slate-500">المحاضرة المكتوبة منقحة في بعض المواضع تحت إشراف سماحة السيدولذا
                            ستجد بعض
                            التفاوت عن المحاضرة المسموعة</p>
                    </div>
                </div>

                @push('styles')
                    <style>
                        #transcript-body {
                            --transcript-font-size: 18px;
                        }

                        #transcript-body p,
                        #transcript-body .transcript-p {
                            font-size: var(--transcript-font-size) !important;
                            line-height: 2.2 !important;
                        }

                        #transcript-body .quran-verse {
                            font-size: calc(var(--transcript-font-size) * 1.35) !important;
                            line-height: 2.4 !important;
                        }

                        #transcript-body .quran-ref {
                            font-size: calc(var(--transcript-font-size) * 0.75) !important;
                        }
                    </style>
                @endpush

                <div class="flex flex-wrap items-center gap-2">
                    <!-- Reading Controls -->
                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs text-slate-700">
                        <button type="button" onclick="changeFontSize(-1)"
                            class="px-2 py-1 hover:bg-white rounded-lg transition font-bold" title="تصغير حجم خط القراءة">
                            <span>A-</span>
                        </button>
                        <span id="font-size-display"
                            class="px-1 text-[11px] font-mono text-slate-500 font-semibold min-w-[32px] text-center">18px</span>
                        <button type="button" onclick="changeFontSize(1)"
                            class="px-2 py-1 hover:bg-white rounded-lg transition font-bold" title="تكبير حجم خط القراءة">
                            <span>A+</span>
                        </button>
                        <div class="h-4 w-[1px] bg-slate-300/60 mx-1"></div>
                        <button type="button" onclick="copyTranscript()" id="copy-btn"
                            class="px-2.5 py-1 hover:bg-white rounded-lg transition flex items-center gap-1 text-[11px]"
                            title="نسخ النص بالكامل">
                            <i class="fa-regular fa-copy"></i>
                            <span>نسخ</span>
                        </button>
                    </div>

                    @if(!empty($video->pdf_file))
                        <a href="{{ asset($video->pdf_file) }}" target="_blank" download
                            class="px-3.5 py-1.5 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-gold-300 text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-file-pdf"></i>
                            <span>تحميل الملف النصي 📥</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Transcript Content Area (with Uthmanic Quranic Font) -->
            <div id="transcript-body"
                class="prose prose-slate max-w-none text-slate-800 leading-loose font-scholarly bg-amber-50/20 p-6 sm:p-10 rounded-2xl border border-amber-100/60 transition-all">
                @if(!empty($video->transcript))
                    {!! \App\Services\TranscriptFormatter::format($video->transcript) !!}
                @else
                    <p class="text-slate-400 text-center py-6 text-sm font-sans">التفريغ النصي لهذه المحاضرة قيد التدقيق وسيتم
                        إدراجه قريباً إن شاء الله.</p>
                @endif
            </div>
        </div>

        <!-- Related Lectures from the Same Season -->
        @if($relatedVideos->count() > 0)
            <div class="space-y-4 pt-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold font-scholarly text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-gold-500"></i>
                        <span>محاضرات أخرى من موسم {{ $resolvedSeasonName }} {{ $resolvedYear }} هـ</span>
                    </h3>
                    <a href="{{ route('lectures.season', ['year' => $resolvedYear, 'season' => $resolvedSeasonSlug]) }}"
                        class="text-xs font-bold text-emerald-800 hover:underline">
                        كافة محاضرات الموسم ←
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($relatedVideos as $rel)
                        <a href="{{ route('lectures.show', ['year' => $resolvedYear, 'season' => $resolvedSeasonSlug, 'slug' => $rel->slug]) }}"
                            class="bg-white rounded-2xl p-4 border border-slate-200 hover:border-emerald-500 hover:shadow-md transition space-y-2 block group">
                            <div class="relative bg-slate-900 rounded-xl overflow-hidden aspect-video">
                                <img src="{{ $rel->thumbnail ?? ($rel->youtube_id ? 'https://img.youtube.com/vi/' . $rel->youtube_id . '/mqdefault.jpg' : asset('images/default-image.jpg')) }}"
                                    alt="{{ $rel->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @if($rel->lecture_number)
                                    <span
                                        class="absolute top-2 right-2 px-2 py-0.5 rounded bg-gold-500 text-emerald-950 text-[10px] font-bold">
                                        الليلة {{ $rel->lecture_number }}
                                    </span>
                                @endif
                            </div>
                            <h4
                                class="font-bold text-xs sm:text-sm text-slate-800 group-hover:text-emerald-900 line-clamp-2 leading-snug">
                                {{ $rel->title }}
                            </h4>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    @push('scripts')
        <script>
            let currentFontSize = parseInt(localStorage.getItem('lecture_font_size')) || 18;

            function applyFontSize(size) {
                const body = document.getElementById('transcript-body');
                const display = document.getElementById('font-size-display');
                if (body) {
                    body.style.setProperty('--transcript-font-size', size + 'px');
                }
                if (display) {
                    display.innerText = size + 'px';
                }
            }

            function changeFontSize(delta) {
                currentFontSize = Math.min(32, Math.max(14, currentFontSize + delta * 2));
                applyFontSize(currentFontSize);
                localStorage.setItem('lecture_font_size', currentFontSize);
            }

            document.addEventListener('DOMContentLoaded', () => {
                applyFontSize(currentFontSize);
            });

            function copyTranscript() {
                const text = document.getElementById('transcript-body').innerText;
                navigator.clipboard.writeText(text).then(() => {
                    const btn = document.getElementById('copy-btn');
                    const originalHtml = btn.innerHTML;
                    btn.innerHTML = '<i class="fa-solid fa-check text-emerald-600"></i> تم النسخ!';
                    setTimeout(() => {
                        btn.innerHTML = originalHtml;
                    }, 2500);
                });
            }

            // Lecture Audio Controls
            const lectureAudioData = {
                url: @json($video->effective_audio_url),
                embedUrl: @json($video->soundcloud_embed_url),
                title: @json($video->title),
                isSoundcloud: @json($video->is_soundcloud ? true : false)
            };

            function playLectureInlineAudio() {
                const box = document.getElementById('lecture-audio-box');
                if (box) {
                    box.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    box.classList.add('ring-4', 'ring-gold-400/50');
                    setTimeout(() => box.classList.remove('ring-4', 'ring-gold-400/50'), 1500);
                }
                const localAudio = document.getElementById('local-lecture-audio');
                if (localAudio) {
                    localAudio.play();
                }
            }

            function detachLectureAudio() {
                if (!lectureAudioData.url) return;
                if (typeof playGlobalAudio === 'function') {
                    playGlobalAudio(
                        lectureAudioData.url,
                        lectureAudioData.title,
                        lectureAudioData.isSoundcloud ? 'soundcloud' : 'audio'
                    );
                }
            }

            function openLecturePopup() {
                if (!lectureAudioData.url) return;
                const popupUrl = "{{ route('player.popup') }}?url=" + encodeURIComponent(lectureAudioData.url) +
                    "&title=" + encodeURIComponent(lectureAudioData.title) +
                    "&type=" + (lectureAudioData.isSoundcloud ? 'soundcloud' : 'audio');
                window.open(popupUrl, 'AlmoneerAudioPlayer', 'width=480,height=280,status=no,toolbar=no,menubar=no,location=no,resizable=yes');
            }
        </script>
    @endpush
@endsection
@extends('layouts.app')

@section('title', 'المركز الإعلامي وشبكات التواصل | شبكة العلامة المنير')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">
    
    <!-- Page Header & Channel Quick Links -->
    <div class="max-w-3xl mx-auto text-center space-y-4">
        <span class="text-xs font-bold text-gold-600 uppercase tracking-wider">المركز الإعلامي الرقمي</span>
        <h1 class="text-3xl sm:text-4xl font-bold font-scholarly text-slate-900">تواصل .. ميديا وقنوات التواصل الاجتماعي</h1>
        <p class="text-xs sm:text-sm text-slate-500 font-light leading-relaxed">
            استعرضوا آخر 3 منشورات وتحديثات مباشرة من كل منصة من المنصات الرسمية المعتمدة لسماحة العلامة السيد منير الخباز (دام عزه).
        </p>
    </div>

    <!-- 1. YouTube Official Channel (Last 3 Videos) -->
    <section class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center text-xl shadow-md">
                    <i class="fa-brands fa-youtube"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold font-scholarly text-slate-900">قناة يوتيوب الرسمية (@Alm0neer1)</h2>
                    <p class="text-xs text-slate-400">آخر 3 محاضرات ومجالس مصورة من القناة</p>
                </div>
            </div>
            <a href="https://www.youtube.com/user/Alm0neer1" target="_blank" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fa-brands fa-youtube"></i>
                <span>زيارة القناة والاشتراك ↗</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($youtubePosts as $video)
            <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                <div>
                    <div class="relative bg-black aspect-video overflow-hidden">
                        <img src="{{ $video->thumbnail }}" alt="{{ $video->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        <a href="{{ $video->url }}" target="_blank" class="absolute inset-0 bg-black/35 group-hover:bg-black/15 transition flex items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center text-xl shadow-lg transform group-hover:scale-110 transition">
                                <i class="fa-solid fa-play"></i>
                            </div>
                        </a>
                        <span class="absolute bottom-2 left-2 px-2 py-0.5 rounded bg-black/80 text-white text-[11px] font-mono flex items-center gap-1">
                            <i class="fa-brands fa-youtube text-red-500"></i>
                            <span>YouTube</span>
                        </span>
                    </div>

                    <div class="p-5 space-y-2">
                        <div class="flex items-center justify-between text-xs text-slate-400">
                            <span class="text-emerald-800 font-semibold flex items-center gap-1">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ $video->published_at ? $video->published_at->diffForHumans() : 'مؤخراً' }}</span>
                            </span>
                        </div>
                        <h3 class="font-bold text-sm sm:text-base text-slate-800 leading-snug hover:text-red-600 transition line-clamp-2">
                            <a href="{{ $video->url }}" target="_blank">{{ $video->title }}</a>
                        </h3>
                        @if(!empty($video->description))
                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed font-light">
                            {{ $video->description }}
                        </p>
                        @endif
                    </div>
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                    <a href="{{ $video->url }}" target="_blank" class="font-bold text-red-600 hover:text-red-700 flex items-center gap-1">
                        <span>مشاهدة على يوتيوب</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- 2. Instagram Official Account (Last 3 Posts / Reels) -->
    <section class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-500 via-pink-600 to-purple-600 text-white flex items-center justify-center text-xl shadow-md">
                    <i class="fa-brands fa-instagram"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold font-scholarly text-slate-900">حساب إنستغرام الرسمي (@almoneerorg)</h2>
                    <p class="text-xs text-slate-400">آخر 3 تصاميم وريلز دعوية وفكرية</p>
                </div>
            </div>
            <a href="https://www.instagram.com/almoneerorg" target="_blank" class="px-4 py-2 rounded-xl bg-gradient-to-r from-pink-600 to-purple-600 hover:opacity-90 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fa-brands fa-instagram"></i>
                <span>متابعة الحساب على إنستغرام ↗</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($instagramPosts as $post)
            <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between text-xs">
                        <span class="px-3 py-1 rounded-full bg-pink-50 text-pink-700 font-bold border border-pink-100 flex items-center gap-1.5">
                            <i class="fa-brands fa-instagram"></i>
                            <span>{{ $post->type }}</span>
                        </span>
                        <span class="text-slate-400">{{ $post->date }}</span>
                    </div>

                    @if(!empty($post->image))
                    <div class="relative h-44 rounded-2xl overflow-hidden bg-slate-100 border border-slate-100">
                        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-3">
                            <span class="text-white text-xs font-medium flex items-center gap-1">
                                <i class="fa-solid fa-heart text-pink-500"></i>
                                <span>{{ $post->likes }} إعجاب</span>
                            </span>
                        </div>
                    </div>
                    @else
                    <div class="h-32 rounded-2xl bg-gradient-to-tr from-pink-50 via-purple-50 to-amber-50 border border-pink-100 p-4 flex flex-col justify-between">
                        <i class="fa-solid fa-quote-right text-pink-300 text-2xl"></i>
                        <span class="text-slate-500 text-xs font-light">{{ $post->summary }}</span>
                    </div>
                    @endif

                    <h3 class="font-bold text-base text-slate-800 leading-snug hover:text-pink-600 transition">
                        <a href="{{ $post->url }}" target="_blank">{{ $post->title }}</a>
                    </h3>
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400"><i class="fa-solid fa-heart text-pink-500"></i> {{ $post->likes }}</span>
                    <a href="{{ $post->url }}" target="_blank" class="font-bold text-pink-600 hover:text-pink-700 flex items-center gap-1">
                        <span>عرض على إنستغرام</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- 3. TikTok Official Account (Last 3 Youth Clips) -->
    <section class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center text-xl shadow-md">
                    <i class="fa-brands fa-tiktok"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold font-scholarly text-slate-900">حساب تيك توك الرسمي (@almoneerorg)</h2>
                    <p class="text-xs text-slate-400">آخر 3 ومضات فكرية ودقائق معرفية للشباب</p>
                </div>
            </div>
            <a href="https://www.tiktok.com/@almoneerorg" target="_blank" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-black text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fa-brands fa-tiktok"></i>
                <span>مشاهدة الحساب على تيك توك ↗</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tiktokPosts as $clip)
            <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between text-xs">
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-800 font-bold flex items-center gap-1.5">
                            <i class="fa-brands fa-tiktok"></i>
                            <span>{{ $clip->duration }}</span>
                        </span>
                        <span class="text-slate-400">{{ $clip->date }}</span>
                    </div>

                    <div class="h-32 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 text-white p-4 flex flex-col justify-between shadow-inner">
                        <div class="flex items-center justify-between">
                            <span class="text-emerald-400 text-xs font-bold"><i class="fa-solid fa-play"></i> ومضة معرفية</span>
                            <span class="text-slate-400 text-xs">{{ $clip->views }} مشاهدة</span>
                        </div>
                        <p class="text-xs text-slate-200 line-clamp-2 leading-relaxed font-light">
                            {{ $clip->summary }}
                        </p>
                    </div>

                    <h3 class="font-bold text-base text-slate-800 leading-snug hover:text-slate-900 transition">
                        <a href="{{ $clip->url }}" target="_blank">{{ $clip->title }}</a>
                    </h3>
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500"><i class="fa-solid fa-eye"></i> {{ $clip->views }}</span>
                    <a href="{{ $clip->url }}" target="_blank" class="font-bold text-slate-900 hover:text-black flex items-center gap-1">
                        <span>مشاهدة المقطع على تيك توك</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>

</div>
@endsection

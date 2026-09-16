<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} | المشغل الصوتي المستقل</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Amiri:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'IBM Plex Sans Arabic', sans-serif;
            background-color: #04242a;
            color: #f8fafc;
            margin: 0;
            padding: 0;
            user-select: none;
        }
    </style>
</head>
<body class="p-4 flex flex-col justify-between min-h-screen bg-gradient-to-b from-[#06262d] to-[#041a1f]">
    
    <!-- Top Header Bar -->
    <div class="flex items-center justify-between pb-3 border-b border-white/10 text-xs">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center text-xs border border-amber-500/30">
                <i class="fa-solid fa-headphones"></i>
            </div>
            <div>
                <span class="font-bold text-amber-300 block leading-tight">شبكة العلامة المنير</span>
                <span class="text-[10px] text-slate-400">مشغل البث الصوتي المستقل</span>
            </div>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-900/60 text-emerald-300 text-[10px] border border-emerald-700/50">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                بث مباشر
            </span>
        </div>
    </div>

    <!-- Lecture Info -->
    <div class="my-3 space-y-1">
        <h1 class="text-sm sm:text-base font-bold text-white line-clamp-2 leading-snug">
            {{ $title }}
        </h1>
        <p class="text-xs text-amber-200/80 font-light">
            سماحة العلامة السيد منير الخباز
        </p>
    </div>

    <!-- Player Container -->
    <div class="flex-grow flex items-center justify-center my-2">
        @if(str_contains($url, 'soundcloud.com'))
            @php
                $scEmbed = "https://w.soundcloud.com/player/?url=" . urlencode($url) . "&color=%230f4c5c&auto_play=true&hide_related=true&show_comments=false&show_user=true&show_reposts=false&show_teaser=false";
            @endphp
            <iframe width="100%" height="135" scrolling="no" frameborder="no" allow="autoplay" src="{{ $scEmbed }}" class="rounded-xl shadow-lg border border-white/10"></iframe>
        @else
            <div class="w-full bg-black/40 rounded-2xl p-4 border border-white/10 space-y-3">
                <audio id="popup-audio" src="{{ $url }}" autoplay controls class="w-full"></audio>
            </div>
        @endif
    </div>

    <!-- Bottom Footer Controls -->
    <div class="pt-3 border-t border-white/10 flex items-center justify-between text-[11px] text-slate-400">
        <span class="text-slate-400 flex items-center gap-1">
            <i class="fa-solid fa-lock text-[10px] text-amber-400"></i>
            <span>يعمل في الخلفية أثناء تصفحك للموقع</span>
        </span>
        <button type="button" onclick="window.close()" class="px-3 py-1 bg-white/10 hover:bg-red-600/80 text-white rounded-lg transition font-medium">
            إغلاق
        </button>
    </div>

</body>
</html>

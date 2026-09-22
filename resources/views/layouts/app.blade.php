<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ rtrim(url('/'), '/') }}/">
    <title>@yield('title', ($siteSettings['site.title'] ?? 'سماحة السيد منير الخباز') . ' | الموقع الرسمي')</title>
    <meta name="description" content="@yield('description', 'الموقع العام والفكري لسماحة العلامة السيد منير الخباز - المحاضرات العامة، ديوان الشعر، الكتب والمؤلفات، الاستفسارات الفكرية والفقهية.')">

    <!-- Open Graph / Meta -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', $siteSettings['site.title'] ?? 'شبكة العلامة السيد منير الخباز')">
    <meta property="og:description" content="@yield('description', 'الموقع العام والفكري لسماحة العلامة السيد منير الخباز')">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/logo.png'))">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Google Fonts: IBM Plex Sans Arabic (UI) + Amiri (Scholarly & Poetry) + Amiri Quran (Uthmanic Quran) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri+Quran&family=Amiri:ital,wght@0,400;0,700;1,400&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @php
        $isTurquoise = ($activeTheme === 'almoneer-turquoise');
        $emeraldColors = $isTurquoise ? [
            '700' => '#14b8a6',
            '800' => '#0d8a9e',
            '850' => '#0a7284',
            '900' => '#075866',
            '950' => '#043640',
        ] : [
            '700' => '#0f766e',
            '800' => '#0f4c5c',
            '850' => '#0c404f',
            '900' => '#0a3d47',
            '950' => '#06262d',
        ];
        $sandColors = $isTurquoise ? [
            '50' => '#f2fafb',
            '100' => '#e5f6f8',
            '200' => '#cceef2',
        ] : [
            '50' => '#faf9f5',
            '100' => '#f5f3ec',
            '200' => '#e8e4d8',
        ];
        $emeraldJson = json_encode($emeraldColors);
        $sandJson = json_encode($sandColors);
    @endphp

    <!-- Tailwind CSS (CDN with Custom Dynamic Color Palette) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {!! $emeraldJson !!},
                        gold: {
                            300: '#f6d87e',
                            400: '#e5bc52',
                            500: '#cfa234',
                            600: '#b28622',
                        },
                        sand: {!! $sandJson !!}
                    },
                    fontFamily: {
                        sans: ['"IBM Plex Sans Arabic"', 'sans-serif'],
                        scholarly: ['"Amiri"', 'serif'],
                        quran: ['"Amiri Quran"', '"KFGQPC Uthman Taha Naskh"', '"KFGQPC Uthmanic Script HAFS"', '"Amiri"', 'serif'],
                    }
                }
            }
        }
    </script>

    @if(file_exists(public_path("site_assets/{$activeTheme}/theme.css")))
        <link rel="stylesheet" href="{{ asset("site_assets/{$activeTheme}/theme.css") }}">
    @endif

    <style>
        body {
            font-family: 'IBM Plex Sans Arabic', sans-serif;
            background-color: {{ $isTurquoise ? '#f2fafb' : '#faf9f5' }};
            color: #1e293b;
        }

        /* Uthmanic Quranic Font Styles */
        @font-face {
            font-family: 'KFGQPC Uthman Taha Naskh';
            src: local('KFGQPC Uthman Taha Naskh'), local('KFGQPC Uthmanic Script HAFS');
        }
        .font-quran, .quran-verse {
            font-family: 'Amiri Quran', 'KFGQPC Uthman Taha Naskh', 'KFGQPC Uthmanic Script HAFS', 'Amiri', serif !important;
            font-feature-settings: "cv01", "ss01";
            line-height: 2.2 !important;
        }
        .quran-verse {
            color: #04242a;
            font-size: 1.05em;
            font-weight: 400;
            display: inline;
            padding: 0 3px;
        }
        .quran-ref {
            font-family: 'IBM Plex Sans Arabic', sans-serif;
            font-size: 0.75rem;
            color: #a87920;
            font-weight: 600;
            margin-right: 0.35rem;
        }
        .font-scholarly {
            font-family: 'Amiri', serif;
        }

        /* Authentic Islamic Geometric Pattern (Dynamic based on theme) */
        @if($isTurquoise)
        .islamic-pattern {
            background-color: #074b57 !important;
            background-image: 
                url("{{ asset('images/user-pattern-transparent.png') }}"),
                radial-gradient(circle at 85% 15%, rgba(246, 216, 126, 0.35) 0%, transparent 50%),
                radial-gradient(circle at 15% 85%, rgba(34, 211, 238, 0.45) 0%, transparent 60%),
                radial-gradient(circle at 50% 50%, rgba(13, 138, 158, 0.42) 0%, transparent 70%),
                linear-gradient(135deg, #0e8b9f 0%, #074b57 45%, #032b33 100%) !important;
            background-size: 580px auto, 100% 100%, 100% 100%, 100% 100%, 100% 100% !important;
            background-repeat: repeat, no-repeat, no-repeat, no-repeat, no-repeat !important;
        }

        .header-islamic-pattern {
            background-color: #085360 !important;
            background-image: 
                url("{{ asset('images/user-pattern-transparent.png') }}"),
                radial-gradient(ellipse at 50% 0%, rgba(246, 216, 126, 0.28) 0%, rgba(8, 83, 96, 0) 75%),
                radial-gradient(circle at 85% 85%, rgba(34, 211, 238, 0.3) 0%, transparent 50%),
                linear-gradient(135deg, #0f879b 0%, #085360 50%, #04353f 100%) !important;
            background-size: 440px auto, 100% 100%, 100% 100% !important;
            background-repeat: repeat, no-repeat, no-repeat !important;
        }
        @else
        .islamic-pattern {
            background-color: #083c46 !important;
            background-image: 
                url("{{ asset('images/user-pattern-transparent.png') }}"),
                radial-gradient(circle at 85% 15%, rgba(212, 170, 72, 0.28) 0%, transparent 50%),
                radial-gradient(circle at 15% 85%, rgba(45, 212, 191, 0.25) 0%, transparent 60%),
                radial-gradient(circle at 50% 50%, rgba(15, 104, 119, 0.4) 0%, transparent 70%),
                linear-gradient(135deg, #0e5665 0%, #083c46 45%, #042128 100%) !important;
            background-size: 580px auto, 100% 100%, 100% 100%, 100% 100%, 100% 100% !important;
            background-repeat: repeat, no-repeat, no-repeat, no-repeat, no-repeat !important;
        }

        .header-islamic-pattern {
            background-color: #073842 !important;
            background-image: 
                url("{{ asset('images/user-pattern-transparent.png') }}"),
                radial-gradient(ellipse at 50% 0%, rgba(212, 170, 72, 0.2) 0%, rgba(7, 56, 66, 0) 75%),
                linear-gradient(135deg, #0d5462 0%, #073842 50%, #042229 100%) !important;
            background-size: 440px auto, 100% 100%, 100% 100% !important;
            background-repeat: repeat, no-repeat, no-repeat !important;
        }
        @endif
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(197, 148, 45, 0.2);
        }
        /* Audio Player Bar */
        #global-audio-player {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        /* Couplets formatting */
        .couplet-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed rgba(197, 148, 45, 0.25);
            padding: 0.75rem 0;
        }
        @media (max-width: 640px) {
            .couplet-line {
                flex-direction: column;
                text-align: center;
                gap: 0.35rem;
            }
        }

        /* ------------------------------------------------------------- */
        /* Desktop Horizontal Navigation Menu Styles (menu.htm)          */
        /* ------------------------------------------------------------- */
        .horizontal-menu-wrapper {
            width: 100%;
        }
        .horizontal-menu-wrapper ul#ittsc-menu,
        .horizontal-menu-wrapper ul.sortable-list,
        .horizontal-menu-wrapper ul {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: wrap !important;
            align-items: center !important;
            gap: 0.35rem !important;
            list-style: none !important;
            margin: 0 !important;
            padding: 0.35rem 0 !important;
            width: 100% !important;
        }
        .horizontal-menu-wrapper ul#ittsc-menu > li,
        .horizontal-menu-wrapper ul > li {
            display: inline-flex !important;
            align-items: center !important;
            margin: 0 !important;
            padding: 0 !important;
            list-style: none !important;
        }
        .horizontal-menu-wrapper ul#ittsc-menu > li > a,
        .horizontal-menu-wrapper ul > li > a {
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.4rem !important;
            padding: 0.5rem 0.9rem !important;
            font-size: 0.85rem !important;
            font-weight: 600 !important;
            color: #d1d5db !important; /* slate-300 */
            border-radius: 0.75rem !important;
            text-decoration: none !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            white-space: nowrap !important;
            border: 1px solid transparent !important;
            background: transparent !important;
        }
        .horizontal-menu-wrapper ul#ittsc-menu > li > a:hover,
        .horizontal-menu-wrapper ul > li > a:hover {
            color: #fbf8ee !important;
            background: rgba(15, 104, 119, 0.75) !important;
            border-color: rgba(212, 170, 72, 0.45) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
        }
        .horizontal-menu-wrapper ul#ittsc-menu > li.active > a,
        .horizontal-menu-wrapper ul#ittsc-menu > li > a.active,
        .horizontal-menu-wrapper ul > li.active > a,
        .horizontal-menu-wrapper ul > li > a.active {
            color: #e1c374 !important;
            background: rgba(5, 51, 58, 0.95) !important;
            border-color: rgba(212, 170, 72, 0.65) !important;
            font-weight: 700 !important;
            box-shadow: inset 0 0 0 1px rgba(212, 170, 72, 0.3), 0 2px 8px rgba(0, 0, 0, 0.25) !important;
        }
        /* Hawza External Cross-portal link in menu */
        .horizontal-menu-wrapper ul#ittsc-menu > li.hawza-link > a,
        .horizontal-menu-wrapper ul#ittsc-menu > li[data-url*="localhost:8000"] > a,
        .horizontal-menu-wrapper ul#ittsc-menu > li[data-url*="droos"] > a {
            background: linear-gradient(135deg, rgba(212, 170, 72, 0.22), rgba(197, 148, 45, 0.32)) !important;
            border: 1px solid rgba(212, 170, 72, 0.5) !important;
            color: #fef08a !important;
            font-weight: 700 !important;
            margin-right: auto !important; /* Pushes to left in RTL */
        }
        .horizontal-menu-wrapper ul#ittsc-menu > li.hawza-link > a:hover,
        .horizontal-menu-wrapper ul#ittsc-menu > li[data-url*="localhost:8000"] > a:hover,
        .horizontal-menu-wrapper ul#ittsc-menu > li[data-url*="droos"] > a:hover {
            background: linear-gradient(135deg, rgba(212, 170, 72, 0.4), rgba(197, 148, 45, 0.5)) !important;
            border-color: #d4aa48 !important;
            color: #ffffff !important;
        }

        /* ------------------------------------------------------------- */
        /* Mobile Drawer Navigation Menu Styles (menuv.htm)              */
        /* ------------------------------------------------------------- */
        .mobile-menu-links ul#ittsc-menu2,
        .mobile-menu-links ul.sortable-list,
        .mobile-menu-links ul {
            display: flex !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .mobile-menu-links ul > li {
            display: block !important;
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .mobile-menu-links ul > li > a {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: 0.75rem 1rem !important;
            border-radius: 0.85rem !important;
            background: rgba(255, 255, 255, 0.06) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #e2e8f0 !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            text-decoration: none !important;
            transition: all 0.2s !important;
        }
        .mobile-menu-links ul > li > a:hover,
        .mobile-menu-links ul > li.active > a,
        .mobile-menu-links ul > li > a.active {
            background: rgba(212, 170, 72, 0.18) !important;
            border-color: rgba(212, 170, 72, 0.5) !important;
            color: #f6f0d5 !important;
            padding-right: 1.25rem !important;
        }
        .mobile-menu-links ul > li.hawza-link > a {
            background: linear-gradient(135deg, rgba(212, 170, 72, 0.2), rgba(197, 148, 45, 0.3)) !important;
            border-color: rgba(212, 170, 72, 0.4) !important;
            color: #fef08a !important;
            font-weight: 700 !important;
        }

        /* ------------------------------------------------------------- */
        /* Global Print Rules                                            */
        /* ------------------------------------------------------------- */
        @media print {
            header,
            nav,
            footer,
            #mobile-menu-drawer,
            #global-audio-player,
            .fixed,
            .no-print,
            [class*="no-print"] {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="theme-{{ $activeTheme }} min-h-screen flex flex-col antialiased selection:bg-gold-500 selection:text-white">

    <!-- 1. Top Cross-Portal Switcher Bar (Connecting with Hawza Portal on Port 8000) -->
    <div class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 text-gold-300 text-xs border-b border-gold-500/30 py-1.5 px-4 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>الموقع الرسمي</span>
                </span>
                <span class="text-gold-500/50">|</span>
                <span class="hidden sm:inline text-slate-300">{{ $siteSettings['site.hadith'] ?? 'لا يزال المرء عالماً ما طلب العلم' }}</span>
            </div>
            <div class="flex items-center gap-3">
                <!-- Theme Switcher Pill -->
                <div class="inline-flex items-center rounded-full bg-black/30 p-0.5 border border-gold-500/30 text-[11px] shadow-inner">
                    <a href="{{ route('theme.switch', 'almoneer-emerald') }}" 
                       class="px-2.5 py-0.5 rounded-full transition flex items-center gap-1.5 {{ $activeTheme === 'almoneer-emerald' ? 'bg-emerald-800 text-gold-300 font-bold shadow-sm' : 'text-slate-300 hover:text-white' }}"
                       title="الثيم الزمردي الكحلي">
                        <span class="w-2 h-2 rounded-full bg-[#0a3d47] border border-gold-400/50"></span>
                        <span>الزمردي</span>
                    </a>
                    <a href="{{ route('theme.switch', 'almoneer-turquoise') }}" 
                       class="px-2.5 py-0.5 rounded-full transition flex items-center gap-1.5 {{ $activeTheme === 'almoneer-turquoise' ? 'bg-[#0d8a9e] text-white font-bold shadow-sm' : 'text-slate-300 hover:text-white' }}"
                       title="الثيم الفيروزي المشرق">
                        <span class="w-2 h-2 rounded-full bg-[#22d3ee] border border-white/60"></span>
                        <span>الفيروزي المشرق</span>
                    </a>
                </div>

                <span class="text-gold-500/30">|</span>

                <a href="{{ $hawzaPortalUrl }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-gold-500/20 hover:bg-gold-500 hover:text-emerald-950 text-gold-300 border border-gold-500/40 transition-all font-semibold text-[11px] shadow-sm">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span>بوابة الدروس الحوزوية</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Main Scholarly Header -->
    <header class="header-islamic-pattern text-white relative border-b border-gold-500/30 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-7">
            <div class="flex items-center justify-between gap-3 sm:gap-4">
                
                <!-- Right Side (RTL): Mobile Hamburger + Brand Title & Calligraphy -->
                <div class="flex items-center gap-2.5 sm:gap-4">
                    <!-- Mobile Menu Button (Placed on the Right in Arabic RTL) -->
                    <button onclick="toggleMobileMenu()" class="lg:hidden p-2.5 rounded-xl bg-emerald-800/90 text-gold-300 hover:bg-emerald-700 hover:text-white border border-gold-500/30 focus:outline-none flex-shrink-0 transition shadow-sm active:scale-95" aria-label="قائمة الموقع">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>

                    <!-- Brand Link & Calligraphy -->
                    <a href="{{ route('home') }}" class="group flex items-center gap-3 sm:gap-5">
                        <div class="h-14 sm:h-20 md:h-24 lg:h-28 w-auto flex-shrink-0 flex items-center justify-center">
                            <img src="{{ asset('images/almoneer-logo-official.png') }}" alt="شعار شبكة المنير" class="h-14 sm:h-20 md:h-24 lg:h-28 w-auto object-contain drop-shadow-[0_4px_18px_rgba(212,170,72,0.65)] group-hover:scale-105 transition-transform">
                        </div>
                        <div class="flex flex-col justify-center">
                            <!-- Handwritten Arabic Calligraphy (Official Artwork) -->
                            <div class="flex flex-col items-start gap-1.5 sm:gap-2">
                                <img src="{{ asset('images/calligraphy-sayyid-muneer-gold.png') }}" 
                                     alt="{{ $siteSettings['site.title'] ?? 'سماحة السيد منير الخباز' }}" 
                                     class="h-10 sm:h-14 md:h-18 lg:h-22 w-auto object-contain drop-shadow-[0_4px_16px_rgba(0,0,0,0.7)] group-hover:brightness-110 transition">
                                <img src="{{ asset('images/calligraphy-markaz-light.png') }}" 
                                     alt="{{ $siteSettings['site.subtitle'] ?? 'موقع يُعنى بمجمع النتاج الفقهي والفكري والنشاط التبليغي' }}" 
                                     class="h-3.5 sm:h-5 md:h-6.5 lg:h-8 w-auto object-contain opacity-95 group-hover:opacity-100 transition drop-shadow-[0_2px_8px_rgba(0,0,0,0.55)]">
                            </div>
                            <h1 class="sr-only">{{ $siteSettings['site.title'] ?? 'سماحة السيد منير الخباز' }} - {{ $siteSettings['site.subtitle'] ?? 'موقع يُعنى بمجمع النتاج الفقهي والفكري والنشاط التبليغي' }}</h1>
                        </div>
                    </a>
                </div>

                <!-- Left Side (RTL): Desktop Search & Inquiries + Mobile Quick Actions -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- Desktop Search & Inquiries -->
                    <div class="hidden lg:flex items-center gap-3 xl:gap-4">
                        <!-- Search Form -->
                        <form action="{{ route('search') }}" method="GET" class="relative">
                            <input type="text" 
                                   name="q" 
                                   value="{{ request('q') }}" 
                                   placeholder="ابحث في الموقع (محاضرات، كتب...)" 
                                   class="w-56 xl:w-72 pl-9 pr-4 py-2.5 text-xs rounded-xl bg-emerald-950/70 border border-gold-500/40 text-white placeholder-slate-400 focus:outline-none focus:border-gold-400 focus:ring-1 focus:ring-gold-400/50 shadow-inner transition backdrop-blur-sm">
                            <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-gold-400 hover:text-gold-200 transition" aria-label="بحث">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </button>
                        </form>

                        <!-- Inquiries Button -->
                        <a href="{{ route('inquiries.index') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-gold-500 to-gold-600 hover:from-gold-400 hover:to-gold-500 text-emerald-950 font-bold text-xs shadow-md transition flex items-center gap-2 flex-shrink-0">
                            <i class="fa-solid fa-circle-question"></i>
                            <span>إرسال استفسار</span>
                        </a>
                    </div>

                    <!-- Mobile Quick Inquiry Button (Left side of mobile header) -->
                    <div class="flex lg:hidden items-center gap-1.5">
                        <a href="{{ route('inquiries.index') }}" class="px-3 py-2 rounded-xl bg-gradient-to-r from-gold-500 to-gold-600 text-emerald-950 font-bold text-xs shadow-sm flex items-center gap-1.5" title="إرسال استفسار">
                            <i class="fa-solid fa-circle-question"></i>
                            <span class="hidden sm:inline">استفسار</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Desktop Horizontal Navigation (Loaded from cached menu.htm) -->
        <nav class="hidden lg:block bg-[#052c33] border-t border-b border-gold-500/25 shadow-inner w-full">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="horizontal-menu-wrapper py-1">
                    {!! $globalHorizontalMenu !!}
                </div>
            </div>
        </nav>
    </header>

    <!-- Mobile Slide-out Drawer -->
    <div id="mobile-menu-drawer" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden" onclick="toggleMobileMenu()">
        <div class="fixed top-0 right-0 bottom-0 w-4/5 max-w-xs bg-emerald-950 text-white shadow-2xl p-6 overflow-y-auto" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between pb-4 border-b border-emerald-800">
                <h3 class="font-scholarly text-lg font-bold text-gold-300">أقسام الموقع</h3>
                <button onclick="toggleMobileMenu()" class="text-slate-400 hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <!-- Mobile Search Form -->
            <div class="mt-4 mb-2">
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="بحث في الموقع..." 
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-emerald-900/90 border border-gold-500/30 text-white placeholder-slate-400 focus:outline-none focus:border-gold-400">
                    <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-gold-400" aria-label="بحث">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </button>
                </form>
            </div>

            <!-- Mobile Theme Switcher -->
            <div class="mt-3 p-2 rounded-xl bg-emerald-900/60 border border-gold-500/20 flex items-center justify-between text-xs">
                <span class="text-gold-300 font-semibold text-[11px] flex items-center gap-1.5">
                    <i class="fa-solid fa-palette text-xs"></i>
                    <span>مظهر الموقع:</span>
                </span>
                <div class="inline-flex items-center rounded-lg bg-black/40 p-0.5 border border-gold-500/20 text-[11px]">
                    <a href="{{ route('theme.switch', 'almoneer-emerald') }}" 
                       class="px-2 py-0.5 rounded-md transition flex items-center gap-1 {{ $activeTheme === 'almoneer-emerald' ? 'bg-emerald-800 text-gold-300 font-bold' : 'text-slate-400 hover:text-white' }}">
                        <span>الزمردي</span>
                    </a>
                    <a href="{{ route('theme.switch', 'almoneer-turquoise') }}" 
                       class="px-2 py-0.5 rounded-md transition flex items-center gap-1 {{ $activeTheme === 'almoneer-turquoise' ? 'bg-[#0d8a9e] text-white font-bold' : 'text-slate-400 hover:text-white' }}">
                        <span>الفيروزي</span>
                    </a>
                </div>
            </div>

            <div class="mt-3 space-y-2 mobile-menu-links">
                {!! $globalVerticalMenu !!}
            </div>
        </div>
    </div>

    <!-- Live Stream Alert Bar (if active) -->
    @if(($siteSettings['livestream.is_live'] ?? '0') === '1')
    <div class="bg-red-700 text-white py-2 px-4 shadow-md text-sm font-medium">
        <div class="max-w-7xl mx-auto flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-white animate-ping"></span>
                <span><strong>بث مباشر الآن:</strong> {{ $siteSettings['livestream.title'] ?? 'محاضرة سماحة العلامة السيد منير الخباز' }}</span>
            </div>
            <a href="{{ $siteSettings['livestream.url'] ?? '#' }}" target="_blank" class="px-3 py-1 bg-white text-red-700 rounded-lg text-xs font-bold hover:bg-slate-100 transition flex items-center gap-1">
                <i class="fa-solid fa-play"></i>
                <span>مشاهدة البث</span>
            </a>
        </div>
    </div>
    @endif

    <!-- Alert Notifications -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
        <div class="p-4 rounded-xl bg-emerald-100 border border-emerald-300 text-emerald-900 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900"><i class="fa-solid fa-xmark"></i></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
        <div class="p-4 rounded-xl bg-red-100 border border-red-300 text-red-900 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-red-600 text-base"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900"><i class="fa-solid fa-xmark"></i></button>
        </div>
    </div>
    @endif

    <!-- Main Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <!-- Footer Styles for Dynamic Menus -->
    <style>
        .footer-dynamic-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }
        .footer-dynamic-menu li a {
            color: #cbd5e1;
            transition: all 0.2s ease-in-out;
            display: inline-block;
        }
        .footer-dynamic-menu li a:hover {
            color: #facc15;
            transform: translateX(-3px);
        }
    </style>

    <footer class="bg-emerald-950 text-slate-300 border-t border-gold-500/30 pt-12 pb-20 lg:pb-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                
                <!-- Col 1: About & Scholarly Note -->
                <div class="space-y-4">
                    <h4 class="font-scholarly text-xl text-gold-300 font-bold">{{ $siteSettings['site.title'] ?? 'سماحة السيد منير الخباز' }}</h4>
                    <p class="text-xs text-gold-400/90 font-medium">{{ $siteSettings['site.subtitle'] ?? 'مركز النتاج الفقهي والفكري والنشاط التبليغي' }}</p>
                    <p class="text-sm leading-relaxed text-slate-300 font-light">
                        {{ $siteSettings['site.footer_about'] ?? 'البوابة العامة لنشر المحاضرات الفكرية، ديوان الشعر، المؤلفات، والندوات، مع الربط المباشر ببوابة الدروس الحوزوية والبحث الخارج.' }}
                    </p>
                    @if(($siteSettings['site.footer_show_hadith'] ?? '1') !== '0')
                    <div class="pt-2 text-xs text-gold-400 font-medium flex items-center gap-2">
                        <i class="fa-solid fa-quote-right"></i>
                        <span>{{ $siteSettings['site.hadith'] ?? 'لا يزال المرء عالماً ما طلب العلم، فإذا ظن أنه قد علم فقد جهل' }}</span>
                    </div>
                    @endif
                </div>

                <!-- Col 2: Quick Links (Footer Menu 1) -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 border-r-2 border-gold-400 pr-2">
                        {{ $siteSettings['site.footer_col1_title'] ?? 'أقسام الموقع' }}
                    </h4>
                    <div class="footer-dynamic-menu text-xs text-slate-300">
                        @if(!empty(trim($globalFooterMenu1 ?? '')))
                            {!! $globalFooterMenu1 !!}
                        @else
                            <ul class="space-y-2">
                                <li><a href="{{ route('home') }}">الصفحة الرئيسية</a></li>
                                <li><a href="{{ route('bio') }}">نبذة عن حياته الشريفة</a></li>
                                <li><a href="{{ route('lectures.index') }}">أرشيف المحاضرات والمواسم</a></li>
                                <li><a href="{{ route('poems.index') }}">ديوان الشعر والقصائد</a></li>
                                <li><a href="{{ route('books.index') }}">المؤلفات والكتب الإلكترونية</a></li>
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Col 3: Services & Links (Footer Menu 2) -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 border-r-2 border-gold-400 pr-2">
                        {{ $siteSettings['site.footer_col2_title'] ?? 'الخدمات والتواصل' }}
                    </h4>
                    <div class="footer-dynamic-menu text-xs text-slate-300">
                        @if(!empty(trim($globalFooterMenu2 ?? '')))
                            {!! $globalFooterMenu2 !!}
                        @else
                            <ul class="space-y-2">
                                <li><a href="{{ route('inquiries.index') }}">إرسال استفسار أو مسألة</a></li>
                                <li><a href="{{ route('inquiries.track') }}">متابعة حالة استفسار سابق</a></li>
                                <li><a href="{{ route('gallery.index') }}">ألبوم الصور والمناسبات</a></li>
                                <li><a href="{{ route('social.index') }}">المركز الإعلامي وشبكات التواصل</a></li>
                                <li><a href="{{ route('contact.index') }}">عناوين المكاتب والاتصال</a></li>
                                <li><a href="{{ $hawzaPortalUrl }}" target="_blank">بوابة الدروس الحوزوية ↗</a></li>
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Col 4: Official Accounts -->
                <div class="space-y-4">
                    <h4 class="text-white font-bold text-sm border-r-2 border-gold-400 pr-2">
                        {{ $siteSettings['site.footer_col3_title'] ?? 'الحسابات الرسمية المعتمدة' }}
                    </h4>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        {{ $siteSettings['site.footer_social_text'] ?? 'تابعوا جديد المحاضرات والمقاطع اليومية عبر الحسابات الرسمية الموثقة:' }}
                    </p>
                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        @if(!empty($siteSettings['social.youtube']))
                        <a href="{{ $siteSettings['social.youtube'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-900 hover:bg-red-600 text-white flex items-center justify-center transition shadow-md hover:scale-105" title="قناة اليوتيوب الرسمية"><i class="fa-brands fa-youtube"></i></a>
                        @endif
                        @if(!empty($siteSettings['social.instagram']))
                        <a href="{{ $siteSettings['social.instagram'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-900 hover:bg-gradient-to-tr hover:from-amber-600 hover:via-pink-600 hover:to-purple-600 text-white flex items-center justify-center transition shadow-md hover:scale-105" title="انستغرام"><i class="fa-brands fa-instagram"></i></a>
                        @endif
                        @if(!empty($siteSettings['social.facebook']))
                        <a href="{{ $siteSettings['social.facebook'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-900 hover:bg-blue-600 text-white flex items-center justify-center transition shadow-md hover:scale-105" title="فيسبوك"><i class="fa-brands fa-facebook-f"></i></a>
                        @endif
                        @if(!empty($siteSettings['social.twitter']))
                        <a href="{{ $siteSettings['social.twitter'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-900 hover:bg-sky-500 text-white flex items-center justify-center transition shadow-md hover:scale-105" title="منصة إكس (تويتر)"><i class="fa-brands fa-x-twitter"></i></a>
                        @endif
                        @if(!empty($siteSettings['social.tiktok']))
                        <a href="{{ $siteSettings['social.tiktok'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-900 hover:bg-black text-white flex items-center justify-center transition shadow-md hover:scale-105" title="تيك توك"><i class="fa-brands fa-tiktok"></i></a>
                        @endif
                        @if(!empty($siteSettings['social.snapchat']))
                        <a href="{{ $siteSettings['social.snapchat'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-900 hover:bg-[#FFFC00] hover:text-black text-white flex items-center justify-center transition shadow-md hover:scale-105" title="سناب شات"><i class="fa-brands fa-snapchat text-base"></i></a>
                        @endif
                        @if(!empty($siteSettings['social.telegram']))
                        <a href="{{ $siteSettings['social.telegram'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-900 hover:bg-sky-600 text-white flex items-center justify-center transition shadow-md hover:scale-105" title="قناة تيليجرام"><i class="fa-brands fa-telegram"></i></a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="pt-8 border-t border-emerald-900/80 flex flex-wrap justify-between items-center gap-4 text-xs text-slate-400">
                <p>© {{ date('Y') }} {{ $siteSettings['site.title'] ?? 'سماحة السيد منير الخباز' }} - {{ $siteSettings['site.copyright_text'] ?? 'جميع الحقوق محفوظة.' }}</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="hover:text-gold-300">الرئيسية</a>
                    <span>•</span>
                    <a href="{{ route('bio') }}" class="hover:text-gold-300">السيرة الذاتية</a>
                    <span>•</span>
                    <a href="{{ route('contact.index') }}" class="hover:text-gold-300">اتصل بنا</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Persistent Detachable Floating Audio Player -->
    <div id="global-audio-player" class="fixed bottom-14 lg:bottom-0 left-0 right-0 z-50 bg-emerald-950/95 text-white border-t border-gold-500/40 backdrop-blur-md shadow-2xl p-3 transform translate-y-[150%] transition-transform duration-300">
        <div class="max-w-7xl mx-auto flex flex-col gap-2">
            <!-- Main Control Bar -->
            <div class="flex items-center justify-between gap-3">
                
                <!-- Left: Play/Pause/Icon & Track Info -->
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <button id="player-play-btn" onclick="togglePlay()" class="w-10 h-10 rounded-full bg-gradient-to-r from-gold-400 to-gold-600 text-emerald-950 flex items-center justify-center text-lg font-bold shadow-md hover:scale-105 transition flex-shrink-0">
                        <i id="player-icon" class="fa-solid fa-play"></i>
                    </button>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <p id="player-title" class="text-xs sm:text-sm font-bold text-gold-200 truncate">عنوان المادة الصوتية</p>
                            <span id="player-type-badge" class="hidden px-2 py-0.5 rounded-full bg-orange-500/20 text-orange-300 border border-orange-500/30 text-[10px] font-bold">SoundCloud</span>
                        </div>
                        <p id="player-time" class="text-[11px] text-slate-400 font-mono en-num" dir="ltr">00:00 / 00:00</p>
                    </div>
                </div>

                <!-- Middle: Progress bar (Audio) or Notice (SoundCloud) -->
                <div id="player-direct-progress" class="hidden md:flex flex-grow items-center gap-3 px-4 max-w-md">
                    <input id="player-seek" type="range" min="0" max="100" value="0" oninput="seekAudio(this.value)" class="w-full h-1.5 bg-emerald-800 rounded-lg appearance-none cursor-pointer accent-gold-400">
                </div>
                <div id="player-sc-notice" class="hidden md:flex items-center gap-2 text-xs text-amber-300/80 px-2">
                    <i class="fa-solid fa-headphones-simple text-amber-400"></i>
                    <span>المشغل العائم مستمر أثناء تصفح الموقع</span>
                </div>

                <!-- Right Controls: Speed, Pop-out, Toggle Drawer, Close -->
                <div class="flex items-center gap-2 shrink-0">
                    <button onclick="changeSpeed()" id="speed-btn" class="px-2 py-1 bg-emerald-800 text-[11px] font-bold rounded text-gold-300 hover:bg-emerald-700 en-num font-mono" dir="ltr">1.0x</button>
                    
                    <!-- Popout Mini Window Button -->
                    <button type="button" onclick="openGlobalPopout()" class="px-2.5 py-1 bg-gold-500/20 hover:bg-gold-500/30 text-gold-300 text-xs font-semibold rounded-lg border border-gold-500/40 flex items-center gap-1.5 transition" title="فتح نافذة مشغل مستقلة تستمر أثناء تصفحك لكافة الصفحات">
                        <i class="fa-solid fa-up-right-from-square text-[10px]"></i>
                        <span class="hidden sm:inline">نافذة مصغرة</span>
                    </button>

                    <!-- Toggle SoundCloud Frame Drawer Button -->
                    <button id="toggle-sc-drawer-btn" type="button" onclick="toggleSoundcloudDrawer()" class="hidden px-2 py-1 bg-emerald-800 text-xs text-slate-300 hover:text-white rounded-lg transition" title="إظهار / إخفاء واجهة المشغل">
                        <i id="sc-drawer-icon" class="fa-solid fa-chevron-up"></i>
                    </button>

                    <!-- Close Player -->
                    <button onclick="closeAudioPlayer()" class="text-slate-400 hover:text-white p-1 text-base transition" title="إغلاق المشغل">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>

            <!-- Expandable SoundCloud Frame Drawer -->
            <div id="global-sc-drawer" class="hidden w-full rounded-xl overflow-hidden bg-black/60 border border-white/10 mt-1">
                <div id="global-sc-frame-container" class="w-full h-[120px]"></div>
            </div>
        </div>

        <audio id="core-audio-element" ontimeupdate="updateAudioProgress()" onended="onAudioEnded()"></audio>
    </div>

    <!-- Sticky Mobile Bottom App Bar -->
    <div class="no-print lg:hidden fixed bottom-0 left-0 right-0 z-30 bg-emerald-950 border-t border-gold-500/30 py-2 px-4 flex justify-around items-center text-center shadow-lg">
        <a href="{{ route('home') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('home') ? 'text-gold-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <i class="fa-solid fa-house text-base"></i>
            <span class="text-[10px] mt-1">الرئيسية</span>
        </a>
        <a href="{{ route('lectures.index') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('lectures.*') ? 'text-gold-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <i class="fa-solid fa-layer-group text-base"></i>
            <span class="text-[10px] mt-1">المحاضرات</span>
        </a>
        <a href="{{ route('news.index') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('news.*') ? 'text-gold-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <i class="fa-solid fa-newspaper text-base"></i>
            <span class="text-[10px] mt-1">الأخبار</span>
        </a>
        <a href="{{ route('books.index') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('books.*') ? 'text-gold-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <i class="fa-solid fa-book-bookmark text-base"></i>
            <span class="text-[10px] mt-1">الكتب</span>
        </a>
        <button onclick="toggleMobileMenu()" class="flex flex-col items-center text-xs text-slate-400 hover:text-gold-400">
            <i class="fa-solid fa-bars text-base"></i>
            <span class="text-[10px] mt-1">القائمة</span>
        </button>
    </div>

    <!-- Scripts -->
    <script>
        function toggleMobileMenu() {
            const drawer = document.getElementById('mobile-menu-drawer');
            drawer.classList.toggle('hidden');
        }

        // Global Floating & Detachable Audio Player Logic
        const audio = document.getElementById('core-audio-element');
        const playerBar = document.getElementById('global-audio-player');
        const playBtn = document.getElementById('player-icon');
        const playBtnContainer = document.getElementById('player-play-btn');
        const titleEl = document.getElementById('player-title');
        const timeEl = document.getElementById('player-time');
        const seekEl = document.getElementById('player-seek');
        const speedBtn = document.getElementById('speed-btn');
        const typeBadge = document.getElementById('player-type-badge');
        const directProgress = document.getElementById('player-direct-progress');
        const scNotice = document.getElementById('player-sc-notice');
        const scDrawer = document.getElementById('global-sc-drawer');
        const scFrameContainer = document.getElementById('global-sc-frame-container');
        const toggleScDrawerBtn = document.getElementById('toggle-sc-drawer-btn');
        const scDrawerIcon = document.getElementById('sc-drawer-icon');

        let currentGlobalTrack = {
            url: null,
            title: null,
            type: 'audio'
        };

        let speeds = [1.0, 1.25, 1.5, 2.0];
        let currentSpeedIndex = 0;

        function playGlobalAudio(url, title, type) {
            if (!url) return;
            
            if (!type) {
                type = (url.indexOf('soundcloud.com') !== -1) ? 'soundcloud' : 'audio';
            }

            currentGlobalTrack = {
                url: url,
                title: title || 'محاضرة صوتية',
                type: type
            };

            // Save to sessionStorage for cross-page persistence
            try {
                sessionStorage.setItem('almoneer_audio_player_state', JSON.stringify({
                    url: currentGlobalTrack.url,
                    title: currentGlobalTrack.title,
                    type: currentGlobalTrack.type,
                    active: true
                }));
            } catch (e) {}

            titleEl.innerText = currentGlobalTrack.title;
            playerBar.classList.remove('translate-y-[150%]');

            if (type === 'soundcloud') {
                audio.pause();
                audio.src = '';
                typeBadge.classList.remove('hidden');
                directProgress.classList.add('hidden');
                scNotice.classList.remove('hidden');
                speedBtn.classList.add('hidden');
                toggleScDrawerBtn.classList.remove('hidden');
                playBtnContainer.innerHTML = '<i class="fa-brands fa-soundcloud text-orange-500 text-lg"></i>';
                playBtnContainer.onclick = toggleSoundcloudDrawer;
                timeEl.innerText = 'بث صوتي عبر ساوندكلاود';

                const scEmbedUrl = "https://w.soundcloud.com/player/?url=" + encodeURIComponent(url) + "&color=%230f4c5c&auto_play=true&hide_related=true&show_comments=false&show_user=true&show_reposts=false&show_teaser=false";
                scFrameContainer.innerHTML = '<iframe width="100%" height="120" scrolling="no" frameborder="no" allow="autoplay" src="' + scEmbedUrl + '" class="w-full"></iframe>';
                scDrawer.classList.remove('hidden');
                scDrawerIcon.className = 'fa-solid fa-chevron-down';
            } else {
                typeBadge.classList.add('hidden');
                directProgress.classList.remove('hidden');
                scNotice.classList.add('hidden');
                speedBtn.classList.remove('hidden');
                toggleScDrawerBtn.classList.add('hidden');
                scDrawer.classList.add('hidden');
                scFrameContainer.innerHTML = '';
                playBtnContainer.innerHTML = '<i id="player-icon" class="fa-solid fa-pause"></i>';
                playBtnContainer.onclick = togglePlay;

                audio.src = url;
                audio.play().catch(() => {});
            }
        }

        function togglePlay() {
            if (audio.paused) {
                audio.play();
                const icon = document.getElementById('player-icon');
                if (icon) icon.className = 'fa-solid fa-pause';
            } else {
                audio.pause();
                const icon = document.getElementById('player-icon');
                if (icon) icon.className = 'fa-solid fa-play';
            }
        }

        function toggleSoundcloudDrawer() {
            if (scDrawer.classList.contains('hidden')) {
                scDrawer.classList.remove('hidden');
                scDrawerIcon.className = 'fa-solid fa-chevron-down';
            } else {
                scDrawer.classList.add('hidden');
                scDrawerIcon.className = 'fa-solid fa-chevron-up';
            }
        }

        function openGlobalPopout() {
            if (!currentGlobalTrack.url) return;
            const popupUrl = "{{ route('player.popup') }}?url=" + encodeURIComponent(currentGlobalTrack.url) + 
                             "&title=" + encodeURIComponent(currentGlobalTrack.title) + 
                             "&type=" + currentGlobalTrack.type;
            window.open(popupUrl, 'AlmoneerAudioPlayer', 'width=480,height=280,status=no,toolbar=no,menubar=no,location=no,resizable=yes');
        }

        function updateAudioProgress() {
            if (!audio.duration) return;
            const progress = (audio.currentTime / audio.duration) * 100;
            seekEl.value = progress;
            timeEl.innerText = formatTime(audio.currentTime) + ' / ' + formatTime(audio.duration);
        }

        function seekAudio(percent) {
            if (!audio.duration) return;
            audio.currentTime = (percent / 100) * audio.duration;
        }

        function changeSpeed() {
            currentSpeedIndex = (currentSpeedIndex + 1) % speeds.length;
            const speed = speeds[currentSpeedIndex];
            audio.playbackRate = speed;
            speedBtn.innerText = speed.toFixed(1) + 'x';
        }

        function onAudioEnded() {
            const icon = document.getElementById('player-icon');
            if (icon) icon.className = 'fa-solid fa-play';
        }

        function closeAudioPlayer() {
            audio.pause();
            audio.src = '';
            scFrameContainer.innerHTML = '';
            playerBar.classList.add('translate-y-[150%]');
            try {
                sessionStorage.removeItem('almoneer_audio_player_state');
            } catch (e) {}
        }

        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;
        }

        // Restore player state if navigated to another page while playing
        document.addEventListener('DOMContentLoaded', function() {
            try {
                const saved = sessionStorage.getItem('almoneer_audio_player_state');
                if (saved) {
                    const state = JSON.parse(saved);
                    if (state && state.active && state.url) {
                        playGlobalAudio(state.url, state.title, state.type);
                    }
                }
            } catch (e) {}
        });

        // Active Navigation Highlighting
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname.replace(/\/$/, '') || '/';
            document.querySelectorAll('#ittsc-menu a, #ittsc-menu2 a').forEach(a => {
                const href = a.getAttribute('href');
                if (!href) return;
                try {
                    const urlPath = new URL(a.href, window.location.origin).pathname.replace(/\/$/, '') || '/';
                    if (urlPath === currentPath) {
                        a.classList.add('active');
                        if (a.parentElement) a.parentElement.classList.add('active');
                    }
                } catch (e) {}
            });
        });

        // Automatic Eastern Arabic / Indic Digits Converter (الأرقام المشرقية / الهندية: ٠، ١، ٢، ...)
        (function() {
            const digitMap = {
                '0': '٠', '1': '١', '2': '٢', '3': '٣', '4': '٤',
                '5': '٥', '6': '٦', '7': '٧', '8': '٨', '9': '٩'
            };
            const digitRegex = /[0-9]/;
            const globalDigitRegex = /[0-9]/g;
            const skipSelector = 'script, style, textarea, pre, code, kbd, input, .en-num, .latin-num, .no-indic, [dir="ltr"], [data-no-indic]';

            function shouldSkip(node) {
                const parent = node.parentElement;
                if (!parent) return true;
                if (parent.closest(skipSelector)) return true;
                return false;
            }

            function convertText(text) {
                if (!digitRegex.test(text)) return text;
                // Preserve URLs and email addresses from digit conversion
                const parts = text.split(/(https?:\/\/[^\s]+|[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/g);
                for (let i = 0; i < parts.length; i++) {
                    if (i % 2 === 0) {
                        parts[i] = parts[i].replace(globalDigitRegex, function(d) {
                            return digitMap[d];
                        });
                    }
                }
                return parts.join('');
            }

            function processNode(root) {
                if (!root) return;
                if (root.nodeType === Node.TEXT_NODE) {
                    if (!shouldSkip(root) && digitRegex.test(root.nodeValue)) {
                        root.nodeValue = convertText(root.nodeValue);
                    }
                    return;
                }
                if (root.nodeType === Node.ELEMENT_NODE) {
                    if (root.matches && root.matches(skipSelector)) return;
                    if (root.closest && root.closest(skipSelector)) return;
                }

                const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
                    acceptNode: function(node) {
                        if (shouldSkip(node)) return NodeFilter.FILTER_REJECT;
                        if (!digitRegex.test(node.nodeValue)) return NodeFilter.FILTER_SKIP;
                        return NodeFilter.FILTER_ACCEPT;
                    }
                });

                let textNode;
                while ((textNode = walker.nextNode())) {
                    textNode.nodeValue = convertText(textNode.nodeValue);
                }
            }

            function initIndicDigits() {
                try {
                    if (digitRegex.test(document.title)) {
                        document.title = convertText(document.title);
                    }
                } catch (e) {}

                processNode(document.body);

                // Observe dynamically added content
                try {
                    const observer = new MutationObserver(function(mutations) {
                        for (let i = 0; i < mutations.length; i++) {
                            const addedNodes = mutations[i].addedNodes;
                            for (let j = 0; j < addedNodes.length; j++) {
                                processNode(addedNodes[j]);
                            }
                        }
                    });

                    observer.observe(document.body, {
                        childList: true,
                        subtree: true
                    });
                } catch (e) {}
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initIndicDigits);
            } else {
                initIndicDigits();
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>

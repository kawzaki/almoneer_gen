<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $siteSettings['site.title'] ?? 'شبكة سماحة العلامة السيد منير الخباز | الموقع العام')</title>
    <meta name="description" content="@yield('description', 'الموقع العام والفكري لسماحة العلامة السيد منير الخباز - المحاضرات العامة، ديوان الشعر، الكتب والمؤلفات، الاستفسارات الفكرية والفقهية.')">

    <!-- Open Graph / Meta -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', $siteSettings['site.title'] ?? 'شبكة العلامة السيد منير الخباز')">
    <meta property="og:description" content="@yield('description', 'الموقع العام والفكري لسماحة العلامة السيد منير الخباز')">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/logo.png'))">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Google Fonts: IBM Plex Sans Arabic (UI) + Amiri (Scholarly & Poetry) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS (CDN with Custom Color Palette) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            800: '#0f4c5c',
                            850: '#0c404f',
                            900: '#0a3d47',
                            950: '#06262d',
                        },
                        gold: {
                            300: '#e5ca85',
                            400: '#d4aa48',
                            500: '#c5942d',
                            600: '#a87920',
                        },
                        sand: {
                            50: '#faf9f5',
                            100: '#f5f3ec',
                            200: '#e8e4d8',
                        }
                    },
                    fontFamily: {
                        sans: ['"IBM Plex Sans Arabic"', 'sans-serif'],
                        scholarly: ['"Amiri"', 'serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'IBM Plex Sans Arabic', sans-serif;
            background-color: #faf9f5;
            color: #1e293b;
        }
        .font-scholarly {
            font-family: 'Amiri', serif;
        }
        /* Authentic Islamic Geometric Pattern (Enhanced & Zoomed) */
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
    </style>

    @stack('styles')
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-gold-500 selection:text-white pb-24 md:pb-12">

    <!-- 1. Top Cross-Portal Switcher Bar (Connecting with Hawza Portal on Port 8000) -->
    <div class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 text-gold-300 text-xs border-b border-gold-500/30 py-1.5 px-4 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>الموقع العام والفكري الرسمي</span>
                </span>
                <span class="text-gold-500/50">|</span>
                <span class="hidden sm:inline text-slate-300">{{ $siteSettings['site.hadith'] ?? 'لا يزال المرء عالماً ما طلب العلم' }}</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ $hawzaPortalUrl }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-gold-500/20 hover:bg-gold-500 hover:text-emerald-950 text-gold-300 border border-gold-500/40 transition-all font-semibold text-[11px] shadow-sm">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span>بوابة الدروس الحوزوية والبحث الخارج</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                </a>
                <a href="{{ route('login') }}" class="text-slate-400 hover:text-gold-300 text-[11px] flex items-center gap-1">
                    <i class="fa-solid fa-lock text-[10px]"></i>
                    <span>لوحة الإدارة</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Main Scholarly Header -->
    <header class="header-islamic-pattern text-white relative border-b border-gold-500/30 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex items-center justify-between gap-4">
                
                <!-- Brand Title & Calligraphy -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="group flex items-center gap-3.5">
                        <div class="h-12 sm:h-14 w-auto flex-shrink-0 flex items-center justify-center">
                            <img src="{{ asset('images/almoneer-logo-official.png') }}" alt="شعار شبكة المنير" class="h-12 sm:h-14 w-auto object-contain drop-shadow-[0_2px_12px_rgba(212,170,72,0.5)] group-hover:scale-105 transition-transform">
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl md:text-2xl font-bold font-scholarly text-gold-300 group-hover:text-gold-200 transition leading-tight">
                                {{ $siteSettings['site.title'] ?? 'شبكة سماحة العلامة السيد منير الخباز' }}
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-300 font-light mt-0.5">
                                {{ $siteSettings['site.subtitle'] ?? 'الموقع العام والفكري الرسمي' }}
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Live Ticker / Quick Actions -->
                <div class="hidden lg:flex items-center gap-4">
                    <!-- Social Bar -->
                    <div class="flex items-center gap-2 text-gold-300 text-sm">
                        @if(!empty($siteSettings['social.youtube']))
                        <a href="{{ $siteSettings['social.youtube'] }}" target="_blank" class="w-8 h-8 rounded-full bg-emerald-800/80 hover:bg-red-600 hover:text-white flex items-center justify-center transition" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                        @endif
                        @if(!empty($siteSettings['social.instagram']))
                        <a href="{{ $siteSettings['social.instagram'] }}" target="_blank" class="w-8 h-8 rounded-full bg-emerald-800/80 hover:bg-pink-600 hover:text-white flex items-center justify-center transition" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        @endif
                        @if(!empty($siteSettings['social.tiktok']))
                        <a href="{{ $siteSettings['social.tiktok'] }}" target="_blank" class="w-8 h-8 rounded-full bg-emerald-800/80 hover:bg-black hover:text-white flex items-center justify-center transition" title="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                        @endif
                        @if(!empty($siteSettings['social.twitter']))
                        <a href="{{ $siteSettings['social.twitter'] }}" target="_blank" class="w-8 h-8 rounded-full bg-emerald-800/80 hover:bg-sky-500 hover:text-white flex items-center justify-center transition" title="X"><i class="fa-brands fa-x-twitter"></i></a>
                        @endif
                    </div>

                    <!-- Inquiries Button -->
                    <a href="{{ route('inquiries.index') }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-gold-500 to-gold-600 hover:from-gold-400 hover:to-gold-500 text-emerald-950 font-bold text-xs shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-circle-question"></i>
                        <span>إرسال استفسار</span>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="toggleMobileMenu()" class="lg:hidden p-2 rounded-lg bg-emerald-800 text-gold-300 hover:bg-emerald-700 focus:outline-none" aria-label="Toggle Menu">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- 3. Desktop Horizontal Navigation (Loaded from cached menu.htm) -->
        <nav class="hidden lg:block bg-[#052c33] border-t border-b border-gold-500/25 shadow-inner">
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
            <div class="mt-4 space-y-2 mobile-menu-links">
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
    <footer class="bg-emerald-950 text-slate-300 border-t border-gold-500/30 pt-12 pb-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                
                <!-- Col 1: About & Scholarly Note -->
                <div class="space-y-4">
                    <h4 class="font-scholarly text-xl text-gold-300 font-bold">{{ $siteSettings['site.title'] ?? 'شبكة سماحة العلامة السيد منير الخباز' }}</h4>
                    <p class="text-sm leading-relaxed text-slate-300 font-light">
                        البوابة العامة لنشر المحاضرات الفكرية، ديوان الشعر، المؤلفات، والندوات، مع الربط المباشر ببوابة الدروس الحوزوية والبحث الخارج.
                    </p>
                    <div class="pt-2 text-xs text-gold-400 font-medium flex items-center gap-2">
                        <i class="fa-solid fa-quote-right"></i>
                        <span>{{ $siteSettings['site.hadith'] ?? 'لا يزال المرء عالماً ما طلب العلم' }}</span>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 border-r-2 border-gold-400 pr-2">أقسام الموقع</h4>
                    <ul class="space-y-2 text-xs text-slate-300">
                        <li><a href="{{ route('home') }}" class="hover:text-gold-300 transition">الصفحة الرئيسية</a></li>
                        <li><a href="{{ route('bio') }}" class="hover:text-gold-300 transition">نبذة عن حياته الشريفة</a></li>
                        <li><a href="{{ route('audios.index') }}" class="hover:text-gold-300 transition">المكتبة الصوتية ومحاضرات عاشوراء</a></li>
                        <li><a href="{{ route('videos.index') }}" class="hover:text-gold-300 transition">المكتبة المرئية والمقاطع القصيرة</a></li>
                        <li><a href="{{ route('poems.index') }}" class="hover:text-gold-300 transition">ديوان الشعر والقصائد</a></li>
                        <li><a href="{{ route('books.index') }}" class="hover:text-gold-300 transition">المؤلفات والكتب الإلكترونية</a></li>
                    </ul>
                </div>

                <!-- Col 3: Media & Inquiries -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 border-r-2 border-gold-400 pr-2">الخدمات والتواصل</h4>
                    <ul class="space-y-2 text-xs text-slate-300">
                        <li><a href="{{ route('inquiries.index') }}" class="hover:text-gold-300 transition">إرسال استفسار أو مسألة</a></li>
                        <li><a href="{{ route('inquiries.track') }}" class="hover:text-gold-300 transition">متابعة حالة استفسار سابق</a></li>
                        <li><a href="{{ route('gallery.index') }}" class="hover:text-gold-300 transition">ألبوم الصور والمناسبات</a></li>
                        <li><a href="{{ route('social.index') }}" class="hover:text-gold-300 transition">المركز الإعلامي وشبكات التواصل</a></li>
                        <li><a href="{{ route('contact.index') }}" class="hover:text-gold-300 transition">عناوين المكاتب والاتصال</a></li>
                        <li><a href="{{ $hawzaPortalUrl }}" target="_blank" class="text-gold-400 font-semibold hover:underline">بوابة الدروس الحوزوية ↗</a></li>
                    </ul>
                </div>

                <!-- Col 4: Official Accounts -->
                <div class="space-y-4">
                    <h4 class="text-white font-bold text-sm border-r-2 border-gold-400 pr-2">الحسابات الرسمية المعتمدة</h4>
                    <p class="text-xs text-slate-400">تابعوا جديد المحاضرات والمقاطع اليومية عبر الحسابات الرسمية الموثقة:</p>
                    <div class="flex items-center gap-2">
                        @if(!empty($siteSettings['social.youtube']))
                        <a href="{{ $siteSettings['social.youtube'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-900 hover:bg-red-600 text-white flex items-center justify-center transition shadow-md"><i class="fa-brands fa-youtube"></i></a>
                        @endif
                        @if(!empty($siteSettings['social.instagram']))
                        <a href="{{ $siteSettings['social.instagram'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-900 hover:bg-pink-600 text-white flex items-center justify-center transition shadow-md"><i class="fa-brands fa-instagram"></i></a>
                        @endif
                        @if(!empty($siteSettings['social.tiktok']))
                        <a href="{{ $siteSettings['social.tiktok'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-900 hover:bg-black text-white flex items-center justify-center transition shadow-md"><i class="fa-brands fa-tiktok"></i></a>
                        @endif
                        @if(!empty($siteSettings['social.twitter']))
                        <a href="{{ $siteSettings['social.twitter'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-900 hover:bg-sky-500 text-white flex items-center justify-center transition shadow-md"><i class="fa-brands fa-x-twitter"></i></a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="pt-8 border-t border-emerald-900/80 flex flex-wrap justify-between items-center gap-4 text-xs text-slate-400">
                <p>© {{ date('Y') }} شبكة سماحة العلامة السيد منير الخباز - جميع الحقوق محفوظة.</p>
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

    <!-- Persistent Floating Audio Player -->
    <div id="global-audio-player" class="fixed bottom-0 left-0 right-0 z-40 bg-emerald-950/95 text-white border-t border-gold-500/40 backdrop-blur-md shadow-2xl p-3 transform translate-y-full transition-transform">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <button id="player-play-btn" onclick="togglePlay()" class="w-10 h-10 rounded-full bg-gradient-to-r from-gold-400 to-gold-600 text-emerald-950 flex items-center justify-center text-lg font-bold shadow-md hover:scale-105 transition flex-shrink-0">
                    <i id="player-icon" class="fa-solid fa-play"></i>
                </button>
                <div class="min-w-0">
                    <p id="player-title" class="text-xs sm:text-sm font-bold text-gold-200 truncate">عنوان المادة الصوتية</p>
                    <p id="player-time" class="text-[11px] text-slate-400">00:00 / 00:00</p>
                </div>
            </div>

            <!-- Progress bar -->
            <div class="hidden md:flex flex-grow items-center gap-3 px-6">
                <input id="player-seek" type="range" min="0" max="100" value="0" oninput="seekAudio(this.value)" class="w-full h-1.5 bg-emerald-800 rounded-lg appearance-none cursor-pointer accent-gold-400">
            </div>

            <!-- Controls -->
            <div class="flex items-center gap-3">
                <button onclick="changeSpeed()" id="speed-btn" class="px-2 py-1 bg-emerald-800 text-[11px] font-bold rounded text-gold-300 hover:bg-emerald-700">1.0x</button>
                <button onclick="closeAudioPlayer()" class="text-slate-400 hover:text-white p-1" title="إغلاق المشغل">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
        <audio id="core-audio-element" ontimeupdate="updateAudioProgress()" onended="onAudioEnded()"></audio>
    </div>

    <!-- Sticky Mobile Bottom App Bar -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-30 bg-emerald-950 border-t border-gold-500/30 py-2 px-4 flex justify-around items-center text-center shadow-lg">
        <a href="{{ route('home') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('home') ? 'text-gold-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <i class="fa-solid fa-house text-base"></i>
            <span class="text-[10px] mt-1">الرئيسية</span>
        </a>
        <a href="{{ route('audios.index') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('audios.*') ? 'text-gold-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <i class="fa-solid fa-headphones text-base"></i>
            <span class="text-[10px] mt-1">الصوتيات</span>
        </a>
        <a href="{{ route('videos.index') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('videos.*') ? 'text-gold-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <i class="fa-solid fa-video text-base"></i>
            <span class="text-[10px] mt-1">المرئيات</span>
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

        // Global Floating Audio Player Logic
        const audio = document.getElementById('core-audio-element');
        const playerBar = document.getElementById('global-audio-player');
        const playBtn = document.getElementById('player-icon');
        const titleEl = document.getElementById('player-title');
        const timeEl = document.getElementById('player-time');
        const seekEl = document.getElementById('player-seek');
        const speedBtn = document.getElementById('speed-btn');

        let speeds = [1.0, 1.25, 1.5, 2.0];
        let currentSpeedIndex = 0;

        function playGlobalAudio(url, title) {
            if (!url) return;
            audio.src = url;
            titleEl.innerText = title || 'محاضرة صوتية';
            audio.play();
            playerBar.classList.remove('translate-y-full');
            playBtn.className = 'fa-solid fa-pause';
        }

        function togglePlay() {
            if (audio.paused) {
                audio.play();
                playBtn.className = 'fa-solid fa-pause';
            } else {
                audio.pause();
                playBtn.className = 'fa-solid fa-play';
            }
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
            playBtn.className = 'fa-solid fa-play';
        }

        function closeAudioPlayer() {
            audio.pause();
            playerBar.classList.add('translate-y-full');
        }

        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;
        }

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
    </script>

    @stack('scripts')
</body>
</html>

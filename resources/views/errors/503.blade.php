<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $maintenanceTitle ?? 'الموقع تحت الصيانة والتحديث' }} | شبكة المنير</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            700: '#0f766e',
                            800: '#0f4c5c',
                            850: '#0c404f',
                            900: '#0a3d47',
                            950: '#06262d',
                        },
                        gold: {
                            300: '#f6d87e',
                            400: '#e5bc52',
                            500: '#cfa234',
                            600: '#b28622',
                        }
                    },
                    fontFamily: {
                        sans: ['"IBM Plex Sans Arabic"', 'sans-serif'],
                        scholarly: ['"Amiri"', 'serif'],
                    }
                }
            }
        };
    </script>

    <style>
        body {
            font-family: 'IBM Plex Sans Arabic', sans-serif;
        }
        .pattern-bg {
            background-color: #06262d;
            background-image: radial-gradient(#0f766e 0.75px, transparent 0.75px), radial-gradient(#0a3d47 0.75px, #06262d 0.75px);
            background-size: 30px 30px;
            background-position: 0 0, 15px 15px;
        }
    </style>
</head>
<body class="pattern-bg min-h-screen text-slate-100 flex flex-col justify-between selection:bg-gold-500 selection:text-slate-950">
    
    <!-- Top Header -->
    <header class="py-6 px-4 text-center">
        <a href="{{ url('/') }}" class="inline-block transition hover:opacity-90">
            <img src="{{ asset('images/logo.png') }}" alt="شبكة المنير" class="h-16 sm:h-20 mx-auto object-contain drop-shadow">
        </a>
    </header>

    <!-- Main Maintenance Notice Box -->
    <main class="w-full max-w-2xl mx-auto px-4 py-8">
        <div class="relative bg-emerald-950/85 backdrop-blur-md rounded-3xl p-8 sm:p-12 border border-emerald-700/50 shadow-2xl text-center space-y-6 overflow-hidden">
            <!-- Decorative Gold Accents -->
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-gold-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-gold-500/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <!-- Icon -->
            <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-br from-gold-400 to-amber-600 p-0.5 shadow-lg shadow-gold-500/20">
                <div class="w-full h-full bg-emerald-950 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-screwdriver-wrench text-2xl text-gold-400 animate-pulse"></i>
                </div>
            </div>

            <!-- Calligraphy Banner -->
            <div class="py-2">
                <img src="{{ asset('images/calligraphy-mowqe-sayyid-dark.png') }}?v=2" 
                     alt="موقع سماحة السيد منير الخباز" 
                     class="h-12 sm:h-14 mx-auto invert opacity-90 object-contain">
            </div>

            <!-- Title & Message -->
            <div class="space-y-3">
                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-wide">
                    {{ $maintenanceTitle ?? 'الموقع تحت أعمال الصيانة والتحديث' }}
                </h1>
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-lg mx-auto">
                    {{ $maintenanceMessage ?? 'نعمل حالياً على تطوير وتحديث الموقع لخدمتكم بشكل أفضل، سنعود قريباً إن شاء الله تعالى.' }}
                </p>
            </div>

            <!-- Contact & Social Link Bar -->
            <div class="pt-6 border-t border-emerald-800/60 flex flex-wrap items-center justify-center gap-4 text-xs text-slate-300">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-900/60 border border-emerald-700/40">
                    <i class="fa-regular fa-envelope text-gold-400"></i>
                    <span>للتواصل: <a href="mailto:info@almoneer.org" class="text-gold-300 hover:underline" dir="ltr">info@almoneer.org</a></span>
                </div>
                <div class="inline-flex items-center gap-3 text-sm">
                    <a href="https://t.me/almoneerorg" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-gold-400 transition" title="Telegram"><i class="fa-brands fa-telegram"></i></a>
                    <a href="https://www.youtube.com/@almoneerorg" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-gold-400 transition" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    <a href="https://soundcloud.com/almoneerorg" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-gold-400 transition" title="SoundCloud"><i class="fa-brands fa-soundcloud"></i></a>
                    <a href="https://x.com/almoneerorg" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-gold-400 transition" title="X"><i class="fa-brands fa-x-twitter"></i></a>
                </div>
            </div>

            <!-- Admin Login Link -->
            <div class="pt-2">
                <a href="{{ route('login') }}" class="text-[11px] text-slate-500 hover:text-gold-400 transition inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-lock text-[10px]"></i>
                    <span>دخول المشرفين</span>
                </a>
            </div>
        </div>
    </main>

    <!-- Footer Copyright -->
    <footer class="py-6 text-center text-xs text-slate-400">
        <p>© {{ date('Y') }} شبكة المنير — الموقع الرسمي لسماحة السيد منير الخباز. جميع الحقوق محفوظة.</p>
    </footer>

</body>
</html>

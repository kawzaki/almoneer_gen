<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') | شبكة العلامة المنير</title>
    
    <!-- Google Fonts: IBM Plex Sans Arabic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            800: '#0f4c5c',
                            900: '#0a3d47',
                            950: '#06262d',
                        },
                        gold: {
                            400: '#d4aa48',
                            500: '#c5942d',
                            600: '#a87920',
                        }
                    },
                    fontFamily: {
                        sans: ['"IBM Plex Sans Arabic"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'IBM Plex Sans Arabic', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-emerald-950 text-white flex-shrink-0 flex flex-col min-h-screen border-l border-emerald-800 shadow-xl">
        <!-- Brand -->
        <div class="p-5 border-b border-emerald-900 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gold-500 text-emerald-950 flex items-center justify-center text-xl font-bold shadow-md">
                <i class="fa-solid fa-feather"></i>
            </div>
            <div>
                <h1 class="text-sm font-bold text-gold-300">لوحة الإدارة الذكية</h1>
                <p class="text-[11px] text-slate-400">شبكة العلامة المنير</p>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-grow p-4 space-y-1 text-sm overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-gold-500 text-emerald-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-emerald-900' }}">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                <span>الرئيسية والإحصائيات</span>
            </a>

            <div class="pt-3 pb-1 text-[11px] font-bold text-gold-400/80 px-3 uppercase tracking-wider">إدارة القوائم والمظهر</div>

            <a href="{{ route('admin.menus.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.menus.*') ? 'bg-gold-500 text-emerald-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-emerald-900' }}">
                <i class="fa-solid fa-bars-staggered w-5 text-center"></i>
                <span>القوائم (الأفقية والعمودية)</span>
            </a>

            <a href="{{ route('admin.themes.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.themes.*') ? 'bg-gold-500 text-emerald-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-emerald-900' }}">
                <i class="fa-solid fa-palette w-5 text-center"></i>
                <span>إدارة القوالب والثيمات</span>
            </a>

            <div class="pt-3 pb-1 text-[11px] font-bold text-gold-400/80 px-3 uppercase tracking-wider">إدارة المحتوى</div>

            <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.articles.*') ? 'bg-gold-500 text-emerald-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-emerald-900' }}">
                <i class="fa-solid fa-newspaper w-5 text-center"></i>
                <span>الأخبار والنشاطات</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.categories.*') ? 'bg-gold-500 text-emerald-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-emerald-900' }}">
                <i class="fa-solid fa-folder-tree w-5 text-center"></i>
                <span>إدارة التصنيفات</span>
            </a>

            <a href="{{ route('admin.media.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.media.*') ? 'bg-gold-500 text-emerald-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-emerald-900' }}">
                <i class="fa-solid fa-photo-film w-5 text-center"></i>
                <span>الصوتيات والمرئيات</span>
            </a>

            <a href="{{ route('admin.poems.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.poems.*') ? 'bg-gold-500 text-emerald-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-emerald-900' }}">
                <i class="fa-solid fa-feather-pointed w-5 text-center"></i>
                <span>ديوان الشعر والقصائد</span>
            </a>

            <a href="{{ route('admin.books.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.books.*') ? 'bg-gold-500 text-emerald-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-emerald-900' }}">
                <i class="fa-solid fa-book-bookmark w-5 text-center"></i>
                <span>الكتب والمؤلفات</span>
            </a>

            <a href="{{ route('admin.gallery.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.gallery.*') ? 'bg-gold-500 text-emerald-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-emerald-900' }}">
                <i class="fa-solid fa-images w-5 text-center"></i>
                <span>ألبوم الصور</span>
            </a>

            <a href="{{ route('admin.inquiries.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.inquiries.*') ? 'bg-gold-500 text-emerald-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-emerald-900' }}">
                <i class="fa-solid fa-circle-question w-5 text-center"></i>
                <span>الاستفسارات والفتاوى</span>
            </a>

            <a href="{{ route('admin.wisdom.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.wisdom.*') ? 'bg-gold-500 text-emerald-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-emerald-900' }}">
                <i class="fa-solid fa-quote-right w-5 text-center"></i>
                <span>كلمة الأسبوع والحكم</span>
            </a>

            <div class="pt-3 pb-1 text-[11px] font-bold text-gold-400/80 px-3 uppercase tracking-wider">أدوات التحرير والموقع</div>

            <a href="{{ route('admin.tools.vacum') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.tools.vacum') ? 'bg-gold-500 text-emerald-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-emerald-900' }}">
                <i class="fa-solid fa-broom w-5 text-center text-amber-400"></i>
                <span>أداة المخمة (تنظيف النصوص)</span>
            </a>

            <div class="pt-3 pb-1 text-[11px] font-bold text-gold-400/80 px-3 uppercase tracking-wider">النظام والرقابة</div>

            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.settings.*') ? 'bg-gold-500 text-emerald-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-emerald-900' }}">
                <i class="fa-solid fa-sliders w-5 text-center"></i>
                <span>إعدادات الموقع والبث</span>
            </a>

            <a href="{{ route('admin.audit.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.audit.*') ? 'bg-gold-500 text-emerald-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-emerald-900' }}">
                <i class="fa-solid fa-shield-halved w-5 text-center"></i>
                <span>سجل الرقابة (Audit Log)</span>
            </a>
        </nav>

        <!-- User profile footer -->
        <div class="p-4 border-t border-emerald-900 flex items-center justify-between text-xs text-slate-300">
            <div class="truncate">
                <p class="font-bold text-white">{{ auth()->user()->name ?? 'المدير' }}</p>
                <p class="text-[10px] text-gold-400">{{ auth()->user()->role ?? 'Super Admin' }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-red-400 p-1" title="تسجيل الخروج">
                    <i class="fa-solid fa-right-from-bracket text-base"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Admin Content Area -->
    <div class="flex-grow flex flex-col min-w-0">
        
        <!-- Top Admin Header -->
        <header class="bg-white border-b border-slate-200 px-6 py-3.5 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-900 text-xs font-semibold flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-globe"></i>
                    <span>معاينة الموقع الرئيسي</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>

                <a href="{{ $hawzaPortalUrl ?? 'https://almoneer-droos.onrender.com' }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-graduation-cap text-gold-600"></i>
                    <span>بوابة الدروس الحوزوية</span>
                </a>
            </div>

            <!-- Instant Cache Flush Button -->
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.cache.flush') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 border border-amber-300 text-amber-900 text-xs font-bold transition flex items-center gap-2 shadow-sm" title="تفريغ كافة ملفات الكاش وتحديث الموقع فورياً">
                        <i class="fa-solid fa-bolt text-amber-600"></i>
                        <span>تفريغ الكاش وتحديث الموقع</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Flash messages -->
        @if(session('success'))
        <div class="p-4 mx-6 mt-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900"><i class="fa-solid fa-xmark"></i></button>
        </div>
        @endif

        @if(session('error'))
        <div class="p-4 mx-6 mt-4 rounded-xl bg-red-50 border border-red-200 text-red-900 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-red-600 text-base"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900"><i class="fa-solid fa-xmark"></i></button>
        </div>
        @endif

        <!-- Main View Content -->
        <main class="p-6 flex-grow">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>

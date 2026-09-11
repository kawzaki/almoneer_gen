<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول إلى لوحة التحكم | شبكة العلامة المنير</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@700&family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS with Custom Colors -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            700: '#135d6e',
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
        body { font-family: 'IBM Plex Sans Arabic', sans-serif; }
        .font-scholarly { font-family: 'Amiri', serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-950 via-[#06262d] to-slate-900 flex items-center justify-center p-4 relative">

    <!-- Background Subtle Geometric Glow -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_20%,rgba(197,148,45,0.08),transparent_50%)] pointer-events-none"></div>

    <div class="max-w-md w-full bg-white text-slate-900 rounded-3xl p-8 sm:p-10 shadow-2xl border border-slate-200/80 space-y-6 relative z-10">
        
        <!-- Header & Logo -->
        <div class="text-center space-y-2">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-400 via-gold-500 to-gold-600 text-emerald-950 flex items-center justify-center text-2xl font-bold mx-auto shadow-md shadow-gold-500/20">
                <i class="fa-solid fa-feather"></i>
            </div>
            <h2 class="text-2xl font-bold font-scholarly text-emerald-950">لوحة الإدارة الذكية</h2>
            <p class="text-xs text-slate-500 font-medium">شبكة سماحة العلامة السيد منير الخباز (الموقع الرسمي)</p>
        </div>

        @if($errors->any())
        <div class="p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-red-500 text-sm"></i>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">البريد الإلكتروني أو اسم المستخدم:</label>
                <div class="relative">
                    <input type="text" name="login" value="{{ old('login', 'admin@almoneer.org') }}" required 
                           class="w-full text-xs sm:text-sm rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 p-3 pr-10 focus:bg-white focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 transition outline-none" 
                           dir="ltr" placeholder="admin@almoneer.org">
                    <i class="fa-solid fa-user absolute right-3.5 top-3.5 text-slate-400 text-xs"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">كلمة المرور:</label>
                <div class="relative">
                    <input type="password" id="passwordInput" name="password" value="admin123" required 
                           class="w-full text-xs sm:text-sm rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 p-3 pr-10 pl-10 focus:bg-white focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 transition outline-none" 
                           dir="ltr" placeholder="••••••••">
                    <i class="fa-solid fa-lock absolute right-3.5 top-3.5 text-slate-400 text-xs"></i>
                    <button type="button" onclick="togglePassword()" class="absolute left-3.5 top-3.5 text-slate-400 hover:text-slate-600 focus:outline-none text-xs">
                        <i id="toggleIcon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-emerald-800 focus:ring-emerald-700 border-slate-300 cursor-pointer">
                    <span class="font-medium">تذكرني على هذا الجهاز</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-emerald-800 to-emerald-950 hover:from-emerald-700 hover:to-emerald-900 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg shadow-emerald-950/20 hover:shadow-xl transition-all flex items-center justify-center gap-2 cursor-pointer">
                <i class="fa-solid fa-right-to-bracket text-gold-400 text-sm"></i>
                <span>تسجيل الدخول إلى النظام</span>
            </button>
        </form>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <a href="{{ route('home') }}" class="text-emerald-800 hover:text-emerald-950 font-bold flex items-center gap-1.5 transition">
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                <span>العودة للموقع الرئيسي</span>
            </a>
            <span class="bg-slate-100 border border-slate-200 text-slate-600 px-2.5 py-1 rounded-lg text-[11px] font-mono">حساب: admin / admin123</span>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

</body>
</html>

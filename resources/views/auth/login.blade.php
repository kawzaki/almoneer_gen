<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول إلى لوحة التحكم | شبكة العلامة المنير</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@700&family=IBM+Plex+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'IBM Plex Sans Arabic', sans-serif; }
        .font-scholarly { font-family: 'Amiri', serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-900 flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-emerald-950 text-white rounded-3xl p-8 border border-gold-500/40 shadow-2xl space-y-6">
        <div class="text-center space-y-2">
            <div class="w-16 h-16 rounded-2xl bg-gold-500 text-emerald-950 flex items-center justify-center text-3xl font-bold mx-auto shadow-lg">
                <i class="fa-solid fa-feather"></i>
            </div>
            <h2 class="text-2xl font-bold font-scholarly text-gold-300">لوحة الإدارة الذكية</h2>
            <p class="text-xs text-slate-300">شبكة سماحة العلامة السيد منير الخباز (الموقع العام)</p>
        </div>

        @if($errors->any())
        <div class="p-3 rounded-xl bg-red-900/50 border border-red-500 text-red-200 text-xs">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">البريد الإلكتروني أو اسم المستخدم:</label>
                <div class="relative">
                    <input type="text" name="login" value="{{ old('login', 'admin@almoneer.org') }}" required class="w-full text-xs rounded-xl bg-emerald-900/80 border border-emerald-700 text-white p-3 pr-10 focus:ring-gold-500 focus:border-gold-500" dir="ltr">
                    <i class="fa-solid fa-user absolute right-3.5 top-3.5 text-slate-400 text-xs"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">كلمة المرور:</label>
                <div class="relative">
                    <input type="password" name="password" value="admin123" required class="w-full text-xs rounded-xl bg-emerald-900/80 border border-emerald-700 text-white p-3 pr-10 focus:ring-gold-500 focus:border-gold-500" dir="ltr">
                    <i class="fa-solid fa-lock absolute right-3.5 top-3.5 text-slate-400 text-xs"></i>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-300">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded text-gold-500 focus:ring-gold-500 bg-emerald-900 border-emerald-700">
                    <span>تذكرني على هذا الجهاز</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 bg-gradient-to-r from-gold-500 to-gold-600 hover:from-gold-400 hover:to-gold-500 text-emerald-950 font-bold text-xs rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-right-to-bracket"></i>
                <span>تسجيل الدخول إلى النظام</span>
            </button>
        </form>

        <div class="pt-4 border-t border-emerald-900 text-center text-xs text-slate-400 flex items-center justify-between">
            <a href="{{ route('home') }}" class="hover:text-gold-300">← العودة للموقع الرئيسي</a>
            <span class="text-[10px] text-slate-500">حساب افتراضي: admin / admin123</span>
        </div>
    </div>

</body>
</html>

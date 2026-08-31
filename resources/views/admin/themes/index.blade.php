@extends('layouts.admin')

@section('title', 'إدارة القوالب والثيمات')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">إدارة القوالب والثيمات (Themes)</h2>
            <p class="text-xs text-slate-500 mt-1">التبديل بين تصاميم وهوية الموقع ورفع قوالب جديدة بتنسيق ZIP.</p>
        </div>

        <button onclick="document.getElementById('upload-theme-modal').classList.remove('hidden')" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-2">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <span>رفع ثيم جديد (.zip)</span>
        </button>
    </div>

    <!-- Active Theme Badge -->
    <div class="p-4 rounded-2xl bg-emerald-900 text-white flex items-center justify-between shadow-md">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gold-500 text-emerald-950 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-palette"></i>
            </div>
            <div>
                <p class="text-xs text-gold-300">القالب النشط حالياً في الموقع:</p>
                <h3 class="font-bold text-base text-white capitalize">{{ $currentTheme }}</h3>
            </div>
        </div>
        <span class="px-3 py-1 bg-emerald-800 rounded-full text-xs text-gold-300 border border-gold-500/30 flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>مفعل ويعمل الآن</span>
        </span>
    </div>

    <!-- Themes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($themes as $theme)
        <div class="bg-white rounded-2xl border {{ $theme->active ? 'border-2 border-gold-500 shadow-md' : 'border-slate-200' }} overflow-hidden flex flex-col justify-between">
            <div>
                <!-- Mockup Preview Banner -->
                <div class="h-36 bg-gradient-to-br from-emerald-950 to-emerald-900 p-4 flex flex-col justify-between text-white relative">
                    <div class="flex justify-between items-start">
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-black/40 text-gold-300 font-mono">v{{ $theme->version }}</span>
                        @if($theme->active)
                        <span class="text-xs px-3 py-0.5 rounded-full bg-gold-500 text-emerald-950 font-bold shadow">نشط الآن</span>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-lg text-gold-300">{{ $theme->title ?? $theme->name }}</h4>
                        <p class="text-xs text-slate-300">{{ $theme->author ?? 'شبكة المنير' }}</p>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-5 space-y-3">
                    <p class="text-xs text-slate-600 leading-relaxed min-h-[40px]">
                        {{ $theme->description ?? 'قالب مخصص مصمم لشبكة سماحة السيد منير الخباز.' }}
                    </p>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-2">
                @if(!$theme->active)
                <form action="{{ route('admin.themes.activate', $theme->name) }}" method="POST" class="flex-grow">
                    @csrf
                    <button type="submit" class="w-full py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>تفعيل هذا الثيم</span>
                    </button>
                </form>

                <form action="{{ route('admin.themes.destroy', $theme->name) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا القالب؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-xl transition" title="حذف القالب">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                    </button>
                </form>
                @else
                <span class="w-full text-center py-2 text-xs font-bold text-emerald-800 flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>هذا هو الثيم المفعل للموقع</span>
                </span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

</div>

<!-- Upload Modal -->
<div id="upload-theme-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-file-zipper text-gold-500"></i>
                <span>رفع حزمة ثيم مضغوطة (.zip)</span>
            </h3>
            <button onclick="document.getElementById('upload-theme-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.themes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">اختر ملف القالب المضغوط:</label>
                <input type="file" name="theme_zip" accept=".zip" required class="w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-800 hover:file:bg-emerald-100 border border-slate-200 rounded-xl p-2">
                <p class="text-[11px] text-slate-400 mt-1">يجب أن تحتوي حزمة الـ ZIP على ملف `theme.json` في مجلدها الجذري.</p>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('upload-theme-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl">إلغاء</button>
                <button type="submit" class="px-5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold rounded-xl shadow-md">رفع واستخراج</button>
            </div>
        </form>
    </div>
</div>
@endsection

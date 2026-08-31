@extends('layouts.admin')

@section('title', 'إعدادات الموقع العام')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">إعدادات الموقع والبث المباشر</h2>
        <p class="text-xs text-slate-500 mt-1">تحديث هوية الموقع، روابط التواصل، والتحكم في إشارة البث المباشر.</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- 1. General Info -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-sm text-slate-800 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-emerald-800"></i>
                <span>الهوية والنصوص الأساسية</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان الموقع الرئيسي:</label>
                    <input type="text" name="site.title" value="{{ $settings['site.title'] ?? 'شبكة سماحة العلامة السيد منير الخباز' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">الوصف المختصر (Subtitle):</label>
                    <input type="text" name="site.subtitle" value="{{ $settings['site.subtitle'] ?? 'الموقع العام والفكري الرسمي' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">الحديث الشريف في الشريط العلوي:</label>
                <input type="text" name="site.hadith" value="{{ $settings['site.hadith'] ?? 'لا يزال المرء عالماً ما طلب العلم، فإذا ظن أنه قد علم فقد جهل' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 font-scholarly text-sm">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">البريد الإلكتروني المعتمد:</label>
                    <input type="email" name="site.email" value="{{ $settings['site.email'] ?? 'info@almoneer.org' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">الهاتف:</label>
                    <input type="text" name="site.phone" value="{{ $settings['site.phone'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">المكتب / العنوان:</label>
                    <input type="text" name="site.address" value="{{ $settings['site.address'] ?? 'النجف الأشرف / القطيف' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                </div>
            </div>
        </div>

        <!-- 2. Live Stream Switcher -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-sm text-slate-800 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-tower-broadcast text-red-600"></i>
                <span>التحكم في البث المباشر</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">حالة البث:</label>
                    <select name="livestream.is_live" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 font-bold">
                        <option value="0" {{ ($settings['livestream.is_live'] ?? '0') === '0' ? 'selected' : '' }}>🔴 البث غير مفعل حالياً (متوقف)</option>
                        <option value="1" {{ ($settings['livestream.is_live'] ?? '0') === '1' ? 'selected' : '' }}>🟢 البث مباشر الآن (يظهر في الهيدر والصفحة الرئيسية)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان المحاضرة المبثوثة:</label>
                    <input type="text" name="livestream.title" value="{{ $settings['livestream.title'] ?? 'البث المباشر لمحاضرات سماحة السيد' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">رابط البث (يوتيوب أو ميديا سيرفر):</label>
                <input type="text" name="livestream.url" value="{{ $settings['livestream.url'] ?? '' }}" placeholder="https://youtube.com/live/..." class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
            </div>
        </div>

        <!-- 3. Social Media Platforms -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-sm text-slate-800 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-share-nodes text-gold-500"></i>
                <span>حسابات التواصل الاجتماعي المعتمدة</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1"><i class="fa-brands fa-youtube text-red-600"></i> يوتيوب (YouTube):</label>
                    <input type="text" name="social.youtube" value="{{ $settings['social.youtube'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1"><i class="fa-brands fa-instagram text-pink-600"></i> إنستغرام (Instagram):</label>
                    <input type="text" name="social.instagram" value="{{ $settings['social.instagram'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1"><i class="fa-brands fa-tiktok text-black"></i> تيك توك (TikTok):</label>
                    <input type="text" name="social.tiktok" value="{{ $settings['social.tiktok'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1"><i class="fa-brands fa-x-twitter text-slate-800"></i> منصة إكس (Twitter):</label>
                    <input type="text" name="social.twitter" value="{{ $settings['social.twitter'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-8 py-3 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>حفظ كافة الإعدادات وتحديث الكاش</span>
            </button>
        </div>
    </form>
</div>
@endsection

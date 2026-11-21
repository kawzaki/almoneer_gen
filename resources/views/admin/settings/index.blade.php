@extends('layouts.admin')

@section('title', 'إعدادات الموقع العام')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">إعدادات الموقع والبث المباشر</h2>
        <p class="text-xs text-slate-500 mt-1">تحديث هوية الموقع، روابط التواصل، والتحكم في إشارة البث المباشر.</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- 0. Site Status & Maintenance Mode -->
        <div id="maintenance_card" class="bg-white rounded-2xl p-6 border {{ ($settings['site.maintenance_mode'] ?? '0') == '1' ? 'border-amber-400 bg-amber-50/20' : 'border-slate-200' }} shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-power-off text-amber-500"></i>
                    <span>حالة الموقع ووضع الصيانة (إغلاق الموقع مؤقتاً)</span>
                </h3>
                <span id="maintenance_badge">
                    @if(($settings['site.maintenance_mode'] ?? '0') == '1')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            الموقع مغلق حالياً (وضع الصيانة مفعل)
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            الموقع متاح للزوار
                        </span>
                    @endif
                </span>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                <div class="space-y-1">
                    <div class="text-xs font-bold text-slate-800">تفعيل وضع الصيانة / إغلاق الموقع للعموم:</div>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        عند تفعيل هذا الخيار، سيتم عرض صفحة صيانة مخصصة لجميع الزوار مع عنوان ورسالة توضيحية، بينما يمكن للمشرفين والمديرين تسجيل الدخول والتصفح بصورة اعتيادية.
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <span id="label_maintenance_mode" class="text-xs font-bold {{ ($settings['site.maintenance_mode'] ?? '0') == '1' ? 'text-amber-600' : 'text-slate-500' }}">
                        {{ ($settings['site.maintenance_mode'] ?? '0') == '1' ? 'مفعل (الموقع مغلق)' : 'معطل (الموقع مفتوح)' }}
                    </span>
                    <label dir="ltr" class="relative inline-flex items-center cursor-pointer select-none">
                        <input type="hidden" name="site.maintenance_mode" value="0">
                        <input type="checkbox" id="toggle_maintenance_mode" name="site.maintenance_mode" value="1" class="sr-only peer" {{ ($settings['site.maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600"></div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان رسالة الصيانة:</label>
                    <input type="text" name="site.maintenance_title" value="{{ $settings['site.maintenance_title'] ?? 'الموقع تحت أعمال الصيانة والتحديث' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">نص رسالة التوضيح للزوار:</label>
                    <textarea name="site.maintenance_message" rows="2" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">{{ $settings['site.maintenance_message'] ?? 'نعمل حالياً على تطوير وتحديث الموقع لخدمتكم بشكل أفضل، سنعود قريباً إن شاء الله تعالى.' }}</textarea>
                </div>
            </div>
        </div>

        <!-- 1. General Info -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-sm text-slate-800 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-emerald-800"></i>
                <span>الهوية والنصوص الأساسية</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان الموقع الرئيسي:</label>
                    <input type="text" name="site.title" value="{{ $settings['site.title'] ?? 'سماحة السيد منير الخباز' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">الوصف المختصر (Subtitle):</label>
                    <input type="text" name="site.subtitle" value="{{ $settings['site.subtitle'] ?? 'مركز النتاج الفقهي والفكري والنشاط التبليغي' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
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

            <!-- Direct Contact Form Toggle -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-4">
                <div>
                    <span class="block text-xs font-bold text-slate-800">نموذج المراسلة المباشرة (صفحة تواصل):</span>
                    <span class="text-[11px] text-slate-500">تفعيل أو تعطيل استقبال الرسائل عبر النموذج الإلكتروني في صفحة تواصل (معطل افتراضياً).</span>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <span id="label_contact_form" class="text-xs font-bold {{ ($settings['site.contact_form_enabled'] ?? '0') == '1' ? 'text-emerald-700' : 'text-slate-400' }}">
                        {{ ($settings['site.contact_form_enabled'] ?? '0') == '1' ? 'مفعل (النموذج متاح)' : 'معطل (النموذج مغلق)' }}
                    </span>
                    <label dir="ltr" class="relative inline-flex items-center cursor-pointer select-none">
                        <input type="hidden" name="site.contact_form_enabled" value="0">
                        <input type="checkbox" id="toggle_contact_form" name="site.contact_form_enabled" value="1" class="sr-only peer" {{ ($settings['site.contact_form_enabled'] ?? '0') == '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- 2. Homepage Hero Portrait Card & Identity -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-id-badge text-gold-500"></i>
                    <span>بطاقة التعريف والصورة الشخصية (الصفحة الرئيسية)</span>
                </h3>
                <span class="text-xs text-slate-400">تظهر في واجهة الصفحة الأولى بجانب الخبر الرئيسي</span>
            </div>

            <!-- Photo Upload & Preview -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row items-center gap-6">
                <!-- Current / Preview Thumbnail -->
                <div class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-full p-1 bg-gradient-to-tr from-gold-400 via-gold-500 to-emerald-800 shadow-lg overflow-hidden border-2 border-gold-400 shrink-0">
                    @php
                        $adminHeroPath = $settings['site.hero_photo'] ?? $settings['site_hero_photo'] ?? null;
                        $adminHeroExists = $adminHeroPath && file_exists(public_path($adminHeroPath)) && !is_dir(public_path($adminHeroPath));
                        $adminHeroSrc = $adminHeroExists 
                            ? asset($adminHeroPath) . '?v=' . filemtime(public_path($adminHeroPath))
                            : asset('images/sayyid-muneer-portrait.png');
                    @endphp
                    <img id="hero-photo-preview" 
                         src="{{ $adminHeroSrc }}" 
                         alt="معاينة الصورة" 
                         class="w-full h-full object-cover rounded-full bg-emerald-950">
                </div>

                <div class="space-y-3 flex-1 text-center sm:text-right">
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">
                            <i class="fa-solid fa-camera text-emerald-700 ml-1"></i>
                            تغيير الصورة الشخصية لسماحة السيد:
                        </label>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            اختر صورة واضحة (مربعة أو دائرية، بصيغة JPG أو PNG أو WebP). ستظهر محاطة بالإطار الدائري الذهبي المتوهج في الصفحة الأولى.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                        <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold transition shadow-xs">
                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                            <span>رفع صورة جديدة...</span>
                            <input type="file" name="site_hero_photo_file" accept="image/*" class="hidden" onchange="previewHeroPhoto(event)">
                        </label>

                        @if(!empty($settings['site.hero_photo']))
                        <label class="inline-flex items-center gap-1.5 text-xs text-red-600 hover:text-red-700 font-semibold cursor-pointer">
                            <input type="checkbox" name="remove_hero_photo" value="1" class="rounded text-red-600 border-slate-300">
                            <span>استعادة الصورة الافتراضية الأصلية</span>
                        </label>
                        @endif
                    </div>
                    <div id="hero-photo-filename" class="text-[11px] text-emerald-800 font-semibold hidden"></div>
                </div>
            </div>

            <!-- Texts in Hero Card -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">الاسم في البطاقة التعريفية:</label>
                    <input type="text" name="site.hero_name" value="{{ $settings['site.hero_name'] ?? $settings['site_hero_name'] ?? 'سماحة السيد منير الخباز' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">نص زر السيرة الذاتية:</label>
                    <input type="text" name="site.hero_btn_text" value="{{ $settings['site.hero_btn_text'] ?? $settings['site_hero_btn_text'] ?? 'استعراض السيرة الذاتية المفصلة' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">رابط زر السيرة الذاتية:</label>
                    <input type="text" name="site.hero_btn_url" value="{{ $settings['site.hero_btn_url'] ?? $settings['site_hero_btn_url'] ?? route('bio') }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
                </div>
            </div>
        </div>

        <!-- 3. Live Stream Switcher -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-sm text-slate-800 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-tower-broadcast text-red-600"></i>
                <span>التحكم في البث المباشر</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">حالة البث:</label>
                    <select name="livestream.is_live" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 font-bold">
                        <option value="0" {{ (string)($settings['livestream.is_live'] ?? $settings['livestream_is_live'] ?? '0') === '0' ? 'selected' : '' }}>🔴 البث غير مفعل حالياً (متوقف)</option>
                        <option value="1" {{ (string)($settings['livestream.is_live'] ?? $settings['livestream_is_live'] ?? '0') === '1' ? 'selected' : '' }}>🟢 البث مباشر الآن (يظهر في الهيدر وكافة صفحات الموقع)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان المحاضرة المبثوثة:</label>
                    <input type="text" name="livestream.title" value="{{ $settings['livestream.title'] ?? $settings['livestream_title'] ?? 'البث المباشر لمحاضرات سماحة السيد' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">رابط البث (يوتيوب أو ميديا سيرفر):</label>
                <input type="text" name="livestream.url" value="{{ $settings['livestream.url'] ?? $settings['livestream_url'] ?? '' }}" placeholder="https://youtube.com/live/... أو رابط أي فيديو يوتيوب" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
            </div>
        </div>

        <!-- 4. Social Media Platforms -->
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
                    <label class="block text-xs font-semibold text-slate-700 mb-1"><i class="fa-brands fa-facebook text-blue-600"></i> فيسبوك (Facebook):</label>
                    <input type="text" name="social.facebook" value="{{ $settings['social.facebook'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1"><i class="fa-brands fa-snapchat text-amber-500"></i> سناب شات (Snapchat):</label>
                    <input type="text" name="social.snapchat" value="{{ $settings['social.snapchat'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1"><i class="fa-brands fa-tiktok text-black"></i> تيك توك (TikTok):</label>
                    <input type="text" name="social.tiktok" value="{{ $settings['social.tiktok'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1"><i class="fa-brands fa-x-twitter text-slate-800"></i> منصة إكس (Twitter):</label>
                    <input type="text" name="social.twitter" value="{{ $settings['social.twitter'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1"><i class="fa-brands fa-telegram text-sky-500"></i> تيليجرام (Telegram):</label>
                    <input type="text" name="social.telegram" value="{{ $settings['social.telegram'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
                </div>
            </div>
        </div>

        <!-- 5. Footer Content Settings -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-shoe-prints text-gold-500"></i>
                    <span>محتوى أسفل الموقع (الفوتر - Footer)</span>
                </h3>
                <a href="{{ route('admin.menus.index') }}" class="text-xs font-bold text-emerald-800 hover:underline flex items-center gap-1">
                    <i class="fa-solid fa-list-check"></i>
                    <span>إدارة روابط الفوتر في شاشة القوائم ←</span>
                </a>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">النبذة التعريفية في الفوتر (عن المركز والمنصة):</label>
                <textarea name="site.footer_about" rows="3" class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 leading-relaxed font-light">{{ $settings['site.footer_about'] ?? 'البوابة العامة لنشر المحاضرات الفكرية، ديوان الشعر، المؤلفات، والندوات، مع الربط المباشر ببوابة الدروس الحوزوية والبحث الخارج.' }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان عمود الروابط 1:</label>
                    <input type="text" name="site.footer_col1_title" value="{{ $settings['site.footer_col1_title'] ?? 'أقسام الموقع' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان عمود الروابط 2:</label>
                    <input type="text" name="site.footer_col2_title" value="{{ $settings['site.footer_col2_title'] ?? 'الخدمات والتواصل' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان عمود الحسابات:</label>
                    <input type="text" name="site.footer_col3_title" value="{{ $settings['site.footer_col3_title'] ?? 'الحسابات الرسمية المعتمدة' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">نص الدعوة لمتابعة الحسابات:</label>
                    <input type="text" name="site.footer_social_text" value="{{ $settings['site.footer_social_text'] ?? 'تابعوا جديد المحاضرات والمقاطع اليومية عبر الحسابات الرسمية الموثقة:' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">نص حقوق النشر (Copyright):</label>
                    <input type="text" name="site.copyright_text" value="{{ $settings['site.copyright_text'] ?? 'جميع الحقوق محفوظة.' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">إظهار الحديث الشريف في الفوتر:</label>
                <select name="site.footer_show_hadith" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="1" {{ ((string)($settings['site.footer_show_hadith'] ?? $settings['site_footer_show_hadith'] ?? '1')) === '1' ? 'selected' : '' }}>نعم، إظهار الحديث الشريف في أسفل النبذة</option>
                    <option value="0" {{ ((string)($settings['site.footer_show_hadith'] ?? $settings['site_footer_show_hadith'] ?? '1')) === '0' ? 'selected' : '' }}>إخفاء الحديث الشريف من الفوتر</option>
                </select>
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

@push('scripts')
<script>
    function previewHeroPhoto(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('hero-photo-preview');
                if (img) img.src = e.target.result;
            };
            reader.readAsDataURL(file);
            const nameEl = document.getElementById('hero-photo-filename');
            if (nameEl) {
                nameEl.textContent = 'الملف المختار: ' + file.name;
                nameEl.classList.remove('hidden');
            }
        }
    }

    // Dynamic UI feedback for Maintenance Mode toggle
    const toggleMaint = document.getElementById('toggle_maintenance_mode');
    const lblMaint = document.getElementById('label_maintenance_mode');
    const badgeMaint = document.getElementById('maintenance_badge');
    const cardMaint = document.getElementById('maintenance_card');

    if (toggleMaint && lblMaint) {
        toggleMaint.addEventListener('change', function() {
            if (this.checked) {
                lblMaint.textContent = 'مفعل (الموقع مغلق)';
                lblMaint.className = 'text-xs font-bold text-amber-600';
                if (badgeMaint) {
                    badgeMaint.innerHTML = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300"><span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>الموقع سيصبح مغلقاً بعد الحفظ</span>';
                }
                if (cardMaint) {
                    cardMaint.classList.add('border-amber-400', 'bg-amber-50/20');
                    cardMaint.classList.remove('border-slate-200');
                }
            } else {
                lblMaint.textContent = 'معطل (الموقع مفتوح)';
                lblMaint.className = 'text-xs font-bold text-slate-500';
                if (badgeMaint) {
                    badgeMaint.innerHTML = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>الموقع متاح للزوار</span>';
                }
                if (cardMaint) {
                    cardMaint.classList.remove('border-amber-400', 'bg-amber-50/20');
                    cardMaint.classList.add('border-slate-200');
                }
            }
        });
    }

    // Dynamic UI feedback for Contact Form toggle
    const toggleContact = document.getElementById('toggle_contact_form');
    const lblContact = document.getElementById('label_contact_form');
    if (toggleContact && lblContact) {
        toggleContact.addEventListener('change', function() {
            if (this.checked) {
                lblContact.textContent = 'مفعل (النموذج متاح)';
                lblContact.className = 'text-xs font-bold text-emerald-700';
            } else {
                lblContact.textContent = 'معطل (النموذج مغلق)';
                lblContact.className = 'text-xs font-bold text-slate-400';
            }
        });
    }
</script>
@endpush
@endsection

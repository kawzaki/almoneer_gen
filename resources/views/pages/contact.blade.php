@extends('layouts.app')

@section('title', 'تواصل مع مكتب سماحة السيد منير الخباز')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="max-w-3xl mx-auto text-center space-y-3 pb-10">
            <span class="text-xs font-bold text-gold-600 uppercase tracking-wider">الاتصال والتواصل</span>
            <h1 class="text-3xl font-bold font-scholarly text-slate-900">تواصل مع مكتب سماحة السيد (دام عطاؤه)</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            <!-- Contact Form or Official Notice (7 cols) -->
            @if(!empty($siteSettings['site.contact_form_enabled']) && $siteSettings['site.contact_form_enabled'] == '1')
            <div class="lg:col-span-7 bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-6">
                <h3 class="text-base font-bold text-slate-800 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-envelope-open-text text-emerald-800"></i>
                    <span>نموذج المراسلة المباشرة</span>
                </h3>

                <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">الاسم الكريم:</label>
                            <input type="text" name="name" required
                                class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">البريد الإلكتروني:</label>
                            <input type="email" name="email" required
                                class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50" dir="ltr">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">رقم الهاتف / واتساب
                                (اختياري):</label>
                            <input type="text" name="phone"
                                class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50" dir="ltr">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">موضوع الرسالة:</label>
                            <input type="text" name="subject" required
                                class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">نص الرسالة:</label>
                        <textarea name="message" rows="5" required
                            class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50"></textarea>
                    </div>

                    <button type="submit"
                        class="px-8 py-3 bg-emerald-800 hover:bg-emerald-900 text-gold-300 font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>إرسال الرسالة</span>
                    </button>
                </form>
            </div>
            @else
            <!-- Direct Messaging Disabled Notice (Per Official Directive) -->
            <div class="lg:col-span-7 bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-sm space-y-6 flex flex-col justify-between">
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-200 text-amber-700 flex items-center justify-center text-xl shadow-xs">
                            <i class="fa-solid fa-envelope-circle-check"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold font-scholarly text-slate-900">المراسلة والاستفسارات</h3>
                            <p class="text-xs text-slate-500">القنوات الرسمية المعتمدة للتواصل مع سماحة السيد وإدارة شبكة المنير</p>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3 text-xs sm:text-sm text-slate-700 leading-relaxed">
                        <div class="flex items-center gap-2 text-amber-800 font-bold text-xs">
                            <i class="fa-solid fa-circle-info text-amber-600"></i>
                            <span>تنويه حول نموذج المراسلة المباشرة:</span>
                        </div>
                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                            نحيطكم علماً بأن استقبال الرسائل عبر النموذج الإلكتروني متوقف حالياً.
                        </p>
                    </div>

                    <div class="space-y-3 pt-2 text-xs text-slate-600">
                        <div class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 shrink-0"></i>
                            <span><strong>الاستفتاءات والمسائل الفقهية:</strong> يرجى التوجه إلى قسم <a href="{{ route('inquiries.index') }}" class="text-emerald-800 font-bold hover:underline">«الاستفتاءات الفقهية»</a> المخصص بالموقع.</span>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                    <span>شبكة المنير — سماحة السيد منير الخباز</span>
                    <span>نسألكم الدعاء</span>
                </div>
            </div>
            @endif

            <!-- Contact Info (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <div
                    class="bg-gradient-to-br from-emerald-950 to-emerald-900 rounded-3xl p-8 text-white shadow-xl space-y-6 border border-gold-500/30">
                    <h3
                        class="text-base font-bold font-scholarly text-gold-300 pb-3 border-b border-emerald-800 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>العنوان</span>
                    </h3>

                    <div class="space-y-4 text-xs text-slate-300">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-mosque text-gold-400 text-base mt-0.5"></i>
                            <div>
                                <strong class="text-white block text-sm">النجف الأشرف</strong>
                                <p class="font-light mt-0.5">حاضرة الحوزة العلمية الشريفة - قرب الصحن الحيدري الشريف</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-landmark text-gold-400 text-base mt-0.5"></i>
                            <div>
                                <strong class="text-white block text-sm">المملكة العربية السعودية</strong>
                                <p class="font-light mt-0.5">المنطقة الشرقية - القطيف</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-envelope text-gold-400 text-base mt-0.5"></i>
                            <div>
                                <strong class="text-white block text-sm">البريد الإلكتروني المعتمد</strong>
                                <p class="font-mono mt-0.5 text-gold-300">{{ $siteSettings['site.email'] ??
                                    'info@almoneer.org' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-emerald-800">
                        <p class="text-[11px] text-slate-400 mb-2">للدروس الحوزوية وبحوث الخارج:</p>
                        <a href="{{ $hawzaPortalUrl }}" target="_blank"
                            class="text-xs font-bold text-gold-300 hover:underline flex items-center gap-1.5">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <span>زيارة بوابة الدروس الحوزوية (droos.almoneer.org) ↗</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
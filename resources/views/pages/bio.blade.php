@extends('layouts.app')

@section('title', 'نبذة عن حياة سماحة العلامة السيد منير الخباز | الموقع الرسمي')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
    
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-emerald-800">الرئيسية</a>
        <span>/</span>
        <span class="text-emerald-950 font-bold">نبذة عن حياته</span>
    </div>

    <!-- Main Bio Header Card -->
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-sm space-y-8">
        
        <div class="flex flex-col sm:flex-row items-center gap-6 pb-8 border-b border-slate-100 text-center sm:text-right">
            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl p-1 bg-gradient-to-tr from-gold-400 to-emerald-800 shadow-xl overflow-hidden flex-shrink-0 border border-gold-500/40">
                <img src="{{ asset('images/sayyid-muneer-portrait.jpg') }}" alt="سماحة العلامة السيد منير الخباز" class="w-full h-full object-cover object-top">
            </div>
            <div class="space-y-1">
                <span class="px-3 py-0.5 rounded-full bg-gold-50 text-gold-700 text-xs font-bold border border-gold-200">
                    السيرة الذاتية والعلمية
                </span>
                <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900">
                    سماحة العلامة السيد منير بن السيد عدنان الخباز (دام عزه)
                </h1>
                <p class="text-xs text-slate-500">أستاذ البحث الخارج في الحوزة العلمية والمفكر الإسلامي</p>
            </div>
        </div>

        <!-- Bio Content / Timeline -->
        <div class="prose prose-slate max-w-none text-sm sm:text-base leading-relaxed space-y-6 text-slate-700">
            @if($bioArticle)
                {!! $bioArticle->content !!}
            @else
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-emerald-900 font-scholarly mb-2">الولادة والنشأة:</h3>
                        <p>ولد سماحة العلامة السيد منير بن السيد عدنان الخباز في مدينة القطيف بالمنطقة الشرقية عام 1384هـ، ونشأ في بيت علم وفضل وتقوى، وتلقى تعليمه الأولي ومبادئ العلوم في مدينته قبل أن يشد الرحال إلى الحوزات العلمية العريقة.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-emerald-900 font-scholarly mb-2">الهجرة إلى النجف الأشرف وقم المقدسة:</h3>
                        <p>هاجر إلى حاضرة العلم الكبرى النجف الأشرف وتتلمذ على كبار علمائها، ثم انتقل إلى الحوزة العلمية في قم المقدسة لحضور أبحاث الخارج لكبار المراجع العظام، مستفيداً من مناهجهم التحقيقية الدقيقة في الفقه والأصول والرجال.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-emerald-900 font-scholarly mb-2">أبرز أساتذته في البحث الخارج:</h3>
                        <ul class="list-disc list-inside space-y-1 text-slate-600 text-sm">
                            <li>سماحة آية الله العظمى المرجع الديني الأعلى السيد علي السيستاني (دام ظله)</li>
                            <li>سماحة آية الله العظمى السيد أبو القاسم الخوئي (قدس سره)</li>
                            <li>سماحة آية الله العظمى الشيخ الميرزا جواد التبريزي (قدس سره)</li>
                            <li>سماحة آية الله العظمى الشيخ الوحيد الخراساني (دام ظله)</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-emerald-900 font-scholarly mb-2">المشروع الفكري والتبليغي:</h3>
                        <p>يتميز سماحته بطرحه الفكري المعاصر الذي يمزج بين دقة التحقيق الحوزوي ومخاطبة العقل الحديث، وله جولات تبليغية سنوية واسعة في الولايات المتحدة، المملكة المتحدة، وأرجاء العالم الإسلامي، بالإضافة إلى نتاجه الزاخر في حوار الملاحدة، تفكيك الشبهات المعاصرة، وإثراء الفكر الإسلامي.</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('books.index') }}" class="px-5 py-2.5 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-gold-300 font-bold text-xs shadow transition flex items-center gap-2">
                <i class="fa-solid fa-book-bookmark"></i>
                <span>استعراض مؤلفات سماحة السيد</span>
            </a>
            <a href="{{ $hawzaPortalUrl }}" target="_blank" class="text-xs text-gold-600 hover:text-gold-700 font-bold flex items-center gap-1">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>متابعة دروس البحث الخارج لسماحته ↗</span>
            </a>
        </div>

    </div>

</div>
@endsection

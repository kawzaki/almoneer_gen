@extends('layouts.app')

@section('title', $poem->title . ' | ديوان العلامة المنير')

@push('styles')
<style>
    @media print {
        nav, header, footer,
        .reading-controls-bar,
        .no-print,
        button,
        a[href] {
            display: none !important;
        }

        body {
            background: #ffffff !important;
            color: #0f172a !important;
            font-size: 13.5pt !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .poem-container-card {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }

        .couplet-line {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            margin-bottom: 12pt !important;
            font-size: 14pt !important;
            border-bottom: 1px dotted #e2e8f0;
            padding-bottom: 4pt;
        }

        .couplet-first, .couplet-second {
            width: 46% !important;
            text-align: justify !important;
        }

        .print-poem-footer {
            display: block !important;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #cbd5e1;
            padding-top: 4pt;
            font-size: 8pt;
            color: #64748b;
        }

        @page {
            size: A4;
            margin: 15mm 15mm 18mm 15mm;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 space-y-6">

    <!-- Top Navigation & Action Toolbar -->
    <div class="no-print flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-emerald-800 transition">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('poems.index') }}" class="hover:text-emerald-800 transition">ديوان الشعر</a>
            <span>/</span>
            <span class="text-emerald-950 font-bold truncate max-w-xs sm:max-w-md">{{ $poem->title }}</span>
        </div>

        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-2xl border border-slate-200 shadow-2xs text-xs">
            <button type="button" onclick="copyPoemShortUrl('https://almoneer.org/p/{{ $poem->id }}')" id="poem-share-btn"
                class="px-2.5 py-1 hover:bg-slate-100 rounded-lg transition flex items-center gap-1.5 text-slate-700"
                title="نسخ الرابط المختصر للقصيدة">
                <i class="fa-solid fa-share-nodes text-slate-500"></i>
                <span class="font-mono text-slate-600 font-semibold" lang="en" dir="ltr" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important; font-variant-numeric: lining-nums tabular-nums !important; font-feature-settings: 'locl' 0 !important; unicode-bidi: isolate;">almoneer.org/p/{{ $poem->id }}</span>
            </button>
            <div class="h-4 w-[1px] bg-slate-200"></div>
            <button type="button" onclick="window.print()"
                class="px-2.5 py-1 hover:bg-emerald-50 text-emerald-800 rounded-lg transition flex items-center gap-1 font-bold"
                title="طباعة القصيدة">
                <i class="fa-solid fa-print text-emerald-700"></i>
                <span>طباعة</span>
            </button>
        </div>
    </div>

    <!-- Main Poem Presentation Card -->
    <div class="poem-container-card bg-white rounded-3xl p-8 sm:p-12 border border-gold-500/40 shadow-sm space-y-8 text-center">
        
        <!-- Header Banner & Metadata -->
        <div class="space-y-4 pb-6 border-b border-slate-100">
            
            <div class="flex flex-wrap items-center justify-center gap-2">
                @if(!empty($poem->occasion))
                    <span class="px-3.5 py-1 rounded-full bg-gold-50 text-gold-800 text-xs font-bold border border-gold-300 shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-feather-pointed text-gold-600 text-[11px]"></i>
                        <span>{{ $poem->occasion }}</span>
                    </span>
                @endif

                @if(!empty($poem->poem_date))
                    <span class="px-3.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200 flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar-days text-slate-500 text-[11px]"></i>
                        <span>{{ $poem->poem_date }}</span>
                    </span>
                @endif

                @if(!empty($poem->meter))
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-semibold border border-emerald-200">
                        {{ $poem->meter }}
                    </span>
                @endif
            </div>

            <h1 class="text-2xl sm:text-4xl font-bold font-scholarly text-emerald-950 pt-2 leading-tight">
                {{ $poem->title }}
            </h1>

            <p class="text-xs text-slate-500 font-scholarly">
                نظم: سماحة العلامة السيد منير الخباز (دام عزه)
            </p>

            <!-- Poem Specific Image (Displayed ONLY inside the topic) -->
            @if(!empty($poem->image))
                <div class="pt-4 max-w-md mx-auto">
                    <div class="rounded-2xl overflow-hidden shadow-md border border-gold-500/40 bg-slate-50 p-1">
                        <img src="{{ asset($poem->image) }}" alt="{{ $poem->title }}" class="w-full h-auto max-h-96 object-cover rounded-xl mx-auto">
                    </div>
                </div>
            @endif

            <!-- Enhanced Description Container -->
            @if(!empty($poem->description))
                <div class="max-w-2xl mx-auto mt-4 p-5 sm:p-6 rounded-2xl bg-gradient-to-br from-amber-50/70 via-sand-50/40 to-amber-100/50 border border-amber-200/90 text-right shadow-2xs">
                    <div class="flex items-center gap-2 mb-2 text-xs font-bold text-amber-900 border-b border-amber-200/70 pb-2">
                        <i class="fa-solid fa-scroll text-gold-600"></i>
                        <span>عن المناسبة وخلفية النظم:</span>
                    </div>
                    <div class="text-xs sm:text-sm text-slate-700 leading-loose font-scholarly whitespace-pre-line">
                        {{ $poem->description }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Couplets Full List -->
        <div class="space-y-4 py-6 max-w-2xl mx-auto text-base sm:text-xl font-scholarly leading-loose">
            @foreach($poem->couplets as $index => $c)
            <div class="couplet-line flex flex-col sm:flex-row items-center justify-between gap-2 sm:gap-4 py-2 border-b border-dotted border-slate-100 hover:bg-slate-50/60 rounded-xl px-2 transition">
                <span class="couplet-first text-slate-900 font-medium text-center sm:text-right w-full sm:w-[46%]">{{ $c['first'] }}</span>
                <span class="text-gold-500 text-xs sm:text-sm shrink-0">✤</span>
                <span class="couplet-second text-slate-800 text-center sm:text-left w-full sm:w-[46%]">{{ $c['second'] }}</span>
            </div>
            @endforeach
        </div>

        <!-- Footer Card Details -->
        <div class="no-print pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
            <a href="{{ route('poems.index') }}" class="text-emerald-800 font-bold hover:underline flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                <span>العودة لديوان الشعر</span>
            </a>
            <span>
                عدد الأبيات: 
                @php $cnt = count($poem->couplets); @endphp
                @if($cnt == 1)
                    بيت واحد
                @elseif($cnt == 2)
                    بيتان
                @elseif($cnt >= 3 && $cnt <= 10)
                    {{ $cnt }} أبيات
                @else
                    {{ $cnt }} بيتاً
                @endif
            </span>
        </div>

        <!-- Print-only footer -->
        <div class="print-poem-footer hidden">
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 8.5pt; color: #64748b; direction: rtl; font-family: 'IBM Plex Sans Arabic', sans-serif;">
                <span>ديوان شبكة المنير — سماحة العلامة السيد منير الخباز</span>
                <span lang="en" dir="ltr" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important; font-variant-numeric: lining-nums tabular-nums !important; font-size: 8.5pt; color: #1e293b; font-weight: bold; unicode-bidi: isolate;">almoneer.org/p/{{ $poem->id }}</span>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
function copyPoemShortUrl(url) {
    navigator.clipboard.writeText(url).then(() => {
        const btn = document.getElementById('poem-share-btn');
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check text-emerald-600"></i> <span class="text-emerald-700 font-bold">تم نسخ الرابط!</span>';
        setTimeout(() => { btn.innerHTML = orig; }, 2500);
    });
}
</script>
@endpush
@endsection

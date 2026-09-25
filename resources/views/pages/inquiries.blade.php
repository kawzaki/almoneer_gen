@extends('layouts.app')

@section('title', 'استفسارات وفتاوى | شبكة العلامة المنير')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <span class="text-xs font-bold text-gold-600 uppercase tracking-wider">نافذة الاستفسارات والفتاوى</span>
                <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900 mt-1">الأسئلة والمسائل الفكرية
                    والشرعية</h1>
            </div>

            <a href="{{ route('inquiries.track') }}"
                class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>متابعة حالة استفسار سابق برقم التتبع</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            <!-- Left 7 Cols: Search & Public Q&A Archive -->
            <div class="lg:col-span-7 space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-lg text-slate-900 font-scholarly">أرشيف الأسئلة المجابة</h2>
                    <span class="text-xs text-slate-400">إجابات على ضوء فتاوي الأعلام الثلاثة (السيد الخوئي قدس والشيخ
                        التبريزي قده والسيد السيستاني دام ظله</span>
                </div>

                <!-- Search Form -->
                <form action="{{ route('inquiries.index') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="ابحث في نص السؤال أو المسألة..."
                        class="flex-grow text-xs rounded-xl border-slate-200 p-3 bg-white shadow-sm focus:ring-emerald-800 focus:border-emerald-800">
                    <button type="submit"
                        class="px-5 py-3 bg-emerald-800 hover:bg-emerald-900 text-gold-300 font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>بحث</span>
                    </button>
                </form>

                <!-- Q&A List -->
                <div class="space-y-4">
                    @forelse($inquiries as $inq)
                        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                            <div
                                class="flex items-center justify-between text-xs text-slate-400 pb-2 border-b border-slate-100">
                                <span
                                    class="px-2.5 py-0.5 rounded bg-gold-50 text-gold-700 font-semibold">{{ $inq->category->name ?? 'مسألة عامة' }}</span>
                                @if($inq->answered_at)
                                    <span class="inline-flex items-center gap-0.5" dir="rtl">
                                        <span>{{ $inq->answered_at->format('d') }}</span>/<span>{{ $inq->answered_at->format('m') }}</span>/<span>{{ $inq->answered_at->format('Y') }}</span>
                                    </span>
                                @endif
                            </div>

                            <div>
                                <h4 class="font-bold text-sm text-slate-800 leading-snug flex items-start gap-2">
                                    <span class="text-emerald-800 font-bold">س:</span>
                                    <span>{{ $inq->question }}</span>
                                </h4>
                            </div>

                            @if($inq->answer)
                                <div
                                    class="p-4 rounded-xl bg-sand-50 border-r-4 border-emerald-800 text-xs sm:text-sm text-slate-700 leading-relaxed space-y-1">
                                    <span class="font-bold text-emerald-950 block">الجواب:</span>
                                    <p class="font-light">{!! nl2br(e($inq->answer)) !!}</p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-8 text-center bg-white rounded-2xl border border-slate-200 text-slate-500 text-xs">
                            لا توجد استفسارات مطابقة لبحثكم في الأرشيف العام.
                        </div>
                    @endforelse
                </div>

                <div>
                    {{ $inquiries->links() }}
                </div>
            </div>

            <!-- Right 5 Cols: Submit Inquiry Form -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-8 border border-gold-500/30 shadow-lg space-y-6 sticky top-6">
                    <div class="space-y-1 pb-4 border-b border-slate-100">
                        <h3 class="text-lg font-bold font-scholarly text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-pen-nib text-gold-500"></i>
                            <span>طرح استفسار جديد</span>
                        </h3>
                        <p class="text-xs text-slate-500">سيتم تزويدك برقم تتبع خاص فور الإرسال.</p>
                    </div>

                    <form action="{{ route('inquiries.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">الاسم أو اللقب:</label>
                            <input type="text" name="name" required placeholder="مثال: أبو حسن"
                                class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">البريد الإلكتروني (لتلقي الإشعار
                                بالرد):</label>
                            <input type="email" name="email" required placeholder="name@example.com"
                                class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">الدولة أو المدينة:</label>
                            <input type="text" name="country" placeholder="مثال: الكويت / لندن"
                                class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">نص السؤال أو المسألة:</label>
                            <textarea name="question" rows="5" required placeholder="اكتب سؤالك بوضوح وتفصيل..."
                                class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full py-3 bg-gradient-to-r from-gold-500 to-gold-600 hover:from-gold-400 hover:to-gold-500 text-emerald-950 font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>إرسال الاستفسار واعتماد رقم التتبع</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
@endsection
@extends('layouts.app')

@section('title', 'ديوان الشعر والقصائد | شبكة العلامة المنير')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="flex flex-wrap items-end justify-between gap-4 pb-6 border-b border-slate-200 mb-8">
        <div>
            <span class="text-xs font-bold text-gold-600 uppercase tracking-wider">ديوان الشعر</span>
            <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900 mt-1">القصائد الولائية والوجدانية</h1>
        </div>
    </div>

    <!-- Poems Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @forelse($poems as $poem)
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-6">
            <div>
                <div class="flex items-center justify-between text-xs text-slate-400 mb-3">
                    @if(!empty($poem->occasion))
                        <span class="px-2.5 py-0.5 rounded bg-gold-50 text-gold-700 font-semibold">{{ $poem->occasion }}</span>
                    @else
                        <span></span>
                    @endif
                    @if(!empty($poem->meter))
                        <span>{{ $poem->meter }}</span>
                    @endif
                </div>

                <h3 class="font-bold text-xl font-scholarly text-emerald-950 mb-4 hover:text-gold-600 transition">
                    <a href="{{ route('poems.show', $poem->slug) }}">{{ $poem->title }}</a>
                </h3>

                <!-- Couplets Preview (First 2 couplets) -->
                <div class="space-y-3 text-sm sm:text-base font-scholarly leading-loose bg-sand-50 p-4 rounded-2xl border border-dashed border-gold-500/30">
                    @foreach(array_slice($poem->couplets, 0, 2) as $c)
                    <div class="couplet-line text-center sm:text-right">
                        <span class="text-slate-900 font-medium">{{ $c['first'] }}</span>
                        <span class="text-gold-500 text-xs hidden sm:inline">✤</span>
                        <span class="text-slate-700">{{ $c['second'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-400">
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
                <a href="{{ route('poems.show', $poem->slug) }}" class="px-4 py-2 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-gold-300 font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                    <span>قراءة القصيدة كاملة</span>
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-2 text-center py-12 bg-white rounded-2xl border border-slate-200 text-slate-500">
            لا توجد قصائد مدرجة في الديوان حالياً.
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $poems->links() }}
    </div>

</div>
@endsection

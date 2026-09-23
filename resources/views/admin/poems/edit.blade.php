@extends('layouts.admin')

@section('title', 'تعديل القصيدة')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">تعديل: {{ $poem->title }}</h2>
        <a href="{{ route('admin.poems.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← العودة للديوان</a>
    </div>

    <form action="{{ route('admin.poems.update', $poem->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان القصيدة:</label>
            <input type="text" name="title" value="{{ $poem->title }}" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">المناسبة:</label>
                <input type="text" name="occasion" value="{{ $poem->occasion }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">تاريخ القصيدة / المناسبة:</label>
                <input type="text" name="poem_date" value="{{ $poem->poem_date }}" placeholder="مثال: 10 محرم 1445هـ أو 2024م" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">بحر الشعر (اختياري):</label>
                <input type="text" name="meter" value="{{ $poem->meter }}" placeholder="مثال: بحر البسيط / الطويل" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
        </div>

        <!-- Poem Image (Displays ONLY inside poem page) -->
        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
            <label class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-image text-emerald-800"></i>
                    <span>صورة مخصصة للقصيدة (تُعرض فقط داخل صفحة القصيدة):</span>
                </span>
                <span class="text-slate-400 font-normal text-[10px]">(اختياري)</span>
            </label>
            @if($poem->image)
                <div class="mb-2 flex items-center gap-3">
                    <img src="{{ asset($poem->image) }}" alt="صورة القصيدة" class="w-20 h-20 object-cover rounded-lg border border-slate-200 shadow-sm">
                    <span class="text-[11px] text-slate-500">الصورة الحالية مرفوعة. يمكنك اختيار ملف جديد لاستبدالها.</span>
                </div>
            @endif
            <input type="file" name="image" accept="image/*" class="w-full text-xs rounded-xl border border-slate-200 p-2 bg-white cursor-pointer file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-800 file:text-white hover:file:bg-emerald-900">
            <p class="text-[10px] text-slate-400 mt-1">تظهر هذه الصورة كغلاف فني داخل موضوع القصيدة فقط، ولا تظهر في شبكة بطاقات الديوان العامة للحفاظ على رونق النص.</p>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">أبيات القصيدة (صدر | عجز):</label>
            <textarea name="verses" rows="10" required class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 font-scholarly text-sm leading-loose">{{ $poem->verses }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">الوصف والمناسبة:</label>
            <textarea name="description" rows="3" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">{{ $poem->description }}</textarea>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ $poem->is_featured ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>عرض في الصفحة الرئيسية</span>
            </label>
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ $poem->is_active ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>مفعلة ونشطة</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow">تحديث القصيدة وتفريغ الكاش</button>
        </div>
    </form>
</div>
@endsection

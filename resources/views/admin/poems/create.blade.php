@extends('layouts.admin')

@section('title', 'إضافة قصيدة جديدة للديوان')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">إضافة قصيدة جديدة</h2>
        <a href="{{ route('admin.poems.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← العودة للديوان</a>
    </div>

    <form action="{{ route('admin.poems.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان القصيدة:</label>
            <input type="text" name="title" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">المناسبة:</label>
                <input type="text" name="occasion" placeholder="مثال: في رثاء سيد الشهداء (ع)" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">تاريخ القصيدة / المناسبة:</label>
                <input type="text" name="poem_date" placeholder="مثال: 10 محرم 1445هـ أو 2024م" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">بحر الشعر (اختياري):</label>
                <input type="text" name="meter" placeholder="مثال: بحر البسيط / الطويل" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
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
            <input type="file" name="image" accept="image/*" class="w-full text-xs rounded-xl border border-slate-200 p-2 bg-white cursor-pointer file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-800 file:text-white hover:file:bg-emerald-900">
            <p class="text-[10px] text-slate-400 mt-1">تظهر هذه الصورة كغلاف فني داخل موضوع القصيدة فقط، ولا تظهر في شبكة بطاقات الديوان العامة للحفاظ على رونق النص.</p>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">أبيات القصيدة (كل بيت في سطر، مع الفصل بين الصدر والعجز بالرمز | ):</label>
            <textarea name="verses" rows="10" required placeholder="قف بالطفوف وجُد بالدمع منسكبا | والثم تراباً به نبل الهدى انسكبا" class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 font-scholarly text-sm leading-loose"></textarea>
            <p class="text-[11px] text-slate-400 mt-1">ملاحظة: استخدام علامة الشارطة الرأسية | يفصل تلقائياً بين الصدر والعجز متجاوباً مع الجوال.</p>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">نبذة عن مناسبة النظم وتفاصيلها:</label>
            <textarea name="description" rows="3" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50"></textarea>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" class="rounded text-emerald-800">
                <span>عرض في الصفحة الرئيسية</span>
            </label>
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="rounded text-emerald-800">
                <span>مفعلة ونشطة</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow">حفظ القصيدة في الديوان</button>
        </div>
    </form>
</div>
@endsection

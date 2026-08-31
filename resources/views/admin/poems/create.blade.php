@extends('layouts.admin')

@section('title', 'إضافة قصيدة جديدة للديوان')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">إضافة قصيدة جديدة</h2>
        <a href="{{ route('admin.poems.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← العودة للديوان</a>
    </div>

    <form action="{{ route('admin.poems.store') }}" method="POST" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان القصيدة:</label>
            <input type="text" name="title" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">المناسبة:</label>
                <input type="text" name="occasion" placeholder="مثال: في رثاء سيد الشهداء (ع)" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">بحر الشعر:</label>
                <input type="text" name="meter" placeholder="مثال: بحر البسيط / الطويل" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
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

@extends('layouts.admin')

@section('title', 'تعديل القصيدة')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">تعديل: {{ $poem->title }}</h2>
        <a href="{{ route('admin.poems.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← العودة للديوان</a>
    </div>

    <form action="{{ route('admin.poems.update', $poem->id) }}" method="POST" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان القصيدة:</label>
            <input type="text" name="title" value="{{ $poem->title }}" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">المناسبة:</label>
                <input type="text" name="occasion" value="{{ $poem->occasion }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">بحر الشعر:</label>
                <input type="text" name="meter" value="{{ $poem->meter }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
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

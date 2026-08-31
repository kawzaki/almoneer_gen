@extends('layouts.admin')

@section('title', 'تعديل ألبوم الصور')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">تعديل: {{ $album->title }}</h2>
        <a href="{{ route('admin.gallery.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← العودة للألبومات</a>
    </div>

    <form action="{{ route('admin.gallery.update', $album->id) }}" method="POST" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان الألبوم:</label>
            <input type="text" name="title" value="{{ $album->title }}" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">تاريخ المناسبة:</label>
                <input type="date" name="event_date" value="{{ $album->event_date ? $album->event_date->format('Y-m-d') : '' }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">رابط صورة الغلاف:</label>
                <input type="text" name="cover_image" value="{{ $album->cover_image }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">وصف الألبوم:</label>
            <textarea name="description" rows="3" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">{{ $album->description }}</textarea>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ $album->is_active ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>مفعل ونشط</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow">تحديث الألبوم وتفريغ الكاش</button>
        </div>
    </form>
</div>
@endsection

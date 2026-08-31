@extends('layouts.admin')

@section('title', 'إضافة خبر أو نشاط جديد')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">إضافة خبر أو نشاط جديد</h2>
        <a href="{{ route('admin.articles.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← العودة للقائمة</a>
    </div>

    <form action="{{ route('admin.articles.store') }}" method="POST" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان الخبر / النشاط:</label>
            <input type="text" name="title" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">النوع:</label>
                <select name="type" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="news">خبر عام</option>
                    <option value="activity">نشاط / جولة تبليغية</option>
                    <option value="bio">سيرة ذاتية</option>
                    <option value="article">مقال فكري</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">التصنيف:</label>
                <select name="category_id" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="">بدون تصنيف</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">ملخص مختصر (يظهر في البطاقات الرئيسية):</label>
            <textarea name="summary" rows="2" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50"></textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">المحتوى الكامل (HTML مدعوم):</label>
            <textarea name="content" rows="10" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 font-mono"></textarea>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" class="rounded text-emerald-800">
                <span>تثبيت في البانر الرئيسي للموقع</span>
            </label>
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="rounded text-emerald-800">
                <span>مفعل ونشط</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow">حفظ ونشر</button>
        </div>
    </form>
</div>
@endsection

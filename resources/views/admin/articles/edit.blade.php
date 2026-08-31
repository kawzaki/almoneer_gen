@extends('layouts.admin')

@section('title', 'تعديل الخبر')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">تعديل: {{ $article->title }}</h2>
        <a href="{{ route('admin.articles.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← العودة للقائمة</a>
    </div>

    <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان الخبر / النشاط:</label>
            <input type="text" name="title" value="{{ $article->title }}" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">النوع:</label>
                <select name="type" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="news" {{ $article->type === 'news' ? 'selected' : '' }}>خبر عام</option>
                    <option value="activity" {{ $article->type === 'activity' ? 'selected' : '' }}>نشاط / جولة تبليغية</option>
                    <option value="bio" {{ $article->type === 'bio' ? 'selected' : '' }}>سيرة ذاتية</option>
                    <option value="article" {{ $article->type === 'article' ? 'selected' : '' }}>مقال فكري</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">التصنيف:</label>
                <select name="category_id" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="">بدون تصنيف</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $article->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">ملخص مختصر:</label>
            <textarea name="summary" rows="2" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">{{ $article->summary }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">المحتوى الكامل (HTML مدعوم):</label>
            <textarea name="content" rows="10" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 font-mono">{{ $article->content }}</textarea>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ $article->is_featured ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>تثبيت في البانر الرئيسي للموقع</span>
            </label>
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ $article->is_active ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>مفعل ونشط</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow">تحديث الخبر وتفريغ الكاش</button>
        </div>
    </form>
</div>
@endsection

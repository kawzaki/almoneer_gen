@extends('layouts.admin')

@section('title', 'تعديل بيانات الكتاب')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">تعديل: {{ $book->title }}</h2>
        <a href="{{ route('admin.books.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← العودة للمكتبة</a>
    </div>

    <form action="{{ route('admin.books.update', $book->id) }}" method="POST" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان الكتاب:</label>
                <input type="text" name="title" value="{{ $book->title }}" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">المؤلف:</label>
                <input type="text" name="author" value="{{ $book->author }}" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">دار النشر:</label>
                <input type="text" name="publisher" value="{{ $book->publisher }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">سنة النشر:</label>
                <input type="text" name="publication_year" value="{{ $book->publication_year }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">عدد الصفحات:</label>
                <input type="number" name="pages_count" value="{{ $book->pages_count }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">ملخص عام:</label>
            <textarea name="summary" rows="3" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">{{ $book->summary }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">فهرس الموضوعات:</label>
            <textarea name="table_of_contents" rows="6" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">{{ $book->table_of_contents }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">مسار ملف الـ PDF:</label>
                <input type="text" name="pdf_file" value="{{ $book->pdf_file }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">رابط الشراء (اختياري):</label>
                <input type="text" name="buy_url" value="{{ $book->buy_url }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
            </div>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ $book->is_featured ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>إبراز في الصفحة الرئيسية</span>
            </label>
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ $book->is_active ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>مفعل ونشط</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow">تحديث الكتاب وتفريغ الكاش</button>
        </div>
    </form>
</div>
@endsection

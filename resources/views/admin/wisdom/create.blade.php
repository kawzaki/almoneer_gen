@extends('layouts.admin')

@section('title', 'إضافة كلمة أسبوع جديدة')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">إضافة كلمة أسبوع / قبس جديد</h2>
        <a href="{{ route('admin.wisdom.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← العودة للقائمة</a>
    </div>

    <form action="{{ route('admin.wisdom.store') }}" method="POST" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان النافذة:</label>
                <input type="text" name="title" value="من حكم أمير المؤمنين (عليه السلام)" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">المصدر / القائل:</label>
                <input type="text" name="source" value="الإمام علي بن أبي طالب (ع)" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">نص الحكمة / الكلمة:</label>
            <textarea name="quote" rows="4" required placeholder="عليك بالرضا في الشدة والرخاء..." class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 font-scholarly text-sm"></textarea>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="rounded text-emerald-800">
                <span>تفعيل ونشر هذه الحكمة ككلمة الأسبوع النشطة حالياً</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow">حفظ ونشر</button>
        </div>
    </form>
</div>
@endsection

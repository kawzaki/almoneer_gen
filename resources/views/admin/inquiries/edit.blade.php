@extends('layouts.admin')

@section('title', 'مراجعة والرد على الاستفسار')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">مراجعة الاستفسار ({{ $inquiry->tracking_code }})</h2>
        <a href="{{ route('admin.inquiries.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← العودة للاستفسارات</a>
    </div>

    <!-- Inquiry Question Details Box -->
    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-4 text-xs text-slate-500 pb-3 border-b border-slate-200">
            <span><strong>السائل:</strong> {{ $inquiry->name }}</span>
            <span><strong>البريد:</strong> {{ $inquiry->email }}</span>
            <span><strong>الدولة:</strong> {{ $inquiry->country ?? 'غير محدد' }}</span>
            <span><strong>تاريخ الإرسال:</strong> {{ $inquiry->created_at ? $inquiry->created_at->format('Y-m-d H:i') : '' }}</span>
        </div>

        <div class="space-y-1">
            <h4 class="font-bold text-xs text-slate-600">نص السؤال:</h4>
            <p class="text-sm font-semibold text-slate-900 leading-relaxed bg-white p-4 rounded-xl border border-slate-200">{{ $inquiry->question }}</p>
        </div>
    </div>

    <!-- Answer Form -->
    <form action="{{ route('admin.inquiries.update', $inquiry->id) }}" method="POST" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        @csrf @method('PUT')
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">حالة الاستفسار:</label>
                <select name="status" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="new" {{ $inquiry->status === 'new' ? 'selected' : '' }}>جديد</option>
                    <option value="in_review" {{ $inquiry->status === 'in_review' ? 'selected' : '' }}>قيد المراجعة لدى اللجنة</option>
                    <option value="answered" {{ $inquiry->status === 'answered' ? 'selected' : '' }}>تمت الإجابة والاعتماد</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">التصنيف الموضوعي:</label>
                <select name="category_id" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="">بدون تصنيف</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $inquiry->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">نص الجواب المعتمد:</label>
            <textarea name="answer" rows="8" placeholder="اكتب الجواب الشرعي أو الفكري هنا..." class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 leading-relaxed">{{ $inquiry->answer }}</textarea>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" {{ $inquiry->is_published ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>نشر هذا السؤال والجواب في بنك الفتاوى والاستفسارات العام</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow">حفظ واعتماد الجواب</button>
        </div>
    </form>
</div>
@endsection

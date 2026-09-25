@extends('layouts.admin')

@section('title', 'مراجعة والرد على الاستفسار')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">مراجعة الاستفسار ({{ $inquiry->tracking_code }})</h2>
        <a href="{{ route('admin.inquiries.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← العودة للاستفسارات</a>
    </div>

    <!-- Inquiry Metadata Box -->
    <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200">
        <div class="flex flex-wrap items-center justify-between gap-4 text-xs text-slate-500">
            <span><strong>السائل:</strong> {{ $inquiry->name }}</span>
            <span><strong>البريد:</strong> {{ $inquiry->email }}</span>
            <span><strong>الدولة:</strong> {{ $inquiry->country ?? 'غير محدد' }}</span>
            <span><strong>تاريخ الإرسال:</strong> {{ $inquiry->created_at ? $inquiry->created_at->format('Y-m-d H:i') : '' }}</span>
        </div>
    </div>

    <!-- Edit & Answer Form -->
    <form action="{{ route('admin.inquiries.update', $inquiry->id) }}" method="POST" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-5">
        @csrf @method('PUT')
        
        <!-- Editable Question -->
        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <label for="question" class="block text-xs font-bold text-slate-700">نص السؤال:</label>
                <span class="text-[11px] text-slate-400">يمكنك تعديل نص السؤال لتصحيح الأخطاء اللغوية أو الإملائية</span>
            </div>
            <textarea id="question" name="question" rows="4" required class="w-full text-xs sm:text-sm font-semibold text-slate-900 rounded-xl border-slate-200 p-3.5 bg-slate-50 focus:bg-white focus:border-emerald-600 transition leading-relaxed">{{ old('question', $inquiry->question) }}</textarea>
            @error('question')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">حالة الاستفسار:</label>
                <select name="status" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="new" {{ old('status', $inquiry->status) === 'new' ? 'selected' : '' }}>جديد</option>
                    <option value="in_review" {{ old('status', $inquiry->status) === 'in_review' ? 'selected' : '' }}>قيد المراجعة لدى اللجنة</option>
                    <option value="answered" {{ old('status', $inquiry->status) === 'answered' ? 'selected' : '' }}>تمت الإجابة والاعتماد</option>
                </select>
                @error('status')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">التصنيف الموضوعي:</label>
                <select name="category_id" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="">بدون تصنيف</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $inquiry->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">نص الجواب المعتمد:</label>
            <textarea name="answer" rows="8" placeholder="اكتب الجواب الشرعي أو الفكري هنا..." class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:border-emerald-600 transition leading-relaxed">{{ old('answer', $inquiry->answer) }}</textarea>
            @error('answer')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $inquiry->is_published) ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>نشر هذا السؤال والجواب في بنك الفتاوى والاستفسارات العام</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow">حفظ واعتماد التعديلات</button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'إضافة مادة إعلامية جديدة')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">إضافة مادة صوتية أو مرئية</h2>
        <a href="{{ route('admin.media.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← العودة للقائمة</a>
    </div>

    <form action="{{ route('admin.media.store') }}" method="POST" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان المادة الإعلامية:</label>
            <input type="text" name="title" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">النوع:</label>
                <select name="type" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="audio">محاضرة صوتية (MP3)</option>
                    <option value="video">محاضرة مرئية (يوتيوب)</option>
                    <option value="short">ريلز / شورتس قصير</option>
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
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">الموسم / السنة:</label>
                <input type="text" name="season_year" placeholder="مثال: عاشوراء 1447هـ" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    رابط الميديا (يوتيوب أو صوتي):
                    <span class="text-slate-400 font-normal text-[11px]">(اختياري - في حال عدم توفر الفيديو بعد)</span>
                </label>
                <input type="text" name="media_url" placeholder="https://..." class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">المدة (مثال: 45:10):</label>
                <input type="text" name="duration" placeholder="45:10" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 text-center font-mono">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">الكلمات الدلالية والوسوم (Tags):</label>
            <input type="text" name="tags" placeholder="مثال: عاشوراء، العقيدة، الفلسفة، العدل الإلهي، الأخلاق" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            <p class="text-[11px] text-slate-400 mt-1">افصل بين الكلمات الدلالية بفواصل (، أو ,) لتسهيل الفلترة والبحث للمستخدمين.</p>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">الوصف أو الملخص:</label>
            <textarea name="description" rows="3" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50"></textarea>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-semibold text-slate-700">تفريغ المحاضرة المكتوب (Transcript):</label>
                <button type="button" onclick="cleanTranscriptWithVacum()" class="px-3 py-1 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-900 text-xs font-bold transition flex items-center gap-1.5 shadow-sm border border-amber-300/60">
                    <i class="fa-solid fa-broom text-amber-700"></i>
                    <span>تنظيف وتنسيق النص (المخمة)</span>
                </button>
            </div>
            <textarea name="transcript" id="transcript-input" rows="8" class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 font-mono leading-relaxed"></textarea>
            <p class="text-[11px] text-slate-400 mt-1">يمكنك لصق النص المنسوخ من Word والضغط على زر "تنظيف وتنسيق النص (المخمة)" لضبط علامات الترقيم والأقواس والأرقام والآيات آلياً.</p>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" class="rounded text-emerald-800">
                <span>إبراز في الصفحة الرئيسية</span>
            </label>
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="rounded text-emerald-800">
                <span>مفعل ونشط</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow">حفظ المادة الإعلامية وتحديث الكاش</button>
        </div>
    </form>
</div>

<script>
function cleanTranscriptWithVacum() {
    const el = document.getElementById('transcript-input');
    let text = el.value;
    if (!text.trim()) {
        alert('يرجى كتابة أو لصق النص أولاً');
        return;
    }
    text = text.replace(/,/g, "،")
               .replace(/\،\s*/g, "، ")
               .replace(/\.\s*/g, ". ")
               .replace(/:\s*/g, ": ")
               .replace(/؛\s*/g, "؛ ")
               .replace(/؟\s*/g, "؟ ")
               .replace(/!\s*/g, "! ")
               .replace(/\(\(/g, "«")
               .replace(/\)\)/g, "»")
               .replace(/«\s*/g, " «")
               .replace(/\s*»/g, "» ")
               .replace(/"([^"]+?)"/g, " ”$1“ ")
               .replace(/﴿/g, "{")
               .replace(/﴾/g, "}")
               .replace(/–/g, "-")
               .replace(/(?<!\w)-(?!\w)/g, " - ")
               .replace(/[٠-٩]/g, d => "٠١٢٣٤٥٦٧٨٩".indexOf(d))
               .replace(/[\t]+/g, " ")
               .replace(/[ ]{2,}/g, " ")
               .replace(/^[ \t]+|[ \t]+$/gm, "")
               .replace(/ـ/g, "")
               .replace(/\sه\s/g, " هـ ")
               .replace(/«صلى الله عليه وآله وسلم»/g, "(ص)")
               .replace(/"صلى الله عليه وآله وسلم"/g, "(ص)")
               .replace(/صلى الله عليه وآله وسلم/g, "(ص)")
               .replace(/«صلى الله عليه وآله»/g, "(ص)")
               .replace(/صلى الله عليه وآله/g, "(ص)")
               .replace(/«عليه السلام»/g, "(ع)")
               .replace(/"عليه السلام"/g, "(ع)")
               .replace(/عليه السلام/g, "(ع)")
               .replace(/«عليهم السلام»/g, "(عع)")
               .replace(/عليهم السلام/g, "(عع)")
               .replace(/«عليها السلام»/g, "(عه)")
               .replace(/عليها السلام/g, "(عه)")
               .replace(/\. \./g, "..")
               .replace(/\، \،/g, "،،")
               .replace(/\s+،/g, "،")
               .replace(/\s+\./g, ".")
               .replace(/\s+:/g, ":")
               .replace(/«\s+/g, "«")
               .replace(/\s+»/g, "»")
               .replace(/\{\s+/g, "{")
               .replace(/\s+\}/g, "}")
               .replace(/\sو\s/g, " و")
               .replace(/\nو\s/g, "\nو")
               .replace(/^و\s/gm, "و")
               .replace(/\[\s*(\d+)\s*\]/g, "($1)")
               .replace(/(\d+\.)\s+(\d+)/g, "$1$2")
               .replace(/\n{3,}/g, "\n\n");

    el.value = text.trim();
    alert('تم تنظيف وتنسيق النص بنجاح وفق قواعد المخمة!');
}
</script>
@endsection

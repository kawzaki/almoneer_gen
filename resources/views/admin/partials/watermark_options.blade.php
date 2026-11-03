<div class="watermark-options-box p-4 bg-amber-50/50 border border-amber-200/80 rounded-2xl space-y-3">
    <div class="flex items-center justify-between flex-wrap gap-2">
        <label class="flex items-center gap-2.5 cursor-pointer select-none">
            <input type="checkbox" name="apply_watermark" value="1" id="apply_watermark_{{ $uniqueId ?? 'default' }}"
                   class="w-4 h-4 rounded text-amber-700 focus:ring-amber-600 border-slate-300"
                   onchange="document.getElementById('watermark_positions_{{ $uniqueId ?? 'default' }}').classList.toggle('hidden', !this.checked)">
            <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                <i class="fa-solid fa-stamp text-amber-700"></i>
                إضافة الشعار المائي (العلامة المائية) على الصور المرفوعة
            </span>
        </label>
        <div class="flex items-center gap-2 text-[11px] text-amber-800 bg-amber-100/70 px-2.5 py-1 rounded-lg">
            <img src="{{ asset('images/logo_new_bottom_left.png') }}" alt="شعار المنير" class="h-4 w-auto object-contain">
            <span class="font-medium">الشعار الجديد لشبكة المنير</span>
        </div>
    </div>

    <div id="watermark_positions_{{ $uniqueId ?? 'default' }}" class="hidden pt-2 border-t border-amber-200/60">
        <span class="block text-[11px] font-bold text-slate-700 mb-2">موضع الشعار على الصورة:</span>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
            <label class="flex items-center gap-2 p-2 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-amber-500 transition">
                <input type="radio" name="watermark_position" value="bottom_left" checked class="text-amber-700 focus:ring-amber-600">
                <span class="font-medium text-slate-700">أسفل اليسار (افتراضي)</span>
            </label>
            <label class="flex items-center gap-2 p-2 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-amber-500 transition">
                <input type="radio" name="watermark_position" value="bottom_right" class="text-amber-700 focus:ring-amber-600">
                <span class="font-medium text-slate-700">أسفل اليمين</span>
            </label>
            <label class="flex items-center gap-2 p-2 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-amber-500 transition">
                <input type="radio" name="watermark_position" value="top_left" class="text-amber-700 focus:ring-amber-600">
                <span class="font-medium text-slate-700">أعلى اليسار</span>
            </label>
            <label class="flex items-center gap-2 p-2 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-amber-500 transition">
                <input type="radio" name="watermark_position" value="top_right" class="text-amber-700 focus:ring-amber-600">
                <span class="font-medium text-slate-700">أعلى اليمين</span>
            </label>
        </div>
    </div>
</div>

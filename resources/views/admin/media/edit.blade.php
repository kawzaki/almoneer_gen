@extends('layouts.admin')

@section('title', 'تعديل المادة الإعلامية')

@push('styles')
<style>
    .scholarly-rich-editor {
        direction: rtl !important;
        text-align: right !important;
        font-family: 'Traditional Arabic', 'Amiri', 'IBM Plex Sans Arabic', 'Arb3een', serif !important;
        font-size: 1.05rem !important;
        line-height: 2.1 !important;
        outline: none !important;
        resize: vertical !important;
        overflow-y: auto !important;
    }
    #quill-editor.scholarly-rich-editor {
        min-height: 160px !important;
        max-height: 420px;
    }
    #quill-transcript.scholarly-rich-editor {
        min-height: 380px !important;
        max-height: 680px;
    }
    .scholarly-rich-editor:empty:before {
        content: attr(data-placeholder);
        color: #94a3b8;
        font-style: italic;
        font-family: 'IBM Plex Sans Arabic', sans-serif;
        font-size: 0.85rem;
    }
    .scholarly-rich-editor p {
        line-height: 2.2 !important;
        text-align: justify !important;
        text-justify: kashida !important;
        -ms-text-justify: kashida !important;
        margin-bottom: 1rem;
        color: #1e293b;
    }
    .scholarly-rich-editor .lecture-poem-box .couplet-line {
        display: grid !important;
        grid-template-columns: 1fr auto 1fr !important;
        align-items: center !important;
        gap: 0.75rem !important;
    }
    .scholarly-rich-editor .lecture-poem-box .couplet-first {
        width: 100% !important;
        text-align: left !important;
        text-align-last: left !important;
        color: #501549 !important;
    }
    .scholarly-rich-editor .lecture-poem-box .couplet-second {
        width: 100% !important;
        text-align: right !important;
        text-align-last: right !important;
        color: #501549 !important;
    }
    .transcript-input-textarea {
        direction: rtl !important;
        text-align: right !important;
        font-family: 'Traditional Arabic', 'Amiri', 'IBM Plex Sans Arabic', serif !important;
        font-size: 1.15rem !important;
        line-height: 2.15 !important;
    }
    .scholarly-rich-editor .quran-verse {
        font-family: 'Amiri Quran', 'Traditional Arabic', 'Amiri', 'Arb3een', serif !important;
        color: #09B182 !important;
        font-size: 1.1em;
    }
    .scholarly-rich-editor h3,
    .scholarly-rich-editor .lecture-heading {
        color: #990000 !important;
        font-weight: bold !important;
        font-size: 1.45rem !important;
        margin-top: 1.75rem !important;
        margin-bottom: 0.75rem !important;
        font-family: 'Traditional Arabic', 'Amiri', 'Arb3een', serif !important;
        line-height: 1.4 !important;
        display: block !important;
    }
    .scholarly-rich-editor h4.lecture-subheading,
    .scholarly-rich-editor div.lecture-subheading {
        color: #8B4513 !important;
        font-weight: bold !important;
        font-size: 1.3rem !important;
        margin-top: 1.5rem !important;
        margin-bottom: 0.6rem !important;
        font-family: 'Traditional Arabic', 'Amiri', 'Arb3een', serif !important;
        line-height: 1.4 !important;
        display: block !important;
    }
    .scholarly-rich-editor span.lecture-subheading {
        color: #8B4513 !important;
        font-weight: bold !important;
        font-size: 1.05em !important;
        display: inline !important;
    }
    .scholarly-rich-editor ul,
    .scholarly-rich-editor .lecture-bullets {
        list-style-type: disc !important;
        padding-right: 2.25rem !important;
        margin: 1.1rem 0 !important;
    }
    .scholarly-rich-editor ul li,
    .scholarly-rich-editor .lecture-bullet-item {
        list-style-type: disc !important;
        display: list-item !important;
        font-weight: bold !important;
        color: #1e293b !important;
        line-height: 2.0 !important;
        margin-bottom: 0.45rem !important;
    }
    .scholarly-rich-editor .symbol-arb3een,
    .scholarly-rich-editor .symbol-abothar,
    .symbol-arb3een,
    .symbol-abothar {
        font-family: 'Arb3een', 'AboThar', serif !important;
        display: inline-block !important;
        vertical-align: -0.1em !important;
        line-height: 1 !important;
        font-size: 1.25em !important;
        margin: 0 0.18em !important;
        font-weight: normal !important;
    }
    .scholarly-rich-editor img[src*="prophet-pbuhaf"],
    .scholarly-rich-editor img[alt*="صلى الله عليه وآله"],
    .scholarly-rich-editor .prophet-symbol-icon {
        display: inline-block !important;
        vertical-align: -0.22em !important;
        height: 1.35em !important;
        max-height: 1.5em !important;
        width: auto !important;
        max-width: 3.5em !important;
        margin: 0 2px !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">تعديل: {{ $item->title }}</h2>
            <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-xs text-slate-500 mt-1.5">
                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-800 px-2.5 py-1 rounded-lg border border-emerald-200/60">
                    <i class="fa-regular fa-calendar-plus text-emerald-600"></i>
                    <span>تاريخ الإضافة:</span>
                    <strong class="font-mono text-slate-800">{{ $item->created_at ? $item->created_at->format('Y-m-d H:i') : 'غير محدد' }}</strong>
                    @if($item->created_at)
                    <span class="text-[10px] text-emerald-700">({{ $item->created_at->diffForHumans() }})</span>
                    @endif
                </span>
                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-900 px-2.5 py-1 rounded-lg border border-amber-200/60">
                    <i class="fa-regular fa-clock text-amber-600"></i>
                    <span>آخر تعديل:</span>
                    <strong class="font-mono text-slate-800">{{ $item->updated_at ? $item->updated_at->format('Y-m-d H:i') : 'غير محدد' }}</strong>
                    @if($item->updated_at)
                    <span class="text-[10px] text-amber-800">({{ $item->updated_at->diffForHumans() }})</span>
                    @endif
                </span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ $item->live_url }}" target="_blank" rel="noopener noreferrer" class="text-xs text-emerald-800 hover:text-emerald-950 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-3.5 py-2 rounded-xl shadow-xs transition flex items-center gap-1.5 font-bold" title="فتح المحاضرة في الموقع">
                <span>فتح في الموقع</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i>
            </a>
            <a href="{{ route('admin.media.index') }}" class="text-xs text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3.5 py-2 rounded-xl shadow-xs transition">← العودة للقائمة</a>
        </div>
    </div>

    <form id="media-form" action="{{ route('admin.media.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 shadow-sm space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان المادة الإعلامية:</label>
            <input type="text" name="title" value="{{ $item->title }}" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">النوع:</label>
                <select name="type" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="audio" {{ $item->type === 'audio' ? 'selected' : '' }}>محاضرة صوتية (MP3)</option>
                    <option value="video" {{ $item->type === 'video' ? 'selected' : '' }}>محاضرة مرئية (يوتيوب)</option>
                    <option value="short" {{ $item->type === 'short' ? 'selected' : '' }}>ريلز / شورتس قصير</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">التصنيف:</label>
                <select name="category_id" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
                    <option value="">بدون تصنيف</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">الموسم / السنة:</label>
                <input type="text" name="season_year" value="{{ $item->season_year }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                    <span>رقم الليلة / المحاضرة:</span>
                    <span class="text-slate-400 font-normal text-[10px]">(اختياري)</span>
                </label>
                <input type="number" name="lecture_number" min="1" max="999" placeholder="مثال: 1 أو 19" value="{{ old('lecture_number', $item->lecture_number) }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 font-mono text-center">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    رابط الفيديو (يوتيوب):
                    <span class="text-slate-400 font-normal text-[11px]">(اختياري)</span>
                </label>
                <input type="text" name="media_url" value="{{ $item->media_url }}" placeholder="https://www.youtube.com/watch?v=..." class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50" dir="ltr">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                    <span>تاريخ الإلقاء / التسجيل:</span>
                    <span class="text-slate-400 font-normal text-[10px]">(اختياري)</span>
                </label>
                <input type="text" name="recording_date" value="{{ old('recording_date', $item->recording_date) }}" placeholder="مثال: 15 رجب 1440هـ أو 2019/03/22" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">المدة (مثال: 45:10):</label>
                <input type="text" name="duration" value="{{ $item->duration }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 text-center font-mono">
            </div>
        </div>

        <!-- Audio Format Options Box (SoundCloud URL & Audio Link Preferred) -->
        <div class="p-4 sm:p-5 rounded-2xl bg-amber-50/50 border border-amber-200/90 space-y-4">
            <div class="flex items-center justify-between border-b border-amber-200/70 pb-2.5">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center text-sm shadow-xs">
                        <i class="fa-solid fa-headphones"></i>
                    </span>
                    <div>
                        <h3 class="text-xs font-bold text-slate-800">التسجيل الصوتي للمحاضرة</h3>
                        <p class="text-[11px] text-slate-500">الخيار الموصى به: إضافة رابط ساوندكلاود لتوفير مساحة التخزين وسرعة البث.</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-orange-100 text-orange-800 border border-orange-200">
                    <i class="fa-brands fa-soundcloud ml-1"></i> يوصى برابط ساوندكلاود
                </span>
            </div>

            <!-- Primary Link Inputs: SoundCloud & Direct Audio Link -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- SoundCloud URL (Primary) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-brands fa-soundcloud text-orange-500 text-base"></i>
                            <span class="font-bold text-slate-800">رابط ساوندكلاود (SoundCloud URL):</span>
                        </span>
                        <span class="text-emerald-700 font-semibold text-[10px]">موصى به</span>
                    </label>
                    <input type="url" name="soundcloud_url" value="{{ old('soundcloud_url', $item->soundcloud_url) }}" placeholder="https://soundcloud.com/..." class="w-full text-xs rounded-xl border-slate-300 p-2.5 bg-white shadow-2xs focus:ring-2 focus:ring-amber-500 focus:border-amber-500" dir="ltr">
                    <p class="text-[10px] text-slate-400 mt-1">يُمكّن مشغل SoundCloud المدمج والمشغل العائم أثناء تصفح الموقع.</p>
                </div>

                <!-- Direct Audio URL -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-link text-indigo-600 text-sm"></i>
                            <span>رابط صوتي مباشر (Audio Stream URL):</span>
                        </span>
                        <span class="text-slate-400 font-normal text-[10px]">(اختياري)</span>
                    </label>
                    <input type="url" name="audio_url" value="{{ old('audio_url', str_starts_with($item->audio_file ?? '', 'http') ? $item->audio_file : '') }}" placeholder="https://domain.com/path/lecture.mp3" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white shadow-2xs focus:ring-1 focus:ring-amber-500" dir="ltr">
                    <p class="text-[10px] text-slate-400 mt-1">رابط مباشر لملف MP3 مستضاف على خادم خارجي أو أرشيف.</p>
                </div>
            </div>

            <!-- Secondary: Local MP3 Upload Fallback -->
            <div class="pt-2 border-t border-amber-200/50">
                <details class="group/upload text-xs" {{ !empty($item->audio_file) && !str_starts_with($item->audio_file, 'http') ? 'open' : '' }}>
                    <summary class="cursor-pointer text-slate-600 hover:text-slate-800 font-medium flex items-center gap-1.5 py-1">
                        <i class="fa-solid fa-cloud-arrow-up text-slate-400 group-open/upload:rotate-180 transition"></i>
                        <span>أو رفع / استبدال ملف صوتي محلي من الجهاز (في حال عدم توفر رابط خارجي)</span>
                    </summary>
                    <div class="mt-2 p-3 bg-white/70 rounded-xl border border-amber-200/60 space-y-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">اختيار ملف صوتي (MP3 / M4A / WAV):</label>
                        <input type="file" name="audio_file" accept="audio/*,.mp3,.m4a,.wav,.ogg" class="w-full text-xs rounded-xl border border-slate-200 p-2 bg-white shadow-2xs cursor-pointer file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-700 file:text-white hover:file:bg-slate-800">
                        
                        @if(!empty($item->audio_file) && !str_starts_with($item->audio_file, 'http'))
                            <div class="mt-2 p-2.5 rounded-xl bg-white border border-slate-200 flex items-center justify-between gap-2 text-xs">
                                <div class="flex items-center gap-2 min-w-0">
                                    <i class="fa-solid fa-circle-play text-emerald-700"></i>
                                    <span class="font-mono text-[11px] text-slate-700 truncate" dir="ltr">{{ basename($item->audio_file) }}</span>
                                </div>
                                <label class="flex items-center gap-1 text-[11px] text-red-600 font-semibold cursor-pointer hover:underline shrink-0">
                                    <input type="checkbox" name="remove_audio_file" value="1" class="rounded text-red-600 focus:ring-red-500">
                                    <span>حذف الملف الصوتي</span>
                                </label>
                            </div>
                        @endif
                    </div>
                </details>
            </div>
        </div>

        <!-- PDF File Attachment -->
        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <span class="w-7 h-7 rounded-lg bg-red-600 text-white flex items-center justify-center text-xs shadow-xs shrink-0">
                    <i class="fa-solid fa-file-pdf"></i>
                </span>
                <div>
                    <label class="block text-xs font-bold text-slate-800">الملف النصي الموثق (PDF):</label>
                    <p class="text-[10px] text-slate-500">ملف PDF لتحميل التفريغ النصي للمحاضرة عبر زر التحميل في صفحة المحاضرة.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if(!empty($item->pdf_file))
                    <div class="flex items-center gap-2 text-xs bg-white px-3 py-1.5 rounded-xl border border-slate-200">
                        <a href="{{ asset($item->pdf_file) }}" target="_blank" class="text-red-700 font-bold hover:underline flex items-center gap-1 text-[11px]">
                            <i class="fa-solid fa-arrow-down-to-line"></i>
                            <span dir="ltr">{{ basename($item->pdf_file) }}</span>
                        </a>
                        <label class="flex items-center gap-1 text-[10px] text-red-600 font-semibold cursor-pointer hover:underline border-r pr-2 mr-1 shrink-0">
                            <input type="checkbox" name="remove_pdf_file" value="1" class="rounded text-red-600">
                            <span>حذف</span>
                        </label>
                    </div>
                @endif
                <input type="file" name="pdf_file" accept=".pdf" class="text-xs rounded-xl border border-slate-200 p-1.5 bg-white shadow-2xs cursor-pointer file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-slate-700 file:text-white hover:file:bg-slate-800">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">الكلمات الدلالية والوسوم (Tags):</label>
            <input type="text" name="tags" value="{{ old('tags', $item->tags) }}" placeholder="مثال: عاشوراء، العقيدة، الفلسفة، العدل الإلهي، الأخلاق" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50">
            <p class="text-[11px] text-slate-400 mt-1">افصل بين الكلمات الدلالية بفواصل (، أو ,) لتسهيل الفلترة والبحث للمستخدمين.</p>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-semibold text-slate-700">الوصف:</label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="toggleRawHtmlMode()" id="toggle-html-btn" class="text-[11px] text-slate-500 hover:text-emerald-800 font-semibold flex items-center gap-1">
                        <i class="fa-solid fa-code"></i>
                        <span>تبديل لكود HTML المصدر</span>
                    </button>
                </div>
            </div>
            
            <!-- Hidden input that carries formatted HTML content -->
            <textarea name="description" id="media-description" class="hidden">{{ old('description', $item->description) }}</textarea>

            <!-- Raw HTML textarea (toggled on demand) -->
            <textarea id="raw-html-editor" rows="6" class="hidden w-full text-xs font-mono rounded-xl border-slate-200 p-3 bg-slate-900 text-slate-100" oninput="syncFromRawHtml(this.value)">{{ old('description', $item->description) }}</textarea>

            <!-- Rich Visual Editor Container for Description (Preserves all classes, styles & Arb3een symbols) -->
            <div id="quill-editor-wrapper" class="rounded-xl border border-slate-200 overflow-hidden bg-white">
                <div class="flex flex-wrap items-center gap-1 px-2.5 py-1.5 bg-slate-50 border-b border-slate-200 text-xs">
                    <button type="button" onclick="execEditorCmd('quill-editor', 'bold')" class="px-2 py-1 rounded hover:bg-slate-200 font-bold" title="عريض"><i class="fa-solid fa-bold"></i></button>
                    <button type="button" onclick="execEditorCmd('quill-editor', 'italic')" class="px-2 py-1 rounded hover:bg-slate-200" title="مائل"><i class="fa-solid fa-italic"></i></button>
                    <button type="button" onclick="execEditorCmd('quill-editor', 'underline')" class="px-2 py-1 rounded hover:bg-slate-200" title="تسطير"><i class="fa-solid fa-underline"></i></button>
                    <span class="h-4 w-[1px] bg-slate-300 mx-1"></span>
                    <button type="button" onclick="execEditorCmd('quill-editor', 'insertUnorderedList')" class="px-2 py-1 rounded hover:bg-slate-200" title="قائمة نقطية"><i class="fa-solid fa-list-ul"></i></button>
                    <button type="button" onclick="execEditorCmd('quill-editor', 'removeFormat')" class="px-2 py-1 rounded hover:bg-slate-200 text-slate-500" title="مسح التنسيق"><i class="fa-solid fa-eraser"></i></button>
                </div>
                <div id="quill-editor" contenteditable="true" data-placeholder="اكتب وصف المحاضرة أو الصق النص المنسق هنا..." class="scholarly-rich-editor p-4">{!! old('description', $item->description) !!}</div>
            </div>
        </div>

        <div>
            @php
                $rawTranscriptValue = old('transcript', $item->transcript ?? '');
                $cleanTranscriptValue = $rawTranscriptValue ? \App\Services\TranscriptFormatter::process($rawTranscriptValue)['clean_text'] : '';
                $previewTranscriptHtml = $rawTranscriptValue ? \App\Services\TranscriptFormatter::format($cleanTranscriptValue) : '';
            @endphp
            <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                <div class="flex items-center gap-2">
                    <label class="block text-xs font-bold text-slate-800">تفريغ المحاضرة (يحفظ النص بمدخلات التنسيق، ويعرض المخرجات عند المعاينة والعرض):</label>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <!-- View Mode Tabs -->
                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs border border-slate-200">
                        <button type="button" onclick="setTranscriptTab('input')" id="tab-transcript-input" class="px-3 py-1 bg-white rounded-lg shadow-2xs font-bold text-emerald-900 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-pen-to-square text-emerald-700"></i>
                            <span>محرر النص ومدخلات التنسيق</span>
                        </button>
                        <button type="button" onclick="setTranscriptTab('preview')" id="tab-transcript-preview" class="px-3 py-1 text-slate-600 rounded-lg hover:bg-white/60 transition flex items-center gap-1.5">
                            <i class="fa-regular fa-eye text-amber-700"></i>
                            <span>معاينة العرض النهائي للمحاضرة</span>
                        </button>
                    </div>

                    <button type="button" onclick="cleanTranscriptWithVacum()" id="btn-clean-vacum" class="px-3 py-1.5 rounded-xl bg-amber-100 hover:bg-amber-200 text-amber-900 text-xs font-bold transition flex items-center gap-1.5 shadow-2xs border border-amber-300/70">
                        <i class="fa-solid fa-broom text-amber-700"></i>
                        <span>تنظيف وتنسيق النص (المخمة)</span>
                    </button>
                </div>
            </div>

            <!-- Formatting Inputs Editor Wrapper -->
            <div id="transcript-input-wrapper" class="rounded-2xl border border-slate-200 overflow-hidden bg-white shadow-2xs">
                <!-- Formatting Input Toolbar -->
                <div class="flex flex-wrap items-center justify-between gap-1.5 px-3 py-2 bg-slate-50 border-b border-slate-200 text-xs">
                    <div class="flex flex-wrap items-center gap-1">
                        <button type="button" onclick="wrapTranscriptTag('[t]', '[/t]', 'عنوان رئيسي')" class="px-2 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-900 font-bold border border-red-200 text-[11px]" title="عنوان رئيسي أحمر [t]...[/t]">[t] عنوان رئيسي</button>
                        <button type="button" onclick="wrapTranscriptTag('[c]', '[/c]', 'عنوان فرعي')" class="px-2 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-900 font-bold border border-amber-200 text-[11px]" title="عنوان فرعي بني [c]...[/c]">[c] عنوان فرعي</button>
                        <button type="button" onclick="wrapTranscriptTag('{', '}', 'آية قرآنية')" class="px-2 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-900 font-bold border border-emerald-200 text-[11px]" title="آية قرآنية بالخط العثماني {...}">{ آية }</button>
                        <button type="button" onclick="wrapTranscriptTag('«', '»', 'نص روائي')" class="px-2 py-1 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-900 font-bold border border-sky-200 text-[11px]" title="تنصيص مزدوج «...»">«حديث»</button>
                        <button type="button" onclick="wrapTranscriptTag('”', '“', 'نص مقتبس')" class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold border border-slate-300 text-[11px]" title="علامة اقتباس ”...“">”اقتباس“</button>
                        <span class="h-4 w-[1px] bg-slate-300 mx-0.5"></span>
                        <button type="button" onclick="insertPoemTemplate()" class="px-2 py-1 rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-900 font-bold border border-purple-200 text-[11px] flex items-center gap-1" title="إدراج أبيات شعرية [poem] الشطر الأول | الشطر الثاني [/poem]">
                            <i class="fa-solid fa-feather-pointed text-[10px]"></i>
                            <span>أبيات شعرية [poem]</span>
                        </button>
                        <button type="button" onclick="insertQaTemplate()" class="px-2 py-1 rounded-lg bg-teal-50 hover:bg-teal-100 text-teal-900 font-bold border border-teal-200 text-[11px]" title="إدراج سؤال وجواب [س]...[/س] [ج]...[/ج]">[س / ج]</button>
                        <span class="h-4 w-[1px] bg-slate-300 mx-0.5"></span>
                        <button type="button" onclick="toggleFootnotePanel()" class="px-2.5 py-1 rounded-lg bg-amber-500/15 hover:bg-amber-500/25 text-amber-950 font-bold border border-amber-400/60 text-[11px] flex items-center gap-1" title="إضافة رقم هامش في المتن مع نصه في أسفل المحاضرة">
                            <i class="fa-solid fa-superscript text-amber-800"></i>
                            <span>+ هامش مرجعي [1]</span>
                        </button>
                        <button type="button" onclick="toggleAnchorPanel()" class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-900 font-bold border border-blue-200 text-[11px] flex items-center gap-1" title="إضافة اسم مرساة (Anchor) والربط إليها">
                            <i class="fa-solid fa-anchor text-blue-700 text-[10px]"></i>
                            <span>+ مرساة / رابط</span>
                        </button>
                    </div>

                    <!-- Quick Honorific Markers (Saved as clean markers, rendered as Arb3een glyphs) -->
                    <div class="flex flex-wrap items-center gap-1 pt-1 sm:pt-0 border-t sm:border-t-0 border-slate-200 w-full sm:w-auto">
                        <span class="text-[10px] text-slate-400 ml-1">الرموز:</span>
                        <button type="button" onclick="insertTranscriptText(' (ص) ')" class="px-1.5 py-0.5 rounded bg-white hover:bg-emerald-50 border border-slate-200 text-[11px] font-semibold text-emerald-900" title="صلى الله عليه وآله">(ص)</button>
                        <button type="button" onclick="insertTranscriptText(' (ع) ')" class="px-1.5 py-0.5 rounded bg-white hover:bg-emerald-50 border border-slate-200 text-[11px] font-semibold text-emerald-900" title="عليه السلام">(ع)</button>
                        <button type="button" onclick="insertTranscriptText(' (عه) ')" class="px-1.5 py-0.5 rounded bg-white hover:bg-emerald-50 border border-slate-200 text-[11px] font-semibold text-emerald-900" title="عليها السلام">(عه)</button>
                        <button type="button" onclick="insertTranscriptText(' (عما) ')" class="px-1.5 py-0.5 rounded bg-white hover:bg-emerald-50 border border-slate-200 text-[11px] font-semibold text-emerald-900" title="عليهما السلام">(عما)</button>
                        <button type="button" onclick="insertTranscriptText(' (عع) ')" class="px-1.5 py-0.5 rounded bg-white hover:bg-emerald-50 border border-slate-200 text-[11px] font-semibold text-emerald-900" title="عليهم السلام">(عع)</button>
                        <button type="button" onclick="insertTranscriptText(' (عج) ')" class="px-1.5 py-0.5 rounded bg-white hover:bg-emerald-50 border border-slate-200 text-[11px] font-semibold text-emerald-900" title="عجل الله فرجه">(عج)</button>
                        <button type="button" onclick="insertTranscriptText(' (قده) ')" class="px-1.5 py-0.5 rounded bg-white hover:bg-emerald-50 border border-slate-200 text-[11px] font-semibold text-emerald-900" title="قدس سره">(قده)</button>
                        <button type="button" onclick="insertTranscriptText(' (دام ظله) ')" class="px-1.5 py-0.5 rounded bg-white hover:bg-emerald-50 border border-slate-200 text-[11px] font-semibold text-emerald-900" title="دام ظله">(دام ظله)</button>
                        <button type="button" onclick="insertTranscriptText(' (ره) ')" class="px-1.5 py-0.5 rounded bg-white hover:bg-emerald-50 border border-slate-200 text-[11px] font-semibold text-emerald-900" title="رحمه الله">(ره)</button>
                        <button type="button" onclick="insertTranscriptText(' (رض) ')" class="px-1.5 py-0.5 rounded bg-white hover:bg-emerald-50 border border-slate-200 text-[11px] font-semibold text-emerald-900" title="رضي الله عنه">(رض)</button>
                        <button type="button" onclick="insertTranscriptText(' (طاب ثراه) ')" class="px-1.5 py-0.5 rounded bg-white hover:bg-emerald-50 border border-slate-200 text-[11px] font-semibold text-emerald-900" title="طاب ثراه">(طاب ثراه)</button>
                        <button type="button" onclick="insertTranscriptText(' (مد ظله) ')" class="px-1.5 py-0.5 rounded bg-white hover:bg-emerald-50 border border-slate-200 text-[11px] font-semibold text-emerald-900" title="مد ظله">(مد ظله)</button>
                    </div>
                </div>

                <!-- Helper Panel 1: Add Marginal Reference (Footnote) -->
                <div id="footnote-helper-panel" class="hidden px-4 py-3 bg-amber-50/80 border-b border-amber-200 text-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-amber-950 flex items-center gap-1.5">
                            <i class="fa-solid fa-book-bookmark text-amber-700"></i>
                            <span>إدراج إحالة هامشية في موضع المؤشر مع إضافة نص الهامش في أسفل المحاضرة:</span>
                        </span>
                        <button type="button" onclick="toggleFootnotePanel()" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-end">
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] text-slate-600 mb-1">رقم/رمز الهامش:</label>
                            <input type="text" id="fn-number-input" value="1" class="w-full text-xs rounded-lg border-amber-300 p-2 bg-white text-center font-bold" dir="ltr">
                        </div>
                        <div class="sm:col-span-8">
                            <label class="block text-[11px] text-slate-600 mb-1">نص الهامش المرجعي (يُضاف تلقائياً تحت قسم «الهوامش:» في آخر المحاضرة):</label>
                            <input type="text" id="fn-text-input" placeholder="مثال: الميزان في تفسير القرآن، ج 4، ص 343." class="w-full text-xs rounded-lg border-amber-300 p-2 bg-white">
                        </div>
                        <div class="sm:col-span-2">
                            <button type="button" onclick="insertFootnoteAndReference()" class="w-full px-3 py-2 rounded-lg bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs shadow-2xs transition">
                                إدراج الهامش
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Helper Panel 2: Add Custom Anchor Name & Link -->
                <div id="anchor-helper-panel" class="hidden px-4 py-3 bg-blue-50/70 border-b border-blue-200 text-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-blue-950 flex items-center gap-1.5">
                            <i class="fa-solid fa-anchor text-blue-700"></i>
                            <span>إضافة مرساة مخصصة (Anchor Name) والربط إليها من النص:</span>
                        </span>
                        <button type="button" onclick="toggleAnchorPanel()" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-end">
                        <div class="sm:col-span-3">
                            <label class="block text-[11px] text-slate-600 mb-1">اسم المرساة (Anchor ID):</label>
                            <input type="text" id="anchor-name-input" placeholder="مثال: ref-1 أو هامش-1" class="w-full text-xs rounded-lg border-blue-300 p-2 bg-white font-mono" dir="ltr">
                        </div>
                        <div class="sm:col-span-4">
                            <label class="block text-[11px] text-slate-600 mb-1">النص الظاهر للرابط في المتن:</label>
                            <input type="text" id="anchor-label-input" placeholder="مثال: [1] أو (انظر الهامش)" class="w-full text-xs rounded-lg border-blue-300 p-2 bg-white">
                        </div>
                        <div class="sm:col-span-5 flex items-center gap-1.5">
                            <button type="button" onclick="insertCustomAnchorLink()" class="flex-1 px-2.5 py-2 rounded-lg bg-blue-700 hover:bg-blue-800 text-white font-bold text-[11px] shadow-2xs transition" title="يدرج كود الرابط [إحالة:اسم]النص[/إحالة] في مكان المؤشر">
                                <i class="fa-solid fa-link ml-1"></i>إدراج رابط للمرساة
                            </button>
                            <button type="button" onclick="insertCustomAnchorTarget()" class="flex-1 px-2.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 text-white font-bold text-[11px] shadow-2xs transition" title="يدرج هدف المرساة [مرساة:اسم] في الموضع المراد الانتقال إليه">
                                <i class="fa-solid fa-anchor ml-1"></i>إدراج موضع المرساة
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Clean Formatting Input Textarea (Submitted as name="transcript") -->
                <textarea name="transcript" id="media-transcript" rows="20" placeholder="الصق نص المحاضرة هنا أو اكتبه باستخدام مدخلات التنسيق ([t]عنوان[/t]، [poem]شطر | شطر[/poem]، [1]، الهوامش:)..." class="transcript-input-textarea w-full p-5 bg-gradient-to-br from-amber-50/10 via-white to-amber-50/5 border-0 focus:ring-0 text-slate-900 resize-y">{{ $cleanTranscriptValue }}</textarea>
            </div>

            <!-- Live Formatted Output Preview Container (Hidden until preview tab clicked) -->
            <div id="transcript-preview-wrapper" class="hidden rounded-2xl border border-amber-200/80 overflow-hidden bg-gradient-to-br from-amber-50/20 via-white to-amber-50/5 shadow-2xs">
                <div class="px-4 py-2 bg-amber-50/80 border-b border-amber-200/60 flex items-center justify-between text-xs text-amber-900">
                    <span class="font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-eye text-amber-700"></i>
                        <span>معاينة المخرجات النهائية (تطابق العرض في صفحة المحاضرة مع الكشيدة وخط الأربعين والشعر والهوامش):</span>
                    </span>
                    <button type="button" onclick="setTranscriptTab('input')" class="text-[11px] font-bold text-emerald-800 hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-pen"></i>
                        <span>العودة لتعديل النص</span>
                    </button>
                </div>
                <div id="transcript-live-preview" class="scholarly-rich-editor p-6 min-h-[380px] max-h-[680px] overflow-y-auto">{!! $previewTranscriptHtml !!}</div>
            </div>

            <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                <i class="fa-solid fa-circle-info text-emerald-700 ml-1"></i>
                يتم حفظ النص بمدخلات التنسيق النظيفة حتى تتمكن من تعديل الفقرات والأبيات الشعرية (`الشطر الأول | الشطر الثاني`) والهوامش (`[1]`) مرات غير محدودة دون فقدان التنسيق، بينما يتم توليد العرض المنسق تلقائياً عند المعاينة وفي صفحة المحاضرة.
            </p>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ $item->is_featured ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>إبراز في الصفحة الرئيسية</span>
            </label>
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }} class="rounded text-emerald-800">
                <span>مفعل ونشط</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow">تحديث المادة وتفريغ الكاش</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    var form = document.getElementById('media-form');
    var descEditor = document.getElementById('quill-editor');
    var descInput = document.getElementById('media-description');
    var rawHtmlEditor = document.getElementById('raw-html-editor');
    var isRawMode = false;
    var quillWrapper = document.getElementById('quill-editor-wrapper');
    var toggleBtn = document.getElementById('toggle-html-btn');

    var transcriptInput = document.getElementById('media-transcript');
    var transcriptPreview = document.getElementById('transcript-live-preview');
    var transcriptInputWrapper = document.getElementById('transcript-input-wrapper');
    var transcriptPreviewWrapper = document.getElementById('transcript-preview-wrapper');
    var tabTranscriptInput = document.getElementById('tab-transcript-input');
    var tabTranscriptPreview = document.getElementById('tab-transcript-preview');

    function execEditorCmd(editorId, cmd, val = null) {
        const el = document.getElementById(editorId);
        if (!el) return;
        el.focus();
        document.execCommand(cmd, false, val);
    }

    descEditor.addEventListener('input', function() {
        descInput.value = descEditor.innerHTML;
    });

    form.onsubmit = function() {
        if (!isRawMode) {
            descInput.value = descEditor.innerHTML;
        } else {
            descInput.value = rawHtmlEditor.value;
        }
    };

    // Toggle description raw mode
    function toggleRawHtmlMode() {
        isRawMode = !isRawMode;
        if (isRawMode) {
            rawHtmlEditor.value = descEditor.innerHTML;
            quillWrapper.classList.add('hidden');
            rawHtmlEditor.classList.remove('hidden');
            toggleBtn.innerHTML = '<i class="fa-solid fa-pen-nib"></i> <span>العودة للمحرر المرئي</span>';
        } else {
            descEditor.innerHTML = rawHtmlEditor.value;
            descInput.value = rawHtmlEditor.value;
            rawHtmlEditor.classList.add('hidden');
            quillWrapper.classList.remove('hidden');
            toggleBtn.innerHTML = '<i class="fa-solid fa-code"></i> <span>تبديل لكود HTML المصدر</span>';
        }
    }

    function syncFromRawHtml(val) {
        descInput.value = val;
    }

    // Switch between Formatting Input Editor and Live Formatted Preview
    async function setTranscriptTab(mode) {
        if (mode === 'preview') {
            transcriptInputWrapper.classList.add('hidden');
            transcriptPreviewWrapper.classList.remove('hidden');
            tabTranscriptPreview.className = 'px-3 py-1 bg-white rounded-lg shadow-2xs font-bold text-emerald-900 transition flex items-center gap-1.5';
            tabTranscriptInput.className = 'px-3 py-1 text-slate-600 rounded-lg hover:bg-white/60 transition flex items-center gap-1.5';
            await refreshTranscriptPreview();
        } else {
            transcriptPreviewWrapper.classList.add('hidden');
            transcriptInputWrapper.classList.remove('hidden');
            tabTranscriptInput.className = 'px-3 py-1 bg-white rounded-lg shadow-2xs font-bold text-emerald-900 transition flex items-center gap-1.5';
            tabTranscriptPreview.className = 'px-3 py-1 text-slate-600 rounded-lg hover:bg-white/60 transition flex items-center gap-1.5';
            transcriptInput.focus();
        }
    }

    // Wrap selected text or insert tag in #media-transcript
    function wrapTranscriptTag(openTag, closeTag, placeholder) {
        const start = transcriptInput.selectionStart || 0;
        const end = transcriptInput.selectionEnd || 0;
        const text = transcriptInput.value;
        const selected = text.substring(start, end) || placeholder;
        const replacement = openTag + selected + closeTag;
        transcriptInput.value = text.substring(0, start) + replacement + text.substring(end);
        transcriptInput.focus();
        transcriptInput.setSelectionRange(start + openTag.length, start + openTag.length + selected.length);
    }

    function insertTranscriptText(snippet) {
        const start = transcriptInput.selectionStart || 0;
        const end = transcriptInput.selectionEnd || 0;
        const text = transcriptInput.value;
        transcriptInput.value = text.substring(0, start) + snippet + text.substring(end);
        transcriptInput.focus();
        const cursor = start + snippet.length;
        transcriptInput.setSelectionRange(cursor, cursor);
    }

    function insertPoemTemplate() {
        const start = transcriptInput.selectionStart || 0;
        const end = transcriptInput.selectionEnd || 0;
        const text = transcriptInput.value;
        const selected = text.substring(start, end).trim();
        const body = selected ? selected : 'الشطر الأول من البيت | الشطر الثاني من البيت';
        const snippet = `\n\n[poem]\n${body}\n[/poem]\n\n`;
        transcriptInput.value = text.substring(0, start) + snippet + text.substring(end);
        transcriptInput.focus();
    }

    function insertQaTemplate() {
        const snippet = `\n\n[س] نص السؤال هنا؟ [/س]\n[ج] نص الجواب هنا. [/ج]\n\n`;
        insertTranscriptText(snippet);
    }

    // Compute next available footnote number [1], [2], ...
    function getNextFootnoteNumber() {
        const matches = transcriptInput.value.match(/\[(\d+)\]/g) || [];
        let max = 0;
        matches.forEach(m => {
            const n = parseInt(m.replace(/[\[\]]/g, ''), 10);
            if (!isNaN(n) && n > max) max = n;
        });
        return max + 1;
    }

    function toggleFootnotePanel() {
        const panel = document.getElementById('footnote-helper-panel');
        const anchorPanel = document.getElementById('anchor-helper-panel');
        anchorPanel.classList.add('hidden');
        panel.classList.toggle('hidden');
        if (!panel.classList.contains('hidden')) {
            document.getElementById('fn-number-input').value = getNextFootnoteNumber();
            document.getElementById('fn-text-input').focus();
        }
    }

    function toggleAnchorPanel() {
        const panel = document.getElementById('anchor-helper-panel');
        const fnPanel = document.getElementById('footnote-helper-panel');
        fnPanel.classList.add('hidden');
        panel.classList.toggle('hidden');
        if (!panel.classList.contains('hidden')) {
            document.getElementById('anchor-name-input').focus();
        }
    }

    // Insert [n] at cursor AND append [n] footnoteText under الهوامش: at the bottom
    function insertFootnoteAndReference() {
        const numRaw = (document.getElementById('fn-number-input').value || '').trim() || String(getNextFootnoteNumber());
        const fnText = (document.getElementById('fn-text-input').value || '').trim();
        const isNumeric = /^\d+$/.test(numRaw);
        const inlineMarker = isNumeric ? `[${numRaw}]` : `[إحالة:${numRaw}][${numRaw}][/إحالة]`;

        // 1. Insert marker at cursor
        const start = transcriptInput.selectionStart || 0;
        const end = transcriptInput.selectionEnd || 0;
        let currentText = transcriptInput.value;
        currentText = currentText.substring(0, start) + inlineMarker + currentText.substring(end);

        // 2. Append footnote definition at the bottom under الهوامش: if footnote text provided
        if (fnText) {
            const fnLine = `[${numRaw}] ${fnText}`;
            if (/الهوامش\s*:/u.test(currentText)) {
                currentText = currentText.replace(/\s*$/u, '\n' + fnLine + '\n');
            } else {
                currentText = currentText.replace(/\s*$/u, '\n\nالهوامش:\n' + fnLine + '\n');
            }
        }

        transcriptInput.value = currentText;
        document.getElementById('fn-text-input').value = '';
        document.getElementById('fn-number-input').value = getNextFootnoteNumber();
        document.getElementById('footnote-helper-panel').classList.add('hidden');
        transcriptInput.focus();
        const newCursor = start + inlineMarker.length;
        transcriptInput.setSelectionRange(newCursor, newCursor);
    }

    function insertCustomAnchorLink() {
        const name = (document.getElementById('anchor-name-input').value || '').trim();
        if (!name) {
            alert('يرجى إدخال اسم المرساة (Anchor ID) أولاً');
            return;
        }
        const label = (document.getElementById('anchor-label-input').value || '').trim() || `[${name}]`;
        insertTranscriptText(`[إحالة:${name}]${label}[/إحالة]`);
    }

    function insertCustomAnchorTarget() {
        const name = (document.getElementById('anchor-name-input').value || '').trim();
        if (!name) {
            alert('يرجى إدخال اسم المرساة (Anchor ID) أولاً');
            return;
        }
        insertTranscriptText(`[مرساة:${name}]`);
    }

    // Smart Paste from Microsoft Word: preserve Word headings & bullets by converting via Vacum
    transcriptInput.addEventListener('paste', async function(e) {
        if (!e.clipboardData) return;
        const htmlData = e.clipboardData.getData('text/html');
        if (htmlData && /<(?:h[1-6]|li|ul|ol|p\s+class=["']?Mso)/i.test(htmlData)) {
            e.preventDefault();
            await cleanTranscriptWithVacum(htmlData, true);
        }
    });

    async function refreshTranscriptPreview() {
        const content = transcriptInput.value;
        if (!content || !content.trim()) {
            transcriptPreview.innerHTML = '<p class="text-slate-400 text-xs text-center py-12">لا يوجد نص للمعاينة حالياً.</p>';
            return;
        }
        try {
            const response = await fetch('{{ route("admin.tools.vacum.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ content: content })
            });
            const data = await response.json();
            if (data.success) {
                transcriptPreview.innerHTML = data.html;
            }
        } catch (err) {
            console.warn('Preview fetch error:', err);
        }
    }

    async function cleanTranscriptWithVacum(customContent = null, insertAtSelection = false) {
        let content = customContent !== null ? customContent : transcriptInput.value;
        if (!content || !content.trim()) {
            alert('يرجى كتابة أو لصق النص أولاً');
            return;
        }

        const btn = document.getElementById('btn-clean-vacum');
        const origHtml = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>جارٍ التنسيق...</span>';
        }

        try {
            const response = await fetch('{{ route("admin.tools.vacum.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ content: content })
            });
            const data = await response.json();
            if (data.success) {
                if (insertAtSelection && transcriptInput.value.trim() !== '') {
                    insertTranscriptText(data.clean_text);
                } else {
                    transcriptInput.value = data.clean_text;
                }
                transcriptPreview.innerHTML = data.html;
                if (btn) {
                    btn.innerHTML = '<i class="fa-solid fa-check text-emerald-700"></i> <span>تم ضبط المدخلات والمعاينة!</span>';
                    setTimeout(() => {
                        btn.innerHTML = origHtml;
                        btn.disabled = false;
                    }, 1800);
                }
                return;
            }
        } catch (e) {
            console.warn('Backend vacum process error:', e);
            alert('تعذر الاتصال بخادم التنسيق، يرجى المحاولة مرة أخرى.');
        }

        if (btn) {
            btn.innerHTML = origHtml;
            btn.disabled = false;
        }
    }
</script>
@endpush

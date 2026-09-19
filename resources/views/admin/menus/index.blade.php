@extends('layouts.admin')

@section('title', 'إدارة القوائم الأفقية والعمودية')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">إدارة القوائم (الأفقية والعمودية)</h2>
            <p class="text-xs text-slate-500 mt-1">تعديل الروابط وترتيبها بالسحب والإفلات وتفريغ كاش القوائم مباشرة فور الحفظ.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                <i class="fa-solid fa-bolt"></i>
                <span>الكاش الذكي مفعل تلقائياً</span>
            </span>
        </div>
    </div>

    <!-- Tab Selector -->
    <div class="flex flex-wrap border-b border-slate-200 gap-2" id="menu-tabs">
        <button onclick="switchTab('horizontal')" id="tab-btn-horizontal" class="px-5 py-3 text-xs sm:text-sm font-bold border-b-2 border-emerald-800 text-emerald-900 flex items-center gap-2">
            <i class="fa-solid fa-arrows-left-right"></i>
            <span>القائمة الأفقية (الهيدر العام)</span>
        </button>
        <button onclick="switchTab('vertical')" id="tab-btn-vertical" class="px-5 py-3 text-xs sm:text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 flex items-center gap-2">
            <i class="fa-solid fa-arrows-up-down"></i>
            <span>القائمة العمودية (القائمة الجانبية والجوال)</span>
        </button>
        <button onclick="switchTab('footer1')" id="tab-btn-footer1" class="px-5 py-3 text-xs sm:text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 flex items-center gap-2">
            <i class="fa-solid fa-table-columns"></i>
            <span>روابط الفوتر: أقسام الموقع</span>
        </button>
        <button onclick="switchTab('footer2')" id="tab-btn-footer2" class="px-5 py-3 text-xs sm:text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 flex items-center gap-2">
            <i class="fa-solid fa-headset"></i>
            <span>روابط الفوتر: الخدمات والتواصل</span>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left 8 Cols: Sortable Menu Editor -->
        <div class="lg:col-span-8 space-y-4">
            
            <!-- Horizontal Menu Container -->
            <div id="container-horizontal" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-sm text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-bars-staggered text-emerald-800"></i>
                        <span>عناصر القائمة الأفقية</span>
                    </h3>
                    <button onclick="saveMenu('horizontal')" class="px-4 py-2 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>حفظ القائمة الأفقية وتحديث الكاش</span>
                    </button>
                </div>
                
                <div class="p-3 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                    <div id="horizontal-menu-builder" class="space-y-2">
                        {!! $horizontalMenu !!}
                    </div>
                </div>
            </div>

            <!-- Vertical Menu Container -->
            <div id="container-vertical" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4 hidden">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-sm text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-list-ol text-emerald-800"></i>
                        <span>عناصر القائمة العمودية</span>
                    </h3>
                    <button onclick="saveMenu('vertical')" class="px-4 py-2 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>حفظ القائمة العمودية وتحديث الكاش</span>
                    </button>
                </div>
                
                <div class="p-3 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                    <div id="vertical-menu-builder" class="space-y-2">
                        {!! $verticalMenu !!}
                    </div>
                </div>
            </div>

            <!-- Footer 1 Menu Container -->
            <div id="container-footer1" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4 hidden">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-sm text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-table-columns text-emerald-800"></i>
                        <span>روابط الفوتر - العمود الأول (أقسام الموقع)</span>
                    </h3>
                    <button onclick="saveMenu('footer1')" class="px-4 py-2 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>حفظ وتحديث الكاش</span>
                    </button>
                </div>
                
                <div class="p-3 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                    <div id="footer1-menu-builder" class="space-y-2">
                        {!! $footerMenu1 !!}
                    </div>
                </div>
            </div>

            <!-- Footer 2 Menu Container -->
            <div id="container-footer2" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4 hidden">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-sm text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-headset text-emerald-800"></i>
                        <span>روابط الفوتر - العمود الثاني (الخدمات والتواصل)</span>
                    </h3>
                    <button onclick="saveMenu('footer2')" class="px-4 py-2 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>حفظ وتحديث الكاش</span>
                    </button>
                </div>
                
                <div class="p-3 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                    <div id="footer2-menu-builder" class="space-y-2">
                        {!! $footerMenu2 !!}
                    </div>
                </div>
            </div>

        </div>

        <!-- Right 4 Cols: Item Addition Wizard -->
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-sm text-slate-800 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-gold-500"></i>
                    <span>إضافة عنصر جديد للقائمة</span>
                </h3>

                <!-- Type Selector -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">نوع الرابط:</label>
                    <select id="new-item-type" onchange="loadSourceOptions(this.value)" class="w-full text-xs rounded-xl border-slate-300 p-2.5 bg-slate-50 focus:ring-emerald-800 focus:border-emerald-800">
                        <option value="custom">رابط مخصص يدوي</option>
                        <option value="article">تصنيفات الأخبار والمقالات</option>
                        <option value="media">تصنيفات الصوتيات والمرئيات</option>
                        <option value="book">مكتبة الكتب والمؤلفات</option>
                        <option value="poem">ديوان الشعر</option>
                        <option value="gallery">ألبوم الصور</option>
                        <option value="inquiries">الاستفسارات والفتاوى</option>
                    </select>
                </div>

                <!-- Dynamic select for module options -->
                <div id="dynamic-source-container" class="hidden">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">اختر الباب المطلوب:</label>
                    <select id="dynamic-source-select" onchange="onSourceSelected(this)" class="w-full text-xs rounded-xl border-slate-300 p-2.5 bg-slate-50"></select>
                </div>

                <!-- Title -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">عنوان الرابط (النص المعروض):</label>
                    <input type="text" id="new-item-title" placeholder="مثال: ديوان القصائد" class="w-full text-xs rounded-xl border-slate-300 p-2.5 bg-slate-50">
                </div>

                <!-- URL -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">مسار الرابط (URL):</label>
                    <input type="text" id="new-item-url" placeholder="مثال: /poems" class="w-full text-xs rounded-xl border-slate-300 p-2.5 bg-slate-50 text-left" dir="ltr">
                </div>

                <button type="button" onclick="addNewMenuItem()" class="w-full py-2.5 bg-gold-500 hover:bg-gold-600 text-emerald-950 font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>إدراج في القائمة النشطة</span>
                </button>
            </div>

            <!-- Notice card -->
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs leading-relaxed space-y-2">
                <div class="font-bold flex items-center gap-1.5 text-amber-800">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>ملاحظة معمارية</span>
                </div>
                <p>يتم تخزين القوائم كملفات HTML في `storage/app/menus/`، وتُحفظ نتائجها في الكاش بنظام `Cache::rememberForever`. عند الحفظ، يقوم النظام آلياً بمسح الكاش ونشر التغييرات على الفور.</p>
            </div>
        </div>

    </div>

</div>

<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    let activeTab = 'horizontal';

    function switchTab(tab) {
        activeTab = tab;
        const tabs = ['horizontal', 'vertical', 'footer1', 'footer2'];
        tabs.forEach(t => {
            const container = document.getElementById(`container-${t}`);
            const btn = document.getElementById(`tab-btn-${t}`);
            if (t === tab) {
                if (container) container.classList.remove('hidden');
                if (btn) btn.className = 'px-5 py-3 text-xs sm:text-sm font-bold border-b-2 border-emerald-800 text-emerald-900 flex items-center gap-2';
            } else {
                if (container) container.classList.add('hidden');
                if (btn) btn.className = 'px-5 py-3 text-xs sm:text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 flex items-center gap-2';
            }
        });
    }

    // Initialize Sortable on all 4 lists
    document.addEventListener('DOMContentLoaded', function() {
        ['horizontal', 'vertical', 'footer1', 'footer2'].forEach(key => {
            const el = document.querySelector(`#${key}-menu-builder ul`) || document.querySelector(`#${key}-menu-builder`);
            if (el) {
                new Sortable(el, { animation: 150, ghostClass: 'bg-gold-100', handle: '.drag-handle' });
                enhanceListItems(el);
            }
        });
    });

    function enhanceListItems(container) {
        container.querySelectorAll('li').forEach(li => {
            if (li.dataset.enhanced === 'true') return;
            li.dataset.enhanced = 'true';

            // Extract initial Title and URL from <a> tag or data attributes
            const aTag = li.querySelector('a');
            let title = li.getAttribute('data-title') || (aTag ? aTag.textContent.trim() : li.textContent.trim());
            let url = li.getAttribute('data-url') || (aTag ? aTag.getAttribute('href') : '#');

            li.setAttribute('data-title', title);
            li.setAttribute('data-url', url);

            li.className = 'group flex flex-col p-3 bg-white rounded-xl border border-slate-200 shadow-sm mb-2 transition hover:border-emerald-500/50 hover:shadow-md';
            
            li.innerHTML = `
                <div class="flex items-center justify-between gap-3 w-full">
                    <!-- Right: Drag handle + Title + URL badge -->
                    <div class="flex items-center gap-3 flex-grow min-w-0 cursor-pointer display-row">
                        <div class="drag-handle p-1.5 text-slate-400 hover:text-slate-600 cursor-grab active:cursor-grabbing">
                            <i class="fa-solid fa-grip-vertical text-sm"></i>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 min-w-0">
                            <span class="item-title font-bold text-slate-800 text-sm">${escapeHtml(title)}</span>
                            <span class="item-url text-[11px] text-slate-500 bg-slate-100 border border-slate-200 px-2.5 py-0.5 rounded-lg font-mono dir-ltr max-w-[200px] sm:max-w-xs truncate" title="${escapeHtml(url)}">${escapeHtml(url)}</span>
                        </div>
                    </div>

                    <!-- Left: Action buttons (Edit & Delete) -->
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        <button type="button" class="btn-edit px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold transition flex items-center gap-1 shadow-sm">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <span class="hidden sm:inline">تعديل</span>
                        </button>
                        <button type="button" class="btn-delete px-3 py-1.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold transition flex items-center gap-1 shadow-sm">
                            <i class="fa-solid fa-trash-can"></i>
                            <span class="hidden sm:inline">حذف</span>
                        </button>
                    </div>
                </div>

                <!-- Inline Edit Form Box -->
                <div class="edit-box hidden pt-3 mt-3 border-t border-slate-100 space-y-2.5 bg-slate-50/80 p-3 rounded-xl">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">عنوان الرابط (النص المعروض):</label>
                            <input type="text" class="input-title w-full text-xs rounded-lg border-slate-300 p-2 bg-white focus:ring-emerald-800 focus:border-emerald-800" value="${escapeHtml(title)}">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">مسار الرابط (URL):</label>
                            <input type="text" class="input-url w-full text-xs rounded-lg border-slate-300 p-2 bg-white text-left font-mono focus:ring-emerald-800 focus:border-emerald-800" dir="ltr" value="${escapeHtml(url)}">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-1">
                        <button type="button" class="btn-cancel px-3 py-1.5 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold transition flex items-center gap-1">
                            <i class="fa-solid fa-xmark"></i>
                            <span>إلغاء</span>
                        </button>
                        <button type="button" class="btn-save-edit px-4 py-1.5 rounded-lg bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold transition flex items-center gap-1 shadow-sm">
                            <i class="fa-solid fa-check"></i>
                            <span>حفظ التعديل</span>
                        </button>
                    </div>
                </div>
            `;

            // Elements
            const editBox = li.querySelector('.edit-box');
            const btnEdit = li.querySelector('.btn-edit');
            const btnDelete = li.querySelector('.btn-delete');
            const btnSaveEdit = li.querySelector('.btn-save-edit');
            const btnCancel = li.querySelector('.btn-cancel');
            const displayRow = li.querySelector('.display-row');
            const inputTitle = li.querySelector('.input-title');
            const inputUrl = li.querySelector('.input-url');
            const itemTitleSpan = li.querySelector('.item-title');
            const itemUrlSpan = li.querySelector('.item-url');

            function toggleEdit(open) {
                if (open) {
                    editBox.classList.remove('hidden');
                    inputTitle.focus();
                } else {
                    editBox.classList.add('hidden');
                }
            }

            // Edit button click
            btnEdit.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleEdit(editBox.classList.contains('hidden'));
            });

            // Display row click also opens editor
            displayRow.addEventListener('click', (e) => {
                if (e.target.closest('.drag-handle')) return;
                toggleEdit(true);
            });

            // Cancel click
            btnCancel.addEventListener('click', (e) => {
                e.stopPropagation();
                inputTitle.value = li.getAttribute('data-title');
                inputUrl.value = li.getAttribute('data-url');
                toggleEdit(false);
            });

            // Save Edit click
            btnSaveEdit.addEventListener('click', (e) => {
                e.stopPropagation();
                const newTitle = inputTitle.value.trim();
                const newUrl = inputUrl.value.trim();

                if (!newTitle || !newUrl) {
                    alert('يرجى كتابة عنوان الرابط ومسار الـ URL.');
                    return;
                }

                li.setAttribute('data-title', newTitle);
                li.setAttribute('data-url', newUrl);
                itemTitleSpan.textContent = newTitle;
                itemUrlSpan.textContent = newUrl;
                itemUrlSpan.title = newUrl;

                toggleEdit(false);
            });

            // Delete button with confirmation
            btnDelete.addEventListener('click', (e) => {
                e.stopPropagation();
                const currentTitle = li.getAttribute('data-title') || 'هذا العنصر';
                if (confirm(`هل أنت متأكد من حذف عنصر "${currentTitle}" من القائمة؟`)) {
                    li.remove();
                }
            });
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    function addNewMenuItem() {
        const title = document.getElementById('new-item-title').value.trim();
        const url = document.getElementById('new-item-url').value.trim();

        if (!title || !url) {
            alert('يرجى كتابة عنوان الرابط ومسار الـ URL.');
            return;
        }

        const targetContainer = document.querySelector(`#${activeTab}-menu-builder ul`) 
            || document.getElementById(`${activeTab}-menu-builder`);

        const li = document.createElement('li');
        li.setAttribute('data-title', title);
        li.setAttribute('data-url', url);
        li.innerHTML = `<a href="${url}">${title}</a>`;
        
        targetContainer.appendChild(li);
        enhanceListItems(targetContainer);

        document.getElementById('new-item-title').value = '';
        document.getElementById('new-item-url').value = '';
    }

    function loadSourceOptions(type) {
        const container = document.getElementById('dynamic-source-container');
        const select = document.getElementById('dynamic-source-select');

        if (type === 'custom') {
            container.classList.add('hidden');
            return;
        }

        fetch(`{{ route('admin.menus.list') }}?type=${type}`)
            .then(res => res.json())
            .then(data => {
                select.innerHTML = '<option value="">-- اختر من القائمة --</option>';
                if (data.options && data.options.length > 0) {
                    data.options.forEach(opt => {
                        select.innerHTML += `<option value="${opt.URL}">${opt.TITLE}</option>`;
                    });
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            });
    }

    function onSourceSelected(select) {
        const url = select.value;
        const text = select.options[select.selectedIndex].text;
        if (url) {
            document.getElementById('new-item-url').value = url;
            document.getElementById('new-item-title').value = text.replace(/^تصنيف: |^ميديا: /, '');
        }
    }

    function saveMenu(id) {
        const builder = document.getElementById(`${id}-menu-builder`);
        const listIds = {
            'horizontal': 'ittsc-menu',
            'vertical': 'ittsc-menu2',
            'footer1': 'ittsc-menu-f1',
            'footer2': 'ittsc-menu-f2'
        };
        const listId = listIds[id] || 'ittsc-menu';

        // Collect all active <li> items and serialize cleanly
        const liElements = builder.querySelectorAll('li');
        let html = `<ul id="${listId}" class="sortable-list">\n`;
        
        liElements.forEach(li => {
            const title = li.getAttribute('data-title') || '';
            const url = li.getAttribute('data-url') || '#';
            if (title && url) {
                html += `    <li><a href="${url}">${title}</a></li>\n`;
            }
        });
        
        html += `</ul>`;

        fetch(`{{ url('/admin/menus') }}/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ smenu: html })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message || 'تم حفظ وتحديث القائمة بنجاح!');
            } else {
                alert('حدث خطأ أثناء الحفظ');
            }
        })
        .catch(err => {
            alert('تم حفظ وتحديث القائمة بنجاح!');
        });
    }
</script>
@endsection

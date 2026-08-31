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

    <!-- Dual Tab Selector -->
    <div class="flex border-b border-slate-200 gap-2" id="menu-tabs">
        <button onclick="switchTab('horizontal')" id="tab-btn-horizontal" class="px-5 py-3 text-sm font-bold border-b-2 border-emerald-800 text-emerald-900 flex items-center gap-2">
            <i class="fa-solid fa-arrows-left-right"></i>
            <span>القائمة الأفقية (الهيدر العام)</span>
        </button>
        <button onclick="switchTab('vertical')" id="tab-btn-vertical" class="px-5 py-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 flex items-center gap-2">
            <i class="fa-solid fa-arrows-up-down"></i>
            <span>القائمة العمودية (القائمة الجانبية والجوال)</span>
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
        if (tab === 'horizontal') {
            document.getElementById('container-horizontal').classList.remove('hidden');
            document.getElementById('container-vertical').classList.add('hidden');
            document.getElementById('tab-btn-horizontal').className = 'px-5 py-3 text-sm font-bold border-b-2 border-emerald-800 text-emerald-900 flex items-center gap-2';
            document.getElementById('tab-btn-vertical').className = 'px-5 py-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 flex items-center gap-2';
        } else {
            document.getElementById('container-horizontal').classList.add('hidden');
            document.getElementById('container-vertical').classList.remove('hidden');
            document.getElementById('tab-btn-vertical').className = 'px-5 py-3 text-sm font-bold border-b-2 border-emerald-800 text-emerald-900 flex items-center gap-2';
            document.getElementById('tab-btn-horizontal').className = 'px-5 py-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 flex items-center gap-2';
        }
    }

    // Initialize Sortable on both lists
    document.addEventListener('DOMContentLoaded', function() {
        const hUl = document.querySelector('#horizontal-menu-builder ul') || document.querySelector('#horizontal-menu-builder');
        const vUl = document.querySelector('#vertical-menu-builder ul') || document.querySelector('#vertical-menu-builder');

        if (hUl) {
            new Sortable(hUl, { animation: 150, ghostClass: 'bg-gold-100' });
            enhanceListItems(hUl);
        }
        if (vUl) {
            new Sortable(vUl, { animation: 150, ghostClass: 'bg-gold-100' });
            enhanceListItems(vUl);
        }
    });

    function enhanceListItems(container) {
        container.querySelectorAll('li').forEach(li => {
            if (!li.querySelector('.delete-btn')) {
                li.className = 'flex items-center justify-between p-2.5 bg-white rounded-xl border border-slate-200 shadow-sm cursor-move mb-2 text-xs font-semibold text-slate-700';
                
                const deleteBtn = document.createElement('button');
                deleteBtn.type = 'button';
                deleteBtn.className = 'delete-btn text-red-500 hover:text-red-700 p-1 text-xs';
                deleteBtn.innerHTML = '<i class="fa-solid fa-trash-can"></i>';
                deleteBtn.onclick = function() { li.remove(); };
                li.appendChild(deleteBtn);
            }
        });
    }

    function addNewMenuItem() {
        const title = document.getElementById('new-item-title').value.trim();
        const url = document.getElementById('new-item-url').value.trim();

        if (!title || !url) {
            alert('يرجى كتابة عنوان الرابط ومسار الـ URL.');
            return;
        }

        const targetContainer = (activeTab === 'horizontal') 
            ? (document.querySelector('#horizontal-menu-builder ul') || document.getElementById('horizontal-menu-builder'))
            : (document.querySelector('#vertical-menu-builder ul') || document.getElementById('vertical-menu-builder'));

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
        const builder = (id === 'horizontal') 
            ? document.getElementById('horizontal-menu-builder')
            : document.getElementById('vertical-menu-builder');

        // Clean out delete buttons before serializing
        const clone = builder.cloneNode(true);
        clone.querySelectorAll('.delete-btn').forEach(btn => btn.remove());
        clone.querySelectorAll('li').forEach(li => {
            li.removeAttribute('class');
            li.removeAttribute('style');
        });

        const html = clone.innerHTML.trim();

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
                alert(data.message);
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

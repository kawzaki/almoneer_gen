@extends('layouts.admin')

@section('title', 'إدارة التصنيفات')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-folder-tree text-gold-500"></i>
                <span>إدارة التصنيفات والتبويبات (Categories)</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                تنظيم وتصنيف الأخبار، المحاضرات، الكتب، والقصائد لتسهيل استعراضها وفهرستها في الموقع.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.articles.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition flex items-center gap-1.5">
                <i class="fa-solid fa-newspaper text-slate-500"></i>
                <span>العودة لإدارة الأخبار</span>
            </a>
        </div>
    </div>

    <!-- Module Selector Tabs -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-2">
        @foreach($modules as $modKey => $modLabel)
        <a href="{{ route('admin.categories.index', ['module' => $modKey]) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $currentModule === $modKey ? 'bg-emerald-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            <span>{{ $modLabel }}</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $currentModule === $modKey ? 'bg-emerald-950 text-gold-300' : 'bg-slate-100 text-slate-500' }}">
                {{ $countsByModule[$modKey] ?? 0 }}
            </span>
        </a>
        @endforeach
    </div>

    <!-- Main Content: Create Form & Categories Table -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- Right Column: Add New Category Form -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
            <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-800 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-800">إضافة تصنيف جديد</h3>
                    <p class="text-[11px] text-slate-400">لقسم: {{ $modules[$currentModule] ?? $currentModule }}</p>
                </div>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="module" value="{{ $currentModule }}">

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">اسم التصنيف الجديد:</label>
                    <input type="text" 
                           name="name" 
                           required 
                           placeholder="مثال: بيانات رسمية، مؤتمرات فكرية..." 
                           class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white focus:border-emerald-800 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ترتيب الظهور (اختياري):</label>
                    <input type="number" 
                           name="order" 
                           value="0" 
                           class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white focus:border-emerald-800 transition">
                </div>

                <div>
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded text-emerald-800 focus:ring-emerald-800">
                        <span>مفعل ونشط في الموقع</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>حفظ وإضافة التصنيف</span>
                </button>
            </form>
        </div>

        <!-- Left Column: Categories List -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between text-xs">
                <span class="font-bold text-slate-700">التصنيفات المتاحة في ({{ $modules[$currentModule] ?? $currentModule }}):</span>
                <span class="text-slate-400">العدد الكلي: {{ $categories->total() }}</span>
            </div>

            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50/50 text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="p-4 font-bold">#</th>
                        <th class="p-4 font-bold">اسم التصنيف</th>
                        <th class="p-4 font-bold">المعرف (Slug)</th>
                        <th class="p-4 font-bold text-center">المواد المرتبطة</th>
                        <th class="p-4 font-bold text-center">الحالة</th>
                        <th class="p-4 font-bold text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $cat)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 text-slate-400 font-mono">{{ $cat->id }}</td>
                        <td class="p-4">
                            <span class="font-bold text-slate-800 text-sm block">{{ $cat->name }}</span>
                            @if($cat->order > 0)
                            <span class="text-[10px] text-slate-400">الترتيب: {{ $cat->order }}</span>
                            @endif
                        </td>
                        <td class="p-4 text-slate-500 font-mono text-[11px]">{{ $cat->slug }}</td>
                        <td class="p-4 text-center">
                            @php
                                $itemsCount = match($currentModule) {
                                    'article' => $cat->articles_count,
                                    'media'   => $cat->media_items_count,
                                    'book'    => $cat->books_count,
                                    'poem'    => $cat->poems_count,
                                    default   => 0
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $itemsCount > 0 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-400' }}">
                                {{ $itemsCount }} عنصر
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            @if($cat->is_active)
                            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">نشط</span>
                            @else
                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[10px]">معطل</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="inline-flex items-center gap-1.5">
                                <button type="button" 
                                        onclick="openEditCategoryModal({{ $cat->id }}, '{{ addslashes($cat->name) }}', {{ $cat->order ?? 0 }}, {{ $cat->is_active ? 'true' : 'false' }})" 
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" 
                                        title="تعديل التصنيف">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف التصنيف \'{{ addslashes($cat->name) }}\'؟ لن يتم حذف المواد المرتبطة به بل ستصبح بدون تصنيف.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="حذف التصنيف">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">
                            لا توجد تصنيفات مضافة في هذا القسم حالياً. يمكنك إضافة أول تصنيف من النموذج.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($categories->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="edit-category-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-pen text-gold-500"></i>
                <span>تعديل التصنيف</span>
            </h3>
            <button onclick="closeEditCategoryModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="edit-category-form" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">اسم التصنيف:</label>
                <input type="text" name="name" id="edit-cat-name" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white focus:border-emerald-800 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">الترتيب:</label>
                <input type="number" name="order" id="edit-cat-order" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-slate-50 focus:bg-white focus:border-emerald-800 transition">
            </div>

            <div>
                <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_active" id="edit-cat-active" value="1" class="rounded text-emerald-800 focus:ring-emerald-800">
                    <span>مفعل ونشط في الموقع</span>
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditCategoryModal()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl">إلغاء</button>
                <button type="submit" class="px-5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold rounded-xl shadow-md">تحديث وحفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openEditCategoryModal(id, name, order, isActive) {
        var modal = document.getElementById('edit-category-modal');
        var form = document.getElementById('edit-category-form');
        form.action = '/admin/categories/' + id;
        document.getElementById('edit-cat-name').value = name;
        document.getElementById('edit-cat-order').value = order;
        document.getElementById('edit-cat-active').checked = isActive;
        modal.classList.remove('hidden');
    }

    function closeEditCategoryModal() {
        document.getElementById('edit-category-modal').classList.add('hidden');
    }

    // Close modal on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeEditCategoryModal();
    });
</script>
@endpush

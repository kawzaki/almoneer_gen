@extends('layouts.admin')

@section('title', 'إدارة المستخدمين والمشرفين')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800">إدارة المستخدمين والمشرفين</h2>
            <p class="text-xs text-slate-500 mt-0.5 sm:mt-1">إضافة مشرفين، تعديل الصلاحيات، وإدارة حسابات الدخول إلى لوحة التحكم.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="self-start sm:self-auto px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i>
            <span>إضافة مستخدم جديد</span>
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Search Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <i class="fa-solid fa-magnifying-glass absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="البحث بالاسم أو اسم المستخدم أو البريد الإلكتروني..." class="w-full text-xs rounded-xl border-slate-200 pr-9 pl-3 py-2.5 bg-slate-50 focus:bg-white focus:border-emerald-600 transition">
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none px-4 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold rounded-xl transition">
                    بحث
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs rounded-xl transition">
                        إلغاء
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200 whitespace-nowrap">
                    <tr>
                        <th class="p-3.5 sm:p-4 font-bold">#</th>
                        <th class="p-3.5 sm:p-4 font-bold">الاسم</th>
                        <th class="p-3.5 sm:p-4 font-bold">اسم المستخدم</th>
                        <th class="p-3.5 sm:p-4 font-bold">البريد الإلكتروني</th>
                        <th class="p-3.5 sm:p-4 font-bold">الدور / الصلاحية</th>
                        <th class="p-3.5 sm:p-4 font-bold">تاريخ الإنشاء</th>
                        <th class="p-3.5 sm:p-4 font-bold text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-3.5 sm:p-4 text-slate-400 font-mono">{{ $user->id }}</td>
                        <td class="p-3.5 sm:p-4 font-bold text-slate-800 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-[11px]">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </div>
                                <span>{{ $user->name }}</span>
                                @if(auth()->id() === $user->id)
                                    <span class="text-[10px] bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded font-bold border border-emerald-200">حسابك الحالي</span>
                                @endif
                            </div>
                        </td>
                        <td class="p-3.5 sm:p-4 font-mono text-slate-600 whitespace-nowrap" dir="ltr">{{ $user->username }}</td>
                        <td class="p-3.5 sm:p-4 text-slate-500 font-mono whitespace-nowrap" dir="ltr">{{ $user->email }}</td>
                        <td class="p-3.5 sm:p-4 whitespace-nowrap">
                            @if($user->role === 'super_admin')
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                    <i class="fa-solid fa-shield-halved ml-1 text-amber-600"></i>
                                    مدير عام (Super Admin)
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-300">
                                    <i class="fa-solid fa-user-pen ml-1 text-slate-500"></i>
                                    مشرف محتوى (Admin)
                                </span>
                            @endif
                        </td>
                        <td class="p-3.5 sm:p-4 text-slate-500 whitespace-nowrap">{{ $user->created_at ? $user->created_at->format('Y/m/d') : '-' }}</td>
                        <td class="p-3.5 sm:p-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="تعديل">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                @if(auth()->id() !== $user->id && $user->id !== 1)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف المستخدم {{ $user->name }}؟ لا يمكن التراجع عن هذا الإجراء.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="حذف">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">
                            لا يوجد مستخدمون يطابقون معايير البحث.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $users->links() }}</div>
</div>
@endsection

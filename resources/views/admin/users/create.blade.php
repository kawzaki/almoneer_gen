@extends('layouts.admin')

@section('title', 'إضافة مستخدم جديد')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">إضافة مستخدم جديد</h2>
            <p class="text-xs text-slate-500 mt-1">إنشاء حساب جديد للمشرفين بصلاحيات محددة للدخول إلى لوحة التحكم.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-right"></i>
            <span>العودة للقائمة</span>
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

    <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">الاسم الكامل: <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="مثال: محمد علي" class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:border-emerald-600 transition">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">اسم المستخدم (للدخول): <span class="text-red-500">*</span></label>
                <input type="text" name="username" value="{{ old('username') }}" required placeholder="username" dir="ltr" class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:border-emerald-600 transition">
                <span class="text-[10px] text-slate-400 mt-1 block">أحرف إنجليزية وأرقام بدون مسافات</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">البريد الإلكتروني: <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="user@almoneer.org" dir="ltr" class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:border-emerald-600 transition">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">الدور والصلاحية: <span class="text-red-500">*</span></label>
            <select name="role" required class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:border-emerald-600 transition">
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>مشرف محتوى (Admin) - إدارة المقالات، الصوتيات، المعرض، الفتاوى</option>
                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>مدير عام (Super Admin) - صلاحيات كاملة على النظام والمستخدمين والإعدادات</option>
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">كلمة المرور: <span class="text-red-500">*</span></label>
                <input type="password" name="password" required placeholder="8 أحرف كحد أدنى" dir="ltr" class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:border-emerald-600 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">تأكيد كلمة المرور: <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required placeholder="أعد إدخال كلمة المرور" dir="ltr" class="w-full text-xs rounded-xl border-slate-200 p-3 bg-slate-50 focus:bg-white focus:border-emerald-600 transition">
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                إلغاء
            </a>
            <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
                <i class="fa-solid fa-check"></i>
                <span>حفظ وإنشاء المستخدم</span>
            </button>
        </div>
    </form>
</div>
@endsection

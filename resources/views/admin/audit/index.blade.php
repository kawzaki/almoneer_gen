@extends('layouts.admin')

@section('title', 'سجل الرقابة وتتبع العمليات')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">سجل الرقابة وتتبع العمليات (Audit Trail)</h2>
        <p class="text-xs text-slate-500 mt-1">توثيق شامل لكافة العمليات الإدارية (إنشاء، تعديل، حذف) مع اسم المستخدم وعنوان الـ IP.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-right text-xs">
            <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                <tr>
                    <th class="p-4 font-bold">المستخدم</th>
                    <th class="p-4 font-bold">عنوان الـ IP</th>
                    <th class="p-4 font-bold">العملية</th>
                    <th class="p-4 font-bold">الوحدة / القسم</th>
                    <th class="p-4 font-bold">معرف الكائن</th>
                    <th class="p-4 font-bold">التاريخ والوقت</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($logs as $log)
                <tr class="hover:bg-slate-50 transition">
                    <td class="p-4 font-bold text-slate-800">{{ $log->user->name ?? 'مستخدم النظام' }}</td>
                    <td class="p-4 font-mono text-slate-500">{{ $log->ip_address }}</td>
                    <td class="p-4">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $log->action === 'create' ? 'bg-emerald-100 text-emerald-800' : ($log->action === 'update' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="p-4 font-semibold text-slate-700">{{ $log->module }}</td>
                    <td class="p-4 font-mono text-slate-400">#{{ $log->object_id }}</td>
                    <td class="p-4 text-slate-500">{{ $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-6 text-center text-slate-400">لا توجد سجلات رقابية مسجلة حتى الآن.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $logs->links() }}</div>
</div>
@endsection

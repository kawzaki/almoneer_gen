@extends('layouts.admin')

@section('title', 'إدارة الصوتيات والمرئيات')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">إدارة الصوتيات والمرئيات والريلز</h2>
            <p class="text-xs text-slate-500 mt-1">إضافة محاضرات صوتية ومرئيات يوتيوب والريلز مع الكشف عن المدة.</p>
        </div>
        <a href="{{ route('admin.media.create') }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>إضافة مادة إعلامية</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-right text-xs">
            <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                <tr>
                    <th class="p-4 font-bold">العنوان</th>
                    <th class="p-4 font-bold">النوع</th>
                    <th class="p-4 font-bold">الموسم</th>
                    <th class="p-4 font-bold">المدة</th>
                    <th class="p-4 font-bold">المشاهدات</th>
                    <th class="p-4 font-bold text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($items as $m)
                <tr class="hover:bg-slate-50 transition">
                    <td class="p-4 font-bold text-slate-800 max-w-xs truncate">{{ $m->title }}</td>
                    <td class="p-4">
                        <span class="px-2 py-0.5 rounded font-bold {{ $m->type === 'audio' ? 'bg-emerald-100 text-emerald-800' : ($m->type === 'short' ? 'bg-purple-100 text-purple-800' : 'bg-red-100 text-red-800') }}">
                            {{ $m->type === 'audio' ? 'صوتي' : ($m->type === 'short' ? 'ريلز قصير' : 'مرئي') }}
                        </span>
                    </td>
                    <td class="p-4 text-slate-500">
                        <div>{{ $m->season_year ?? '-' }}</div>
                        @if($m->lecture_number)
                            <span class="inline-block mt-0.5 text-[10px] font-bold px-1.5 py-0.5 rounded bg-amber-100 text-amber-900">الليلة {{ $m->lecture_number }}</span>
                        @endif
                    </td>
                    <td class="p-4 text-slate-500 font-mono">{{ $m->duration ?? '-' }}</td>
                    <td class="p-4 text-slate-500">{{ $m->views_count }}</td>
                    <td class="p-4 text-center">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.media.edit', $m->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('admin.media.destroy', $m->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-6 text-center text-slate-400">لا توجد مواد إعلامية مضافة.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $items->links() }}</div>
</div>
@endsection

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WeeklyWisdom;
use Illuminate\Http\Request;

class WeeklyWisdomController extends Controller
{
    public function index()
    {
        $wisdoms = WeeklyWisdom::latest()->paginate(15);
        return view('admin.wisdom.index', compact('wisdoms'));
    }

    public function create()
    {
        return view('admin.wisdom.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'quote' => 'required|string',
        ]);

        WeeklyWisdom::create([
            'title'     => $request->title ?: 'حِكَم وأقوال',
            'quote'     => $request->quote,
            'source'    => $request->source,
            'image'     => $request->image,
            'is_active' => $request->boolean('is_active', true),
        ]);

        \Illuminate\Support\Facades\Cache::forget('site.wisdom.active_ids');

        return redirect()->route('admin.wisdom.index')->with('success', 'تم حفظ الحكمة بنجاح وتحديث الكاش!');
    }

    public function edit(WeeklyWisdom $wisdom)
    {
        return view('admin.wisdom.edit', compact('wisdom'));
    }

    public function update(Request $request, WeeklyWisdom $wisdom)
    {
        $request->validate([
            'quote' => 'required|string',
        ]);

        $wisdom->update([
            'title'     => $request->title ?: 'حِكَم وأقوال',
            'quote'     => $request->quote,
            'source'    => $request->source,
            'image'     => $request->image,
            'is_active' => $request->boolean('is_active'),
        ]);

        \Illuminate\Support\Facades\Cache::forget('site.wisdom.active_ids');

        return redirect()->route('admin.wisdom.index')->with('success', 'تم تحديث الحكمة بنجاح وتحديث الكاش!');
    }

    public function destroy(WeeklyWisdom $wisdom)
    {
        $wisdom->delete();
        \Illuminate\Support\Facades\Cache::forget('site.wisdom.active_ids');
        return redirect()->route('admin.wisdom.index')->with('success', 'تم حذف الحكمة بنجاح!');
    }
}

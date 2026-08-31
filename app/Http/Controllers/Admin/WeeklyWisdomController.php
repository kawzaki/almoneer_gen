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

        if ($request->boolean('is_active')) {
            WeeklyWisdom::where('is_active', true)->update(['is_active' => false]);
        }

        WeeklyWisdom::create([
            'title'     => $request->title,
            'quote'     => $request->quote,
            'source'    => $request->source,
            'image'     => $request->image,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.wisdom.index')->with('success', 'تم حفظ كلمة الأسبوع بنجاح وتحديث الكاش!');
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

        if ($request->boolean('is_active')) {
            WeeklyWisdom::where('id', '!=', $wisdom->id)->update(['is_active' => false]);
        }

        $wisdom->update([
            'title'     => $request->title,
            'quote'     => $request->quote,
            'source'    => $request->source,
            'image'     => $request->image,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.wisdom.index')->with('success', 'تم تحديث كلمة الأسبوع بنجاح وتحديث الكاش!');
    }

    public function destroy(WeeklyWisdom $wisdom)
    {
        $wisdom->delete();
        return redirect()->route('admin.wisdom.index')->with('success', 'تم حذف كلمة الأسبوع بنجاح!');
    }
}

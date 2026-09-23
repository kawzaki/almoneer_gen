<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PoemController extends Controller
{
    public function index()
    {
        $poems = Poem::with('category')->latest()->paginate(15);
        return view('admin.poems.index', compact('poems'));
    }

    public function create()
    {
        $categories = Category::where('module', 'poem')->get();
        return view('admin.poems.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'verses' => 'required',
        ]);

        $slug = Str::slug($request->title, '-', null) ?: 'poem-' . time();
        if (Poem::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/poems'), $filename);
            $imagePath = 'uploads/poems/' . $filename;
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        Poem::create([
            'title'       => $request->title,
            'slug'        => $slug,
            'category_id' => $request->category_id,
            'occasion'    => $request->occasion,
            'poem_date'   => $request->poem_date,
            'meter'       => $request->meter,
            'image'       => $imagePath,
            'audio_url'   => $request->audio_url,
            'verses'      => $request->verses,
            'description' => $request->description,
            'is_featured' => $request->boolean('is_featured'),
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.poems.index')->with('success', 'تم حفظ القصيدة في الديوان بنجاح!');
    }

    public function edit(Poem $poem)
    {
        $categories = Category::where('module', 'poem')->get();
        return view('admin.poems.edit', compact('poem', 'categories'));
    }

    public function update(Request $request, Poem $poem)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'verses' => 'required',
        ]);

        $imagePath = $poem->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/poems'), $filename);
            $imagePath = 'uploads/poems/' . $filename;
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        $poem->update([
            'title'       => $request->title,
            'category_id' => $request->category_id,
            'occasion'    => $request->occasion,
            'poem_date'   => $request->poem_date,
            'meter'       => $request->meter,
            'image'       => $imagePath,
            'audio_url'   => $request->audio_url,
            'verses'      => $request->verses,
            'description' => $request->description,
            'is_featured' => $request->boolean('is_featured'),
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.poems.index')->with('success', 'تم تحديث القصيدة بنجاح!');
    }

    public function destroy(Poem $poem)
    {
        $poem->delete();
        return redirect()->route('admin.poems.index')->with('success', 'تم حذف القصيدة بنجاح!');
    }
}

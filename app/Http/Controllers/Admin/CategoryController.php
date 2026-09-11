<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $currentModule = $request->query('module', 'article');
        
        $modules = [
            'article' => 'الأخبار والنشاطات والمقالات',
            'media'   => 'المحاضرات والمرئيات والصوتيات',
            'book'    => 'الكتب والمؤلفات',
            'poem'    => 'ديوان الشعر والقصائد',
            'inquiry' => 'الاستفسارات والفتاوى',
        ];

        $categories = Category::where('module', $currentModule)
            ->withCount(['articles', 'mediaItems', 'books', 'poems'])
            ->orderBy('order')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Also count categories per module for tab badges
        $countsByModule = Category::groupBy('module')
            ->selectRaw('module, count(*) as count')
            ->pluck('count', 'module')
            ->toArray();

        return view('admin.categories.index', compact('categories', 'modules', 'currentModule', 'countsByModule'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'module' => 'required|string|in:article,media,book,poem,gallery,inquiry',
            'order'  => 'nullable|integer',
        ]);

        $slug = Str::slug($request->name, '-', null);
        if (empty($slug)) {
            $slug = 'cat-' . time();
        }

        // Ensure slug uniqueness
        $count = Category::where('slug', $slug)->count();
        if ($count > 0) {
            $slug .= '-' . time();
        }

        Category::create([
            'name'      => $request->name,
            'slug'      => $slug,
            'module'    => $request->module,
            'order'     => $request->input('order', 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->back()->with('success', "تمت إضافة التصنيف '{$request->name}' بنجاح!");
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'order'  => 'nullable|integer',
        ]);

        $category->update([
            'name'      => $request->name,
            'order'     => $request->input('order', $category->order),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->back()->with('success', "تم تحديث التصنيف '{$category->name}' بنجاح!");
    }

    public function destroy(Category $category)
    {
        $name = $category->name;

        // Disassociate related items safely
        $category->articles()->update(['category_id' => null]);
        $category->mediaItems()->update(['category_id' => null]);
        $category->books()->update(['category_id' => null]);
        $category->poems()->update(['category_id' => null]);

        $category->delete();

        return redirect()->back()->with('success', "تم حذف التصنيف '{$name}' بنجاح!");
    }
}

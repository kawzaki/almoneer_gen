<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('category')->latest()->paginate(15);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::where('module', 'article')->get();
        return view('admin.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required',
            'type'       => 'required|in:news,activity,bio,article',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
        ]);

        $slug = Str::slug($request->title, '-', null);
        if (empty($slug)) {
            $slug = 'art-' . time();
        }

        $count = Article::where('slug', $slug)->count();
        if ($count > 0) {
            $slug .= '-' . time();
        }

        $imagePath = $request->image;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'news_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/articles');
            if (!File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $imagePath = 'uploads/articles/' . $filename;
        }

        Article::create([
            'title'        => $request->title,
            'slug'         => $slug,
            'category_id'  => $request->category_id,
            'summary'      => $request->summary,
            'content'      => $request->content,
            'image'        => $imagePath,
            'type'         => $request->type,
            'tags'         => $request->tags,
            'is_featured'  => $request->boolean('is_featured'),
            'is_active'    => $request->boolean('is_active', true),
            'published_at' => $request->published_at ?? now(),
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'تم حفظ الخبر بنجاح وتحديث كاش الموقع تلقائياً!');
    }

    public function edit(Article $article)
    {
        $categories = Category::where('module', 'article')->get();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
        ]);

        $imagePath = $article->image;

        if ($request->boolean('remove_image')) {
            $imagePath = null;
        } elseif ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'news_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/articles');
            if (!File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $imagePath = 'uploads/articles/' . $filename;
        } elseif ($request->filled('image')) {
            $imagePath = $request->image;
        }

        $article->update([
            'title'        => $request->title,
            'category_id'  => $request->category_id,
            'summary'      => $request->summary,
            'content'      => $request->content,
            'image'        => $imagePath,
            'type'         => $request->type,
            'tags'         => $request->tags,
            'is_featured'  => $request->boolean('is_featured'),
            'is_active'    => $request->boolean('is_active', true),
            'published_at' => $request->published_at ?? $article->published_at,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'تم تحديث الخبر بنجاح وتحديث كاش الموقع تلقائياً!');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'تم حذف المقال بنجاح!');
    }
}

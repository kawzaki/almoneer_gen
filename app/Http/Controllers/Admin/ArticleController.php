<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'title'   => 'required|string|max:255',
            'content' => 'required',
            'type'    => 'required|in:news,activity,bio,article',
        ]);

        $slug = Str::slug($request->title, '-', null);
        if (empty($slug)) {
            $slug = 'art-' . time();
        }

        $count = Article::where('slug', $slug)->count();
        if ($count > 0) {
            $slug .= '-' . time();
        }

        Article::create([
            'title'        => $request->title,
            'slug'         => $slug,
            'category_id'  => $request->category_id,
            'summary'      => $request->summary,
            'content'      => $request->content,
            'image'        => $request->image,
            'type'         => $request->type,
            'is_featured'  => $request->boolean('is_featured'),
            'is_active'    => $request->boolean('is_active', true),
            'published_at' => $request->published_at ?? now(),
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'تم حفظ المقال بنجاح وتحديث كاش الموقع تلقائياً!');
    }

    public function edit(Article $article)
    {
        $categories = Category::where('module', 'article')->get();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required',
        ]);

        $article->update([
            'title'        => $request->title,
            'category_id'  => $request->category_id,
            'summary'      => $request->summary,
            'content'      => $request->content,
            'image'        => $request->image,
            'type'         => $request->type,
            'is_featured'  => $request->boolean('is_featured'),
            'is_active'    => $request->boolean('is_active', true),
            'published_at' => $request->published_at ?? $article->published_at,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'تم تحديث المقال بنجاح وتحديث كاش الموقع تلقائياً!');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'تم حذف المقال بنجاح!');
    }
}

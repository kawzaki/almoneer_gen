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
        $articles = Article::with('category')
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->paginate(15);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::where('module', 'article')->active()->orderBy('order')->get();
        return view('admin.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required',
            'category_id'  => 'nullable|exists:categories,id',
            'image_file'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
            'published_at' => 'nullable|date',
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

        $publishedAt = now();
        if ($request->filled('published_at')) {
            try {
                $publishedAt = \Carbon\Carbon::parse($request->published_at);
            } catch (\Exception $e) {
                $publishedAt = now();
            }
        }

        $category = $request->filled('category_id') ? Category::find($request->category_id) : null;
        $type = 'news';
        if ($category && in_array($category->slug, ['news', 'activity', 'statement', 'article', 'bio'])) {
            $type = $category->slug;
        }

        Article::create([
            'title'        => $request->title,
            'slug'         => $slug,
            'category_id'  => $request->category_id,
            'summary'      => $request->summary,
            'content'      => $request->content,
            'image'        => $imagePath,
            'type'         => $type,
            'tags'         => $request->tags,
            'is_featured'  => $request->boolean('is_featured'),
            'is_active'    => $request->boolean('is_active', true),
            'published_at' => $publishedAt,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'تم حفظ الخبر بنجاح وتحديث كاش الموقع تلقائياً!');
    }

    public function edit(Article $article)
    {
        $categories = Category::where('module', 'article')->active()->orderBy('order')->get();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required',
            'category_id'  => 'nullable|exists:categories,id',
            'image_file'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
            'published_at' => 'nullable|date',
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

        $publishedAt = $article->published_at ?? $article->created_at;
        if ($request->filled('published_at')) {
            try {
                $publishedAt = \Carbon\Carbon::parse($request->published_at);
            } catch (\Exception $e) {
                // keep existing
            }
        }

        $category = $request->filled('category_id') ? Category::find($request->category_id) : null;
        $type = $article->type ?: 'news';
        if ($category && in_array($category->slug, ['news', 'activity', 'statement', 'article', 'bio'])) {
            $type = $category->slug;
        }

        $article->update([
            'title'        => $request->title,
            'category_id'  => $request->category_id,
            'summary'      => $request->summary,
            'content'      => $request->content,
            'image'        => $imagePath,
            'type'         => $type,
            'tags'         => $request->tags,
            'is_featured'  => $request->boolean('is_featured'),
            'is_active'    => $request->boolean('is_active', true),
            'published_at' => $publishedAt,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'تم تحديث الخبر بنجاح وتحديث كاش الموقع تلقائياً!');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'تم حذف المقال بنجاح!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('category')->orderBy('order')->paginate(15);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::where('module', 'book')->get();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'author' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->title, '-', null) ?: 'book-' . time();
        if (Book::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        Book::create([
            'title'             => $request->title,
            'slug'              => $slug,
            'category_id'       => $request->category_id,
            'author'            => $request->author,
            'publisher'         => $request->publisher,
            'publication_year'  => $request->publication_year,
            'pages_count'       => $request->pages_count,
            'isbn'              => $request->isbn,
            'cover_image'       => $request->cover_image,
            'pdf_file'          => $request->pdf_file,
            'summary'           => $request->summary,
            'table_of_contents' => $request->table_of_contents,
            'buy_url'           => $request->buy_url,
            'order'             => $request->order ?? 0,
            'is_featured'       => $request->boolean('is_featured'),
            'is_active'         => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.books.index')->with('success', 'تم حفظ بيانات الكتاب بنجاح!');
    }

    public function edit(Book $book)
    {
        $categories = Category::where('module', 'book')->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'author' => 'required|string|max:255',
        ]);

        $book->update([
            'title'             => $request->title,
            'category_id'       => $request->category_id,
            'author'            => $request->author,
            'publisher'         => $request->publisher,
            'publication_year'  => $request->publication_year,
            'pages_count'       => $request->pages_count,
            'isbn'              => $request->isbn,
            'cover_image'       => $request->cover_image,
            'pdf_file'          => $request->pdf_file,
            'summary'           => $request->summary,
            'table_of_contents' => $request->table_of_contents,
            'buy_url'           => $request->buy_url,
            'order'             => $request->order ?? 0,
            'is_featured'       => $request->boolean('is_featured'),
            'is_active'         => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.books.index')->with('success', 'تم تحديث بيانات الكتاب بنجاح!');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('admin.books.index')->with('success', 'تم حذف الكتاب بنجاح!');
    }
}

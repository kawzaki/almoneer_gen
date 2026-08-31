<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;

class BookController extends Controller
{
    public function index()
    {
        $query = Book::active()->orderBy('order');
        if (request('category')) {
            $cat = Category::where('slug', request('category'))->first();
            if ($cat) {
                $query->where('category_id', $cat->id);
            }
        }
        $books = $query->paginate(12);
        $categories = Category::where(function($q) {
            $q->where('module', 'book')->orWhere('module', 'books');
        })->active()->get();

        return view('pages.books', compact('books', 'categories'));
    }

    public function show($slug)
    {
        $book = Book::active()->where('slug', $slug)->firstOrFail();
        $relatedBooks = Book::active()
            ->where('id', '!=', $book->id)
            ->latest()
            ->take(3)
            ->get();

        return view('pages.book_show', compact('book', 'relatedBooks'));
    }

    public function download($slug)
    {
        $book = Book::active()->where('slug', $slug)->firstOrFail();
        $book->increment('download_count');

        if ($book->pdf_file && file_exists(public_path($book->pdf_file))) {
            return response()->download(public_path($book->pdf_file));
        }

        return redirect()->back()->with('error', 'الملف غير متوفر حالياً للتحميل');
    }
}

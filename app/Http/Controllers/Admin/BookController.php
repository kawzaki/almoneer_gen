<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('category')->orderBy('order')->paginate(15);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::whereIn('module', ['book', 'books'])->get();
        $availableCovers = $this->getAvailableCovers();
        return view('admin.books.create', compact('categories', 'availableCovers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'author'     => 'required|string|max:255',
            'cover_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:10240',
            'pdf_upload' => 'nullable|mimes:pdf|max:51200',
        ]);

        $slug = Str::slug($request->title, '-', null) ?: 'book-' . time();
        if (Book::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        $coverPath = $request->cover_image;
        if ($request->hasFile('cover_file')) {
            $file = $request->file('cover_file');
            $filename = 'book_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/books');
            if (!File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $coverPath = 'uploads/books/' . $filename;
        }

        $pdfPath = $request->pdf_file;
        if ($request->hasFile('pdf_upload')) {
            $file = $request->file('pdf_upload');
            $filename = 'book_pdf_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/books');
            if (!File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $pdfPath = 'uploads/books/' . $filename;
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
            'cover_image'       => $coverPath,
            'pdf_file'          => $pdfPath,
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
        $categories = Category::whereIn('module', ['book', 'books'])->get();
        $availableCovers = $this->getAvailableCovers();
        return view('admin.books.edit', compact('book', 'categories', 'availableCovers'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'author'     => 'required|string|max:255',
            'cover_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:10240',
            'pdf_upload' => 'nullable|mimes:pdf|max:51200',
        ]);

        $coverPath = $book->cover_image;

        if ($request->boolean('remove_cover')) {
            $coverPath = null;
        } elseif ($request->hasFile('cover_file')) {
            $file = $request->file('cover_file');
            $filename = 'book_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/books');
            if (!File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $coverPath = 'uploads/books/' . $filename;
        } elseif ($request->filled('cover_image')) {
            $coverPath = $request->cover_image;
        } elseif ($request->has('cover_image') && empty($request->cover_image)) {
            $coverPath = null;
        }

        $pdfPath = $book->pdf_file;
        if ($request->boolean('remove_pdf')) {
            $pdfPath = null;
        } elseif ($request->hasFile('pdf_upload')) {
            $file = $request->file('pdf_upload');
            $filename = 'book_pdf_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/books');
            if (!File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $pdfPath = 'uploads/books/' . $filename;
        } elseif ($request->filled('pdf_file')) {
            $pdfPath = $request->pdf_file;
        }

        $book->update([
            'title'             => $request->title,
            'category_id'       => $request->category_id,
            'author'            => $request->author,
            'publisher'         => $request->publisher,
            'publication_year'  => $request->publication_year,
            'pages_count'       => $request->pages_count,
            'isbn'              => $request->isbn,
            'cover_image'       => $coverPath,
            'pdf_file'          => $pdfPath,
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

    /**
     * Retrieve list of all available uploaded covers & images for the selector modal.
     */
    protected function getAvailableCovers()
    {
        $covers = [];
        $paths = [
            'uploads/books'    => [public_path('uploads/books'), 'أغلفة الكتب', false],
            'uploads/articles' => [public_path('uploads/articles'), 'الأخبار والمقالات', false],
            'uploads/gallery'  => [public_path('uploads/gallery'), 'معرض الصور', true],
            'images'           => [public_path('images'), 'صور الموقع', false],
        ];

        foreach ($paths as $prefix => [$dir, $folderLabel, $recursive]) {
            if (File::isDirectory($dir)) {
                $files = $recursive ? File::allFiles($dir) : File::files($dir);
                foreach ($files as $file) {
                    $ext = strtolower($file->getExtension());
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg', 'gif'])) {
                        $rel = str_replace('\\', '/', ltrim(str_replace(public_path(), '', $file->getRealPath()), '\\/'));
                        $covers[] = [
                            'path'   => $rel,
                            'url'    => asset($rel),
                            'name'   => $file->getFilename(),
                            'folder' => $folderLabel,
                        ];
                    }
                }
            }
        }

        // Include any distinct custom cover_images stored in DB
        $dbCovers = Book::whereNotNull('cover_image')->where('cover_image', '!=', '')->distinct()->pluck('cover_image');
        $existingPaths = array_column($covers, 'path');
        foreach ($dbCovers as $c) {
            if (!in_array($c, $existingPaths)) {
                $covers[] = [
                    'path'   => $c,
                    'url'    => str_starts_with($c, 'http') ? $c : asset($c),
                    'name'   => basename($c),
                    'folder' => 'مخصص',
                ];
            }
        }

        return $covers;
    }
}

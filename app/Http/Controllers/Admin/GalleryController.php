<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $parentId = $request->get('parent_id');
        $query = GalleryAlbum::with('parent')->withCount('items');

        if ($parentId !== null && $parentId !== '') {
            if ($parentId == '0' || $parentId == 'main') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $parentId);
            }
        }

        $albums = $query->orderBy('order')->orderByDesc('id')->paginate(15);
        $parents = GalleryAlbum::parents()->orderBy('title')->get();

        return view('admin.gallery.index', compact('albums', 'parents', 'parentId'));
    }

    public function create()
    {
        $parentAlbums = GalleryAlbum::parents()->orderBy('title')->get();
        return view('admin.gallery.create', compact('parentAlbums'));
    }

    /**
     * Handle FilePond temporary upload
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $tempDir = public_path('uploads/gallery/tmp');
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }

            $filename = 'tmp_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move($tempDir, $filename);

            $relativePath = 'uploads/gallery/tmp/' . $filename;
            return response($relativePath, 200)->header('Content-Type', 'text/plain');
        }

        return response('No file uploaded', 400);
    }

    /**
     * Handle FilePond temporary upload revert
     */
    public function revertUpload(Request $request)
    {
        $path = $request->getContent();
        if ($path) {
            $fullPath = public_path(trim($path));
            if (File::exists($fullPath) && str_contains($fullPath, 'uploads/gallery/tmp')) {
                File::delete($fullPath);
                return response('Deleted', 200);
            }
        }

        return response('File not found', 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->title, '-', null);
        if (empty($slug)) {
            $slug = 'album-' . time();
        }

        if (GalleryAlbum::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        $parentId = $request->filled('pid') ? $request->input('pid') : $request->input('parent_id');
        if ($parentId == 0 || $parentId == '0') {
            $parentId = null;
        }

        $isActive = true;
        if ($request->has('isdisabled')) {
            $isActive = !$request->boolean('isdisabled');
        } elseif ($request->has('is_active')) {
            $isActive = $request->boolean('is_active');
        }

        $order = $request->filled('ord') ? (int)$request->input('ord') : (int)$request->input('order', 0);
        $description = $request->input('brief') ?? $request->input('description');

        $album = GalleryAlbum::create([
            'parent_id'   => $parentId,
            'title'       => $request->title,
            'slug'        => $slug,
            'cover_image' => $request->cover_image,
            'description' => $description,
            'event_date'  => $request->event_date,
            'order'       => $order,
            'is_active'   => $isActive,
        ]);

        // Process uploaded photos
        $photos = $request->input('photos');
        if (is_string($photos)) {
            $photos = json_decode($photos, true) ?: [];
        }

        if (is_array($photos) && count($photos) > 0) {
            $albumDir = public_path("uploads/gallery/{$album->id}");
            if (!File::exists($albumDir)) {
                File::makeDirectory($albumDir, 0755, true);
            }

            $firstPhotoPath = null;
            foreach ($photos as $idx => $tempPath) {
                if (empty($tempPath)) continue;
                $tempFullPath = public_path($tempPath);

                if (File::exists($tempFullPath)) {
                    $ext = pathinfo($tempFullPath, PATHINFO_EXTENSION) ?: 'jpg';
                    $finalFilename = 'img_' . time() . '_' . Str::random(6) . '.' . $ext;
                    $finalRelPath = "uploads/gallery/{$album->id}/" . $finalFilename;

                    File::move($tempFullPath, public_path($finalRelPath));

                    GalleryItem::create([
                        'album_id'   => $album->id,
                        'image_path' => $finalRelPath,
                        'caption'    => null,
                        'order'      => $idx,
                    ]);

                    if (!$firstPhotoPath) {
                        $firstPhotoPath = $finalRelPath;
                    }
                }
            }

            if (empty($album->cover_image) && $firstPhotoPath) {
                $album->cover_image = $firstPhotoPath;
                $album->save();
            }
        }

        return redirect()->route('admin.gallery.index')->with('success', 'تم إنشاء ألبوم الصور وحفظ المرفقات بنجاح!');
    }

    public function edit(GalleryAlbum $gallery)
    {
        $gallery->load(['items', 'parent']);
        $parentAlbums = GalleryAlbum::parents()->where('id', '!=', $gallery->id)->orderBy('title')->get();
        return view('admin.gallery.edit', [
            'album'        => $gallery,
            'parentAlbums' => $parentAlbums,
            'items'        => $gallery->items,
        ]);
    }

    public function update(Request $request, GalleryAlbum $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $parentId = $request->filled('pid') ? $request->input('pid') : $request->input('parent_id');
        if ($parentId == 0 || $parentId == '0' || $parentId == $gallery->id) {
            $parentId = null;
        }

        $isActive = true;
        if ($request->has('isdisabled')) {
            $isActive = !$request->boolean('isdisabled');
        } elseif ($request->has('is_active')) {
            $isActive = $request->boolean('is_active');
        }

        $order = $request->filled('ord') ? (int)$request->input('ord') : (int)$request->input('order', 0);
        $description = $request->input('brief') ?? $request->input('description');

        $coverImage = $gallery->cover_image;
        if ($request->filled('selected_cover')) {
            $coverImage = $request->input('selected_cover');
        } elseif ($request->filled('cover_image')) {
            $coverImage = $request->input('cover_image');
        }

        // Handle direct cover file upload if provided
        if ($request->hasFile('cover_file')) {
            $coverFile = $request->file('cover_file');
            $coverDir = public_path("uploads/gallery/{$gallery->id}");
            if (!File::exists($coverDir)) {
                File::makeDirectory($coverDir, 0755, true);
            }
            $coverFilename = 'cover_' . time() . '.' . $coverFile->getClientOriginalExtension();
            $coverFile->move($coverDir, $coverFilename);
            $coverImage = "uploads/gallery/{$gallery->id}/" . $coverFilename;
        }

        $gallery->update([
            'parent_id'   => $parentId,
            'title'       => $request->title,
            'cover_image' => $coverImage,
            'description' => $description,
            'event_date'  => $request->event_date,
            'order'       => $order,
            'is_active'   => $isActive,
        ]);

        // Process newly uploaded photos if any
        $photos = $request->input('photos');
        if (is_string($photos)) {
            $photos = json_decode($photos, true) ?: [];
        }

        if (is_array($photos) && count($photos) > 0) {
            $albumDir = public_path("uploads/gallery/{$gallery->id}");
            if (!File::exists($albumDir)) {
                File::makeDirectory($albumDir, 0755, true);
            }

            $currentMaxOrder = $gallery->items()->max('order') ?? 0;

            foreach ($photos as $idx => $tempPath) {
                if (empty($tempPath)) continue;
                $tempFullPath = public_path($tempPath);

                if (File::exists($tempFullPath)) {
                    $ext = pathinfo($tempFullPath, PATHINFO_EXTENSION) ?: 'jpg';
                    $finalFilename = 'img_' . time() . '_' . Str::random(6) . '.' . $ext;
                    $finalRelPath = "uploads/gallery/{$gallery->id}/" . $finalFilename;

                    File::move($tempFullPath, public_path($finalRelPath));

                    GalleryItem::create([
                        'album_id'   => $gallery->id,
                        'image_path' => $finalRelPath,
                        'caption'    => null,
                        'order'      => $currentMaxOrder + $idx + 1,
                    ]);

                    if (empty($gallery->cover_image)) {
                        $gallery->cover_image = $finalRelPath;
                        $gallery->save();
                    }
                }
            }
        }

        return redirect()->route('admin.gallery.index')->with('success', 'تم تحديث الألبوم وحفظ التغييرات بنجاح!');
    }

    /**
     * Set cover photo for an album
     */
    public function setCover(Request $request, GalleryAlbum $gallery)
    {
        $request->validate([
            'cover_image' => 'required|string',
        ]);

        $gallery->update([
            'cover_image' => $request->cover_image,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => 'تم تعيين وتحديث صورة الغلاف بنجاح!',
                'cover_url' => $gallery->cover_url,
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث صورة الغلاف بنجاح!');
    }

    /**
     * Delete an individual gallery photo item
     */
    public function destroyItem($id)
    {
        $item = GalleryItem::findOrFail($id);
        $albumId = $item->album_id;
        $item->delete(); // triggers booted deleting hook to clean up physical file

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'تم حذف الصورة بنجاح']);
        }

        return redirect()->back()->with('success', 'تم حذف الصورة بنجاح');
    }

    public function destroy(GalleryAlbum $gallery)
    {
        // Delete physical directory of album if exists
        $albumDir = public_path("uploads/gallery/{$gallery->id}");
        if (File::exists($albumDir)) {
            File::deleteDirectory($albumDir);
        }

        $gallery->delete();
        return redirect()->route('admin.gallery.index')->with('success', 'تم حذف الألبوم وجميع صوره بنجاح!');
    }
}

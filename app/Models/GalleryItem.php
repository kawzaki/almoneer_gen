<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsFrontendCache;

class GalleryItem extends Model
{
    use ClearsFrontendCache;

    protected $fillable = [
        'album_id',
        'image_path',
        'caption',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class, 'album_id');
    }

    public function getUrlAttribute()
    {
        if (!$this->image_path) {
            return asset('images/default-image.jpg');
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return asset($this->image_path);
    }

    protected static function booted()
    {
        static::deleting(function ($item) {
            if ($item->image_path && !str_starts_with($item->image_path, 'http://') && !str_starts_with($item->image_path, 'https://')) {
                $fullPath = public_path($item->image_path);
                if (file_exists($fullPath) && is_file($fullPath)) {
                    @unlink($fullPath);
                }
            }
        });
    }
}

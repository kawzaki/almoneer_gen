<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsFrontendCache;
use App\Traits\LogsActivity;

class GalleryAlbum extends Model
{
    use ClearsFrontendCache, LogsActivity;

    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'cover_image',
        'description',
        'event_date',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'order'      => 'integer',
        'event_date' => 'date',
    ];

    public function parent()
    {
        return $this->belongsTo(GalleryAlbum::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(GalleryAlbum::class, 'parent_id')->orderBy('order');
    }

    public function items()
    {
        return $this->hasMany(GalleryItem::class, 'album_id')->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function getCoverUrlAttribute()
    {
        if ($this->cover_image) {
            if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
                return $this->cover_image;
            }
            return asset($this->cover_image);
        }

        // Fallback to the first item image if available
        $firstItem = $this->items->first();
        if ($firstItem) {
            return $firstItem->url;
        }

        return null;
    }
}

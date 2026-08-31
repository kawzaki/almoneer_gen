<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsFrontendCache;
use App\Traits\LogsActivity;

class GalleryAlbum extends Model
{
    use ClearsFrontendCache, LogsActivity;

    protected $fillable = [
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

    public function items()
    {
        return $this->hasMany(GalleryItem::class, 'album_id')->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

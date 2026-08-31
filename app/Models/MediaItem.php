<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsFrontendCache;
use App\Traits\LogsActivity;

class MediaItem extends Model
{
    use ClearsFrontendCache, LogsActivity;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'type',
        'media_url',
        'soundcloud_url',
        'thumbnail',
        'duration',
        'description',
        'transcript',
        'pdf_file',
        'season_year',
        'is_featured',
        'is_active',
        'views_count',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
        'views_count' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAudios($query)
    {
        return $query->where('type', 'audio');
    }

    public function scopeVideos($query)
    {
        return $query->whereIn('type', ['video', 'short']);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getYoutubeIdAttribute(): ?string
    {
        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $this->media_url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}

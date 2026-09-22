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
        'audio_file',
        'thumbnail',
        'duration',
        'description',
        'tags',
        'transcript',
        'pdf_file',
        'season_year',
        'hijri_year',
        'season',
        'season_slug',
        'lecture_number',
        'is_featured',
        'is_active',
        'views_count',
    ];

    protected $casts = [
        'is_featured'     => 'boolean',
        'is_active'       => 'boolean',
        'views_count'     => 'integer',
        'lecture_number'  => 'integer',
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

    public function scopeYear($query, $year)
    {
        return $query->where('hijri_year', $year);
    }

    public function scopeSeason($query, $seasonSlug)
    {
        return $query->where(function ($q) use ($seasonSlug) {
            $q->where('season_slug', $seasonSlug)
              ->orWhere('season', $seasonSlug);
        });
    }

    public function scopeWithTag($query, $tag)
    {
        return $query->where('tags', 'like', "%{$tag}%");
    }

    public function getTagsListAttribute(): array
    {
        if (empty($this->tags)) {
            return [];
        }
        $tags = preg_split('/[,،|]+/u', $this->tags);
        return array_values(array_filter(array_map('trim', $tags)));
    }

    public function hasVideo(): bool
    {
        if (empty($this->media_url)) {
            return false;
        }
        $url = trim($this->media_url);
        return !in_array($url, ['#', 'pending', '', 'none']);
    }

    public function hasAudio(): bool
    {
        return !empty($this->soundcloud_url) || !empty($this->audio_file) || $this->type === 'audio';
    }

    public function getEffectiveAudioUrlAttribute(): ?string
    {
        if (!empty($this->soundcloud_url)) {
            return $this->soundcloud_url;
        }
        if (!empty($this->audio_file)) {
            return str_starts_with($this->audio_file, 'http') ? $this->audio_file : asset($this->audio_file);
        }
        if ($this->type === 'audio' && !empty($this->media_url)) {
            return $this->media_url;
        }
        return null;
    }

    public function getIsSoundcloudAttribute(): bool
    {
        $url = $this->effective_audio_url ?? '';
        return str_contains($url, 'soundcloud.com');
    }

    public function getSoundcloudEmbedUrlAttribute(): ?string
    {
        if (!$this->is_soundcloud) {
            return null;
        }
        $encoded = urlencode($this->effective_audio_url);
        return "https://w.soundcloud.com/player/?url={$encoded}&color=%230f4c5c&auto_play=true&hide_related=true&show_comments=false&show_user=true&show_reposts=false&show_teaser=false";
    }

    public function hasTranscript(): bool
    {
        return !empty($this->transcript) || !empty($this->pdf_file);
    }

    public function getYoutubeIdAttribute(): ?string
    {
        if (empty($this->media_url)) {
            return null;
        }
        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $this->media_url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function getDisplayThumbnailAttribute(): string
    {
        if (!empty($this->thumbnail)) {
            return asset($this->thumbnail);
        }
        if ($this->youtube_id) {
            return 'https://img.youtube.com/vi/' . $this->youtube_id . '/hqdefault.jpg';
        }
        return asset('images/video-pending-placeholder.svg');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsFrontendCache;
use App\Traits\LogsActivity;

class Poem extends Model
{
    use ClearsFrontendCache, LogsActivity;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'occasion',
        'poem_date',
        'meter',
        'image',
        'audio_url',
        'verses',
        'description',
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

    public function getCoupletsAttribute(): array
    {
        $lines = explode("\n", str_replace("\r", "", $this->verses));
        $couplets = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            if (str_contains($line, '|')) {
                $parts = explode('|', $line, 2);
                $couplets[] = [
                    'first'  => trim($parts[0]),
                    'second' => trim($parts[1]),
                ];
            } elseif (str_contains($line, '...')) {
                $parts = explode('...', $line, 2);
                $couplets[] = [
                    'first'  => trim($parts[0]),
                    'second' => trim($parts[1]),
                ];
            } else {
                $couplets[] = [
                    'first'  => $line,
                    'second' => '',
                ];
            }
        }

        return $couplets;
    }
}

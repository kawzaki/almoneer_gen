<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsFrontendCache;
use App\Traits\LogsActivity;

class Category extends Model
{
    use ClearsFrontendCache, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'module',
        'parent_id',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function mediaItems()
    {
        return $this->hasMany(MediaItem::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function poems()
    {
        return $this->hasMany(Poem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

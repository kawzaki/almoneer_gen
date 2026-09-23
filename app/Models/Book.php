<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsFrontendCache;
use App\Traits\LogsActivity;

class Book extends Model
{
    use ClearsFrontendCache, LogsActivity;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'author',
        'publisher',
        'publication_year',
        'pages_count',
        'isbn',
        'deposit_number',
        'cover_image',
        'pdf_file',
        'summary',
        'table_of_contents',
        'buy_url',
        'download_count',
        'order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured'    => 'boolean',
        'is_active'      => 'boolean',
        'download_count' => 'integer',
        'pages_count'    => 'integer',
        'order'          => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}

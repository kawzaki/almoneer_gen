<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsFrontendCache;
use App\Traits\LogsActivity;

class Article extends Model
{
    use ClearsFrontendCache, LogsActivity;

    protected $with = ['category'];

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'summary',
        'content',
        'image',
        'type',
        'tags',
        'is_featured',
        'is_active',
        'views_count',
        'published_at',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'is_active'    => 'boolean',
        'views_count'  => 'integer',
        'published_at' => 'datetime',
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

    public function getTagsListAttribute(): array
    {
        if (empty($this->tags)) {
            return [];
        }
        return array_values(array_filter(array_map('trim', preg_split('/[,،|]+/u', $this->tags))));
    }

    public function scopeWithTag($query, $tag)
    {
        return $query->where('tags', 'like', '%' . $tag . '%');
    }

    public static function typeLabels(): array
    {
        return [
            'news'      => 'خبر عام',
            'activity'  => 'نشاط وتبليغ',
            'statement' => 'بيان رسمي',
            'article'   => 'مقال فكري',
            'bio'       => 'سيرة ذاتية',
        ];
    }

    public function getTypeNameAttribute(): string
    {
        if ($this->category) {
            return $this->category->name;
        }
        return self::typeLabels()[$this->type] ?? ($this->type ?: 'خبر عام');
    }
}

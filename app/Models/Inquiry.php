<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsFrontendCache;
use App\Traits\LogsActivity;

class Inquiry extends Model
{
    use ClearsFrontendCache, LogsActivity;

    protected $fillable = [
        'category_id',
        'name',
        'email',
        'country',
        'tracking_code',
        'question',
        'answer',
        'answered_by',
        'status',
        'is_published',
        'answered_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'answered_at'  => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'answered_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('answer');
    }
}

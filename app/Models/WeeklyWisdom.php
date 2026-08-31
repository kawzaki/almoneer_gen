<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsFrontendCache;
use App\Traits\LogsActivity;

class WeeklyWisdom extends Model
{
    use ClearsFrontendCache, LogsActivity;

    protected $table = 'weekly_wisdoms';

    protected $fillable = [
        'title',
        'quote',
        'source',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

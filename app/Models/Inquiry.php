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
        'responder_title',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'answered_at'  => 'datetime',
    ];

    /**
     * Convert Eastern Arabic (Indic) numbers to standard ASCII digits
     * e.g., 'INQ-٢٠٢٦-٦J١KQ٢' => 'INQ-2026-6J1KQ2'
     */
    public static function normalizeDigits(?string $text): ?string
    {
        if ($text === null) return null;
        $eastern = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩', '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $standard = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($eastern, $standard, $text);
    }

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

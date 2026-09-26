<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsFrontendCache;
use App\Traits\LogsActivity;

class Setting extends Model
{
    use ClearsFrontendCache, LogsActivity;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    public static function get(string $key, $default = null)
    {
        $dotKey = str_replace('_', '.', $key);
        $underKey = str_replace('.', '_', $key);

        $setting = static::whereIn('key', [$key, $dotKey, $underKey])->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value, string $group = 'general', string $type = 'string')
    {
        $dotKey = str_replace('_', '.', $key);
        $underKey = str_replace('.', '_', $key);

        static::updateOrCreate(
            ['key' => $dotKey],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );

        return static::updateOrCreate(
            ['key' => $underKey],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );
    }
}

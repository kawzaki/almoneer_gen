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

    /**
     * Get setting value by key, checking exact key and variations.
     */
    public static function get(string $key, $default = null)
    {
        $keys = self::getPossibleKeys($key);
        $setting = static::whereIn('key', $keys)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value, updating all existing variations of the key to keep them in sync.
     */
    public static function set(string $key, $value, string $group = 'general', string $type = 'string')
    {
        $keys = self::getPossibleKeys($key);

        // Update all existing records matching any variation of this key
        static::whereIn('key', $keys)->update([
            'value' => $value,
            'group' => $group,
            'type'  => $type,
        ]);

        // Always ensure the exact key requested exists
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );
    }

    /**
     * Helper to get common key variations (dots, underscores, all dots, all underscores).
     */
    public static function getPossibleKeys(string $key): array
    {
        $keys = [$key];
        $keys[] = str_replace('_', '.', $key);
        $keys[] = str_replace('.', '_', $key);
        return array_values(array_unique($keys));
    }
}

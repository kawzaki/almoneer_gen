<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Sync latest hero card values
        $heroName = Setting::whereIn('key', ['site_hero_name', 'site.hero_name'])->orderBy('updated_at', 'desc')->value('value');
        if ($heroName) {
            Setting::set('site.hero_name', $heroName);
            Setting::set('site_hero_name', $heroName);
        }

        // 2. Sync all other settings so both dot and underscore keys exist
        $allSettings = Setting::all();
        foreach ($allSettings as $st) {
            $dotKey = str_replace('_', '.', $st->key);
            $underKey = str_replace('.', '_', $st->key);
            Setting::updateOrCreate(['key' => $dotKey], ['value' => $st->value, 'group' => $st->group, 'type' => $st->type]);
            Setting::updateOrCreate(['key' => $underKey], ['value' => $st->value, 'group' => $st->group, 'type' => $st->type]);
        }
    }

    public function down(): void
    {
    }
};

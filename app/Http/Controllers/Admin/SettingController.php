<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        $raw = Setting::all()->pluck('value', 'key')->toArray();
        $settings = [];
        foreach ($raw as $k => $v) {
            $settings[$k] = $v;
            $settings[str_replace('_', '.', $k)] = $v;
            $settings[str_replace('.', '_', $k)] = $v;
        }
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'site_hero_photo_file', 'remove_hero_photo']);

        // Fundamentally remove academic title from hero settings
        unset($data['site.hero_title'], $data['site_hero_title'], $data['site.hero.title']);
        Setting::whereIn('key', ['site.hero_title', 'site_hero_title', 'site.hero.title'])->delete();

        // Handle Hero Portrait Photo Upload
        if ($request->hasFile('site_hero_photo_file')) {
            $file = $request->file('site_hero_photo_file');
            $extension = strtolower($file->getClientOriginalExtension());
            $filename = 'hero_portrait_' . time() . '.' . $extension;
            $destPath = public_path('uploads/settings');
            if (!File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            
            // Delete old uploaded photo if it exists
            $oldPhoto = Setting::get('site.hero_photo');
            if ($oldPhoto && File::exists(public_path($oldPhoto))) {
                @File::delete(public_path($oldPhoto));
            }

            $newRelativePath = 'uploads/settings/' . $filename;
            Setting::set('site.hero_photo', $newRelativePath);
            Setting::set('site_hero_photo', $newRelativePath);
            Setting::set('site.hero.photo', $newRelativePath);
        } elseif ($request->input('remove_hero_photo') == '1') {
            $oldPhoto = Setting::get('site.hero_photo');
            if ($oldPhoto && File::exists(public_path($oldPhoto))) {
                @File::delete(public_path($oldPhoto));
            }
            Setting::set('site.hero_photo', null);
            Setting::set('site_hero_photo', null);
            Setting::set('site.hero.photo', null);
        }

        // Build a canonical lookup map of existing keys in database
        $existingKeys = Setting::pluck('key')->toArray();
        $normalizedMap = [];
        foreach ($existingKeys as $k) {
            $cleaned = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $k));
            $normalizedMap[$cleaned][] = $k;
        }

        foreach ($data as $key => $val) {
            $cleaned = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $key));
            if (!empty($normalizedMap[$cleaned])) {
                foreach ($normalizedMap[$cleaned] as $targetKey) {
                    Setting::updateOrCreate(['key' => $targetKey], ['value' => $val]);
                }
            } else {
                Setting::set($key, $val);
            }
        }

        // Ensure footer hadith setting is explicitly synchronized in both naming formats
        if (isset($data['site.footer_show_hadith'])) {
            Setting::set('site.footer_show_hadith', (string)$data['site.footer_show_hadith']);
            Setting::set('site_footer_show_hadith', (string)$data['site.footer_show_hadith']);
        }

        // Ensure maintenance mode setting is explicitly synchronized in both naming formats
        if (isset($data['site.maintenance_mode'])) {
            Setting::set('site.maintenance_mode', (string)$data['site.maintenance_mode']);
            Setting::set('site_maintenance_mode', (string)$data['site.maintenance_mode']);
        }

        // Flush cached settings, home payload, and views
        Cache::forget('site.settings');
        Cache::forget('site.home.payload');
        try {
            \Artisan::call('view:clear');
        } catch (\Throwable $e) {}

        return redirect()->back()->with('success', 'تم حفظ وتحديث كافة الإعدادات والصور وتفريغ كاش الموقع تلقائياً!');
    }
}

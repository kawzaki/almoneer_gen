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
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'site_hero_photo_file', 'remove_hero_photo']);

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

            Setting::set('site.hero_photo', 'uploads/settings/' . $filename);
        } elseif ($request->input('remove_hero_photo') == '1') {
            $oldPhoto = Setting::get('site.hero_photo');
            if ($oldPhoto && File::exists(public_path($oldPhoto))) {
                @File::delete(public_path($oldPhoto));
            }
            Setting::set('site.hero_photo', null);
        }

        foreach ($data as $key => $val) {
            Setting::set($key, $val);
        }

        // Flush cached settings and home payload
        Cache::forget('site.settings');
        Cache::forget('site.home.payload');

        return redirect()->back()->with('success', 'تم حفظ وتحديث كافة الإعدادات والصور وتفريغ كاش الموقع تلقائياً!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use ZipArchive;
use App\Models\Setting;

class ThemeController extends Controller
{
    public function index()
    {
        $themes = [];
        $path = public_path('site_assets');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $directories = File::directories($path);
        $currentTheme = Setting::get('site.theme', 'almoneer-emerald');

        foreach ($directories as $dir) {
            $name = basename($dir);
            $jsonPath = $dir . '/theme.json';

            $info = [
                'name'        => $name,
                'title'       => ucfirst($name),
                'path'        => $dir,
                'active'      => ($name === $currentTheme),
                'version'     => '1.0',
                'author'      => 'شبكة المنير',
                'description' => 'قالب مخصص لموقع سماحة السيد منير الخباز',
            ];

            if (File::exists($jsonPath)) {
                $params = json_decode(File::get($jsonPath), true);
                if (is_array($params)) {
                    $info = array_merge($info, $params);
                    $info['active'] = ($name === $currentTheme);
                }
            }

            $themes[] = (object) $info;
        }

        return view('admin.themes.index', compact('themes', 'currentTheme'));
    }

    public function activate($name)
    {
        Setting::set('site.theme', $name, 'theme');

        // Instantly invalidate theme and settings cache
        Cache::forget('site.theme.active');
        Cache::forget('site.settings');
        Cache::forget('site.home.payload');

        return redirect()->back()->with('success', "تم تفعيل القالب '{$name}' بنجاح وتحديث الموقع مباشرة!");
    }

    public function store(Request $request)
    {
        $request->validate([
            'theme_zip' => 'required|file|mimes:zip|max:30000',
        ]);

        $zip = new ZipArchive();
        $file = $request->file('theme_zip');

        if ($zip->open($file->path()) === true) {
            $themesPath = public_path('site_assets');
            $zip->extractTo($themesPath);
            $zip->close();

            Cache::forget('site.theme.active');

            return redirect()->back()->with('success', 'تم رفع حزمة الثيم وفك ضغطها بنجاح!');
        }

        return redirect()->back()->with('error', 'تعذر قراءة ملف الـ ZIP المضغوط.');
    }

    public function destroy($name)
    {
        $currentTheme = Setting::get('site.theme', 'almoneer-emerald');
        if ($name === $currentTheme) {
            return redirect()->back()->with('error', 'لا يمكن حذف القالب النشط حالياً.');
        }

        $path = public_path("site_assets/{$name}");
        if (File::exists($path)) {
            File::deleteDirectory($path);
            return redirect()->back()->with('success', 'تم حذف القالب بنجاح.');
        }

        return redirect()->back()->with('error', 'القالب غير موجود.');
    }
}

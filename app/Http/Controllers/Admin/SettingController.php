<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token']);

        foreach ($data as $key => $val) {
            Setting::set($key, $val);
        }

        // Flush cached settings and home payload
        Cache::forget('site.settings');
        Cache::forget('site.home.payload');

        return redirect()->back()->with('success', 'تم حفظ وتحديث كافة الإعدادات وتفريغ كاش الموقع تلقائياً!');
    }
}

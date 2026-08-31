<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    protected string $horizontalFile;
    protected string $verticalFile;

    public function __construct()
    {
        $this->horizontalFile = storage_path('app/menus/menu.htm');
        $this->verticalFile   = storage_path('app/menus/menuv.htm');
    }

    public function index()
    {
        $horizontalMenu = File::exists($this->horizontalFile) ? File::get($this->horizontalFile) : '';
        $verticalMenu   = File::exists($this->verticalFile) ? File::get($this->verticalFile) : '';

        if (trim($horizontalMenu) === '') {
            $horizontalMenu = '<ul id="ittsc-menu" class="sortable-list"><li><a href="/">الرئيسية</a></li></ul>';
        }
        if (trim($verticalMenu) === '') {
            $verticalMenu = '<ul id="ittsc-menu2" class="sortable-list"><li><a href="/">الرئيسية</a></li></ul>';
        }

        return view('admin.menus.index', compact('horizontalMenu', 'verticalMenu'));
    }

    public function update(Request $request, $id)
    {
        $content = $request->input('smenu');
        $file = ($id === 'vertical') ? $this->verticalFile : $this->horizontalFile;

        File::put($file, $content);

        // Instantly invalidate menu cache
        Cache::forget('site.menus.horizontal');
        Cache::forget('site.menus.vertical');
        Cache::forget('site.home.payload');

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ وتحديث القائمة وتفريغ الكاش بنجاح'
        ]);
    }

    public function getList(Request $request)
    {
        $type = $request->input('type'); // article, media, book, poem, gallery
        $options = [];

        if ($type === 'article') {
            $items = DB::table('categories')->where('module', 'article')->get(['id', 'name as title', 'slug']);
            foreach ($items as $item) {
                $options[] = ['URL' => '/news?category=' . $item->slug, 'TITLE' => 'تصنيف: ' . $item->title];
            }
        } elseif ($type === 'media') {
            $items = DB::table('categories')->where('module', 'media')->get(['id', 'name as title', 'slug']);
            foreach ($items as $item) {
                $options[] = ['URL' => '/audios?category=' . $item->slug, 'TITLE' => 'ميديا: ' . $item->title];
            }
        } elseif ($type === 'book') {
            $options[] = ['URL' => '/books', 'TITLE' => 'مكتبة الكتب والمؤلفات'];
        } elseif ($type === 'poem') {
            $options[] = ['URL' => '/poems', 'TITLE' => 'ديوان الشعر'];
        } elseif ($type === 'gallery') {
            $options[] = ['URL' => '/gallery', 'TITLE' => 'ألبوم الصور'];
        } elseif ($type === 'inquiries') {
            $options[] = ['URL' => '/inquiries', 'TITLE' => 'استفسارات وفتاوى'];
        }

        return response()->json(['options' => $options]);
    }
}

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
    protected string $footer1File;
    protected string $footer2File;

    public function __construct()
    {
        $this->horizontalFile = storage_path('app/menus/menu.htm');
        $this->verticalFile   = storage_path('app/menus/menuv.htm');
        $this->footer1File    = storage_path('app/menus/menu_f1.htm');
        $this->footer2File    = storage_path('app/menus/menu_f2.htm');
    }

    public function index()
    {
        $horizontalMenu = File::exists($this->horizontalFile) ? File::get($this->horizontalFile) : '';
        $verticalMenu   = File::exists($this->verticalFile) ? File::get($this->verticalFile) : '';
        $footerMenu1    = File::exists($this->footer1File) ? File::get($this->footer1File) : '';
        $footerMenu2    = File::exists($this->footer2File) ? File::get($this->footer2File) : '';

        if (trim($horizontalMenu) === '') {
            $horizontalMenu = '<ul id="ittsc-menu" class="sortable-list"><li><a href="/">الرئيسية</a></li></ul>';
        }
        if (trim($verticalMenu) === '') {
            $verticalMenu = '<ul id="ittsc-menu2" class="sortable-list"><li><a href="/">الرئيسية</a></li></ul>';
        }
        if (trim($footerMenu1) === '') {
            $footerMenu1 = '<ul id="ittsc-menu-f1" class="sortable-list">
                <li><a href="/">الصفحة الرئيسية</a></li>
                <li><a href="/bio">نبذة عن حياته الشريفة</a></li>
                <li><a href="/lectures">أرشيف المحاضرات والمواسم</a></li>
                <li><a href="/poems">ديوان الشعر والقصائد</a></li>
                <li><a href="/books">المؤلفات والكتب الإلكترونية</a></li>
            </ul>';
        }
        if (trim($footerMenu2) === '') {
            $footerMenu2 = '<ul id="ittsc-menu-f2" class="sortable-list">
                <li><a href="/inquiries">إرسال استفسار أو مسألة</a></li>
                <li><a href="/inquiries/track">متابعة حالة استفسار سابق</a></li>
                <li><a href="/gallery">ألبوم الصور والمناسبات</a></li>
                <li><a href="/social">المركز الإعلامي وشبكات التواصل</a></li>
                <li><a href="/contact">عناوين المكاتب والاتصال</a></li>
                <li><a href="https://almoneer-droos.onrender.com">بوابة الدروس الحوزوية ↗</a></li>
            </ul>';
        }

        return view('admin.menus.index', compact('horizontalMenu', 'verticalMenu', 'footerMenu1', 'footerMenu2'));
    }

    public function update(Request $request, $id)
    {
        $content = $request->input('smenu');

        switch ($id) {
            case 'vertical':
                $file = $this->verticalFile;
                $cacheKey = 'site.menus.vertical';
                break;
            case 'footer1':
                $file = $this->footer1File;
                $cacheKey = 'site.menus.footer1';
                break;
            case 'footer2':
                $file = $this->footer2File;
                $cacheKey = 'site.menus.footer2';
                break;
            default:
                $file = $this->horizontalFile;
                $cacheKey = 'site.menus.horizontal';
                break;
        }

        File::ensureDirectoryExists(dirname($file));
        File::put($file, $content);

        // Instantly invalidate menu caches
        Cache::forget($cacheKey);
        Cache::forget('site.menus.horizontal');
        Cache::forget('site.menus.vertical');
        Cache::forget('site.menus.footer1');
        Cache::forget('site.menus.footer2');
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

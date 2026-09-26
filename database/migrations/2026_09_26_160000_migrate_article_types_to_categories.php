<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Category;
use App\Models\Article;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $typesData = [
            [
                'name'      => 'خبر عام',
                'slug'      => 'news',
                'module'    => 'article',
                'order'     => 1,
                'is_active' => true,
            ],
            [
                'name'      => 'نشاط / جولة تبليغية',
                'slug'      => 'activity',
                'module'    => 'article',
                'order'     => 2,
                'is_active' => true,
            ],
            [
                'name'      => 'بيان رسمي',
                'slug'      => 'statement',
                'module'    => 'article',
                'order'     => 3,
                'is_active' => true,
            ],
            [
                'name'      => 'مقال فكري',
                'slug'      => 'article',
                'module'    => 'article',
                'order'     => 4,
                'is_active' => true,
            ],
            [
                'name'      => 'سيرة ذاتية',
                'slug'      => 'bio',
                'module'    => 'article',
                'order'     => 5,
                'is_active' => true,
            ],
        ];

        $categoryMap = [];

        foreach ($typesData as $item) {
            $cat = Category::where('module', 'article')
                ->where(function ($query) use ($item) {
                    $query->where('slug', $item['slug'])
                          ->orWhere('name', $item['name']);
                })->first();

            if (!$cat) {
                $cat = Category::create($item);
            }

            $categoryMap[$item['slug']] = $cat->id;
        }

        // Migrate existing articles where category is null or matches the type mapping
        foreach ($categoryMap as $type => $catId) {
            Article::where('type', $type)
                ->whereNull('category_id')
                ->update(['category_id' => $catId]);
        }

        // For existing activity articles that might have been in old generic category (like 'أخبار ونشاطات')
        if (isset($categoryMap['activity'])) {
            Article::where('type', 'activity')
                ->whereHas('category', function ($q) {
                    $q->where('slug', 'news-activities');
                })
                ->update(['category_id' => $categoryMap['activity']]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe down: do not delete user records
    }
};

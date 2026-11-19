<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class QuotesSeeder extends Seeder
{
    /**
     * Seed quotes imported from the legacy website.
     */
    public function run(): void
    {
        $path = storage_path('quotes_dump.json');
        if (!File::exists($path)) {
            return;
        }

        $quotes = json_decode(File::get($path), true);
        if (!$quotes) {
            return;
        }

        DB::transaction(function () use ($quotes) {
            DB::table('weekly_wisdoms')->truncate();

            $now = now();
            $batch = [];
            foreach ($quotes as $q) {
                $batch[] = [
                    'id'         => (int)$q['old_id'],
                    'title'      => 'حِكَم وأقوال',
                    'quote'      => $q['quote'],
                    'source'     => $q['author'] ?: null,
                    'image'      => null,
                    'is_active'  => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (count($batch) >= 500) {
                    DB::table('weekly_wisdoms')->insert($batch);
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                DB::table('weekly_wisdoms')->insert($batch);
            }
        });
    }
}

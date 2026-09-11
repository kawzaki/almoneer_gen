<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media_items', function (Blueprint $table) {
            $table->string('hijri_year', 10)->nullable()->index()->after('season_year');
            $table->string('season', 50)->nullable()->index()->after('hijri_year');
            $table->string('season_slug', 50)->nullable()->index()->after('season');
            $table->integer('lecture_number')->nullable()->after('season_slug');
        });
    }

    public function down(): void
    {
        Schema::table('media_items', function (Blueprint $table) {
            $table->dropColumn(['hijri_year', 'season', 'season_slug', 'lecture_number']);
        });
    }
};

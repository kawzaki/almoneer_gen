<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_wisdoms', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // e.g. من حكم أمير المؤمنين (ع)
            $table->text('quote');
            $table->string('source')->nullable(); // e.g. الإمام علي (ع) / نهج البلاغة
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_wisdoms');
    }
};

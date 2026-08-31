<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author')->default('سماحة العلامة السيد منير الخباز');
            $table->string('publisher')->nullable();
            $table->string('publication_year')->nullable();
            $table->integer('pages_count')->nullable();
            $table->string('isbn')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('pdf_file')->nullable();
            $table->text('summary')->nullable();
            $table->longText('table_of_contents')->nullable();
            $table->string('buy_url')->nullable();
            $table->unsignedBigInteger('download_count')->default(0);
            $table->integer('order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};

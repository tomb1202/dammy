<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->unique()->comment('Category name');
            $table->string('slug')->nullable()->comment('URL slug');
            $table->string('meta_title')->nullable()->comment('Meta SEO title');
            $table->text('meta_description')->nullable()->comment('Meta SEO description');
            $table->string('meta_keywords')->nullable()->comment('Meta SEO keywords');
            $table->integer('sort')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->integer('comic_id')->nullable();
            $table->string('title')->nullable()->comment('Chapter title');
            $table->integer('number')->nullable()->comment('Chapter number');
            $table->string('slug')->nullable()->comment('Chapter URL slug');
            $table->integer('views')->default(0)->comment('Total views');
            $table->string('meta_title')->nullable()->comment('Meta SEO title');
            $table->text('meta_description')->nullable()->comment('Meta SEO description');
            $table->string('meta_keywords')->nullable()->comment('Meta SEO keywords');
            $table->string('url')->nullable();
            $table->tinyInteger('crawl')->default(0);
            $table->timestamp('crawl_created_at')->nullable();
            $table->timestamp('crawl_updated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};

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
        Schema::create('comics', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->comment('Comic title');
            $table->string('slug')->nullable()->comment('URL slug');
            $table->text('description')->nullable()->comment('Comic description');
            $table->string('author')->nullable()->comment('Author name');
            $table->string('artist')->nullable()->comment('Artist name');
            $table->string('status')->default('ongoing')->comment('Status: ongoing, completed...');
            $table->string('image')->nullable()->comment('Cover image URL');
            $table->integer('views')->default(0)->comment('Total views');
            $table->integer('votes')->default(0)->comment('Total votes');
            $table->decimal('ratings', 3, 2)->default(0)->comment('Average rating');
            $table->string('meta_title')->nullable()->comment('Meta SEO title');
            $table->text('meta_description')->nullable()->comment('Meta SEO description');
            $table->string('meta_keywords')->nullable()->comment('Meta SEO keywords');
            $table->string('url')->nullable();
            $table->string('url_image')->nullable();
            $table->tinyInteger('crawl')->default(0);
            $table->tinyInteger('is_hot')->default(0);
            $table->tinyInteger('hidden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comics');
    }
};

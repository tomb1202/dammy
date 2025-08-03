<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comics', function (Blueprint $table) {
            $table->index('hidden');
            $table->index('crawl');
            $table->index('status');
            $table->index('updated_at');
            $table->index('created_at');
            $table->index('views');
            $table->index('ratings');
            $table->index('is_hot');
            $table->unique('slug'); 
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->index('comic_id');
            $table->index('slug');
            $table->index('number');
        });

        Schema::table('chapter_pages', function (Blueprint $table) {
            $table->index('chapter_id');
            $table->index('page_number');
            $table->unique(['chapter_id', 'page_number']);
        });
    }

    public function down(): void
    {
        Schema::table('comics', function (Blueprint $table) {
            $table->dropIndex(['hidden']);
            $table->dropIndex(['crawl']);
            $table->dropIndex(['status']);
            $table->dropIndex(['updated_at']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['views']);
            $table->dropIndex(['ratings']);
            $table->dropIndex(['is_hot']);
            $table->dropUnique(['slug']);
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->dropIndex(['comic_id']);
            $table->dropIndex(['slug']);
            $table->dropIndex(['number']);
        });

        Schema::table('chapter_pages', function (Blueprint $table) {
            $table->dropIndex(['chapter_id']);
            $table->dropIndex(['page_number']);
            $table->dropUnique(['chapter_id', 'page_number']);
        });
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'comic_id',           // ID of the related comic
        'title',              // Chapter title
        'number',             // Chapter number
        'slug',               // Chapter URL slug
        'views',              // Total views
        'meta_title',         // Meta SEO title
        'meta_description',   // Meta SEO description
        'meta_keywords',      // Meta SEO keywords
        'url',
        'crawl_created_at',
        'crawl_updated_at',
    ];

    /**
     * Quan hệ đến truyện (Comic)
     */
    public function comic()
    {
        return $this->belongsTo(Comic::class);
    }

    /**
     * Quan hệ đến các bình luận
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Quan hệ đến các trang (pages) của chương
     */
    public function pages()
    {
        return $this->hasMany(ChapterPage::class);
    }

    protected static function booted()
    {
        static::deleting(function ($chapter) {
            $chapter->pages()->delete();
            $chapter->comments()->delete();
        });
    }
}

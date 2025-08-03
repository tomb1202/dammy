<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',              // Comic title
        'slug',               // URL slug
        'description',        // Comic description
        'author',             // Author name
        'artist',             // Artist name
        'status',             // Status: ongoing, completed...
        'image',              // Cover image URL
        'views',              // Total views
        'votes',              // Total votes
        'ratings',            // Average rating
        'meta_title',         // Meta SEO title
        'meta_description',   // Meta SEO description
        'meta_keywords',      // Meta SEO keywords
        'url',
        'url_image',
        'crawl',
        'is_hot',
        'hidden'
    ];

    /**
     * Danh sách chapter của truyện
     */
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * Danh sách thể loại (many to many)
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'comic_categories', 'comic_id', 'category_id');
    }

    /**
     * Tổng số comment của tất cả chapter trong truyện
     */
    public function comments()
    {
        return $this->hasManyThrough(Comment::class, Chapter::class);
    }

    protected static function booted()
    {
        static::deleting(function ($comic) {
            foreach ($comic->chapters as $chapter) {
                $chapter->delete();
            }

            // Nếu có quan hệ trung gian với categories
            $comic->categories()->detach();
        });
    }

    public function latestChapter()
    {
        return $this->hasOne(Chapter::class)->orderByDesc('number');
    }

    public function follows()
    {
        return $this->hasMany(\App\Models\Follow::class);
    }
}

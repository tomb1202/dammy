<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',               // Category name
        'slug',               // URL slug
        'meta_title',         // Meta SEO title
        'meta_description',   // Meta SEO description
        'meta_keywords',      // Meta SEO keywords
        'sort',               // Sort order
        'url'
    ];

    public function comics()
    {
        return $this->belongsToMany(Comic::class, 'comic_categories', 'category_id', 'comic_id');
    }
}

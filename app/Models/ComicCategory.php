<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComicCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'comic_id',     // ID of the related comic
        'category_id',  // ID of the related category
    ];

    public function comic() {
        return $this->belongsTo(Comic::class);
    }
    
    public function category() {
        return $this->belongsTo(Category::class);
    }
}

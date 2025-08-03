<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChapterPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',     // ID of the related chapter
        'page_number',    // Page order in chapter
        'image',          // Image URL of the page
        'sort',           // Optional sort order
        'url_image'
    ];
}

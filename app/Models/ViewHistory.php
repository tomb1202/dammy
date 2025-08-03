<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'comic_id',     // ID of the viewed comic
        'chapter_id',   // ID of the viewed chapter
        'ip_address',   // IP address of the viewer
        'user_id',      // ID of the user (nullable if guest)
        'user_agent',   // Browser/device info
    ];

    public function comic()
    {
        return $this->belongsTo(Comic::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

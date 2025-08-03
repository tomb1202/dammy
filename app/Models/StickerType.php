<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StickerType extends Model
{
    use HasFactory;

    public function stickers()
    {
        return $this->hasMany(Sticker::class, 'type_id', 'id');
    }
}

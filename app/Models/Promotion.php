<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = ['room_type_id', 'title', 'tag', 'description', 'image_path', 'original_price', 'discounted_price', 'expiry_date', 'status'];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}

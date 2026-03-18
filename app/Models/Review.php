<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'room_type_id',
        'name',
        'rating',
        'comment'
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}

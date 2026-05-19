<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = ['room_type_id', 'title', 'tag', 'description', 'image_path', 'original_price', 'discounted_price', 'expiry_date', 'status'];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class, 'room_type_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'room_type_facility');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['room_type_id', 'user_id', 'parent_id', 'name', 'rating', 'comment', 'status'];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Review::class, 'parent_id')->where('status', 1)->oldest();
    }
}

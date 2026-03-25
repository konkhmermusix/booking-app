<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon', 'type', 'is_active'];

    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'room_type_facility');
    }
}

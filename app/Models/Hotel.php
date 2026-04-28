<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{

    protected $fillable = ['name', 'description', 'address', 'phone', 'email', 'logo', 'latitude', 'longitude', 'status'];

    protected $casts = [
        'status' => 'integer', // ឬប្រើ Boolean បើអ្នកចង់
    ];

    // សណ្ឋាគារមួយ មានបន្ទប់ច្រើនប្រភេទ
    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomType::class);
    }

    // សណ្ឋាគារមួយ មានបន្ទប់ច្រើន
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }
}

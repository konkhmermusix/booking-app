<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Facility;

class RoomType extends Model
{
    protected $with = ['facilities'];

    protected $fillable = ['hotel_id', 'name', 'category', 'description', 'max_guests', 'base_price'];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
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

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($roomType) {
            foreach ($roomType->images as $image) {
                $image->delete();
            }
        });
    }
}

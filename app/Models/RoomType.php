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

    public function getBedsAttribute()
    {
        if (isset($this->attributes['beds']) && $this->attributes['beds'] !== null) {
            return (int) $this->attributes['beds'];
        }

        $name = $this->name ?? '';
        if (mb_strpos($name, 'គ្រែពីរ') !== false || mb_stripos($name, 'double') !== false || mb_stripos($name, 'twin') !== false) {
            return 2;
        }
        if (mb_strpos($name, 'គ្រែបី') !== false || mb_stripos($name, 'triple') !== false) {
            return 3;
        }
        if (mb_strpos($name, 'គ្រែបួន') !== false || mb_stripos($name, 'quad') !== false) {
            return 4;
        }
        if (mb_strpos($name, 'គ្រែមួយ') !== false || mb_stripos($name, 'single') !== false) {
            return 1;
        }

        return ($this->max_guests && $this->max_guests >= 4) ? 2 : 1;
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

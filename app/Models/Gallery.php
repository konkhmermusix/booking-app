<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['hotel_id', 'image', 'is_active', 'sort_order'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}

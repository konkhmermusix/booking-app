<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelHistory extends Model
{
    use HasFactory;

    protected $table = 'hotel_histories';

    protected $fillable = [
        'year',
        'title_kh',
        'description_kh',
        'order_priority',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}

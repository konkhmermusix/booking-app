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

    /**
     * Scope សម្រាប់ទាញយកតែទិន្នន័យដែល Active
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}

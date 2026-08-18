<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slideshow extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'order_column',
        'is_active',
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slideshow extends Model
{
    // បន្ថែមបន្ទាត់ខាងក្រោមនេះ
    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'order_column',
        'is_active',
    ];

    // protected $guarded = []; // បើកចំហរគ្រប់ Field ទាំងអស់
}

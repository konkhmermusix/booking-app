<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutHistory extends Model
{

    protected $fillable = [
        'id',         
        'year',
        'title_kh',
        'description_kh',
        'status',
        'sort_order'
    ];
}

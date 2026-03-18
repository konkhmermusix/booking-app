<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $fillable = [
        'name',
        'image',
        'distance',
        'google_map_link',
        'description',
        'status'
    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutContent extends Model
{
    use HasFactory;

    protected $table = 'about_contents';

    protected $fillable = [
        'key',
        'title_kh',
        'content_kh',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}

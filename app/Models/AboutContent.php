<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutContent extends Model
{
    use HasFactory;

    // កំណត់ឈ្មោះ Table (ប្រសិនបើខុសពីការរំពឹងទុករបស់ Laravel)
    protected $table = 'about_contents';

    // អនុញ្ញាតឱ្យបញ្ចូលទិន្នន័យក្នុង Column ទាំងនេះ
    protected $fillable = [
        'key',
        'title_kh',
        'content_kh',
        'image',
        'status',
    ];

    // កំណត់ប្រភេទជួរឈរ (Casting)
    protected $casts = [
        'status' => 'boolean',
    ];
}

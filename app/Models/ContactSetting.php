<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    use HasFactory;

    // កំណត់ឈ្មោះ Table (ប្រសិនបើឈ្មោះ Model មិនទាន់ត្រូវតាមស្តង់ដារ)
    protected $table = 'contact_settings';

    /**
     * fillable: អនុញ្ញាតឱ្យបញ្ចូលទិន្នន័យតាមរយៈ Array
     * នេះការពារកុំឱ្យ User បញ្ចូលទិន្នន័យទៅ Column ដែលយើងមិនចង់ (ឧទាហរណ៍: id)
     */
    protected $fillable = [
        'key',
        'label',
        'value',
        'icon',
        'color',
        'status',
    ];

    /**
     * casts: បំប្លែងប្រភេទទិន្នន័យឱ្យបានត្រឹមត្រូវរាល់ពេលទាញយក
     * ជួយឱ្យ 'status' ក្លាយជា boolean (true/false) ស្វ័យប្រវត្តិ
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    public function setKeyAttribute($value)
    {
        // បំប្លែងទៅជាអក្សរតូច និងដូរដកឃ្លាទៅជា underscore
        $this->attributes['key'] = strtolower(str_replace(' ', '_', $value));
    }
}

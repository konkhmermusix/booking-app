<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    use HasFactory;

    protected $table = 'contact_settings';

 
    protected $fillable = [
        'key',
        'label',
        'value',
        'icon',
        'color',
        'status',
    ];

 
    protected $casts = [
        'status' => 'boolean',
    ];

    public function setKeyAttribute($value)
    {
        $this->attributes['key'] = strtolower(str_replace(' ', '_', $value));
    }

    public static function getExchangeRate($default = 4050): float
    {
        try {
            $setting = self::whereIn('key', ['khr_rate', 'exchange_rate', 'usd_khr_rate', 'riel_rate'])
                ->where('status', 1)
                ->first();
            if ($setting && is_numeric($setting->value) && (float)$setting->value > 0) {
                return (float) $setting->value;
            }
        } catch (\Exception $e) {}
        return (float) $default;
    }
}

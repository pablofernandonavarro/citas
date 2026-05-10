<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'business_name',
        'specialty',
        'tagline',
        'logo_path',
        'hero_image_path',
        'phone',
        'address',
        'instagram',
        'whatsapp',
        'evolution_connected',
    ];

    protected function casts(): array
    {
        return [
            'evolution_connected' => 'boolean',
        ];
    }

    public static function get(): self
    {
        return static::firstOrCreate([], [
            'business_name' => tenant('business_name') ?? config('app.name'),
        ]);
    }
}

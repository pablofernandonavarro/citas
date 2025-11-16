<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabinet extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relación muchos a muchos con doctores
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_cabinet')
            ->withTimestamps();
    }

    // Relación con citas
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

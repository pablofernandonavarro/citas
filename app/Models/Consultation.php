<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'appointment_id',
        'diagnosis',
        'treatment',
        'notes',
        'prescriptions',
    ];

    protected $casts = [
        'prescriptions' => 'json',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }
}

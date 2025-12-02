<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\AppointmentEnum;
use App\Models\Scopes\VerifyRole;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;

#[ScopedBy([VerifyRole::class])]
class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'cabinet_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'reason',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'status' => AppointmentEnum::class,
    ];

    // Scopes




    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Speciality;


class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'speciality_id',
        'medical_license_number',
        'biography',
        'active',
        'appointment_duration',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function speciality()
    {
        return $this->belongsTo(Speciality::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function unavailabilities()
    {
        return $this->hasMany(DoctorUnavailability::class);
    }

    // Relación muchos a muchos con gabinetes
    public function cabinets()
    {
        return $this->belongsToMany(Cabinet::class, 'doctor_cabinet')
            ->withTimestamps();
    }

    /**
     * Obtener la duración de citas del doctor
     * Si no tiene una personalizada, usa el valor por defecto del config
     */
    public function getAppointmentDuration()
    {
        return $this->appointment_duration ?? config('schedule.appointments_duration');
    }

}

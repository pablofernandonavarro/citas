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

}

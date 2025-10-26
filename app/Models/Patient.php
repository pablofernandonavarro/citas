<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\SocialWork;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'social_work_id',
        'affiliate_number',
        'allergies',
        'medical_record_number',
        'chronic_conditions',
        'surgeries_history',
        'family_history',
        'genetic_conditions',
        'other_conditions',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'date_of_birth',
    ];

    // relacion con el modelo User INVERSA
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // relacion con el modelo SocialWork INVERSA
    public function socialWork()
    {
        return $this->belongsTo(SocialWork::class);
    }
}

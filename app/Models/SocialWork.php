<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialWork extends Model
{
    protected $fillable = [
        'name',
    ];

    // Relación con pacientes
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}

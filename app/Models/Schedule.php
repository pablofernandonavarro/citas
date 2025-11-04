<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        // 'end_time',
    ];
   protected $casts = [
         'day_of_week' => 'integer',
         // start_time se mantiene como string para evitar conversión de timezone
   ];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}

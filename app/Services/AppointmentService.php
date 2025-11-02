<?php
namespace App\Services;
use Carbon\Carbon;
use App\Models\Doctor;
class AppointmentService
{
    public function searchAvailability($date, $hour, $speciality_id)
    {
     $date = Carbon::parse($date);
     $hourStart = Carbon::parse($hour)->format('H:i:s');
     $hourEnd = Carbon::parse($hour)->addHour()->format('H:i:s');
     $speciality_id = (int) $speciality_id;

     $doctors = Doctor::whereHas('schedules', function($query) use ($date, $hourStart, $hourEnd, $speciality_id) {
        $query->where('day_of_week', $date->dayOfWeek)
              ->where('start_time', '>=', $hourStart)
              ->where('start_time', '<', $hourEnd);
              
    })->when($speciality_id, function($query,$speciality_id) {
        return $query->where('speciality_id', $speciality_id);
       })
        ->with([
        'user',
        'speciality',
        'schedules' => function($query) use ($date, $hourStart, $hourEnd) {
            $query->where('day_of_week', $date->dayOfWeek)
                  ->where('start_time', '>=', $hourStart)
                  ->where('start_time', '<', $hourEnd);
        }
       ])
   ->get();

     dd($doctors->toArray());

}
}
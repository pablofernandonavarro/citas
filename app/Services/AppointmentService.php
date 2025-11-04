<?php

namespace App\Services;

use App\Models\Doctor;
use Carbon\Carbon;

class AppointmentService
{
    public function searchAvailability($date, $hour, $speciality_id)
    {

        // dd('searchAvailability', $date, $hour, $speciality_id);
        $date = Carbon::parse($date);
        $hourStart = Carbon::parse($hour)->format('H:i:s');
        $hourEnd = Carbon::parse($hour)->addHour()->format('H:i:s');
        $speciality_id = (int) $speciality_id;

        $doctors = Doctor::whereHas('schedules', function ($query) use ($date, $hourStart, $hourEnd) {
            $query->where('day_of_week', $date->dayOfWeek)
                ->where('start_time', '>=', $hourStart)
                ->where('start_time', '<', $hourEnd);

        })->when($speciality_id, function ($query, $speciality_id) {
            return $query->where('speciality_id', $speciality_id);
        })
            ->with([
                'user',
                'speciality',
                'schedules' => function ($query) use ($date, $hourStart, $hourEnd) {
                    $query->where('day_of_week', $date->dayOfWeek);
                    $query->where('start_time', '>=', $hourStart);
                    $query->where('start_time', '<', $hourEnd);
                },
                'appointments' => function ($query) use ($date, $hourStart, $hourEnd) {
                    $query->whereDate('date', $date->format('Y-m-d'));
                    $query->where('start_time', '>=', $hourStart);
                    $query->where('start_time', '<', $hourEnd);
                },
            ])
            ->get();

        // dd($doctors->toArray());
        $result = $this->processResults($doctors);
        return $result;

    }

    public function processResults($doctors)
    {
        return $doctors->mapWithKeys(function ($doctor) {
            return [
                $doctor->id => [
                    'doctor' => $doctor,
                    'schedules' => $doctor->schedules->map(function ($schedule) {
                        return [
                            'start_time' => $schedule->start_time, // Ya es string H:i:s
                        ];
                    })->toArray(),
                ],
            ];
        });
    }
}

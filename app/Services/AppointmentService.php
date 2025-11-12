<?php

namespace App\Services;

use App\Models\Doctor;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

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

        })
            ->whereHas('user', function ($querry) {
                $querry->whereHas('roles', function($q) {
                    $q->where('name', 'doctor');
                });
            })
            ->whereDoesntHave('unavailabilities', function ($query) use ($date) {
                $query->where('start_date', '<=', $date->format('Y-m-d'))
                    ->where('end_date', '>=', $date->format('Y-m-d'));
            })



            ->when($speciality_id, function ($query, $speciality_id) {
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
            $schedules = $this->getAvailableSchedules(
                $doctor->schedules,
                $doctor->appointments
            );
            // return [
            //     $doctor->id => [
            //         'doctor' => $doctor,
            //         'schedules' => $schedules,
            //     ],
            // ];
            return $schedules->contains('disabled', false) ?
                [
                    $doctor->id => [
                        'doctor' => $doctor,
                        'schedules' => $schedules,
                    ]
                ] : [];

        });
    }
    public function getAvailableSchedules($schedules, $appointments)
    {
        return $schedules->map(function ($schedule) use ($appointments) {
            // Verificar si este horario está ocupado por alguna cita
            $isBooked = $appointments->contains(function ($appointment) use ($schedule) {
                // Parsear solo los tiempos (sin fechas)
                $scheduleTime = $schedule->start_time; // Ya es string 'HH:MM:SS'
                $appointmentStart = $appointment->start_time; // Ya es string 'HH:MM:SS'
                $appointmentEnd = $appointment->end_time; // Ya es string 'HH:MM:SS'

                // Un horario está ocupado si el schedule.start_time está entre
                // appointment.start_time y appointment.end_time (excluyendo el fin)
                return $scheduleTime >= $appointmentStart && $scheduleTime < $appointmentEnd;
            });

            return [
                'start_time' => $schedule->start_time,
                'disabled' => $isBooked,
            ];
        });
    }
}

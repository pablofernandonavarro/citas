<?php

use App\Models\Appointment;
use App\Enums\AppointmentEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

Route::get('/patient', function (Request $request) {
    return User::query()
        ->select('id', 'name', 'email')
        ->when(
            $request->search,
            fn(Builder $query) => $query
                ->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")
        )
        ->when(
            $request->exists('selected'),
            // fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
            fn(Builder $query) => $query->whereHas('patient', fn(Builder $query) => $query->whereIn('id', $request->input('selected', []))),
            fn(Builder $query) => $query->limit(10)
        )
        ->whereHas('patient')
        ->with('patient')
        ->orderBy('name')
        ->get()
        ->map(function (User $user) {
            return [
                'id' => $user->patient->id,
                'name' => $user->name,
                'email' => $user->email,
                'patient' => $user->patient,
            ];
        });
})->name('api.patient');

Route::middleware(['web', 'auth'])->get('/appointments', function (Request $request) {
    $query = Appointment::withoutGlobalScope(\App\Models\Scopes\VerifyRole::class)
        ->with(['patient.user', 'doctor.user'])
        ->whereBetween('date', [$request->start, $request->end])
        ->whereNotNull('patient_id')
        ->whereNotNull('doctor_id')
        ->whereHas('patient.user')
        ->whereHas('doctor.user')
        ->where('status', '!=', AppointmentEnum::AVAILABLE->value);
    
    // Filtrar según el rol del usuario autenticado
    if (auth()->check()) {
        if (auth()->user()->hasRole('Paciente')) {
            // El paciente solo ve sus propias citas
            $query->whereHas('patient', function ($q) {
                $q->where('user_id', auth()->id());
            });
        } elseif (auth()->user()->hasRole('Doctor')) {
            // El doctor solo ve sus propias citas
            $query->whereHas('doctor', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }
        // Admin y Recepcionista ven todas las citas (no se aplica filtro)
    }
    
    $appointments = $query->get()
        ->map(function (Appointment $appointment) {
            return [
                'id' => $appointment->id,
                'title' => $appointment->patient->user->name . ' - ' . $appointment->doctor->user->name,
                'start' => $appointment->date->format('Y-m-d') . ' ' . $appointment->start_time,
                'end' => $appointment->date->format('Y-m-d') . ' ' . $appointment->end_time,
                'backgroundColor' => $appointment->status->color(),
                'borderColor' => $appointment->status->color(),
                'extendedProps' => [
                    'datetime' => $appointment->date->format('d/m/Y') . ' ' . substr($appointment->start_time, 0, 5) . ' - ' . substr($appointment->end_time, 0, 5),
                    'patient' => $appointment->patient->user->name,
                    'doctor' => $appointment->doctor->user->name,
                    'status' => $appointment->status->label(),
                    'url' => route('admin.appointments.consultation', $appointment->id)
                ]
            ];
        });
    
    return $appointments;
})->name('api.appointments.index');



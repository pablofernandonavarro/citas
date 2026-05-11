<?php

use App\Enums\AppointmentEnum;
use App\Http\Controllers\Api\TenantProvisioningController;
use App\Models\Appointment;
use App\Models\DoctorUnavailability;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Provisioning — secured by shared secret token
Route::middleware('provision.secret')->group(function () {
    Route::post('/tenants', [TenantProvisioningController::class, 'provision']);
    Route::delete('/tenants/{subdomain}', [TenantProvisioningController::class, 'deprovision']);
});

Route::get('/patient', function (Request $request) {
    return User::query()
        ->select('id', 'name', 'email')
        ->when(
            $request->search,
            fn (Builder $query) => $query
                ->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")
        )
        ->when(
            $request->exists('selected'),
            // fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
            fn (Builder $query) => $query->whereHas('patient', fn (Builder $query) => $query->whereIn('id', $request->input('selected', []))),
            fn (Builder $query) => $query->limit(10)
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
        ->whereBetween('date', [
            substr($request->start, 0, 10),
            substr($request->end, 0, 10),
        ])
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
                'title' => $appointment->patient->user->name.' - '.$appointment->doctor->user->name,
                'start' => $appointment->date->format('Y-m-d').' '.$appointment->start_time,
                'end' => $appointment->date->format('Y-m-d').' '.$appointment->end_time,
                'backgroundColor' => $appointment->status->color(),
                'borderColor' => $appointment->status->color(),
                'extendedProps' => [
                    'datetime' => $appointment->date->format('d/m/Y').' '.substr($appointment->start_time, 0, 5).' - '.substr($appointment->end_time, 0, 5),
                    'patient' => $appointment->patient->user->name,
                    'doctor' => $appointment->doctor->user->name,
                    'status' => $appointment->status->label(),
                    'url' => route('admin.appointments.consultation', $appointment->id),
                ],
            ];
        });

    // Agregar bloqueos de doctores como eventos de fondo
    $startDate = substr($request->start, 0, 10);
    $endDate = substr($request->end, 0, 10);

    $unavailabilities = DoctorUnavailability::with('doctor.user')
        ->where('start_date', '<=', $endDate)
        ->where('end_date', '>=', $startDate)
        ->get()
        ->map(function (DoctorUnavailability $u) {
            $doctorName = $u->doctor->user->name ?? 'Doctor';

            if ($u->all_day) {
                // FullCalendar requiere que end sea el día siguiente para eventos all-day
                $start = $u->start_date->format('Y-m-d');
                $end = $u->end_date->copy()->addDay()->format('Y-m-d');
            } else {
                $start = $u->start_date->format('Y-m-d').' '.($u->start_time ?? '00:00:00');
                $end = $u->end_date->format('Y-m-d').' '.($u->end_time ?? '23:59:00');
            }

            return [
                'id' => 'block-'.$u->id,
                'title' => $u->reason ? "🚫 {$doctorName}: {$u->reason}" : "🚫 {$doctorName}: No disponible",
                'start' => $start,
                'end' => $end,
                'display' => 'background',
                'backgroundColor' => '#FCA5A5',
                'borderColor' => '#EF4444',
                'classNames' => ['fc-block-event'],
                'extendedProps' => [
                    'type' => 'block',
                    'doctor' => $doctorName,
                    'reason' => $u->reason ?? 'Sin especificar',
                ],
            ];
        });

    return $appointments->merge($unavailabilities)->values();
})->name('api.appointments.index');

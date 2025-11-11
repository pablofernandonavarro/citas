<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentEnum;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class DashboardContoller extends Controller
{
    public function index()
    {
        Gate::authorize('access_dashboard');

        $data = [];

        if (auth()->user()->hasRole(['Admin', 'Recepcionista'])) {
            $data['total_patients'] = Patient::count();
            $data['total_doctors'] = Doctor::count();
            $data['appointment_today'] = Appointment::whereDate('date', now()->toDateString())
                ->where('status', AppointmentEnum::SCHEDULED)
                ->count();
            $data['recent_user'] = User::latest()
                ->take(5)
                ->get();

        }
        if (auth()->user()->hasRole('Doctor')) {
            $data['appointment_today_count'] = Appointment::whereDate('date', now()->toDateString())
                ->where('status', AppointmentEnum::SCHEDULED)
                ->whereHas('doctor', function ($query) {
                    $query->where('user_id', auth()->id());
                })->count();
            $data['appointment_week_count'] = Appointment::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->where('status', AppointmentEnum::SCHEDULED)
                ->whereHas('doctor', function ($query) {
                    $query->where('user_id', auth()->id());
                })->count();

            $data['next_appointment'] = Appointment::where('status', AppointmentEnum::SCHEDULED)
                ->whereDate('date', '>=', now())
                ->where(function ($query) {
                    $query->whereTime('end_time', '>=', now()->toTimeString());
                })
                ->whereHas('doctor', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->orderBy('start_time')
                ->first();

            $data['appointment_today'] = Appointment::whereDate('date', now()->toDateString())
                ->where('status', AppointmentEnum::SCHEDULED)
                ->whereHas('doctor', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->with(['patient', 'doctor'])
                ->orderBy('start_time')
                ->get();
        }

        if (auth()->user()->hasRole('Paciente')) {

            $data['next_appointment'] = Appointment::where('status', AppointmentEnum::SCHEDULED)
                ->where(function ($query) {
                    $query->where('date', '>', now()->toDateString())
                        ->orWhere(function ($q) {
                            $q->whereDate('date', now()->toDateString())
                              ->whereTime('start_time', '>=', now()->toTimeString());
                        });
                })
                ->whereHas('patient', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->with(['doctor.user', 'doctor.speciality'])
                ->orderBy('date')
                ->orderBy('start_time')
                ->first();

            $data['past_appointments'] = Appointment::whereIn('status', [AppointmentEnum::COMPLETED, AppointmentEnum::CANCELED])
                ->where(function ($query) {
                    $query->where('date', '<', now()->toDateString())
                        ->orWhere(function ($q) {
                            $q->whereDate('date', now()->toDateString())
                              ->whereTime('end_time', '<', now()->toTimeString());
                        });
                })
                ->whereHas('patient', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->with(['doctor.user', 'doctor.speciality'])
                ->orderBy('date', 'desc')
                ->orderBy('start_time', 'desc')
                ->take(10)
                ->get();

        }

        return view('admin.dashboard', compact('data'));
    }
}

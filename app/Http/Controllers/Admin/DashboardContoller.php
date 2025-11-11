<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentEnum;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardContoller extends Controller
{
    public function index(){
        Gate::authorize('access_dashboard');

        $data= [];
        $data['total_patients']= Patient::count();
        $data['total_doctors'] = Doctor::count();
        $data['appointment_today'] = Appointment::whereDate('date', now()->toDateString())
        ->where('status', AppointmentEnum::SCHEDULED)
        ->count();
        $data['recent_user'] = User::latest()
        ->take(5)
        ->get();
    
        return view('admin.dashboard',compact('data'));
    }
}

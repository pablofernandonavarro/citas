<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\SocialWorkController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\CalendarController;
use App\Models\Appointment;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

// Gestion de roles
Route::resource('roles', RoleController::class);
// Gestion de usuarios
Route::resource('users', UserController::class);
// Gestion de pacientes
Route::resource('patients', PatientController::class)->only(['index', 'edit', 'update']);
// gestion de obras sociales
 Route::resource('socialworks', SocialWorkController::class);
// gestion de doctores
 Route::resource('doctors', DoctorController::class)->only(['index', 'edit', 'update']);

 Route::get('doctors/{doctor}/schedules', [DoctorController::class, 'schedules'])->name('doctors.schedules');

 Route::get('appointments/{appointment}/consultation', [AppointmentController::class, 'consultation'])->name('appointments.consultation');

 Route::resource('appointments', AppointmentController::class);

 Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');

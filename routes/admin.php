<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\SocialWorkController;
use App\Http\Controllers\Admin\SpecialityController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\DashboardContoller;
use App\Http\Controllers\Admin\DoctorUnavailabilityController;
use App\Http\Controllers\Admin\CabinetController;
use App\Models\Appointment;

Route::get('/', [DashboardContoller::class, 'index'])->name('dashboard');

// Gestion de roles
Route::resource('roles', RoleController::class);
// Gestion de usuarios
Route::resource('users', UserController::class);
// Gestion de pacientes
Route::resource('patients', PatientController::class)->only(['index', 'edit', 'update']);
// gestion de obras sociales
 Route::resource('socialworks', SocialWorkController::class);
// gestion de especialidades
 Route::resource('specialities', SpecialityController::class);
// gestion de doctores
 Route::resource('doctors', DoctorController::class)->only(['index', 'edit', 'update']);

 Route::get('doctors/{doctor}/schedules', [DoctorController::class, 'schedules'])->name('doctors.schedules');
 Route::post('doctors/{doctor}/cabinets', [CabinetController::class, 'assignToDoctor'])->name('doctors.cabinets.assign');

 Route::get('appointments/{appointment}/consultation', [AppointmentController::class, 'consultation'])->name('appointments.consultation');
 Route::get('appointments-available', [AppointmentController::class, 'available'])->name('appointments.available');
 Route::post('appointments/{appointment}/release', [AppointmentController::class, 'release'])->name('appointments.release');
 Route::post('appointments/{appointment}/assign', [AppointmentController::class, 'assign'])->name('appointments.assign');
 Route::post('appointments/{appointment}/assign-cabinet', [AppointmentController::class, 'assignCabinet'])->name('appointments.assign.cabinet');

 Route::resource('appointments', AppointmentController::class);

 Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');

 // Gestion de períodos de bloqueo/vacaciones
 Route::resource('unavailability', DoctorUnavailabilityController::class);

 // Gestion de gabinetes
 Route::resource('cabinets', CabinetController::class);
 

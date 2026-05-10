<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\CabinetController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\DashboardContoller;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\DoctorUnavailabilityController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SocialWorkController;
use App\Http\Controllers\Admin\SpecialityController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {

    Route::get('/', function () {
        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('landing');
    });

    Route::get('/inicio', fn () => view('landing'))->name('landing');

    Route::get('/politica-de-privacidad', fn () => view('privacy-policy'))->name('privacy.policy');
    Route::get('/privacy-policy', fn () => view('privacy-policy'));
    Route::get('/condiciones-del-servicio', fn () => view('terms-of-service'))->name('terms.service');
    Route::get('/terms-of-service', fn () => view('terms-of-service'));

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))->name('dashboard');
    });

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardContoller::class, 'index'])->name('dashboard');

        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::resource('patients', PatientController::class)->only(['index', 'edit', 'update']);
        Route::resource('socialworks', SocialWorkController::class);
        Route::resource('specialities', SpecialityController::class);
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
        Route::resource('unavailability', DoctorUnavailabilityController::class);
        Route::resource('cabinets', CabinetController::class);

        Route::get('company-settings', [\App\Http\Controllers\Admin\CompanySettingController::class, 'edit'])->name('company-settings.edit');
        Route::put('company-settings', [\App\Http\Controllers\Admin\CompanySettingController::class, 'update'])->name('company-settings.update');

        Route::get('whatsapp', [\App\Http\Controllers\Admin\WhatsAppController::class, 'index'])->name('whatsapp.index');
        Route::get('whatsapp/status', [\App\Http\Controllers\Admin\WhatsAppController::class, 'status'])->name('whatsapp.status');
        Route::delete('whatsapp/disconnect', [\App\Http\Controllers\Admin\WhatsAppController::class, 'disconnect'])->name('whatsapp.disconnect');
    });
});

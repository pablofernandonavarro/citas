<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;

Route::get('/', function () {
    return view('admin.dashboard');  
})->name('dashboard');

// Gestion de roles
Route::resource('roles', RoleController::class);
// Gestion de usuarios
Route::resource('users', UserController::class);
// Gestion de pacientes
Route::resource('patients', PatientController::class);
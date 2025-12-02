<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('landing');
});

// Política de Privacidad (pública)
Route::get('/politica-de-privacidad', function () {
    return view('privacy-policy');
})->name('privacy.policy');

// Alias en inglés
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
});

// Términos de Servicio (pública)
Route::get('/condiciones-del-servicio', function () {
    return view('terms-of-service');
})->name('terms.service');

// Alias en inglés
Route::get('/terms-of-service', function () {
    return view('terms-of-service');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');
});

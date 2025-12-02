<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web', 'auth')
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Enviar recordatorios 24 horas antes de las citas (ejecutar diariamente a las 9:00 AM)
        $schedule->command('appointments:send-day-before-reminders')
            ->dailyAt('09:00')
            ->timezone('America/Argentina/Buenos_Aires');

        // Enviar recordatorios 2 horas antes de las citas (ejecutar cada 15 minutos)
        $schedule->command('appointments:send-two-hours-before-reminders')
            ->everyFifteenMinutes()
            ->timezone('America/Argentina/Buenos_Aires');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

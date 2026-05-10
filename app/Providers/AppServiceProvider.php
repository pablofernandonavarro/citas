<?php

namespace App\Providers;

use App\Notifications\Channels\WhatsAppChannel;
use App\View\Composers\SiderbarComposer;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Exceptions\NotASubdomainException;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Allow central domain requests to pass through without tenant identification
        InitializeTenancyBySubdomain::$onFail = function ($exception, $request, $next) {
            if ($exception instanceof NotASubdomainException) {
                return $next($request);
            }
            throw $exception;
        };

        View::composer('layouts.admin', SiderbarComposer::class);

        // Registrar canal personalizado de WhatsApp
        Notification::extend('whatsapp', function ($app) {
            return $app->make(WhatsAppChannel::class);
        });
    }
}

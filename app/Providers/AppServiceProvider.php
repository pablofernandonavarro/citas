<?php

namespace App\Providers;

use App\View\Composers\SiderbarComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Channels\WhatsAppChannel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.admin',SiderbarComposer::class);
        
        // Registrar canal personalizado de WhatsApp
        Notification::extend('whatsapp', function ($app) {
            return $app->make(WhatsAppChannel::class);
        });
    }
}

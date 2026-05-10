<?php

namespace App\Notifications\Channels;

use App\Models\CompanySetting;
use App\Services\EvolutionService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    public function __construct(private EvolutionService $evolution) {}

    public function send(object $notifiable, Notification $notification): void
    {
        if (! $this->evolution->isConfigured()) {
            Log::info('WhatsApp: Evolution API no configurada');

            return;
        }

        $settings = CompanySetting::get();

        if (! $settings->evolution_connected) {
            Log::info('WhatsApp: instancia no conectada para este tenant', ['tenant' => tenant('id')]);

            return;
        }

        $message = $notification->toWhatsApp($notifiable);

        if (empty($message['to']) || empty($message['message'])) {
            return;
        }

        $this->evolution->sendText(
            instanceName: tenant('id'),
            phone: $message['to'],
            message: $message['message'],
        );
    }
}

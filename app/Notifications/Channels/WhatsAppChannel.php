<?php

namespace App\Notifications\Channels;

use App\Services\WhatsAppService;
use Illuminate\Notifications\Notification;

class WhatsAppChannel
{
    protected WhatsAppService $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Enviar la notificación
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (!$this->whatsappService->isConfigured()) {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);

        if (!isset($message['to']) || !isset($message['message'])) {
            return;
        }

        $this->whatsappService->sendMessage(
            $message['to'],
            $message['message']
        );
    }
}

<?php

namespace App\Notifications\Channels;

use App\Services\WhatsAppService;
use Illuminate\Notifications\Notification;

class WhatsAppChannel
{
    /**
     * Enviar la notificación
     */
    public function send(object $notifiable, Notification $notification): void
    {
        // Crear una instancia fresca del servicio en cada envío
        // para evitar problemas con tokens en caché cuando se serializa el job
        $whatsappService = app(WhatsAppService::class);
        
        if (!$whatsappService->isConfigured()) {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);

        if (!isset($message['to'])) {
            return;
        }

        // Si es una plantilla (template)
        if (isset($message['template'])) {
            $whatsappService->sendTemplate(
                $message['to'],
                $message['template'],
                $message['parameters'] ?? [],
                $message['language'] ?? 'es_AR'
            );
            return;
        }

        // Si es un mensaje de texto directo
        if (isset($message['message'])) {
            $whatsappService->sendMessage(
                $message['to'],
                $message['message']
            );
        }
    }
}

<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\WhatsAppService;

echo "=== Prueba de Envío Directo de WhatsApp ===\n\n";

$whatsapp = app(WhatsAppService::class);

$phone = '1569975132'; // Número del paciente
$message = "🧪 *Prueba de WhatsApp*\n\nEste es un mensaje de prueba del sistema de recordatorios.\n\nSi recibes este mensaje, el sistema funciona correctamente! ✅";

echo "Enviando mensaje a: {$phone}\n";
echo "Mensaje: {$message}\n\n";

$result = $whatsapp->sendMessage($phone, $message);

if ($result) {
    echo "✅ ¡Mensaje enviado exitosamente!\n";
    echo "Revisa tu WhatsApp para confirmar.\n";
} else {
    echo "❌ Error al enviar el mensaje\n";
    echo "Revisa storage/logs/laravel.log para más detalles\n";
}

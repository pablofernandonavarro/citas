<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\WhatsAppService;

echo "🔄 === Test de WhatsApp con TEXTO ===\n\n";

$whatsapp = app(WhatsAppService::class);

if (!$whatsapp->isConfigured()) {
    echo "❌ WhatsApp no está configurado\n";
    exit(1);
}

echo "✅ WhatsApp configurado correctamente\n\n";

// Número de teléfono
$phone = '1569975132';

// Mensaje de prueba
$message = "🏥 *PRUEBA - Sistema de Turnos*\n\n" .
           "Hola! Este es un mensaje de prueba.\n\n" .
           "Si recibís este mensaje, significa que el sistema de WhatsApp está funcionando correctamente! ✅\n\n" .
           "📅 Cetrip Kinesiología\n" .
           "📞 Contacto: Jose C Paz 5723, San Martín";

echo "📱 Enviando mensaje de texto a: {$phone}\n";
echo "📝 Mensaje:\n{$message}\n\n";
echo "⏳ Enviando...\n\n";

$result = $whatsapp->sendMessage($phone, $message);

if ($result) {
    echo "✅ ¡Mensaje enviado exitosamente!\n\n";
    echo "Revisa tu WhatsApp en el número: +54 11 $phone\n";
} else {
    echo "❌ Error al enviar el mensaje\n\n";
    echo "Revisa los logs:\n";
    echo "  tail -20 storage/logs/laravel.log | grep -i whatsapp\n";
}

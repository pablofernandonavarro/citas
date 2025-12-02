<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\WhatsAppService;

echo "🔄 === Test Directo de WhatsApp ===\n\n";

$whatsapp = app(WhatsAppService::class);

if (!$whatsapp->isConfigured()) {
    echo "❌ WhatsApp no está configurado\n";
    exit(1);
}

echo "✅ WhatsApp configurado correctamente\n\n";

$phone = '1569975132';
$template = 'cita_confirmada';

// Parámetros de prueba con dirección real de la clínica
$params = [
    'Olga Aranda',
    'martes 3 de diciembre de 2025',
    '12:00',
    'Dra. Veronica Navarro',
    'Kinesiología',
    config('services.whatsapp.default_location')
];

echo "📱 Enviando a: {$phone}\n";
echo "📋 Plantilla: {$template}\n";
echo "📝 Parámetros: " . implode(', ', $params) . "\n\n";

$result = $whatsapp->sendTemplate($phone, $template, $params, 'es_AR');

if ($result) {
    echo "✅ ¡Mensaje enviado exitosamente!\n";
    echo "\nRevisa los logs para ver la respuesta completa:\n";
    echo "Get-Content storage/logs/laravel.log -Tail 5\n";
} else {
    echo "❌ Error al enviar el mensaje\n";
    echo "\nRevisa los logs:\n";
    echo "Get-Content storage/logs/laravel.log -Tail 10\n";
}

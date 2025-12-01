<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\WhatsAppService;

echo "=== Prueba Directa de Plantilla WhatsApp ===\n\n";

$whatsapp = app(WhatsAppService::class);

if (!$whatsapp->isConfigured()) {
    echo "❌ Servicio NO configurado\n";
    exit(1);
}

echo "✅ Servicio configurado\n\n";

$phone = '1569975132';
$template = config('services.whatsapp.templates.appointment_created');
$language = config('services.whatsapp.templates.language');

echo "📱 Teléfono: {$phone}\n";
echo "📄 Plantilla: {$template}\n";
echo "🌍 Idioma: {$language}\n\n";

// Tu plantilla NO tiene parámetros, enviar array vacío
$parameters = [];

echo "📤 Enviando mensaje (sin parámetros)...\n\n";

$result = $whatsapp->sendTemplate($phone, $template, $parameters, $language);

if ($result) {
    echo "✅ ¡Mensaje enviado exitosamente!\n";
} else {
    echo "❌ Error al enviar mensaje\n";
    echo "📝 Revisa storage/logs/laravel.log\n";
}

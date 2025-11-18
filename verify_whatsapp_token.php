<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

echo "=== Verificando Token de WhatsApp ===\n\n";

$token = config('services.whatsapp.access_token');
$phoneNumberId = config('services.whatsapp.phone_number_id');

if (empty($token)) {
    echo "❌ ERROR: No hay token configurado en .env\n";
    exit(1);
}

echo "Token configurado: " . substr($token, 0, 20) . "..." . substr($token, -20) . "\n";
echo "Phone Number ID: {$phoneNumberId}\n\n";

// Verificar el token
echo "Verificando validez del token...\n";
$response = Http::withToken($token)->get('https://graph.facebook.com/v21.0/me');

if ($response->successful()) {
    echo "✅ TOKEN VÁLIDO!\n\n";
    $data = $response->json();
    echo "Información de la app:\n";
    echo json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
    
    // Verificar phone number ID
    echo "Verificando Phone Number ID...\n";
    $phoneResponse = Http::withToken($token)
        ->get("https://graph.facebook.com/v21.0/{$phoneNumberId}");
    
    if ($phoneResponse->successful()) {
        echo "✅ PHONE NUMBER ID VÁLIDO!\n";
        echo json_encode($phoneResponse->json(), JSON_PRETTY_PRINT) . "\n\n";
        
        echo "🎉 Todo configurado correctamente!\n";
        echo "Puedes enviar mensajes de prueba ejecutando:\n";
        echo "   php artisan appointments:send-day-before-reminders\n";
    } else {
        echo "❌ ERROR: Phone Number ID inválido\n";
        echo "Status: " . $phoneResponse->status() . "\n";
        echo json_encode($phoneResponse->json(), JSON_PRETTY_PRINT) . "\n";
    }
    
} else {
    echo "❌ TOKEN INVÁLIDO O EXPIRADO\n\n";
    echo "Status HTTP: " . $response->status() . "\n";
    $error = $response->json();
    echo "Error: " . json_encode($error, JSON_PRETTY_PRINT) . "\n\n";
    
    if (isset($error['error']['message'])) {
        echo "Mensaje: " . $error['error']['message'] . "\n\n";
    }
    
    echo "Para obtener un nuevo token:\n";
    echo "1. Ve a https://developers.facebook.com/apps/\n";
    echo "2. Selecciona tu aplicación\n";
    echo "3. Ve a WhatsApp > Configuración de la API\n";
    echo "4. Copia el nuevo token temporal\n";
    echo "5. Actualiza WHATSAPP_ACCESS_TOKEN en .env\n";
    echo "6. Ejecuta: php artisan config:clear\n";
}

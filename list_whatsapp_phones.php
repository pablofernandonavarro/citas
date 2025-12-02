<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

echo "=== Listando Números de WhatsApp Business (por WABA ID) ===\n\n";

$token = config('services.whatsapp.access_token');
$wabaId = '1353372809626769'; // TU WABA ID (ejemplo)

if (empty($token)) {
    echo "❌ No hay token configurado en config('services.whatsapp.access_token').\n";
    exit(1);
}

$version = 'v21.0';
$url = "https://graph.facebook.com/{$version}/{$wabaId}?fields=phone_numbers";

echo "Consultando {$url}\n\n";

$response = Http::withToken($token)->get($url);

echo "HTTP Status: " . $response->status() . "\n";

if ($response->successful()) {
    $data = $response->json();

    if (isset($data['phone_numbers']) && isset($data['phone_numbers']['data']) && count($data['phone_numbers']['data']) > 0) {
        foreach ($data['phone_numbers']['data'] as $phone) {
            echo "✅ Número disponible:\n";
            echo "  Phone Number ID: " . ($phone['id'] ?? 'N/A') . "\n";
            echo "  Display Phone: " . ($phone['display_phone_number'] ?? 'N/A') . "\n";
            echo "  Verified Name: " . ($phone['verified_name'] ?? 'N/A') . "\n";
            echo "  Status: " . ($phone['status'] ?? 'N/A') . "\n";
            echo "  Quality Rating: " . ($phone['quality_rating'] ?? 'N/A') . "\n\n";

            echo "  Agrega este ID a tu .env:\n";
            echo "  WHATSAPP_PHONE_NUMBER_ID=" . ($phone['id'] ?? '') . "\n\n";
        }
    } else {
        echo "⚠️  No hay números de teléfono registrados para este WABA.\n";
        echo "Respuesta completa:\n" . json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
} else {
    echo "❌ Error al obtener phone_numbers\n";
    echo json_encode($response->json(), JSON_PRETTY_PRINT) . "\n";
}

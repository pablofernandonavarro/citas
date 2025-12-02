<?php

/**
 * Script para listar todas las plantillas (templates) de WhatsApp disponibles
 */

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$accessToken = $_ENV['WHATSAPP_ACCESS_TOKEN'] ?? null;
$wabaId = $_ENV['WHATSAPP_BUSINESS_ACCOUNT_ID'] ?? null;

if (!$accessToken || !$wabaId) {
    echo "❌ Error: Faltan variables de entorno\n";
    echo "Asegúrate de tener configuradas:\n";
    echo "- WHATSAPP_ACCESS_TOKEN\n";
    echo "- WHATSAPP_BUSINESS_ACCOUNT_ID\n";
    exit(1);
}

echo "===========================================\n";
echo "PLANTILLAS DE WHATSAPP DISPONIBLES\n";
echo "===========================================\n\n";

// Endpoint para obtener plantillas
$url = "https://graph.facebook.com/v21.0/{$wabaId}/message_templates";

echo "Consultando: {$url}\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer {$accessToken}",
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($response, true);

echo "HTTP Status: {$httpCode}\n\n";

if ($httpCode !== 200) {
    echo "❌ Error al obtener plantillas\n";
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit(1);
}

if (!isset($data['data']) || empty($data['data'])) {
    echo "⚠️ No hay plantillas disponibles\n";
    echo "\nPara crear plantillas:\n";
    echo "1. Ve a: https://business.facebook.com/wa/manage/message-templates/\n";
    echo "2. Selecciona tu cuenta de negocio\n";
    echo "3. Crea una nueva plantilla para 'Confirmación de turno'\n";
    exit(0);
}

echo "✅ Plantillas encontradas: " . count($data['data']) . "\n\n";
echo "===========================================\n\n";

foreach ($data['data'] as $index => $template) {
    $number = $index + 1;
    echo "PLANTILLA #{$number}\n";
    echo str_repeat("-", 40) . "\n";
    echo "Nombre: {$template['name']}\n";
    echo "Estado: {$template['status']}\n";
    echo "Idioma: {$template['language']}\n";
    echo "Categoría: {$template['category']}\n";
    
    if (isset($template['components'])) {
        foreach ($template['components'] as $component) {
            if ($component['type'] === 'BODY') {
                echo "\nContenido:\n";
                echo $component['text'] . "\n";
            }
        }
    }
    
    echo "\nUso en .env:\n";
    echo "WHATSAPP_TEMPLATE_APPOINTMENT_CREATED={$template['name']}\n";
    echo "WHATSAPP_TEMPLATE_LANGUAGE={$template['language']}\n";
    echo "\n" . str_repeat("=", 40) . "\n\n";
}

echo "\n✅ Verifica que la plantilla esté APROBADA (status: APPROVED)\n";
echo "✅ Usa el nombre exacto en tu archivo .env\n\n";

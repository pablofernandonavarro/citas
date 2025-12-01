<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

$token = config('services.whatsapp.access_token');
$businessAccountId = env('WHAT_BUSSINESS_ACCOUNT_ID');

echo "📋 Consultando plantilla 'cita_confirmada'...\n\n";

$response = Http::withToken($token)
    ->get("https://graph.facebook.com/v22.0/{$businessAccountId}/message_templates", [
        'name' => 'cita_confirmada'
    ]);

if ($response->successful()) {
    $templates = $response->json()['data'] ?? [];
    
    if (empty($templates)) {
        echo "❌ No se encontró la plantilla 'cita_confirmada'\n";
        exit(1);
    }
    
    $template = $templates[0];
    
    echo "✅ Plantilla encontrada\n";
    echo "Nombre: {$template['name']}\n";
    echo "Estado: {$template['status']}\n";
    echo "Idioma: {$template['language']}\n\n";
    
    echo "📝 Componentes:\n";
    foreach ($template['components'] as $component) {
        echo "\n- Tipo: {$component['type']}\n";
        
        if ($component['type'] === 'BODY') {
            echo "  Texto:\n";
            echo "  " . str_replace("\n", "\n  ", $component['text']) . "\n";
            
            // Contar variables {{X}}
            preg_match_all('/\{\{(\d+)\}\}/', $component['text'], $matches);
            $varCount = count($matches[0]);
            echo "\n  Variables encontradas: {$varCount}\n";
            if ($varCount > 0) {
                echo "  Variables: " . implode(', ', $matches[0]) . "\n";
            }
        }
        
        if ($component['type'] === 'BUTTONS') {
            echo "  Botones:\n";
            if (isset($component['buttons'])) {
                foreach ($component['buttons'] as $button) {
                    echo "    - Tipo: {$button['type']}\n";
                    echo "      Texto: {$button['text']}\n";
                    if (isset($button['url'])) {
                        echo "      URL: {$button['url']}\n";
                    }
                    if (isset($button['phone_number'])) {
                        echo "      Teléfono: {$button['phone_number']}\n";
                    }
                }
            }
        }
    }
} else {
    echo "❌ Error al obtener plantilla\n";
    echo json_encode($response->json(), JSON_PRETTY_PRINT) . "\n";
}

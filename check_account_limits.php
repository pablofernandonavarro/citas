<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

echo "📊 === Verificando Límites de la Cuenta de WhatsApp ===\n\n";

$token = config('services.whatsapp.access_token');
$phoneNumberId = config('services.whatsapp.phone_number_id');

if (empty($token) || empty($phoneNumberId)) {
    echo "❌ Token o Phone Number ID no configurados\n";
    exit(1);
}

// Obtener información del número de teléfono
$response = Http::withToken($token)
    ->get("https://graph.facebook.com/v22.0/{$phoneNumberId}", [
        'fields' => 'quality_rating,messaging_limit_tier,throughput,name_status,code_verification_status,display_phone_number'
    ]);

if ($response->successful()) {
    $data = $response->json();
    
    echo "✅ Información de la cuenta:\n\n";
    echo "📱 Número: " . ($data['display_phone_number'] ?? 'N/A') . "\n";
    echo "📊 Estado de verificación: " . ($data['code_verification_status'] ?? 'N/A') . "\n";
    echo "🏷️  Estado del nombre: " . ($data['name_status'] ?? 'N/A') . "\n\n";
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Quality Rating
    $quality = $data['quality_rating'] ?? 'UNKNOWN';
    $qualityEmoji = match($quality) {
        'GREEN' => '🟢',
        'YELLOW' => '🟡',
        'RED' => '🔴',
        default => '⚪'
    };
    
    echo "📈 CALIDAD DE LA CUENTA\n";
    echo "   {$qualityEmoji} Rating: {$quality}\n";
    
    if ($quality === 'GREEN') {
        echo "   ✅ Excelente - Sin problemas\n";
    } elseif ($quality === 'YELLOW') {
        echo "   ⚠️  Advertencia - Reduce quejas\n";
    } elseif ($quality === 'RED') {
        echo "   ❌ Crítico - Riesgo de suspensión\n";
    }
    
    echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Messaging Limit Tier
    $tier = $data['messaging_limit_tier'] ?? 'TIER_NOT_SET';
    
    echo "🎯 LÍMITE DE MENSAJES (TIER)\n";
    echo "   Nivel actual: {$tier}\n\n";
    
    $limits = [
        'TIER_NOT_SET' => ['limit' => '1,000', 'description' => 'Cuenta nueva o no verificada'],
        'TIER_1' => ['limit' => '1,000', 'description' => 'Nivel inicial'],
        'TIER_50' => ['limit' => '250', 'description' => 'Cuenta en pruebas'],
        'TIER_250' => ['limit' => '250', 'description' => 'Cuenta en desarrollo'],
        'TIER_1K' => ['limit' => '1,000', 'description' => 'Nivel inicial verificado'],
        'TIER_10K' => ['limit' => '10,000', 'description' => 'Nivel intermedio'],
        'TIER_100K' => ['limit' => '100,000', 'description' => 'Nivel avanzado'],
        'UNLIMITED' => ['limit' => 'Ilimitado', 'description' => 'Sin límites']
    ];
    
    if (isset($limits[$tier])) {
        echo "   📊 Límite diario: {$limits[$tier]['limit']} conversaciones\n";
        echo "   📝 {$limits[$tier]['description']}\n";
    } else {
        echo "   ⚠️  Límite desconocido\n";
    }
    
    echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Throughput
    if (isset($data['throughput'])) {
        $throughput = $data['throughput'];
        echo "⚡ CAPACIDAD DE ENVÍO\n";
        echo "   Nivel: " . ($throughput['level'] ?? 'N/A') . "\n";
    }
    
    echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "💡 CÓMO AUMENTAR TU LÍMITE:\n\n";
    echo "1. Mantén la calidad en VERDE (evita bloqueos/quejas)\n";
    echo "2. Usa solo plantillas aprobadas\n";
    echo "3. El límite aumenta automáticamente cada 24h con buen uso\n";
    echo "4. Verifica tu número de teléfono si aún no lo hiciste\n";
    echo "5. Completa la verificación del negocio en Meta\n\n";
    
    // Tier Progression
    echo "📈 PROGRESIÓN DE TIERS:\n\n";
    echo "   TIER 1 (1K)     →  Envía 1,000 msg sin quejas\n";
    echo "   TIER 2 (10K)    →  Envía 10,000 msg sin quejas\n";
    echo "   TIER 3 (100K)   →  Envía 100,000 msg sin quejas\n";
    echo "   TIER 4 (∞)      →  Sin límites\n\n";
    
} else {
    echo "❌ Error al obtener información de la cuenta\n\n";
    echo "Status: " . $response->status() . "\n";
    echo json_encode($response->json(), JSON_PRETTY_PRINT) . "\n";
}

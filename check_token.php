<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$token = config('services.whatsapp.access_token');

echo "Token (primeros 50 caracteres): " . substr($token, 0, 50) . "...\n";
echo "Token length: " . strlen($token) . "\n";

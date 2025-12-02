<?php

/**
 * Script para verificar el envío de WhatsApp al crear una nueva cita
 * 
 * Este script simula la creación de una cita y envía la notificación de WhatsApp
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;

// Inicializar Laravel
$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        //
    })
    ->withExceptions(function ($exceptions) {
        //
    })
    ->create();

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "===========================================\n";
echo "VERIFICACIÓN DE WHATSAPP - CITA CREADA\n";
echo "===========================================\n\n";

// 1. Verificar configuración de WhatsApp
echo "1. Verificando configuración de WhatsApp...\n";
$whatsappService = app(\App\Services\WhatsAppService::class);

if (!$whatsappService->isConfigured()) {
    echo "   ❌ ERROR: WhatsApp no está configurado correctamente\n";
    echo "   Verifica las siguientes variables en .env:\n";
    echo "   - WHATSAPP_ACCESS_TOKEN\n";
    echo "   - WHATSAPP_PHONE_NUMBER_ID\n";
    echo "   - WHATSAPP_API_URL\n";
    exit(1);
}
echo "   ✅ WhatsApp configurado correctamente\n\n";

// 2. Buscar una cita reciente para probar
echo "2. Buscando cita reciente para probar...\n";
$appointment = \App\Models\Appointment::with(['patient.user', 'doctor.user', 'doctor.speciality', 'cabinet'])
    ->latest()
    ->first();

if (!$appointment) {
    echo "   ❌ ERROR: No hay citas en la base de datos\n";
    echo "   Crea una cita primero desde la aplicación\n";
    exit(1);
}

echo "   ✅ Cita encontrada:\n";
echo "   - ID: {$appointment->id}\n";
echo "   - Paciente: {$appointment->patient->user->name}\n";
echo "   - Doctor: {$appointment->doctor->user->name}\n";
echo "   - Fecha: {$appointment->date->format('d/m/Y')}\n";
echo "   - Hora: " . \Carbon\Carbon::parse($appointment->start_time)->format('H:i') . "\n";
echo "   - Teléfono paciente: {$appointment->patient->user->phone}\n\n";

// 3. Verificar que el paciente tenga teléfono
if (!$appointment->patient->user->phone) {
    echo "   ❌ ERROR: El paciente no tiene número de teléfono registrado\n";
    exit(1);
}

// 4. Confirmar envío (modo automático)
echo "3. Enviando notificación automáticamente...\n";

// 5. Enviar notificación
echo "\n4. Enviando notificación de WhatsApp...\n";

try {
    // Opción A: Usar el servicio directamente (síncrono)
    echo "   Método: Servicio directo (síncrono)\n";
    $result = $whatsappService->sendAppointmentConfirmationToPatient($appointment);
    
    if ($result) {
        echo "   ✅ Mensaje enviado exitosamente!\n";
        echo "\n5. Verifica el WhatsApp del paciente: {$appointment->patient->user->phone}\n";
    } else {
        echo "   ❌ Error al enviar el mensaje\n";
        echo "   Revisa los logs en storage/logs/laravel.log\n";
    }
    
} catch (\Exception $e) {
    echo "   ❌ Excepción: " . $e->getMessage() . "\n";
    echo "   " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n===========================================\n";
echo "Prueba completada\n";
echo "===========================================\n\n";

// 6. Información adicional
echo "NOTAS ADICIONALES:\n";
echo "- Para enviar usando la cola (asíncrono), usa la notificación:\n";
echo "  \$appointment->patient->user->notify(new \\App\\Notifications\\AppointmentCreatedNotification(\$appointment));\n\n";
echo "- Para ver los jobs en cola pendientes:\n";
echo "  php artisan queue:work\n\n";
echo "- Para ver los logs de WhatsApp:\n";
echo "  tail -f storage/logs/laravel.log | grep -i whatsapp\n\n";

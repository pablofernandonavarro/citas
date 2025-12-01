<?php

/**
 * Script para probar el envío de WhatsApp al crear una cita
 * 
 * Uso: php test_whatsapp_created.php [appointment_id]
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Appointment;
use App\Notifications\AppointmentCreatedNotification;

echo "⚙️  === Prueba de Notificación WhatsApp (Confirmación de Cita) ===\n\n";

// Verificar configuración de WhatsApp
$whatsappService = app(\App\Services\WhatsAppService::class);
if (!$whatsappService->isConfigured()) {
    echo "❌ ERROR: El servicio de WhatsApp NO está configurado.\n";
    echo "\n";
    echo "Verifica que tengas en tu .env:\n";
    echo "  WHATSAPP_API_URL=https://graph.facebook.com/v21.0\n";
    echo "  WHATSAPP_ACCESS_TOKEN=tu_token\n";
    echo "  WHATSAPP_PHONE_NUMBER_ID=tu_phone_id\n";
    echo "\n";
    exit(1);
}

echo "✅ Configuración de WhatsApp OK\n\n";

// Obtener ID de la cita desde argumento o buscar la última
$appointmentId = $argv[1] ?? null;

if ($appointmentId) {
    $appointment = Appointment::with(['patient.user', 'doctor.user', 'doctor.speciality', 'cabinet'])
        ->find($appointmentId);
    
    if (!$appointment) {
        echo "❌ No se encontró la cita con ID: {$appointmentId}\n";
        exit(1);
    }
} else {
    // Buscar una cita reciente con paciente y teléfono
    $appointment = Appointment::with(['patient.user', 'doctor.user', 'doctor.speciality', 'cabinet'])
        ->whereHas('patient.user', function($query) {
            $query->whereNotNull('phone');
        })
        ->latest()
        ->first();
    
    if (!$appointment) {
        echo "❌ No se encontró ninguna cita con un paciente que tenga teléfono.\n";
        exit(1);
    }
}

// Mostrar información de la cita
echo "📋 Cita seleccionada:\n";
echo "   ID: {$appointment->id}\n";
echo "   Paciente: {$appointment->patient->user->name}\n";
echo "   Teléfono: {$appointment->patient->user->phone}\n";
echo "   Doctor: {$appointment->doctor->user->name}\n";
echo "   Especialidad: " . ($appointment->doctor->speciality->name ?? 'N/A') . "\n";
echo "   Fecha: {$appointment->date}\n";
echo "   Hora: {$appointment->start_time}\n";
if ($appointment->cabinet) {
    echo "   Gabinete: {$appointment->cabinet->name}\n";
}
echo "\n";

// Enviar notificación
echo "📤 Enviando notificación de WhatsApp...\n";

try {
    $appointment->patient->user->notify(new AppointmentCreatedNotification($appointment));
    
    echo "✅ ¡Notificación agregada a la cola!\n";
    echo "\n";
    echo "🔄 Ahora procesa la cola con:\n";
    echo "   php artisan queue:work --stop-when-empty\n";
    echo "\n";
    echo "📝 Para ver los logs:\n";
    echo "   tail -f storage/logs/laravel.log\n";
    echo "\n";
    echo "💡 Tip: Si usas plantilla 'cita_confirmada', asegúrate de que esté APROBADA en Meta.\n";
    
} catch (\Exception $e) {
    echo "❌ Error al enviar la notificación:\n";
    echo "   " . $e->getMessage() . "\n";
    echo "\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}

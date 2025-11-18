<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Cabinet;
use Carbon\Carbon;

echo "=== Creando Cita de Prueba para WhatsApp ===\n\n";

$patient = Patient::first();
$doctor = Doctor::first();
$cabinet = Cabinet::first();

if (!$patient) {
    echo "❌ Error: No hay pacientes en la base de datos\n";
    exit(1);
}

if (!$doctor) {
    echo "❌ Error: No hay doctores en la base de datos\n";
    exit(1);
}

if (!$cabinet) {
    echo "❌ Error: No hay gabinetes en la base de datos\n";
    exit(1);
}

$tomorrow = Carbon::tomorrow()->format('Y-m-d');

$appointment = Appointment::create([
    'patient_id' => $patient->id,
    'doctor_id' => $doctor->id,
    'cabinet_id' => $cabinet->id,
    'date' => $tomorrow,
    'start_time' => '10:00:00',
    'end_time' => '10:30:00',
    'duration' => 30,
    'reason' => 'Cita de prueba para WhatsApp',
    'status' => 1  // SCHEDULED
]);

echo "✅ Cita creada exitosamente!\n\n";
echo "📅 Fecha: {$appointment->date}\n";
echo "🕐 Hora: {$appointment->start_time}\n";
echo "👤 Paciente: {$patient->user->name}\n";
echo "📞 Teléfono: " . ($patient->user->phone ?? '⚠️  SIN TELEFONO REGISTRADO') . "\n";
echo "👨‍⚕️ Doctor: {$doctor->user->name}\n\n";

if (empty($patient->user->phone)) {
    echo "⚠️  ATENCIÓN: El paciente no tiene teléfono registrado.\n";
    echo "No se podrá enviar el recordatorio por WhatsApp.\n\n";
    echo "Para agregar un teléfono, actualiza el usuario del paciente.\n";
} else {
    echo "✅ El paciente tiene teléfono registrado.\n";
    echo "Puedes probar el envío ejecutando:\n";
    echo "   php artisan appointments:send-day-before-reminders\n";
}

echo "\nID de la cita creada: {$appointment->id}\n";

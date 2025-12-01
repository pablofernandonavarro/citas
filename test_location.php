<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Appointment;
use App\Notifications\AppointmentCreatedNotification;

$appointment = Appointment::with(['patient.user', 'doctor.user', 'doctor.speciality', 'cabinet'])
    ->find(14);

if (!$appointment) {
    echo "❌ Cita no encontrada\n";
    exit(1);
}

$notification = new AppointmentCreatedNotification($appointment);
$params = $notification->toWhatsApp($appointment->patient->user)['parameters'];

echo "📋 Parámetros que se enviarán:\n\n";
echo "1. Nombre paciente: {$params[0]}\n";
echo "2. Fecha: {$params[1]}\n";
echo "3. Hora: {$params[2]}\n";
echo "4. Doctor: {$params[3]}\n";
echo "5. Especialidad: {$params[4]}\n";
echo "6. Ubicación:\n";
echo "   {$params[5]}\n";

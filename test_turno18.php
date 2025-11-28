<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$apt = \App\Models\Appointment::with(['patient.user', 'doctor.user'])->find(18);

echo "Turno: {$apt->id}\n";
echo "Paciente: {$apt->patient->user->name}\n";
echo "Teléfono: " . ($apt->patient->user->phone ?? 'SIN TELÉFONO') . "\n";
echo "Enviando WhatsApp...\n";

app(\App\Services\WhatsAppService::class)->sendAppointmentConfirmationToPatient($apt);

echo "Listo! Revisá el log:\n";
echo "tail storage/logs/laravel.log\n";

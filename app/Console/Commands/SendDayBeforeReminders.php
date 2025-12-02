<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Notifications\AppointmentReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendDayBeforeReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-day-before-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar recordatorios de WhatsApp a pacientes con citas en 24 horas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Buscando citas para enviar recordatorios (24 horas antes)...');

        // Obtener citas que sean mañana
        $tomorrow = Carbon::tomorrow();
        
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->whereDate('date', $tomorrow)
            ->whereIn('status', [1])  // SCHEDULED
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No hay citas programadas para mañana.');
            return 0;
        }

        $sent = 0;
        $failed = 0;

        foreach ($appointments as $appointment) {
            try {
                // Verificar que el paciente tenga número de teléfono
                if (empty($appointment->patient->user->phone)) {
                    $this->warn("Paciente {$appointment->patient->user->name} no tiene número de teléfono.");
                    $failed++;
                    continue;
                }

                // Enviar notificación
                $appointment->patient->user->notify(
                    new AppointmentReminderNotification($appointment, 'day_before')
                );

                $this->info("✓ Recordatorio enviado a {$appointment->patient->user->name}");
                $sent++;

            } catch (\Exception $e) {
                $this->error("✗ Error al enviar recordatorio a {$appointment->patient->user->name}: {$e->getMessage()}");
                $failed++;
            }
        }

        $this->info("\n--- Resumen ---");
        $this->info("Total de citas: {$appointments->count()}");
        $this->info("Recordatorios enviados: {$sent}");
        
        if ($failed > 0) {
            $this->warn("Fallos: {$failed}");
        }

        return 0;
    }
}

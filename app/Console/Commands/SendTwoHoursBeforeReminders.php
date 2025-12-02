<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Notifications\AppointmentReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendTwoHoursBeforeReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-two-hours-before-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar recordatorios de WhatsApp a pacientes con citas en 2 horas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Buscando citas para enviar recordatorios (2 horas antes)...');

        // Obtener citas de hoy que sean en aproximadamente 2 horas
        $now = Carbon::now();
        $twoHoursLater = $now->copy()->addHours(2);
        
        // Buscar citas que comiencen entre 2 horas y 2 horas 15 minutos desde ahora
        // (ventana de 15 minutos para asegurar que no se pierdan citas)
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->whereDate('date', $now->toDateString())
            ->whereTime('start_time', '>=', $twoHoursLater->format('H:i:s'))
            ->whereTime('start_time', '<=', $twoHoursLater->copy()->addMinutes(15)->format('H:i:s'))
            ->whereIn('status', [1])  // SCHEDULED
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No hay citas en las próximas 2 horas.');
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
                    new AppointmentReminderNotification($appointment, 'two_hours_before')
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

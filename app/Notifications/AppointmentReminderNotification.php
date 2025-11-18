<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class AppointmentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Appointment $appointment;
    protected string $reminderType; // 'day_before' o 'two_hours_before'

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment, string $reminderType = 'day_before')
    {
        $this->appointment = $appointment;
        $this->reminderType = $reminderType;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['whatsapp'];
    }

    /**
     * Enviar notificación por WhatsApp usando el canal personalizado
     */
    public function toWhatsApp(object $notifiable): array
    {
        $message = $this->buildMessage();
        
        return [
            'to' => $notifiable->phone,
            'message' => $message,
        ];
    }

    /**
     * Construir el mensaje de recordatorio
     */
    protected function buildMessage(): string
    {
        $patientName = $this->appointment->patient->user->name;
        $doctorName = $this->appointment->doctor->user->name;
        $date = Carbon::parse($this->appointment->date)->locale('es')->isoFormat('dddd D [de] MMMM');
        $time = Carbon::parse($this->appointment->start_time)->format('H:i');
        
        if ($this->reminderType === 'two_hours_before') {
            return "🏥 *Recordatorio de Cita*\n\n" .
                   "Hola {$patientName},\n\n" .
                   "Te recordamos que tienes una cita en *2 horas*:\n\n" .
                   "📅 Fecha: {$date}\n" .
                   "🕐 Hora: {$time}\n" .
                   "👨‍⚕️ Doctor/a: {$doctorName}\n\n" .
                   "Por favor, llega 10 minutos antes.\n\n" .
                   "Si necesitas cancelar o reprogramar, comunícate con nosotros lo antes posible.";
        }
        
        // day_before
        return "🏥 *Recordatorio de Cita*\n\n" .
               "Hola {$patientName},\n\n" .
               "Te recordamos que tienes una cita programada para mañana:\n\n" .
               "📅 Fecha: {$date}\n" .
               "🕐 Hora: {$time}\n" .
               "👨‍⚕️ Doctor/a: {$doctorName}\n\n" .
               "Por favor, confirma tu asistencia o comunícate con nosotros si necesitas cancelar o reprogramar.";
    }
}

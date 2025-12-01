<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class AppointmentCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Appointment $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['whatsapp'];
    }

    /**
     * Enviar notificación por WhatsApp usando plantilla
     */
    public function toWhatsApp(object $notifiable): array
    {
        return [
            'to' => $notifiable->phone,
            'template' => config('services.whatsapp.templates.appointment_created'),
            'language' => config('services.whatsapp.templates.language'),
            'parameters' => $this->getTemplateParameters(),
        ];
    }

    /**
     * Obtener parámetros para la plantilla de WhatsApp
     * Los parámetros deben coincidir con los {{1}}, {{2}}, etc. de tu plantilla
     */
    protected function getTemplateParameters(): array
    {
        $date = Carbon::parse($this->appointment->date)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY');
        $time = Carbon::parse($this->appointment->start_time)->format('H:i');
        
        // Obtener ubicación del gabinete si está asignado, sino del doctor
        $location = $this->getLocation();
        
        return [
            $this->appointment->patient->user->name,                    // {{1}} Nombre paciente
            $date,                                                       // {{2}} Fecha
            $time,                                                       // {{3}} Hora
            $this->appointment->doctor->user->name,                    // {{4}} Nombre doctor
            $this->appointment->doctor->speciality->name ?? 'Médico',  // {{5}} Especialidad
            $location,                                                  // {{6}} Ubicación
        ];
    }

    /**
     * Obtener la ubicación del consultorio
     */
    protected function getLocation(): string
    {
        // Si tiene gabinete asignado con dirección específica
        if ($this->appointment->cabinet && $this->appointment->cabinet->address) {
            $cabinet = $this->appointment->cabinet;
            $location = "Gabinete {$cabinet->name}";
            $location .= "\n{$cabinet->address}";
            
            if ($cabinet->floor) {
                $location .= "\nPiso: {$cabinet->floor}";
            }
            
            return $location;
        }
        
        // Usar dirección por defecto de la clínica (configurada en config/services.php)
        return config('services.whatsapp.default_location', "Jose C Paz 5723, San Martín\nBuenos Aires, Argentina");
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected ?string $apiUrl;
    protected ?string $accessToken;
    protected ?string $phoneNumberId;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->accessToken = config('services.whatsapp.access_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
    }

    /**
     * Enviar mensaje de texto a un número de WhatsApp
     */
    public function sendMessage(string $to, string $message): bool
    {
        try {
            $formattedPhone = $this->formatPhoneNumber($to);
            
            Log::info('WhatsApp sending message', [
                'original' => $to,
                'formatted' => $formattedPhone,
            ]);
            
            $response = Http::withToken($this->accessToken)
                ->post("{$this->apiUrl}/{$this->phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $formattedPhone,
                    'type' => 'text',
                    'text' => [
                        'body' => $message,
                    ],
                ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'to' => $to,
                    'response' => $response->json(),
                ]);
                return true;
            }

            Log::error('WhatsApp message failed', [
                'to' => $to,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp service error', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Enviar mensaje usando una plantilla (template)
     */
    public function sendTemplate(string $to, string $templateName, array $parameters = [], string $language = 'es_AR'): bool
    {
        try {
            $components = [];
            
            if (!empty($parameters)) {
                $components[] = [
                    'type' => 'body',
                    'parameters' => collect($parameters)->map(function ($param) {
                        return ['type' => 'text', 'text' => $param];
                    })->values()->toArray(),
                ];
            }

            $response = Http::withToken($this->accessToken)
                ->post("{$this->apiUrl}/{$this->phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->formatPhoneNumber($to),
                    'type' => 'template',
                    'template' => [
                        'name' => $templateName,
                        'language' => [
                            'code' => $language,
                        ],
                        'components' => $components,
                    ],
                ]);

            if ($response->successful()) {
                Log::info('WhatsApp template sent successfully', [
                    'to' => $to,
                    'template' => $templateName,
                    'response' => $response->json(),
                ]);
                return true;
            }

            Log::error('WhatsApp template failed', [
                'to' => $to,
                'template' => $templateName,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp service error', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Formatear número de teléfono al formato internacional
     * Asume números argentinos si no tienen código de país
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remover espacios, guiones y paréntesis
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);
        
        // Si ya tiene +, solo quitarlo
        if (str_starts_with($phone, '+')) {
            return substr($phone, 1);
        }
        
        // Si ya empieza con 54, está completo
        if (str_starts_with($phone, '54')) {
            return $phone;
        }
        
        // Si empieza con 0, quitarlo
        if (str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }
        
        // Si empieza con 15 (celular argentino), formato en BD: 1569975132
        // Debe quedar como: 54111569975132 (sin el 9 entre 54 y 11)
        if (str_starts_with($phone, '15')) {
            // Agregar 5411 y mantener el resto (15 + número)
            $phone = '5411' . $phone;
        } 
        // Si ya tiene código de área (ej: 1156997132)
        elseif (str_starts_with($phone, '11') && strlen($phone) >= 10) {
            $phone = '54' . $phone;
        }
        // Otros casos: agregar 54
        else {
            $phone = '54' . $phone;
        }
        
        return $phone;
    }

    /**
     * Enviar confirmación de turno al paciente
     */
    public function sendAppointmentConfirmationToPatient($appointment): bool
    {
        if (!$this->isConfigured()) {
            Log::info('WhatsApp: servicio no configurado');
            return false;
        }

        $patient = $appointment->patient;
        if (!$patient || !$patient->user || !$patient->user->phone) {
            Log::info('WhatsApp: paciente sin teléfono', [
                'appointment_id' => $appointment->id,
            ]);
            return false;
        }

        $phone = $patient->user->phone;
        $templateName = config('services.whatsapp.templates.appointment_created', 'confirmacion_de_turno');
        $language = config('services.whatsapp.templates.language', 'es');

        // Formatear fecha y hora
        $date = \Carbon\Carbon::parse($appointment->date)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY');
        $time = \Carbon\Carbon::parse($appointment->start_time)->format('H:i');
        
        // Parámetros del template
        $parameters = [
            $patient->user->name,                              // 1. Nombre del paciente
            $date,                                             // 2. Fecha
            $time,                                             // 3. Hora
            $appointment->doctor->user->name ?? 'Tu médico',  // 4. Nombre del doctor
            $appointment->doctor->speciality->name ?? 'Kinesiología', // 5. Especialidad
            config('services.whatsapp.default_location', 'Jose C Paz 5723, San Martín'), // 6. Ubicación
        ];

        Log::info('WhatsApp: enviando confirmación de turno', [
            'appointment_id' => $appointment->id,
            'phone' => $phone,
            'template' => $templateName,
        ]);

        return $this->sendTemplate($phone, $templateName, $parameters, $language);
    }

    /**
     * Verificar si el servicio está configurado correctamente
     */
    public function isConfigured(): bool
    {
        return !empty($this->accessToken) 
            && !empty($this->phoneNumberId) 
            && !empty($this->apiUrl);
    }
}

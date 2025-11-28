<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Envía al paciente la confirmación del turno por WhatsApp.
     */
    public function sendAppointmentConfirmationToPatient(Appointment $appointment): void
    {
        Log::info('WhatsApp: iniciando envío de confirmación', [
            'appointment_id' => $appointment->id,
            'enabled' => Config::get('services.whatsapp.enabled', false),
        ]);
        
        if (! Config::get('services.whatsapp.enabled', false)) {
            Log::info('WhatsApp: servicio deshabilitado');
            return;
        }

        $patientUser = $appointment->patient?->user;

        if (! $patientUser || ! $patientUser->phone) {
            Log::info('WhatsApp: paciente sin teléfono, se omite envío', [
                'appointment_id' => $appointment->id,
            ]);

            return;
        }

        $to = $this->normalizePhone($patientUser->phone);

        if ($to === null) {
            Log::warning('WhatsApp: teléfono inválido para paciente', [
                'appointment_id' => $appointment->id,
                'raw_phone' => $patientUser->phone,
            ]);

            return;
        }

        $config = Config::get('services.whatsapp', []);

        if (! empty($config['template_name'])) {
            $this->sendTemplateMessage($to, $appointment, $config);
        } else {
            $message = $this->buildAppointmentMessage($appointment);
            $this->sendTextMessage($to, $message, $config);
        }
    }

    /**
     * Normaliza un teléfono al formato 54XXXXXXXXXX.
     * Maneja diferentes formatos de entrada:
     * - 54XXXXXXXXXX (ya normalizado)
     * - +54XXXXXXXXXX (con +)
     * - 0XXXXXXXXXX (código de área con 0)
     * - XXXXXXXXXX (sin código de país)
     */
    protected function normalizePhone(string $rawPhone): ?string
    {
        // Eliminar todos los caracteres no numéricos
        $digits = preg_replace('/\D+/', '', $rawPhone);

        if (! $digits) {
            Log::warning('WhatsApp: teléfono vacío después de limpiar', [
                'raw_phone' => $rawPhone,
            ]);
            return null;
        }

        // Si ya tiene el código de país 54
        if (str_starts_with($digits, '54')) {
            // Verificar longitud válida (54 + código de área + número = 12-13 dígitos)
            if (strlen($digits) >= 12 && strlen($digits) <= 13) {
                return $digits;
            }
            Log::warning('WhatsApp: longitud inválida para número con código 54', [
                'raw_phone' => $rawPhone,
                'digits' => $digits,
                'length' => strlen($digits),
            ]);
            return null;
        }

        // Si empieza con 0, quitarlo (código de área nacional)
        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        // Verificar longitud del número local (debe ser 10 dígitos: código área + número)
        if (strlen($digits) !== 10) {
            Log::warning('WhatsApp: longitud inválida para número argentino', [
                'raw_phone' => $rawPhone,
                'digits' => $digits,
                'length' => strlen($digits),
            ]);
            return null;
        }

        // Remover el 15 si está presente después del código de área
        // Formato: 011-15-XXXX-XXXX o 0XX-15-XXX-XXXX
        if (strlen($digits) == 10 && substr($digits, 2, 2) === '15') {
            $digits = substr($digits, 0, 2) . substr($digits, 4);
        }

        // Agregar código de país
        return '54'.$digits;
    }

    /**
     * Construye el cuerpo del mensaje con los datos del turno.
     */
    protected function buildAppointmentMessage(Appointment $appointment): string
    {
        $patientName = $appointment->patient?->user?->name ?? 'Paciente';
        $doctorName = $appointment->doctor?->user?->name ?? 'Tu médico';
        $speciality = $appointment->doctor?->speciality?->name;
        $cabinetName = $appointment->cabinet?->name;
        $cabinetDesc = $appointment->cabinet?->description;
        $date = $appointment->date?->format('l d \\d\\e F Y');

        $startTime = substr($appointment->start_time, 0, 5);
        $endTime = substr($appointment->end_time, 0, 5);

        $clinicName = Config::get('app.name', 'Tu centro médico');
        $clinicAddress = env('CLINIC_ADDRESS', 'Dirección del consultorio');
        $clinicMapsUrl = env('CLINIC_MAPS_URL');

        $lines = [
            '🏥 *'.$clinicName.'*',
            '',
            'Hola *'.$patientName.'* 👋',
            'Te confirmamos tu turno:',
            '',
            '📅 *Fecha*: '.$date,
            '🕒 *Horario*: '.$startTime.' - '.$endTime,
            '👨‍⚕️ *Profesional*: '.$doctorName,
        ];

        if ($speciality) {
            $lines[] = '🩺 *Especialidad*: '.$speciality;
        }

        if ($cabinetName) {
            $lines[] = '🚪 *Gabinete*: '.$cabinetName;
        }

        if ($cabinetDesc) {
            $lines[] = 'ℹ️ '.$cabinetDesc;
        }

        $lines[] = '';
        $lines[] = '📍 *Ubicación*: '.$clinicAddress;

        if ($clinicMapsUrl) {
            $lines[] = '🗺️ Cómo llegar: '.$clinicMapsUrl;
        }

        $lines[] = '';
        $lines[] = 'Si no podés asistir, por favor avisá con anticipación para reprogramar.';

        return implode("\n", $lines);
    }

    /**
     * Mensaje de texto simple (solo si ya hay ventana activa).
     */
    protected function sendTextMessage(string $to, string $body, array $config = []): void
    {
        $token = $config['token'] ?? env('WHATSAPP_TOKEN');
        $phoneNumberId = $config['phone_number_id'] ?? env('WHATSAPP_PHONE_NUMBER_ID');
        $baseUrl = rtrim($config['api_url'] ?? env('WHATSAPP_API_URL', 'https://graph.facebook.com/v21.0'), '/');

        if (! $token || ! $phoneNumberId) {
            Log::warning('WhatsApp: servicio no configurado correctamente (token o phone_number_id faltante)');

            return;
        }

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->post($baseUrl.'/'.$phoneNumberId.'/messages', [
                    'messaging_product' => 'whatsapp',
                    'to' => $to,
                    'type' => 'text',
                    'text' => [
                        'preview_url' => false,
                        'body' => $body,
                    ],
                ]);

            if ($response->failed()) {
                Log::error('WhatsApp: error al enviar mensaje de texto', [
                    'to' => $to,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            } else {
                Log::info('WhatsApp: mensaje de texto enviado correctamente', [
                    'to' => $to,
                    'response' => $response->json(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('WhatsApp: excepción al enviar mensaje de texto', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Mensaje con template aprobado (business initiated, siempre se entrega).
     */
    protected function sendTemplateMessage(string $to, Appointment $appointment, array $config = []): void
    {
        $token = $config['token'] ?? env('WHATSAPP_TOKEN');
        $phoneNumberId = $config['phone_number_id'] ?? env('WHATSAPP_PHONE_NUMBER_ID');
        $baseUrl = rtrim($config['api_url'] ?? env('WHATSAPP_API_URL', 'https://graph.facebook.com/v21.0'), '/');
        $templateName = $config['template_name'] ?? env('WHATSAPP_TEMPLATE_NAME', 'hello_world');
        $templateLanguage = $config['template_language'] ?? env('WHATSAPP_TEMPLATE_LANGUAGE', 'en_US');

        if (! $token || ! $phoneNumberId || ! $templateName) {
            Log::warning('WhatsApp: template no configurado correctamente');

            return;
        }

        try {
            Log::info('WhatsApp: enviando mensaje con template', [
                'to' => $to,
                'template' => $templateName,
            ]);

            // Construir el payload del template
            $templatePayload = [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => $templateLanguage,
                    ],
                ],
            ];

            // Solo agregar parámetros si el template NO es hello_world
            // hello_world es un template estándar sin parámetros
            // Para templates personalizados en producción, descomentar y ajustar los parámetros
            if ($templateName !== 'hello_world') {
                $patientName = $appointment->patient?->user?->name ?? 'Paciente';
                $doctorName = $appointment->doctor?->user?->name ?? 'Tu médico';
                $date = $appointment->date?->format('d/m/Y');
                $startTime = substr($appointment->start_time, 0, 5);
                $dateTime = "{$date} a las {$startTime}";

                // Ajustar estos parámetros según tu template personalizado en Meta
                $templatePayload['template']['components'] = [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $patientName,
                            ],
                            [
                                'type' => 'text',
                                'text' => $dateTime,
                            ],
                            [
                                'type' => 'text',
                                'text' => $doctorName,
                            ],
                        ],
                    ],
                ];
            }

            $response = Http::withToken($token)
                ->acceptJson()
                ->post($baseUrl.'/'.$phoneNumberId.'/messages', $templatePayload);

            if ($response->failed()) {
                Log::error('WhatsApp: error al enviar mensaje con template', [
                    'to' => $to,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            } else {
                Log::info('WhatsApp: mensaje con template enviado correctamente', [
                    'to' => $to,
                    'response' => $response->json(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('WhatsApp: excepción al enviar mensaje con template', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

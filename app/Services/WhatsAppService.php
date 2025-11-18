<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $apiUrl;
    protected string $accessToken;
    protected string $phoneNumberId;

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
    public function sendTemplate(string $to, string $templateName, array $parameters = []): bool
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
                            'code' => 'es',
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
     * Verificar si el servicio está configurado correctamente
     */
    public function isConfigured(): bool
    {
        return !empty($this->accessToken) 
            && !empty($this->phoneNumberId) 
            && !empty($this->apiUrl);
    }
}

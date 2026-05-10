<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EvolutionService
{
    private string $apiUrl = '';

    private string $apiKey = '';

    public function __construct()
    {
        $this->apiUrl = rtrim(config('services.evolution.api_url') ?? '', '/');
        $this->apiKey = config('services.evolution.api_key') ?? '';
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiUrl) && ! empty($this->apiKey);
    }

    public function createInstance(string $instanceName): bool
    {
        try {
            $response = $this->http()->post('/instance/create', [
                'instanceName' => $instanceName,
                'qrcode' => true,
                'integration' => 'WHATSAPP-BAILEYS',
            ]);

            return $response->successful() || $response->status() === 409;
        } catch (\Exception $e) {
            Log::error('Evolution: error creando instancia', ['instance' => $instanceName, 'error' => $e->getMessage()]);

            return false;
        }
    }

    /** @return array{qrcode?: string, base64?: string}|null */
    public function getQrCode(string $instanceName): ?array
    {
        try {
            // Asegurar que la instancia existe
            $this->createInstance($instanceName);

            $response = $this->http()->get("/instance/connect/{$instanceName}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Evolution: error obteniendo QR', ['instance' => $instanceName, 'error' => $e->getMessage()]);

            return null;
        }
    }

    public function getConnectionState(string $instanceName): string
    {
        try {
            $response = $this->http()->get("/instance/connectionState/{$instanceName}");

            if ($response->successful()) {
                return $response->json('instance.state') ?? 'close';
            }

            return 'close';
        } catch (\Exception $e) {
            return 'close';
        }
    }

    public function isConnected(string $instanceName): bool
    {
        return $this->getConnectionState($instanceName) === 'open';
    }

    public function sendText(string $instanceName, string $phone, string $message): bool
    {
        try {
            $response = $this->http()->post("/message/sendText/{$instanceName}", [
                'number' => $this->formatPhone($phone),
                'text' => $message,
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Evolution: error enviando mensaje', [
                'instance' => $instanceName,
                'phone' => $phone,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Evolution: excepción enviando mensaje', ['error' => $e->getMessage()]);

            return false;
        }
    }

    public function logout(string $instanceName): bool
    {
        try {
            $response = $this->http()->delete("/instance/logout/{$instanceName}");

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[\s\-\(\)\+]/', '', $phone);

        if (str_starts_with($phone, '54')) {
            return $phone;
        }

        if (str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }

        return '54'.$phone;
    }

    private function http(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::baseUrl($this->apiUrl)
            ->withHeaders(['apikey' => $this->apiKey])
            ->timeout(15);
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class DiagnoseWhatsApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:diagnose';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnostica la configuración de WhatsApp';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Diagnóstico de Configuración de WhatsApp ===');
        $this->newLine();

        // 1. Verificar API URL
        $apiUrl = Config::get('services.whatsapp.api_url');
        $this->checkConfig('API URL', $apiUrl, 'WHATSAPP_API_URL');

        // 2. Verificar Access Token
        $token = Config::get('services.whatsapp.access_token');
        $this->checkConfig('Access Token', $token, 'WHATSAPP_ACCESS_TOKEN', true);

        // 3. Verificar Phone Number ID
        $phoneNumberId = Config::get('services.whatsapp.phone_number_id');
        $this->checkConfig('Phone Number ID', $phoneNumberId, 'WHATSAPP_PHONE_NUMBER_ID');

        // 4. Verificar Templates
        $templateName = Config::get('services.whatsapp.templates.appointment_created');
        $this->checkConfig('Template (appointment_created)', $templateName, 'WHATSAPP_TEMPLATE_APPOINTMENT_CREATED', false, true);

        $templateLanguage = Config::get('services.whatsapp.templates.language');
        $this->info("Template Language: " . ($templateLanguage ?: 'es_AR (default)'));
        
        // 5. Verificar ubicación
        $location = Config::get('services.whatsapp.default_location');
        $this->info("Ubicación por defecto: " . ($location ?: 'Jose C Paz 5723, San Martín, Buenos Aires, Argentina'));

        $this->newLine();
        
        // Resumen
        if (!$phoneNumberId || !$token) {
            $this->error('❌ Configuración incompleta. Faltan credenciales obligatorias.');
            $this->info('Agrega estas variables a tu archivo .env:');
            if (!$phoneNumberId) $this->line('WHATSAPP_PHONE_NUMBER_ID=tu_phone_number_id');
            if (!$token) $this->line('WHATSAPP_ACCESS_TOKEN=tu_token_de_acceso_de_meta');
        } else {
            $this->info('✅ Configuración completa');
            
            if (!$templateName) {
                $this->warn('ℹ️  No hay template configurado. Se usarán mensajes de texto simples.');
                $this->warn('   Nota: Los mensajes de texto solo funcionan si ya existe una conversación activa.');
                $this->info('   Recomendación: Configura un template aprobado en Meta para garantizar la entrega.');
            } else {
                $this->info('✅ Template configurado: ' . $templateName);
            }
        }

        $this->newLine();
        $this->info('Para ver los logs de WhatsApp, ejecuta:');
        $this->line('tail -f storage/logs/laravel.log | grep WhatsApp');
        
        return Command::SUCCESS;
    }

    private function checkConfig($label, $value, $envVar, $isSensitive = false, $optional = false)
    {
        $icon = $value ? '✅' : ($optional ? 'ℹ️ ' : '❌');
        $status = $value ? 'configurado' : ($optional ? 'no configurado (opcional)' : 'NO CONFIGURADO');
        
        if ($isSensitive && $value) {
            $displayValue = substr($value, 0, 10) . '...' . substr($value, -4);
        } else {
            $displayValue = $value ?: 'vacío';
        }
        
        $this->line("{$icon} {$label}: {$status}");
        if (!$optional) {
            $this->line("   Variable .env: {$envVar}");
        }
        if (!$value && !$optional) {
            $this->line("   Valor actual: {$displayValue}");
        }
        $this->newLine();
    }
}

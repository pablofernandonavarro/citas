<?php

namespace App\Services;

use App\Models\Tenant;

class PlanService
{
    /**
     * Límites y características por plan
     */
    protected const PLAN_LIMITS = [
        'inicial' => [
            'max_doctors' => 2,
            'max_cabinets' => null, // ilimitado - cada doctor puede tener múltiples gabinetes para atender simultáneamente
            'max_patients' => 500,
            'features' => [
                'basic_appointments' => true,
                'patient_management' => true,
                'basic_calendar' => true,
                'email_notifications' => true,
                'whatsapp_notifications' => false,
                'advanced_reports' => false,
                'custom_branding' => false,
                'api_access' => false,
                'priority_support' => false,
            ],
        ],
        'profesional' => [
            'max_doctors' => 5,
            'max_cabinets' => null, // ilimitado - cada doctor puede tener múltiples gabinetes para atender simultáneamente
            'max_patients' => 2000,
            'features' => [
                'basic_appointments' => true,
                'patient_management' => true,
                'basic_calendar' => true,
                'email_notifications' => true,
                'whatsapp_notifications' => true,
                'advanced_reports' => true,
                'custom_branding' => false,
                'api_access' => false,
                'priority_support' => true,
            ],
        ],
        'clinica' => [
            'max_doctors' => null, // ilimitado
            'max_cabinets' => null, // ilimitado
            'max_patients' => null, // ilimitado
            'features' => [
                'basic_appointments' => true,
                'patient_management' => true,
                'basic_calendar' => true,
                'email_notifications' => true,
                'whatsapp_notifications' => true,
                'advanced_reports' => true,
                'custom_branding' => true,
                'api_access' => true,
                'priority_support' => true,
            ],
        ],
        'demo' => [
            'max_doctors' => 1,
            'max_cabinets' => 1,
            'max_patients' => 50,
            'features' => [
                'basic_appointments' => true,
                'patient_management' => true,
                'basic_calendar' => true,
                'email_notifications' => true,
                'whatsapp_notifications' => false,
                'advanced_reports' => false,
                'custom_branding' => false,
                'api_access' => false,
                'priority_support' => false,
            ],
        ],
        // Legacy
        'premium' => [
            'max_doctors' => null,
            'max_cabinets' => null,
            'max_patients' => null,
            'features' => [
                'basic_appointments' => true,
                'patient_management' => true,
                'basic_calendar' => true,
                'email_notifications' => true,
                'whatsapp_notifications' => true,
                'advanced_reports' => true,
                'custom_branding' => true,
                'api_access' => true,
                'priority_support' => true,
            ],
        ],
    ];

    /**
     * Obtener el plan del tenant actual
     */
    public static function getCurrentPlan(): ?string
    {
        if (!tenancy()->initialized) {
            return null;
        }

        return tenancy()->tenant->plan ?? 'inicial';
    }

    /**
     * Verificar si el tenant puede realizar una acción
     */
    public static function can(string $feature): bool
    {
        $plan = self::getCurrentPlan();

        if (!$plan || !isset(self::PLAN_LIMITS[$plan])) {
            return false;
        }

        return self::PLAN_LIMITS[$plan]['features'][$feature] ?? false;
    }

    /**
     * Obtener límite de un recurso
     */
    public static function getLimit(string $resource): ?int
    {
        $plan = self::getCurrentPlan();

        if (!$plan || !isset(self::PLAN_LIMITS[$plan])) {
            return 0;
        }

        return self::PLAN_LIMITS[$plan][$resource] ?? 0;
    }

    /**
     * Verificar si se alcanzó el límite de un recurso
     */
    public static function hasReachedLimit(string $resource, int $currentCount): bool
    {
        $limit = self::getLimit($resource);

        // null = ilimitado
        if ($limit === null) {
            return false;
        }

        return $currentCount >= $limit;
    }

    /**
     * Obtener todas las características del plan actual
     */
    public static function getFeatures(): array
    {
        $plan = self::getCurrentPlan();

        if (!$plan || !isset(self::PLAN_LIMITS[$plan])) {
            return [];
        }

        return self::PLAN_LIMITS[$plan]['features'];
    }

    /**
     * Obtener todos los límites del plan actual
     */
    public static function getLimits(): array
    {
        $plan = self::getCurrentPlan();

        if (!$plan || !isset(self::PLAN_LIMITS[$plan])) {
            return [];
        }

        return [
            'max_doctors' => self::PLAN_LIMITS[$plan]['max_doctors'],
            'max_cabinets' => self::PLAN_LIMITS[$plan]['max_cabinets'],
            'max_patients' => self::PLAN_LIMITS[$plan]['max_patients'],
        ];
    }

    /**
     * Obtener nombre legible del plan
     */
    public static function getPlanName(?string $plan = null): string
    {
        $plan = $plan ?? self::getCurrentPlan();

        return match ($plan) {
            'inicial' => 'Plan Inicial',
            'profesional' => 'Plan Profesional',
            'clinica' => 'Plan Clínica',
            'demo' => 'Plan Demo',
            'premium' => 'Plan Premium',
            default => 'Plan Desconocido',
        };
    }
}

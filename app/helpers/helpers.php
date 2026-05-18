<?php

// app/Helpers/helpers.php

if (! function_exists('mi_helper')) {
    function mi_helper($val)
    {
        return strtoupper($val);
    }
}

if (! function_exists('format_phone_compact')) {
    function format_phone_compact(?string $phone): string
    {
        return \App\Helpers\PhoneHelper::formatCompact($phone);
    }
}

if (! function_exists('format_phone')) {
    function format_phone(?string $phone): string
    {
        return \App\Helpers\PhoneHelper::format($phone);
    }
}

if (! function_exists('normalize_phone')) {
    function normalize_phone(?string $phone): ?string
    {
        return \App\Helpers\PhoneHelper::normalize($phone);
    }
}

if (! function_exists('plan_can')) {
    /**
     * Verificar si el plan actual tiene acceso a una característica
     */
    function plan_can(string $feature): bool
    {
        return \App\Services\PlanService::can($feature);
    }
}

if (! function_exists('plan_limit')) {
    /**
     * Obtener el límite de un recurso para el plan actual
     */
    function plan_limit(string $resource): ?int
    {
        return \App\Services\PlanService::getLimit($resource);
    }
}

if (! function_exists('plan_reached_limit')) {
    /**
     * Verificar si se alcanzó el límite de un recurso
     */
    function plan_reached_limit(string $resource, int $currentCount): bool
    {
        return \App\Services\PlanService::hasReachedLimit($resource, $currentCount);
    }
}

if (! function_exists('current_plan')) {
    /**
     * Obtener el nombre del plan actual
     */
    function current_plan(): string
    {
        return \App\Services\PlanService::getPlanName();
    }
}

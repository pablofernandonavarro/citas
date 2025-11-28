<?php

use App\Helpers\PhoneHelper;

if (!function_exists('format_phone')) {
    /**
     * Formatea un número de teléfono al formato completo.
     * 
     * @param string|null $phone
     * @return string
     */
    function format_phone(?string $phone): string
    {
        return PhoneHelper::format($phone);
    }
}

if (!function_exists('format_phone_compact')) {
    /**
     * Formatea un número de teléfono al formato compacto.
     * 
     * @param string|null $phone
     * @return string
     */
    function format_phone_compact(?string $phone): string
    {
        return PhoneHelper::formatCompact($phone);
    }
}

if (!function_exists('normalize_phone')) {
    /**
     * Normaliza un número de teléfono eliminando formato.
     * 
     * @param string|null $phone
     * @return string|null
     */
    function normalize_phone(?string $phone): ?string
    {
        return PhoneHelper::normalize($phone);
    }
}

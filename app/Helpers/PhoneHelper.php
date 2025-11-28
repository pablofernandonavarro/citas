<?php

namespace App\Helpers;

class PhoneHelper
{
    /**
     * Formatea un número de teléfono argentino al formato visual uniforme.
     * 
     * @param string|null $phone Número de teléfono sin formato
     * @return string Número formateado o string vacío si es inválido
     * 
     * Ejemplos:
     * - 1112345678 → +54 11 1234-5678
     * - 3514567890 → +54 351 456-7890
     * - 5491112345678 → +54 911 1234-5678
     */
    public static function format(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        // Eliminar todos los caracteres no numéricos
        $digits = preg_replace('/\D+/', '', $phone);

        if (empty($digits)) {
            return $phone; // Devolver original si no hay dígitos
        }

        // Remover el código de país 54 si está presente
        if (str_starts_with($digits, '54')) {
            $digits = substr($digits, 2);
        }

        // Remover el 0 inicial del código de área si está presente
        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        // Validar longitud (debe ser 10 dígitos para Argentina)
        if (strlen($digits) < 10) {
            return $phone; // Devolver original si es muy corto
        }

        // Extraer partes del número
        // Para números de Buenos Aires (11): 11 + 8 dígitos
        // Para otros códigos de área: 3 o 4 dígitos + resto
        
        if (str_starts_with($digits, '11')) {
            // Buenos Aires: +54 11 1234-5678
            $areaCode = '11';
            $firstPart = substr($digits, 2, 4);
            $secondPart = substr($digits, 6, 4);
            return "+54 {$areaCode} {$firstPart}-{$secondPart}";
        } elseif (str_starts_with($digits, '9')) {
            // Número con 9 (celular con código de país): +54 9XX XXX-XXXX
            $areaCode = substr($digits, 0, 4); // 9 + 3 dígitos
            $firstPart = substr($digits, 4, 3);
            $secondPart = substr($digits, 7, 4);
            return "+54 {$areaCode} {$firstPart}-{$secondPart}";
        } else {
            // Otros códigos de área (3 dígitos): +54 351 456-7890
            $areaCode = substr($digits, 0, 3);
            $firstPart = substr($digits, 3, 3);
            $secondPart = substr($digits, 6, 4);
            return "+54 {$areaCode} {$firstPart}-{$secondPart}";
        }
    }

    /**
     * Formatea un número para mostrar en listados (versión más compacta).
     * 
     * @param string|null $phone Número de teléfono
     * @return string Número formateado compacto
     * 
     * Ejemplo: 1112345678 → (11) 1234-5678
     */
    public static function formatCompact(?string $phone): string
    {
        if (empty($phone)) {
            return '-';
        }

        // Eliminar todos los caracteres no numéricos
        $digits = preg_replace('/\D+/', '', $phone);

        if (empty($digits)) {
            return $phone;
        }

        // Remover el código de país 54 si está presente
        if (str_starts_with($digits, '54')) {
            $digits = substr($digits, 2);
        }

        // Remover el 0 inicial si está presente
        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        if (strlen($digits) < 10) {
            return $phone;
        }

        if (str_starts_with($digits, '11')) {
            // Buenos Aires: (11) 1234-5678
            $areaCode = '11';
            $firstPart = substr($digits, 2, 4);
            $secondPart = substr($digits, 6, 4);
            return "({$areaCode}) {$firstPart}-{$secondPart}";
        } elseif (str_starts_with($digits, '9')) {
            // Con 9: (9XX) XXX-XXXX
            $areaCode = substr($digits, 0, 4);
            $firstPart = substr($digits, 4, 3);
            $secondPart = substr($digits, 7, 4);
            return "({$areaCode}) {$firstPart}-{$secondPart}";
        } else {
            // Otros: (351) 456-7890
            $areaCode = substr($digits, 0, 3);
            $firstPart = substr($digits, 3, 3);
            $secondPart = substr($digits, 6, 4);
            return "({$areaCode}) {$firstPart}-{$secondPart}";
        }
    }

    /**
     * Normaliza un número de teléfono al formato de almacenamiento.
     * Elimina formato y mantiene solo dígitos.
     * 
     * @param string|null $phone Número de teléfono con formato
     * @return string|null Número normalizado (solo dígitos)
     * 
     * Ejemplo: +54 11 1234-5678 → 1112345678
     */
    public static function normalize(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Eliminar todos los caracteres no numéricos
        $digits = preg_replace('/\D+/', '', $phone);

        if (empty($digits)) {
            return null;
        }

        // Remover el código de país 54 si está presente
        if (str_starts_with($digits, '54')) {
            $digits = substr($digits, 2);
        }

        // Remover el 0 inicial si está presente
        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        // Remover el 15 después del código de área si está presente
        if (strlen($digits) == 12 && substr($digits, 2, 2) === '15') {
            $digits = substr($digits, 0, 2) . substr($digits, 4);
        }

        return $digits ?: null;
    }
}

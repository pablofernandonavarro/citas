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

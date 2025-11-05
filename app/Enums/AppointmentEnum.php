<?php

namespace App\Enums;

enum AppointmentEnum: int
{
    case SCHEDULED = 1;
    case COMPLETED = 2;
    case CANCELED = 3;

    public function label(): string
    {
        return match($this) {
            self::SCHEDULED => 'Programado',
            self::COMPLETED => 'Completado',
            self::CANCELED => 'Cancelado',
        };
    }
    public function color(): string
    {
        return match($this) {
            self::SCHEDULED => 'blue',
            self::COMPLETED => 'green',
            self::CANCELED => 'red',
        };
    }

}

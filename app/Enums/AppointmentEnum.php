<?php

namespace App\Enums;

enum AppointmentEnum: int
{
    case SCHEDULED = 1;
    case COMPLETED = 2;
    case CANCELED = 3;
    case AVAILABLE = 4;

    public function label(): string
    {
        return match($this) {
            self::SCHEDULED => 'Programado',
            self::COMPLETED => 'Completado',
            self::CANCELED => 'Cancelado',
            self::AVAILABLE => 'Disponible',
        };
    }
    public function color(): string
    {
        return match($this) {
            self::SCHEDULED => 'blue',
            self::COMPLETED => 'green',
            self::CANCELED => 'red',
            self::AVAILABLE => 'yellow',
        };
    }
    public function isEditable(): bool
    {
        return  $this === self::SCHEDULED;
    }
    
    public function isAvailable(): bool
    {
        return $this === self::AVAILABLE;
    }

}

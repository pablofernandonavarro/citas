<?php

namespace App\Enums;

enum AppointmentEnum: int
{
    case SCHEDULED = 1;
    case COMPLETED = 2;
    case CANCELED = 3;

}

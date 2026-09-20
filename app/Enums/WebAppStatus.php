<?php

namespace App\Enums;

enum WebAppStatus: string
{
    case Online = 'online';
    case UnderMaintenance = 'under_maintenance';
}

<?php

namespace App\Enum;

enum TicketType: string
{
    case FLIGHT = 'flight';
    case TRAIN  = 'train';
    case BUS    = 'bus';
}

<?php

namespace App\Enums;

enum ParkingType: string
{
    case GARAGE = 'garage';
    case CARPORT = 'carport';
    case DRIVEWAY = 'oprit';
    case PARKING_SPOT = 'parkeerplaats';
    case UNDERGROUND = 'ondergronds';
    case COVERED = 'overdekt';
    case STREET = 'straat';

    public function label(): string
    {
        return match($this) {
            self::GARAGE => 'Garage',
            self::CARPORT => 'Carport',
            self::DRIVEWAY => 'Oprit',
            self::PARKING_SPOT => 'Parkeerplaats',
            self::UNDERGROUND => 'Ondergrondse parking',
            self::COVERED => 'Overdekte parking',
            self::STREET => 'Straatparkeren',
        };
    }
}

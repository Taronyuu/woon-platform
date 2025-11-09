<?php

namespace App\Enums;

enum OutdoorSpaceType: string
{
    case GARDEN = 'tuin';
    case BALCONY = 'balkon';
    case ROOF_TERRACE = 'dakterras';
    case TERRACE = 'terras';
    case PATIO = 'patio';
    case COURTYARD = 'binnenplaats';
    case VERANDA = 'veranda';
    case FRONT_GARDEN = 'voortuin';
    case BACK_GARDEN = 'achtertuin';

    public function label(): string
    {
        return match($this) {
            self::GARDEN => 'Tuin',
            self::BALCONY => 'Balkon',
            self::ROOF_TERRACE => 'Dakterras',
            self::TERRACE => 'Terras',
            self::PATIO => 'Patio',
            self::COURTYARD => 'Binnenplaats',
            self::VERANDA => 'Veranda',
            self::FRONT_GARDEN => 'Voortuin',
            self::BACK_GARDEN => 'Achtertuin',
        };
    }
}

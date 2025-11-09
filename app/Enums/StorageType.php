<?php

namespace App\Enums;

enum StorageType: string
{
    case BASEMENT = 'kelder';
    case ATTIC = 'zolder';
    case SHED = 'schuur';
    case BOX_ROOM = 'berging';
    case EXTERNAL = 'externe_berging';

    public function label(): string
    {
        return match($this) {
            self::BASEMENT => 'Kelder',
            self::ATTIC => 'Zolder',
            self::SHED => 'Schuur',
            self::BOX_ROOM => 'Berging',
            self::EXTERNAL => 'Externe berging',
        };
    }
}

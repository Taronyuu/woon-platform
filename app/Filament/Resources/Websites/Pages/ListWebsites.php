<?php

namespace App\Filament\Resources\Websites\Pages;

use App\Filament\Resources\Websites\WebsiteResource;
use Filament\Resources\Pages\ListRecords;

class ListWebsites extends ListRecords
{
    protected static string $resource = WebsiteResource::class;
}

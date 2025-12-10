<?php

namespace App\Filament\Resources\CrawledPages\Pages;

use App\Filament\Resources\CrawledPages\CrawledPageResource;
use Filament\Resources\Pages\ListRecords;

class ListCrawledPages extends ListRecords
{
    protected static string $resource = CrawledPageResource::class;
}

<?php

namespace App\Filament\Resources\CrawlJobs\Pages;

use App\Filament\Resources\CrawlJobs\CrawlJobResource;
use Filament\Resources\Pages\ListRecords;

class ListCrawlJobs extends ListRecords
{
    protected static string $resource = CrawlJobResource::class;
}

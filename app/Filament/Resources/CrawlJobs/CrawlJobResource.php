<?php

namespace App\Filament\Resources\CrawlJobs;

use App\Filament\Resources\CrawlJobs\Pages\ListCrawlJobs;
use App\Filament\Resources\CrawlJobs\Tables\CrawlJobsTable;
use App\Models\CrawlJob;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CrawlJobResource extends Resource
{
    protected static ?string $model = CrawlJob::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowPath;

    protected static ?string $navigationLabel = 'Crawl jobs';

    protected static ?string $modelLabel = 'Crawl job';

    protected static ?string $pluralModelLabel = 'Crawl jobs';

    protected static UnitEnum|string|null $navigationGroup = 'Crawler';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return CrawlJobsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCrawlJobs::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

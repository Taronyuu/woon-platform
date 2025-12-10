<?php

namespace App\Filament\Resources\CrawledPages;

use App\Filament\Resources\CrawledPages\Pages\ListCrawledPages;
use App\Filament\Resources\CrawledPages\Tables\CrawledPagesTable;
use App\Models\CrawledPage;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CrawledPageResource extends Resource
{
    protected static ?string $model = CrawledPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Crawled Pages';

    protected static ?string $modelLabel = 'Crawled Page';

    protected static ?string $pluralModelLabel = 'Crawled Pages';

    protected static UnitEnum|string|null $navigationGroup = 'Crawler';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return CrawledPagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCrawledPages::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

<?php

namespace App\Filament\Resources\Websites;

use App\Filament\Resources\Websites\Pages\ListWebsites;
use App\Filament\Resources\Websites\Tables\WebsitesTable;
use App\Models\Website;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WebsiteResource extends Resource
{
    protected static ?string $model = Website::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $navigationLabel = 'Websites';

    protected static ?string $modelLabel = 'Website';

    protected static ?string $pluralModelLabel = 'Websites';

    protected static UnitEnum|string|null $navigationGroup = 'Crawler';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return WebsitesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWebsites::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

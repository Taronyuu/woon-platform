<?php

namespace App\Filament\Resources\SearchProfiles;

use App\Filament\Resources\SearchProfiles\Pages\EditSearchProfile;
use App\Filament\Resources\SearchProfiles\Pages\ListSearchProfiles;
use App\Filament\Resources\SearchProfiles\Schemas\SearchProfileForm;
use App\Filament\Resources\SearchProfiles\Tables\SearchProfilesTable;
use App\Models\SearchProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SearchProfileResource extends Resource
{
    protected static ?string $model = SearchProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?string $navigationLabel = 'Zoekprofielen';

    protected static ?string $modelLabel = 'Zoekprofiel';

    protected static ?string $pluralModelLabel = 'Zoekprofielen';

    public static function form(Schema $schema): Schema
    {
        return SearchProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SearchProfilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSearchProfiles::route('/'),
            'edit' => EditSearchProfile::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

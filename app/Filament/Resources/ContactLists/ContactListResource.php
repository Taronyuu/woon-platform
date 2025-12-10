<?php

namespace App\Filament\Resources\ContactLists;

use App\Filament\Resources\ContactLists\Pages\ListContactLists;
use App\Filament\Resources\ContactLists\Tables\ContactListsTable;
use App\Models\ContactList;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContactListResource extends Resource
{
    protected static ?string $model = ContactList::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelopeOpen;

    protected static ?string $navigationLabel = 'Contactlijst';

    protected static ?string $modelLabel = 'Contact';

    protected static ?string $pluralModelLabel = 'Contactlijst';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return ContactListsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactLists::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

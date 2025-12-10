<?php

namespace App\Filament\Resources\PropertyInquiries;

use App\Filament\Resources\PropertyInquiries\Pages\EditPropertyInquiry;
use App\Filament\Resources\PropertyInquiries\Pages\ListPropertyInquiries;
use App\Filament\Resources\PropertyInquiries\Schemas\PropertyInquiryForm;
use App\Filament\Resources\PropertyInquiries\Tables\PropertyInquiriesTable;
use App\Models\PropertyInquiry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PropertyInquiryResource extends Resource
{
    protected static ?string $model = PropertyInquiry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Inquiries';

    protected static ?string $modelLabel = 'Inquiry';

    protected static ?string $pluralModelLabel = 'Inquiries';

    public static function form(Schema $schema): Schema
    {
        return PropertyInquiryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PropertyInquiriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPropertyInquiries::route('/'),
            'edit' => EditPropertyInquiry::route('/{record}/edit'),
        ];
    }
}

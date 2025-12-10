<?php

namespace App\Filament\Resources\PropertyUnits\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PropertyUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Property')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('General')
                            ->schema([
                                Section::make('Basic Information')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Title')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $set('slug', Str::slug($state));
                                            }),
                                        TextInput::make('slug')
                                            ->label('Slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true),
                                        Select::make('property_type')
                                            ->label('Property Type')
                                            ->options([
                                                'house' => 'House',
                                                'apartment' => 'Apartment',
                                                'villa' => 'Villa',
                                                'townhouse' => 'Townhouse',
                                                'farm' => 'Farm',
                                                'commercial' => 'Commercial',
                                                'land' => 'Land',
                                                'parking' => 'Parking',
                                                'other' => 'Other',
                                            ])
                                            ->required(),
                                        Select::make('transaction_type')
                                            ->label('Transaction Type')
                                            ->options([
                                                'sale' => 'Sale',
                                                'rent' => 'Rent',
                                                'auction' => 'Auction',
                                            ])
                                            ->required(),
                                        Select::make('status')
                                            ->label('Status')
                                            ->options([
                                                'available' => 'Available',
                                                'sold' => 'Sold',
                                                'rented' => 'Rented',
                                                'pending' => 'Pending',
                                                'withdrawn' => 'Withdrawn',
                                            ])
                                            ->required(),
                                        Select::make('living_type')
                                            ->label('Living Type')
                                            ->options([
                                                'permanent' => 'Permanent',
                                                'recreational' => 'Recreational',
                                            ]),
                                    ]),
                                Section::make('Description')
                                    ->schema([
                                        RichEditor::make('description')
                                            ->label('Description')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Location')
                            ->schema([
                                Section::make('Address')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('address_street')
                                            ->label('Street')
                                            ->maxLength(255),
                                        TextInput::make('address_number')
                                            ->label('House Number')
                                            ->maxLength(20),
                                        TextInput::make('address_addition')
                                            ->label('Addition')
                                            ->maxLength(20),
                                        TextInput::make('address_postal_code')
                                            ->label('Postal Code')
                                            ->maxLength(10),
                                        TextInput::make('address_city')
                                            ->label('City')
                                            ->maxLength(255),
                                        TextInput::make('address_province')
                                            ->label('Province')
                                            ->maxLength(255),
                                        TextInput::make('neighborhood')
                                            ->label('Neighborhood')
                                            ->maxLength(255),
                                        TextInput::make('municipality')
                                            ->label('Municipality')
                                            ->maxLength(255),
                                    ]),
                                Section::make('Coordinates')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('latitude')
                                            ->label('Latitude')
                                            ->numeric(),
                                        TextInput::make('longitude')
                                            ->label('Longitude')
                                            ->numeric(),
                                    ]),
                            ]),
                        Tab::make('Price')
                            ->schema([
                                Section::make('Sale Price')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('buyprice')
                                            ->label('Asking Price')
                                            ->numeric()
                                            ->prefix('€'),
                                        TextInput::make('buyprice_label')
                                            ->label('Price Label')
                                            ->maxLength(255)
                                            ->placeholder('e.g. Price on request'),
                                        TextInput::make('land_costs')
                                            ->label('Land Costs')
                                            ->numeric()
                                            ->prefix('€'),
                                        TextInput::make('contract_price')
                                            ->label('Contract Price')
                                            ->numeric()
                                            ->prefix('€'),
                                    ]),
                                Section::make('Rent Price')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('rentprice_month')
                                            ->label('Rent per Month')
                                            ->numeric()
                                            ->prefix('€'),
                                        TextInput::make('service_fee_month')
                                            ->label('Service Fee per Month')
                                            ->numeric()
                                            ->prefix('€'),
                                        TextInput::make('total_rent_month')
                                            ->label('Total Rent per Month')
                                            ->numeric()
                                            ->prefix('€'),
                                    ]),
                                Section::make('Other Costs')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('property_tax')
                                            ->label('Property Tax')
                                            ->numeric()
                                            ->prefix('€'),
                                        TextInput::make('hoa_fees')
                                            ->label('HOA Fees')
                                            ->numeric()
                                            ->prefix('€'),
                                        TextInput::make('ground_lease')
                                            ->label('Ground Lease')
                                            ->maxLength(255),
                                        TextInput::make('canon')
                                            ->label('Canon')
                                            ->numeric()
                                            ->prefix('€'),
                                    ]),
                            ]),
                        Tab::make('Features')
                            ->schema([
                                Section::make('Dimensions')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('surface')
                                            ->label('Living Area')
                                            ->numeric()
                                            ->suffix('m²'),
                                        TextInput::make('lotsize')
                                            ->label('Lot Size')
                                            ->numeric()
                                            ->suffix('m²'),
                                        TextInput::make('volume')
                                            ->label('Volume')
                                            ->numeric()
                                            ->suffix('m³'),
                                        TextInput::make('outdoor_surface')
                                            ->label('Outdoor Space')
                                            ->numeric()
                                            ->suffix('m²'),
                                    ]),
                                Section::make('Layout')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('bedrooms')
                                            ->label('Rooms')
                                            ->numeric(),
                                        TextInput::make('sleepingrooms')
                                            ->label('Bedrooms')
                                            ->numeric(),
                                        TextInput::make('bathrooms')
                                            ->label('Bathrooms')
                                            ->numeric(),
                                        TextInput::make('floors')
                                            ->label('Floors')
                                            ->numeric(),
                                        TextInput::make('floor')
                                            ->label('Floor')
                                            ->maxLength(50),
                                    ]),
                                Section::make('Construction')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('construction_year')
                                            ->label('Year Built')
                                            ->numeric(),
                                        TextInput::make('renovation_year')
                                            ->label('Renovation Year')
                                            ->numeric(),
                                        Select::make('energy_label')
                                            ->label('Energy Label')
                                            ->options([
                                                'A++++' => 'A++++',
                                                'A+++' => 'A+++',
                                                'A++' => 'A++',
                                                'A+' => 'A+',
                                                'A' => 'A',
                                                'B' => 'B',
                                                'C' => 'C',
                                                'D' => 'D',
                                                'E' => 'E',
                                                'F' => 'F',
                                                'G' => 'G',
                                            ]),
                                        TextInput::make('energy_index')
                                            ->label('Energy Index')
                                            ->numeric(),
                                    ]),
                                Section::make('Amenities')
                                    ->columns(3)
                                    ->schema([
                                        Toggle::make('garage')
                                            ->label('Garage'),
                                        Toggle::make('has_parking')
                                            ->label('Parking'),
                                        Toggle::make('has_elevator')
                                            ->label('Elevator'),
                                        Toggle::make('has_ac')
                                            ->label('Air Conditioning'),
                                        Toggle::make('has_alarm')
                                            ->label('Alarm System'),
                                        Toggle::make('berth')
                                            ->label('Berth'),
                                    ]),
                            ]),
                        Tab::make('Media')
                            ->schema([
                                Section::make('Links')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('virtual_tour_url')
                                            ->label('Virtual Tour URL')
                                            ->url()
                                            ->maxLength(500),
                                        TextInput::make('brochure_url')
                                            ->label('Brochure URL')
                                            ->url()
                                            ->maxLength(500),
                                    ]),
                            ]),
                        Tab::make('Agent')
                            ->schema([
                                Section::make('Agent Details')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('agent_name')
                                            ->label('Name')
                                            ->maxLength(255),
                                        TextInput::make('agent_company')
                                            ->label('Company')
                                            ->maxLength(255),
                                        TextInput::make('agent_phone')
                                            ->label('Phone')
                                            ->tel()
                                            ->maxLength(50),
                                        TextInput::make('agent_email')
                                            ->label('Email')
                                            ->email()
                                            ->maxLength(255),
                                        TextInput::make('agent_url')
                                            ->label('Website')
                                            ->url()
                                            ->maxLength(500),
                                        TextInput::make('agent_logo_url')
                                            ->label('Logo URL')
                                            ->url()
                                            ->maxLength(500),
                                    ]),
                            ]),
                        Tab::make('Dates')
                            ->schema([
                                Section::make('Dates')
                                    ->columns(2)
                                    ->schema([
                                        DatePicker::make('listing_date')
                                            ->label('Listing Date'),
                                        DatePicker::make('viewing_date')
                                            ->label('Viewing Date'),
                                        DatePicker::make('first_seen_at')
                                            ->label('First Seen')
                                            ->disabled(),
                                        DatePicker::make('last_seen_at')
                                            ->label('Last Seen')
                                            ->disabled(),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}

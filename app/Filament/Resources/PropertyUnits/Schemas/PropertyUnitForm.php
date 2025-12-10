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
                Tabs::make('Woning')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Algemeen')
                            ->schema([
                                Section::make('Basis informatie')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Titel')
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
                                            ->label('Woningtype')
                                            ->options([
                                                'house' => 'Woonhuis',
                                                'apartment' => 'Appartement',
                                                'villa' => 'Villa',
                                                'townhouse' => 'Herenhuis',
                                                'farm' => 'Boerderij',
                                                'commercial' => 'Bedrijfspand',
                                                'land' => 'Bouwgrond',
                                                'parking' => 'Parkeerplaats',
                                                'other' => 'Overig',
                                            ])
                                            ->required(),
                                        Select::make('transaction_type')
                                            ->label('Transactietype')
                                            ->options([
                                                'sale' => 'Koop',
                                                'rent' => 'Huur',
                                                'auction' => 'Veiling',
                                            ])
                                            ->required(),
                                        Select::make('status')
                                            ->label('Status')
                                            ->options([
                                                'available' => 'Beschikbaar',
                                                'sold' => 'Verkocht',
                                                'rented' => 'Verhuurd',
                                                'pending' => 'In optie',
                                                'withdrawn' => 'Uit de handel',
                                            ])
                                            ->required(),
                                        Select::make('living_type')
                                            ->label('Woonsituatie')
                                            ->options([
                                                'permanent' => 'Permanente bewoning',
                                                'recreational' => 'Recreatie',
                                            ]),
                                    ]),
                                Section::make('Beschrijving')
                                    ->schema([
                                        RichEditor::make('description')
                                            ->label('Beschrijving')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Locatie')
                            ->schema([
                                Section::make('Adres')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('address_street')
                                            ->label('Straat')
                                            ->maxLength(255),
                                        TextInput::make('address_number')
                                            ->label('Huisnummer')
                                            ->maxLength(20),
                                        TextInput::make('address_addition')
                                            ->label('Toevoeging')
                                            ->maxLength(20),
                                        TextInput::make('address_postal_code')
                                            ->label('Postcode')
                                            ->maxLength(10),
                                        TextInput::make('address_city')
                                            ->label('Plaats')
                                            ->maxLength(255),
                                        TextInput::make('address_province')
                                            ->label('Provincie')
                                            ->maxLength(255),
                                        TextInput::make('neighborhood')
                                            ->label('Buurt')
                                            ->maxLength(255),
                                        TextInput::make('municipality')
                                            ->label('Gemeente')
                                            ->maxLength(255),
                                    ]),
                                Section::make('Coordinaten')
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
                        Tab::make('Prijs')
                            ->schema([
                                Section::make('Koopprijs')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('buyprice')
                                            ->label('Vraagprijs')
                                            ->numeric()
                                            ->prefix('€'),
                                        TextInput::make('buyprice_label')
                                            ->label('Prijslabel')
                                            ->maxLength(255)
                                            ->placeholder('bijv. Prijs op aanvraag'),
                                        TextInput::make('land_costs')
                                            ->label('Grondkosten')
                                            ->numeric()
                                            ->prefix('€'),
                                        TextInput::make('contract_price')
                                            ->label('Contractprijs')
                                            ->numeric()
                                            ->prefix('€'),
                                    ]),
                                Section::make('Huurprijs')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('rentprice_month')
                                            ->label('Huurprijs per maand')
                                            ->numeric()
                                            ->prefix('€'),
                                        TextInput::make('service_fee_month')
                                            ->label('Servicekosten per maand')
                                            ->numeric()
                                            ->prefix('€'),
                                        TextInput::make('total_rent_month')
                                            ->label('Totale huur per maand')
                                            ->numeric()
                                            ->prefix('€'),
                                    ]),
                                Section::make('Overige kosten')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('property_tax')
                                            ->label('OZB')
                                            ->numeric()
                                            ->prefix('€'),
                                        TextInput::make('hoa_fees')
                                            ->label('VvE bijdrage')
                                            ->numeric()
                                            ->prefix('€'),
                                        TextInput::make('ground_lease')
                                            ->label('Erfpacht')
                                            ->maxLength(255),
                                        TextInput::make('canon')
                                            ->label('Canon')
                                            ->numeric()
                                            ->prefix('€'),
                                    ]),
                            ]),
                        Tab::make('Kenmerken')
                            ->schema([
                                Section::make('Afmetingen')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('surface')
                                            ->label('Woonoppervlakte')
                                            ->numeric()
                                            ->suffix('m²'),
                                        TextInput::make('lotsize')
                                            ->label('Perceeloppervlakte')
                                            ->numeric()
                                            ->suffix('m²'),
                                        TextInput::make('volume')
                                            ->label('Inhoud')
                                            ->numeric()
                                            ->suffix('m³'),
                                        TextInput::make('outdoor_surface')
                                            ->label('Buitenruimte')
                                            ->numeric()
                                            ->suffix('m²'),
                                    ]),
                                Section::make('Indeling')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('bedrooms')
                                            ->label('Kamers')
                                            ->numeric(),
                                        TextInput::make('sleepingrooms')
                                            ->label('Slaapkamers')
                                            ->numeric(),
                                        TextInput::make('bathrooms')
                                            ->label('Badkamers')
                                            ->numeric(),
                                        TextInput::make('floors')
                                            ->label('Verdiepingen')
                                            ->numeric(),
                                        TextInput::make('floor')
                                            ->label('Verdieping')
                                            ->maxLength(50),
                                    ]),
                                Section::make('Bouw')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('construction_year')
                                            ->label('Bouwjaar')
                                            ->numeric(),
                                        TextInput::make('renovation_year')
                                            ->label('Renovatiejaar')
                                            ->numeric(),
                                        Select::make('energy_label')
                                            ->label('Energielabel')
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
                                            ->label('Energie-index')
                                            ->numeric(),
                                    ]),
                                Section::make('Voorzieningen')
                                    ->columns(3)
                                    ->schema([
                                        Toggle::make('garage')
                                            ->label('Garage'),
                                        Toggle::make('has_parking')
                                            ->label('Parkeerplaats'),
                                        Toggle::make('has_elevator')
                                            ->label('Lift'),
                                        Toggle::make('has_ac')
                                            ->label('Airco'),
                                        Toggle::make('has_alarm')
                                            ->label('Alarmsysteem'),
                                        Toggle::make('berth')
                                            ->label('Ligplaats'),
                                    ]),
                            ]),
                        Tab::make('Media')
                            ->schema([
                                Section::make('Links')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('virtual_tour_url')
                                            ->label('Virtuele tour URL')
                                            ->url()
                                            ->maxLength(500),
                                        TextInput::make('brochure_url')
                                            ->label('Brochure URL')
                                            ->url()
                                            ->maxLength(500),
                                    ]),
                            ]),
                        Tab::make('Makelaar')
                            ->schema([
                                Section::make('Makelaar gegevens')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('agent_name')
                                            ->label('Naam')
                                            ->maxLength(255),
                                        TextInput::make('agent_company')
                                            ->label('Bedrijf')
                                            ->maxLength(255),
                                        TextInput::make('agent_phone')
                                            ->label('Telefoon')
                                            ->tel()
                                            ->maxLength(50),
                                        TextInput::make('agent_email')
                                            ->label('E-mail')
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
                        Tab::make('Datums')
                            ->schema([
                                Section::make('Datums')
                                    ->columns(2)
                                    ->schema([
                                        DatePicker::make('listing_date')
                                            ->label('Publicatiedatum'),
                                        DatePicker::make('viewing_date')
                                            ->label('Bezichtigingsdatum'),
                                        DatePicker::make('first_seen_at')
                                            ->label('Eerst gezien')
                                            ->disabled(),
                                        DatePicker::make('last_seen_at')
                                            ->label('Laatst gezien')
                                            ->disabled(),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}

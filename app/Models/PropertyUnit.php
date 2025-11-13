<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string|null $name
 * @property string $title
 * @property string|null $description
 * @property string $property_type
 * @property string $transaction_type
 * @property string $status
 * @property string|null $living_type
 * @property string|null $buyprice
 * @property string|null $buyprice_label
 * @property string|null $buyprice_range_from
 * @property string|null $buyprice_range_to
 * @property string|null $land_costs
 * @property string|null $contract_price
 * @property string|null $ground_lease
 * @property string|null $canon
 * @property string|null $canon_comment
 * @property string|null $rentprice_month
 * @property string|null $service_fee_month
 * @property string|null $total_rent_month
 * @property string $price_currency
 * @property string|null $property_tax
 * @property string|null $hoa_fees
 * @property string|null $address_street
 * @property string|null $address_number
 * @property string|null $address_addition
 * @property string|null $address_city
 * @property string|null $address_postal_code
 * @property string|null $address_province
 * @property string $address_country
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $neighborhood
 * @property string|null $municipality
 * @property int|null $surface
 * @property int|null $lotsize
 * @property int|null $volume
 * @property int|null $outdoor_surface
 * @property string|null $planarea
 * @property int|null $bedrooms
 * @property int|null $sleepingrooms
 * @property int|null $bathrooms
 * @property int|null $floors
 * @property int|null $construction_year
 * @property int|null $renovation_year
 * @property string|null $energy_label
 * @property string|null $energy_index
 * @property string|null $floor
 * @property string|null $orientation
 * @property bool $berth
 * @property bool $garage
 * @property bool $has_parking
 * @property bool $has_elevator
 * @property bool $has_ac
 * @property bool $has_alarm
 * @property array<array-key, mixed>|null $parking_lots_data
 * @property array<array-key, mixed>|null $storages_data
 * @property array<array-key, mixed>|null $outdoor_spaces_data
 * @property array<array-key, mixed>|null $images
 * @property array<array-key, mixed>|null $videos
 * @property array<array-key, mixed>|null $floor_plans
 * @property string|null $virtual_tour_url
 * @property string|null $brochure_url
 * @property string|null $agent_name
 * @property string|null $agent_company
 * @property string|null $agent_phone
 * @property string|null $agent_email
 * @property string|null $agent_logo_url
 * @property array<array-key, mixed>|null $features
 * @property array<array-key, mixed>|null $amenities
 * @property array<array-key, mixed>|null $data
 * @property \Illuminate\Support\Carbon|null $listing_date
 * @property string|null $acceptance_date
 * @property \Illuminate\Support\Carbon|null $viewing_date
 * @property \Illuminate\Support\Carbon $first_seen_at
 * @property \Illuminate\Support\Carbon $last_seen_at
 * @property \Illuminate\Support\Carbon|null $last_changed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Website> $websites
 * @property-read int|null $websites_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereAcceptanceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereAddressAddition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereAddressCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereAddressCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereAddressNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereAddressPostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereAddressProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereAddressStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereAgentCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereAgentEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereAgentLogoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereAgentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereAgentPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereAmenities($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereBathrooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereBedrooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereBerth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereBrochureUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereBuyprice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereBuypriceLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereBuypriceRangeFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereBuypriceRangeTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereCanon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereCanonComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereConstructionYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereContractPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereEnergyIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereEnergyLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereFirstSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereFloorPlans($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereFloors($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereGarage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereGroundLease($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereHasAc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereHasAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereHasElevator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereHasParking($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereHoaFees($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereLandCosts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereLastChangedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereLastSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereListingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereLivingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereLotsize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereMunicipality($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereNeighborhood($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereOrientation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereOutdoorSpacesData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereOutdoorSurface($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereParkingLotsData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit wherePlanarea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit wherePriceCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit wherePropertyTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit wherePropertyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereRenovationYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereRentpriceMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereServiceFeeMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereSleepingrooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereStoragesData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereSurface($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereTotalRentMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereTransactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereVideos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereViewingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereVirtualTourUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyUnit whereVolume($value)
 * @mixin \Eloquent
 */
class PropertyUnit extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'title',
        'description',
        'property_type',
        'transaction_type',
        'status',
        'living_type',
        'buyprice',
        'buyprice_label',
        'buyprice_range_from',
        'buyprice_range_to',
        'land_costs',
        'contract_price',
        'ground_lease',
        'canon',
        'canon_comment',
        'rentprice_month',
        'service_fee_month',
        'total_rent_month',
        'price_currency',
        'property_tax',
        'hoa_fees',
        'address_street',
        'address_number',
        'address_addition',
        'address_city',
        'address_postal_code',
        'address_province',
        'address_country',
        'latitude',
        'longitude',
        'neighborhood',
        'municipality',
        'surface',
        'lotsize',
        'volume',
        'outdoor_surface',
        'planarea',
        'bedrooms',
        'sleepingrooms',
        'bathrooms',
        'floors',
        'construction_year',
        'renovation_year',
        'energy_label',
        'energy_index',
        'floor',
        'orientation',
        'berth',
        'garage',
        'has_parking',
        'has_elevator',
        'has_ac',
        'has_alarm',
        'parking_lots_data',
        'storages_data',
        'outdoor_spaces_data',
        'images',
        'videos',
        'floor_plans',
        'virtual_tour_url',
        'brochure_url',
        'agent_name',
        'agent_company',
        'agent_phone',
        'agent_email',
        'agent_logo_url',
        'agent_url',
        'features',
        'amenities',
        'data',
        'listing_date',
        'acceptance_date',
        'viewing_date',
        'first_seen_at',
        'last_seen_at',
        'last_changed_at',
    ];

    protected $casts = [
        'berth' => 'boolean',
        'garage' => 'boolean',
        'has_parking' => 'boolean',
        'has_elevator' => 'boolean',
        'has_ac' => 'boolean',
        'has_alarm' => 'boolean',
        'parking_lots_data' => 'array',
        'storages_data' => 'array',
        'outdoor_spaces_data' => 'array',
        'images' => 'array',
        'videos' => 'array',
        'floor_plans' => 'array',
        'features' => 'array',
        'amenities' => 'array',
        'data' => 'array',
        'listing_date' => 'date',
        'viewing_date' => 'datetime',
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'last_changed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($property) {
            if (!$property->slug || $property->isDirty(['address_city', 'address_postal_code', 'address_street'])) {
                $property->slug = $property->generateSlug();
            }
        });
    }

    public function generateSlug(): string
    {
        $city = Str::slug($this->address_city ?? 'unknown');
        $postalCode = str_replace(' ', '', $this->address_postal_code ?? '0000');
        $street = Str::slug($this->address_street ?? 'property');

        $baseSlug = "{$city}-{$postalCode}-{$street}";
        $slug = $baseSlug;
        $counter = 1;

        while (self::query()->where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSourceUrlAttribute(): ?string
    {
        return \DB::table('property_unit_website')
            ->where('property_unit_id', $this->id)
            ->orderBy('first_seen_at', 'asc')
            ->value('source_url');
    }

    public function websites(): BelongsToMany
    {
        return $this->belongsToMany(Website::class)
            ->withPivot('external_id', 'source_url', 'first_seen_at', 'last_seen_at')
            ->withTimestamps();
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_street,
            $this->address_number,
            $this->address_addition,
        ]);

        $street = implode(' ', $parts);

        if ($this->address_postal_code || $this->address_city) {
            $location = array_filter([
                $this->address_postal_code,
                $this->address_city,
            ]);
            $street .= ', ' . implode(' ', $location);
        }

        return $street;
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->transaction_type === 'sale' && $this->buyprice) {
            return '€ ' . number_format($this->buyprice, 0, ',', '.');
        }

        if ($this->transaction_type === 'rent' && $this->rentprice_month) {
            return '€ ' . number_format($this->rentprice_month, 0, ',', '.') . ' p/m';
        }

        if ($this->buyprice_label) {
            return $this->buyprice_label;
        }

        return 'Prijs op aanvraag';
    }

    public function getMainImageAttribute(): ?string
    {
        try {
            $images = $this->images;

            if ($images && is_array($images) && count($images) > 0) {
                $firstImage = reset($images);
                return is_string($firstImage) ? $firstImage : null;
            }
        } catch (\Exception $e) {
        }

        return null;
    }

    public function getFormattedDescriptionAttribute(): ?string
    {
        if (!$this->description) {
            return null;
        }

        $text = $this->description;

        $text = preg_replace('/•\s*/', "\n• ", $text);
        $text = preg_replace('/([.!?])\s+([A-Z][a-z]+(?:\s+[a-z]+)?:)/u', "$1\n\n$2", $text);
        $text = preg_replace('/([.!?])\s+([A-Z][a-z]{2,})\s+([A-Z])/u', "$1\n\n$2 $3", $text);
        $text = preg_replace('/\s{2,}/', "\n\n", $text);

        $text = trim($text);

        return $text;
    }

    public function getPropertyTypeLabelAttribute(): string
    {
        $labels = [
            'house' => 'Woonhuis',
            'apartment' => 'Appartement',
            'villa' => 'Villa',
            'townhouse' => 'Herenhuis',
            'farm' => 'Boerderij',
            'commercial' => 'Bedrijfspand',
            'land' => 'Bouwgrond',
            'parking' => 'Parkeerplaats',
        ];

        return $labels[$this->property_type] ?? ucfirst($this->property_type);
    }

    public function getTransactionTypeLabelAttribute(): string
    {
        $labels = [
            'sale' => 'Te koop',
            'rent' => 'Te huur',
            'auction' => 'Veiling',
        ];

        return $labels[$this->transaction_type] ?? ucfirst($this->transaction_type);
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'available' => 'Beschikbaar',
            'sold' => 'Verkocht',
            'rented' => 'Verhuurd',
            'pending' => 'In optie',
            'withdrawn' => 'Uit de handel',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getDaysOnlineAttribute(): ?int
    {
        if ($this->first_seen_at) {
            return $this->first_seen_at->diffInDays(now());
        }

        return null;
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->withTimestamps();
    }

    public function isFavoritedByUser(?User $user = null): bool
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return false;
        }

        return $this->favoritedBy()->where('user_id', $user->id)->exists();
    }

    public function getTopFeaturesAttribute(): array
    {
        $allFeatures = [
            [
                'value' => $this->surface,
                'suffix' => 'm²',
                'label' => 'Woonoppervlakte',
                'color' => 'blue',
                'priority' => 10,
            ],
            [
                'value' => $this->bedrooms,
                'suffix' => '',
                'label' => 'Kamers',
                'color' => 'purple',
                'priority' => 9,
            ],
            [
                'value' => $this->sleepingrooms,
                'suffix' => '',
                'label' => 'Slaapkamers',
                'color' => 'cyan',
                'priority' => 8,
            ],
            [
                'value' => $this->energy_label,
                'suffix' => '',
                'label' => 'Energielabel',
                'color' => 'green',
                'priority' => 7,
            ],
            [
                'value' => $this->lotsize,
                'suffix' => 'm²',
                'label' => 'Perceeloppervlakte',
                'color' => 'emerald',
                'priority' => 6,
            ],
            [
                'value' => $this->bathrooms,
                'suffix' => '',
                'label' => 'Badkamers',
                'color' => 'indigo',
                'priority' => 5,
            ],
            [
                'value' => $this->construction_year,
                'suffix' => '',
                'label' => 'Bouwjaar',
                'color' => 'amber',
                'priority' => 4,
            ],
            [
                'value' => $this->outdoor_surface,
                'suffix' => 'm²',
                'label' => 'Buitenruimte',
                'color' => 'lime',
                'priority' => 3,
            ],
        ];

        $availableFeatures = array_filter($allFeatures, function ($feature) {
            return !empty($feature['value']);
        });

        usort($availableFeatures, function ($a, $b) {
            return $b['priority'] <=> $a['priority'];
        });

        return array_slice($availableFeatures, 0, 4);
    }

    public function getAllImagesAttribute(): array
    {
        $images = $this->images ?: [];
        $floorPlans = $this->floor_plans ?: [];
        $allImages = array_merge($images, $floorPlans);

        $uniqueImages = [];
        $seenBaseUrls = [];

        foreach ($allImages as $imageUrl) {
            $baseUrl = preg_replace('/\?.*$/', '', $imageUrl);

            if (!isset($seenBaseUrls[$baseUrl])) {
                $uniqueImages[] = $imageUrl;
                $seenBaseUrls[$baseUrl] = true;
            }
        }

        return $uniqueImages;
    }
}

@props(['property'])
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "RealEstateListing",
    "name": "{{ $property->title ?? $property->full_address }}",
    "description": "{{ Str::limit(strip_tags($property->description ?? ''), 500) }}",
    "url": "{{ route('property.show', $property) }}",
    "datePosted": "{{ ($property->listing_date ?? $property->first_seen_at)?->toIso8601String() }}",
    @if($property->images && count($property->images) > 0)
    "image": [
        @foreach(array_slice($property->images, 0, 5) as $image)
        "{{ proxied_image_url($image) }}"@if(!$loop->last),@endif

        @endforeach
    ],
    @endif
    "address": {
        "@@type": "PostalAddress",
        "streetAddress": "{{ $property->address_street }} {{ $property->address_number }}{{ $property->address_addition }}",
        "addressLocality": "{{ $property->address_city }}",
        "postalCode": "{{ $property->address_postal_code }}",
        @if($property->address_province)
        "addressRegion": "{{ $property->address_province }}",
        @endif
        "addressCountry": "NL"
    },
    @if($property->latitude && $property->longitude)
    "geo": {
        "@@type": "GeoCoordinates",
        "latitude": {{ $property->latitude }},
        "longitude": {{ $property->longitude }}
    },
    @endif
    "offers": {
        "@@type": "Offer",
        @if($property->buyprice)
        "price": {{ $property->buyprice }},
        @elseif($property->rentprice_month)
        "price": {{ $property->rentprice_month }},
        @endif
        "priceCurrency": "EUR",
        "availability": "{{ $property->status === 'available' ? 'https://schema.org/InStock' : 'https://schema.org/SoldOut' }}"
        @if($property->transaction_type === 'rent')
        ,"priceSpecification": {
            "@@type": "UnitPriceSpecification",
            "price": {{ $property->rentprice_month ?? 0 }},
            "priceCurrency": "EUR",
            "unitCode": "MON"
        }
        @endif
    }
    @if($property->bedrooms)
    ,"numberOfRooms": {{ $property->bedrooms }}
    @endif
    @if($property->sleepingrooms)
    ,"numberOfBedrooms": {{ $property->sleepingrooms }}
    @endif
    @if($property->bathrooms)
    ,"numberOfBathroomsTotal": {{ $property->bathrooms }}
    @endif
    @if($property->surface)
    ,"floorSize": {
        "@@type": "QuantitativeValue",
        "value": {{ $property->surface }},
        "unitCode": "MTK"
    }
    @endif
    @if($property->lotsize)
    ,"lotSize": {
        "@@type": "QuantitativeValue",
        "value": {{ $property->lotsize }},
        "unitCode": "MTK"
    }
    @endif
    @if($property->construction_year)
    ,"yearBuilt": {{ $property->construction_year }}
    @endif
    @if($property->floors)
    ,"numberOfFloors": {{ $property->floors }}
    @endif
    @if($property->energy_label)
    ,"additionalProperty": {
        "@@type": "PropertyValue",
        "name": "energyEfficiencyRating",
        "value": "{{ $property->energy_label }}"
    }
    @endif
    @if($property->virtual_tour_url)
    ,"virtualTour": {
        "@@type": "VirtualLocation",
        "url": "{{ $property->virtual_tour_url }}"
    }
    @endif
    @if($property->agent_name || $property->agent_company)
    ,"broker": {
        "@@type": "RealEstateAgent",
        @if($property->agent_name)
        "name": "{{ $property->agent_name }}",
        @endif
        @if($property->agent_company)
        "worksFor": {
            "@@type": "Organization",
            "name": "{{ $property->agent_company }}"
        },
        @endif
        @if($property->agent_phone)
        "telephone": "{{ $property->agent_phone }}",
        @endif
        @if($property->agent_email)
        "email": "{{ $property->agent_email }}",
        @endif
        @if($property->agent_logo_url)
        "image": "{{ $property->agent_logo_url }}",
        @endif
        "@@id": "{{ $property->agent_url ?? '#agent' }}"
    }
    @endif
}
</script>

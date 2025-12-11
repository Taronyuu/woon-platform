@extends('layouts.app')

@section('title', ($property->title ?? $property->full_address) . ' - Oxxen.nl')
@section('meta_description', Str::limit($property->description ?? 'Bekijk deze woning: ' . $property->full_address . ' - ' . $property->formatted_price, 160))

@section('canonical')
<link rel="canonical" href="{{ route('property.show', $property) }}">
@endsection

@section('structured-data')
@php
$breadcrumbItems = [
    ['name' => 'Home', 'url' => route('home')],
    ['name' => 'Woningen zoeken', 'url' => route('search')],
    ['name' => $property->address_city, 'url' => route('search', ['search' => $property->address_city])],
    ['name' => $property->full_address, 'url' => route('property.show', $property)]
];
@endphp
<x-seo.real-estate-schema :property="$property" />
<x-seo.breadcrumb-schema :items="$breadcrumbItems" />
@endsection

@section('meta')
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $property->title ?? $property->full_address }} - Oxxen.nl">
<meta property="og:description" content="{{ Str::limit($property->description ?? 'Bekijk deze woning: ' . $property->full_address . ' - ' . $property->formatted_price, 200) }}">
<meta property="og:url" content="{{ url()->current() }}">
@if($property->proxied_main_image)
<meta property="og:image" content="{{ $property->proxied_main_image }}">
<meta property="og:image:alt" content="{{ $property->full_address }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
@endif
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $property->title ?? $property->full_address }}">
<meta name="twitter:description" content="{{ Str::limit($property->description ?? 'Bekijk deze woning: ' . $property->full_address, 200) }}">
@if($property->proxied_main_image)
<meta name="twitter:image" content="{{ $property->proxied_main_image }}">
@endif
@endsection

@if($property->latitude && $property->longitude)
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush
@endif

@section('content')
<div x-data="{
    lightboxOpen: false,
    currentIndex: 0,
    images: {{ json_encode($property->proxied_all_images) }},
    openLightbox(index) {
        this.currentIndex = index;
        this.lightboxOpen = true;
        document.body.style.overflow = 'hidden';
    },
    closeLightbox() {
        this.lightboxOpen = false;
        document.body.style.overflow = 'auto';
    },
    nextImage() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
    },
    prevImage() {
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
    }
}" @keydown.window.escape="closeLightbox()" @keydown.window.arrow-right="lightboxOpen && nextImage()" @keydown.window.arrow-left="lightboxOpen && prevImage()">

    <div class="container mx-auto px-4 py-8">

        <div class="mb-4">
            <a href="{{ route('search') }}" class="text-blue-600 hover:text-blue-700 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Terug naar zoeken
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2">

                <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6 border border-gray-100">
                    <div class="relative h-96 overflow-hidden group cursor-pointer" @click="images.length > 0 && openLightbox(0)">
                        @if($property->proxied_main_image)
                            <img src="{{ $property->proxied_main_image }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400 text-lg">Geen foto beschikbaar</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                        @auth
                            <button
                                onclick="toggleFavorite()"
                                id="favorite-btn"
                                class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm p-3 rounded-full shadow-lg hover:bg-white hover:scale-110 transition-all duration-200">
                                <svg class="w-6 h-6 {{ $property->isFavoritedByUser() ? 'text-red-500 fill-current' : 'text-gray-400' }}" fill="{{ $property->isFavoritedByUser() ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                        @else
                            <div class="absolute top-4 right-4 group relative">
                                <button
                                    class="bg-white/90 backdrop-blur-sm p-3 rounded-full shadow-lg hover:bg-white hover:scale-110 transition-all duration-200 cursor-not-allowed opacity-75">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                                <div class="absolute right-0 top-full mt-2 w-64 bg-gray-900 text-white text-sm rounded-lg shadow-xl p-4 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                    <p class="mb-3">Log in om deze woning aan je favorieten toe te voegen</p>
                                    <div class="flex gap-2">
                                        <a href="{{ route('login') }}" class="flex-1 bg-blue-600 text-white px-3 py-2 rounded-lg text-center hover:bg-blue-700 transition font-medium">
                                            Inloggen
                                        </a>
                                        <a href="{{ route('register') }}" class="flex-1 bg-white text-gray-900 px-3 py-2 rounded-lg text-center hover:bg-gray-100 transition font-medium">
                                            Registreren
                                        </a>
                                    </div>
                                    <div class="absolute top-0 right-4 transform -translate-y-1/2 rotate-45 w-3 h-3 bg-gray-900"></div>
                                </div>
                            </div>
                        @endauth
                    </div>
                    @if($property->proxied_images && count($property->proxied_images) > 1)
                        <div class="grid grid-cols-4 gap-2 p-4">
                            @foreach(array_slice($property->proxied_images, 1, 3) as $index => $image)
                                <div class="relative h-24 rounded-xl overflow-hidden group cursor-pointer" @click="openLightbox({{ $index + 1 }})">
                                    <img src="{{ $image }}" alt="Foto" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                            @endforeach
                            @if(count($property->proxied_images) > 4)
                                <div class="relative h-24 rounded-xl overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center cursor-pointer hover:scale-105 transition-transform duration-200" @click="openLightbox(4)">
                                    <span class="text-white font-bold text-lg">+{{ count($property->proxied_images) - 4 }} foto's</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border border-gray-100">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h1 class="text-4xl font-extrabold mb-2 text-gray-900">
                                {{ $property->address_street }} {{ $property->address_number }}{{ $property->address_addition }}
                            </h1>
                            <p class="text-xl text-gray-600">{{ $property->address_postal_code }} {{ $property->address_city }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-4xl font-extrabold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
                                {{ $property->formatted_price }}
                            </p>
                            <span class="inline-block bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg">
                                ✓ {{ $property->transaction_type_label }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-6 border-y border-gray-200">
                        @foreach($property->top_features as $feature)
                            <div class="text-center p-4 rounded-xl bg-gradient-to-br from-{{ $feature['color'] }}-50 to-{{ $feature['color'] }}-100/50 hover:scale-105 transition-transform duration-200">
                                <p class="text-3xl font-extrabold text-{{ $feature['color'] }}-600 mb-1">{{ $feature['value'] }}{{ $feature['suffix'] ? ' ' . $feature['suffix'] : '' }}</p>
                                <p class="text-sm text-gray-600 font-medium">{{ $feature['label'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    @if($property->description)
                        <div class="mt-8">
                            <h2 class="text-2xl font-bold mb-6 text-gray-900">Beschrijving</h2>
                            <div class="prose prose-gray max-w-none prose-p:text-gray-700 prose-p:leading-relaxed prose-headings:text-gray-900 prose-strong:text-gray-900 prose-ul:text-gray-700 prose-li:marker:text-gray-400">
                                {!! $property->formatted_description !!}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-lg shadow p-8 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Kenmerken</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3">Algemeen</h3>
                            <dl class="space-y-2">
                                @if($property->property_type)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Type woning</dt>
                                        <dd class="font-medium">{{ $property->property_type_label }}</dd>
                                    </div>
                                @endif
                                @if($property->living_type)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Soort woning</dt>
                                        <dd class="font-medium">{{ ucfirst($property->living_type) }}</dd>
                                    </div>
                                @endif
                                @if($property->construction_year)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Bouwjaar</dt>
                                        <dd class="font-medium">{{ $property->construction_year }}</dd>
                                    </div>
                                @endif
                                @if($property->surface)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Woonoppervlakte</dt>
                                        <dd class="font-medium">{{ $property->surface }} m²</dd>
                                    </div>
                                @endif
                                @if($property->lotsize)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Perceeloppervlakte</dt>
                                        <dd class="font-medium">{{ $property->lotsize }} m²</dd>
                                    </div>
                                @endif
                                @if($property->bedrooms)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Aantal kamers</dt>
                                        <dd class="font-medium">{{ $property->bedrooms }}</dd>
                                    </div>
                                @endif
                                @if($property->sleepingrooms)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Aantal slaapkamers</dt>
                                        <dd class="font-medium">{{ $property->sleepingrooms }}</dd>
                                    </div>
                                @endif
                                @if($property->bathrooms)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Aantal badkamers</dt>
                                        <dd class="font-medium">{{ $property->bathrooms }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3">Energie</h3>
                            <dl class="space-y-2">
                                @if($property->energy_label)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Energielabel</dt>
                                        <dd class="font-medium">{{ $property->energy_label }}</dd>
                                    </div>
                                @endif
                                @if($property->energy_index)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Energie-index</dt>
                                        <dd class="font-medium">{{ $property->energy_index }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                        @if($property->outdoor_surface || $property->orientation)
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-3">Buitenruimte</h3>
                                <dl class="space-y-2">
                                    @if($property->outdoor_surface)
                                        <div class="flex justify-between">
                                            <dt class="text-gray-600">Buitenruimte</dt>
                                            <dd class="font-medium">{{ $property->outdoor_surface }} m²</dd>
                                        </div>
                                    @endif
                                    @if($property->orientation)
                                        <div class="flex justify-between">
                                            <dt class="text-gray-600">Ligging</dt>
                                            <dd class="font-medium">{{ ucfirst($property->orientation) }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3">Overig</h3>
                            <dl class="space-y-2">
                                @if($property->has_parking || $property->garage)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Parkeren</dt>
                                        <dd class="font-medium">
                                            @if($property->garage) Garage @elseif($property->has_parking) Ja @else Nee @endif
                                        </dd>
                                    </div>
                                @endif
                                @if($property->hoa_fees)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">VvE bijdrage</dt>
                                        <dd class="font-medium">€ {{ number_format($property->hoa_fees, 0, ',', '.') }} / maand</dd>
                                    </div>
                                @endif
                                @if($property->acceptance_date)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Aanvaarding</dt>
                                        <dd class="font-medium">{{ $property->acceptance_date }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                @if($property->proxied_floor_plans && count($property->proxied_floor_plans) > 0)
                    <div class="bg-white rounded-lg shadow p-8 mb-6">
                        <h2 class="text-xl font-semibold mb-4">Plattegrond</h2>
                        @foreach($property->proxied_floor_plans as $index => $floorPlan)
                            <img src="{{ $floorPlan }}" alt="Plattegrond" class="w-full rounded-lg mb-4 cursor-pointer hover:opacity-90 transition-opacity" @click="openLightbox({{ count($property->proxied_images ?: []) + $index }})">
                        @endforeach
                    </div>
                @endif

                @if($property->latitude && $property->longitude)
                    <div class="bg-white rounded-lg shadow p-8">
                        <h2 class="text-xl font-semibold mb-4">Locatie</h2>
                        <div id="property-map" class="h-96 rounded-lg mb-4 border border-gray-200"></div>
                        @if($property->neighborhood || $property->municipality)
                            <p class="text-gray-700">
                                @if($property->neighborhood) Buurt: {{ $property->neighborhood }}. @endif
                                @if($property->municipality) Gemeente: {{ $property->municipality }}. @endif
                            </p>
                        @endif
                    </div>

                @endif

            </div>

            <div class="lg:col-span-1">

                <div class="bg-white rounded-lg shadow p-6 mb-6 sticky top-4">
                    <h3 class="text-xl font-semibold mb-4">Interesse in deze woning?</h3>

                    @if($property->source_url)
                        <a href="{{ $property->source_url }}" target="_blank" rel="nofollow noopener noreferrer" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition mb-3 block text-center flex items-center justify-center gap-2">
                            <span>Bekijk woning</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    @else
                        <button class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition mb-3">
                            Bekijk woning
                        </button>
                    @endif

                    <a href="{{ route('mortgage.calculator', ['amount' => $property->price, 'duration' => 30]) }}" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg hover:scale-105 transition-all duration-200 mb-3 block text-center">
                        💰 Bereken maandlasten
                    </a>

                    @if($property->agent_name || $property->agent_phone || $property->agent_email)
                        <div class="bg-gray-50 rounded-lg p-4 mb-3">
                            <h4 class="font-semibold text-gray-900 mb-3">Contact makelaar</h4>
                            @if($property->agent_name || $property->agent_company)
                                <p class="font-medium text-gray-800">
                                    {{ $property->agent_name }}
                                    @if($property->agent_company)
                                        <span class="text-gray-500 text-sm block">{{ $property->agent_company }}</span>
                                    @endif
                                </p>
                            @endif
                            @if($property->agent_phone)
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $property->agent_phone) }}" class="flex items-center text-blue-600 hover:text-blue-700 mt-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $property->agent_phone }}
                                </a>
                            @endif
                            @if($property->agent_email)
                                <a href="mailto:{{ $property->agent_email }}" class="flex items-center text-blue-600 hover:text-blue-700 mt-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $property->agent_email }}
                                </a>
                            @endif
                        </div>
                    @elseif($property->agent_url)
                        <a href="{{ $property->agent_url }}" target="_blank" rel="nofollow noopener noreferrer" class="w-full bg-white border-2 border-blue-600 text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition mb-3 block text-center">
                            Contact makelaar
                        </a>
                    @endif

                    @auth
                        <button
                            onclick="toggleFavorite()"
                            id="favorite-btn-sidebar"
                            class="w-full bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition mb-6">
                            <svg id="favorite-icon-sidebar" class="w-5 h-5 inline-block mr-2 {{ $property->isFavoritedByUser() ? 'text-red-500 fill-current' : 'text-gray-400' }}" fill="{{ $property->isFavoritedByUser() ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span id="favorite-text">{{ $property->isFavoritedByUser() ? 'Verwijderen uit favorieten' : 'Toevoegen aan favorieten' }}</span>
                        </button>
                    @else
                        <div class="relative group mb-6">
                            <button
                                class="w-full bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition cursor-not-allowed opacity-75">
                                <svg class="w-5 h-5 inline-block mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                Toevoegen aan favorieten
                            </button>
                            <div class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-2 w-64 bg-gray-900 text-white text-sm rounded-lg shadow-xl p-4 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <p class="mb-3">Log in om deze woning aan je favorieten toe te voegen</p>
                                <div class="flex gap-2">
                                    <a href="{{ route('login') }}" class="flex-1 bg-blue-600 text-white px-3 py-2 rounded-lg text-center hover:bg-blue-700 transition font-medium">
                                        Inloggen
                                    </a>
                                    <a href="{{ route('register') }}" class="flex-1 bg-white text-gray-900 px-3 py-2 rounded-lg text-center hover:bg-gray-100 transition font-medium">
                                        Registreren
                                    </a>
                                </div>
                                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 rotate-45 w-3 h-3 bg-gray-900"></div>
                            </div>
                        </div>
                    @endauth

                    <div class="border-t pt-4">
                        <h4 class="font-semibold mb-3">Deel deze woning</h4>
                        <div class="flex space-x-2">
                            <button onclick="shareOnFacebook()" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </button>
                            <button onclick="shareOnWhatsApp()" class="flex-1 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                                <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </button>
                            <button onclick="shareViaEmail()" class="flex-1 bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>



            </div>

        </div>

    </div>

    <div x-show="lightboxOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 p-4"
         style="display: none;"
         @click.self="closeLightbox()">

        <button @click="closeLightbox()" class="absolute top-4 right-4 text-white hover:text-gray-300 transition z-50 bg-black/50 rounded-full p-3 hover:bg-black/70">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <button @click="prevImage()" class="absolute left-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 transition z-50 bg-black/50 rounded-full p-3 hover:bg-black/70">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <button @click="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 transition z-50 bg-black/50 rounded-full p-3 hover:bg-black/70">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <div class="absolute top-4 left-1/2 -translate-x-1/2 text-white bg-black/50 px-4 py-2 rounded-full z-50">
            <span x-text="(currentIndex + 1) + ' / ' + images.length"></span>
        </div>

        <div class="flex flex-col items-center justify-center w-full h-full max-w-7xl mx-auto">
            <div class="relative flex-1 flex items-center justify-center w-full mb-4 bg-gray-200 rounded-lg">
                <img :src="images[currentIndex]"
                     :alt="'Foto ' + (currentIndex + 1)"
                     class="max-h-[75vh] max-w-full object-contain"
                     @click.stop>
            </div>

            <div class="w-full bg-black/50 backdrop-blur-sm p-4 rounded-lg">
                <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800">
                    <template x-for="(image, index) in images" :key="index">
                        <div @click="currentIndex = index"
                             :class="currentIndex === index ? 'ring-4 ring-blue-500' : 'ring-2 ring-transparent hover:ring-white/50'"
                             class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden cursor-pointer transition-all">
                            <img :src="image"
                                 :alt="'Thumbnail ' + (index + 1)"
                                 class="w-full h-full object-cover">
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
                    @if($property->latitude && $property->longitude)
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const lat = {{ $property->latitude }};
                            const lng = {{ $property->longitude }};

                            const map = L.map('property-map').setView([lat, lng], 15);

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                                attribution: '© OpenStreetMap contributors'
                            }).addTo(map);

                            const customIcon = L.divIcon({
                                className: 'custom-marker',
                                html: `<div class="w-10 h-10 bg-blue-600 rounded-full border-4 border-white shadow-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>`,
                                iconSize: [40, 40],
                                iconAnchor: [20, 40],
                            });

                            const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

                            const popupContent = `
                                <div class="text-center p-2">
                                    <p class="font-semibold text-gray-900">{{ $property->address_street }} {{ $property->address_number }}{{ $property->address_addition }}</p>
                                    <p class="text-sm text-gray-600">{{ $property->address_postal_code }} {{ $property->address_city }}</p>
                                </div>
                            `;
                            marker.bindPopup(popupContent);
                        });
                    </script>
                    @endif
                    <script>
                        const shareUrl = window.location.href;
                        const shareTitle = '{{ addslashes($property->address_street) }} {{ $property->address_number }}{{ $property->address_addition }} - Oxxen.nl';
                        const shareText = 'Bekijk deze woning: {{ addslashes($property->address_street) }} {{ $property->address_number }}{{ $property->address_addition }} in {{ addslashes($property->address_city) }} voor {{ $property->formatted_price }}';

                        function shareOnFacebook() {
                            window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl), '_blank');
                        }

                        function shareOnWhatsApp() {
                            window.open('https://wa.me/?text=' + encodeURIComponent(shareText + ' ' + shareUrl), '_blank');
                        }

                        function shareViaEmail() {
                            window.location.href = 'mailto:?subject=' + encodeURIComponent(shareTitle) + '&body=' + encodeURIComponent(shareText + '\n\n' + shareUrl);
                        }

                        @auth
                        let isFavorited = {{ $property->isFavoritedByUser() ? 'true' : 'false' }};

                        async function toggleFavorite() {
                            const btn = document.getElementById('favorite-btn');
                            const btnSidebar = document.getElementById('favorite-btn-sidebar');
                            const icon = btn?.querySelector('svg');
                            const iconSidebar = document.getElementById('favorite-icon-sidebar');
                            const text = document.getElementById('favorite-text');

                            try {
                                const url = isFavorited
                                    ? '{{ route('property.unfavorite', $property->slug) }}'
                                    : '{{ route('property.favorite', $property->slug) }}';

                                const method = isFavorited ? 'DELETE' : 'POST';

                                const response = await fetch(url, {
                                    method: method,
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                });

                                const data = await response.json();

                                if (data.success) {
                                    isFavorited = data.is_favorited;

                                    [icon, iconSidebar].forEach(svg => {
                                        if (!svg) return;
                                        if (isFavorited) {
                                            svg.classList.remove('text-gray-400');
                                            svg.classList.add('text-red-500', 'fill-current');
                                            svg.setAttribute('fill', 'currentColor');
                                        } else {
                                            svg.classList.remove('text-red-500', 'fill-current');
                                            svg.classList.add('text-gray-400');
                                            svg.setAttribute('fill', 'none');
                                        }
                                    });

                                    if (text) {
                                        text.textContent = isFavorited ? 'Verwijderen uit favorieten' : 'Toevoegen aan favorieten';
                                    }
                                }
                            } catch (error) {
                                console.error('Error toggling favorite:', error);
                            }
                        }
                        @endauth
                    </script>

@endpush
@endsection

@extends('layouts.app')

@section('title', 'Wooon.nl - Vind je ideale woning in Nederland')
@section('meta_description', 'Wooon.nl is het complete onafhankelijke woonplatform voor Nederland. Vind koopwoningen, huurwoningen en nieuwbouw op één plek. Gratis zoekprofielen en meldingen.')

@section('meta')
<meta property="og:type" content="website">
<meta property="og:title" content="Wooon.nl - Vind je ideale woning in Nederland">
<meta property="og:description" content="Het complete onafhankelijke woonplatform voor Nederland. Vind koopwoningen, huurwoningen en nieuwbouw op één plek.">
<meta property="og:url" content="{{ url('/') }}">
@endsection

@section('content')
    <main class="container mx-auto px-4 py-8">

        <section class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-blue-800 text-white rounded-3xl p-12 mb-12 overflow-hidden">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0iZ3JpZCIgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIj48cGF0aCBkPSJNIDQwIDAgTCAwIDAgMCA0MCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMSkiIHN0cm9rZS13aWR0aD0iMSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIvPjwvc3ZnPg==')] opacity-30"></div>
            <div class="max-w-3xl mx-auto text-center relative z-10">
                <div class="inline-block mb-4">
                    <span class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold">🏡 Onafhankelijk & Compleet</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight">Vind je ideale woning</h1>
                <p class="text-xl mb-10 text-blue-100">Het complete onafhankelijke platform voor koop, huur en nieuwbouw</p>

                <form action="{{ route('search') }}" method="GET" class="bg-white/95 backdrop-blur-lg rounded-2xl p-6 shadow-2xl">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="relative" x-data="cityAutocomplete">
                            <input
                                type="text"
                                name="search"
                                placeholder="Plaats of postcode"
                                class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl text-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all"
                                x-model="search"
                                @input.debounce.300ms="filter()"
                                @keydown="handleKeydown($event)"
                                @focus="filter()"
                                @click.away="isOpen = false"
                                autocomplete="off"
                            >
                            <div
                                x-show="isOpen"
                                x-cloak
                                class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-2xl border-2 border-gray-100 max-h-64 overflow-y-auto"
                            >
                                <template x-for="(city, index) in filteredCities" :key="city.id">
                                    <div
                                        @click="selectCity(city)"
                                        :class="{'bg-purple-50': index === selectedIndex}"
                                        class="px-5 py-3 hover:bg-purple-50 cursor-pointer transition-colors border-b border-gray-100 last:border-b-0 text-gray-900"
                                        x-text="city.name"
                                    ></div>
                                </template>
                                <div
                                    x-show="filteredCities.length === 0 && search.length >= 2"
                                    class="px-5 py-3 text-gray-500 text-sm"
                                >
                                    Geen steden gevonden
                                </div>
                            </div>
                        </div>
                        <select name="type" class="px-5 py-4 border-2 border-gray-200 rounded-xl text-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                            <option value="">Koop / Huur / Nieuwbouw</option>
                            <option value="koop">Koop</option>
                            <option value="huur">Huur</option>
                            <option value="nieuwbouw">Nieuwbouw</option>
                        </select>
                        <input type="text" name="max_price" placeholder="Max prijs" class="px-5 py-4 border-2 border-gray-200 rounded-xl text-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                    </div>
                    <button type="submit" class="w-full mt-5 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-bold text-lg hover:shadow-2xl hover:scale-[1.02] transition-all duration-200">
                        🔍 Zoeken
                    </button>
                </form>
            </div>
        </section>

        <section class="mb-12">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">Uitgelicht aanbod</h2>
                <a href="{{ route('search') }}" class="text-blue-600 hover:text-purple-600 font-semibold flex items-center group">
                    Bekijk alles
                    <svg class="w-5 h-5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            @if($featuredProperties->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($featuredProperties as $property)
                        <a href="{{ route('property.show', $property) }}" class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 group border border-gray-100">
                            <div class="relative h-56 overflow-hidden">
                                @if($property->proxied_main_image)
                                    <img src="{{ $property->proxied_main_image }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">Geen foto beschikbaar</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                @if($property->days_online !== null && $property->days_online <= 3)
                                    <div class="absolute top-3 right-3">
                                        <span class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg">✨ Nieuw</span>
                                    </div>
                                @endif
                                <div class="absolute bottom-3 left-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="bg-white/90 backdrop-blur-sm text-gray-900 px-4 py-2 rounded-full text-sm font-semibold hover:bg-white transition-all">Bekijk details</button>
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-xl mb-2 text-gray-900 group-hover:text-blue-600 transition-colors">{{ $property->title ?? $property->full_address }}</h3>
                                @if($property->address_city || $property->address_postal_code)
                                    <p class="text-gray-600 text-sm mb-2">
                                        @if($property->address_postal_code){{ $property->address_postal_code }}@endif
                                        @if($property->address_city && $property->address_postal_code), @endif
                                        @if($property->address_city){{ $property->address_city }}@endif
                                    </p>
                                @endif
                                <p class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent font-bold text-2xl mb-3">{{ $property->formatted_price }}</p>
                                <div class="flex items-center text-sm text-gray-600 space-x-4">
                                    @if($property->bedrooms)
                                        <span class="flex items-center bg-gray-50 px-3 py-1.5 rounded-lg">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                            {{ $property->bedrooms }} kamers
                                        </span>
                                    @endif
                                    @if($property->surface)
                                        <span class="bg-gray-50 px-3 py-1.5 rounded-lg">{{ $property->surface }} m²</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <div class="text-6xl mb-4">🏠</div>
                    <h3 class="text-2xl font-bold mb-2">Nog geen woningen beschikbaar</h3>
                    <p class="text-gray-600">Binnenkort tonen we hier onze uitgelichte woningen</p>
                </div>
            @endif
        </section>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <a href="{{ route('search') }}" class="relative bg-gradient-to-br from-blue-50 to-white rounded-2xl p-8 border-2 border-blue-100 hover:border-blue-300 hover:shadow-xl transition-all duration-300 text-center group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100 rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 group-hover:text-blue-600 transition-colors">Nieuwbouw projecten</h3>
                    <p class="text-gray-600">Ontdek de nieuwste bouwprojecten in heel Nederland</p>
                </div>
            </a>
            <a href="{{ route('search') }}" class="relative bg-gradient-to-br from-purple-50 to-white rounded-2xl p-8 border-2 border-purple-100 hover:border-purple-300 hover:shadow-xl transition-all duration-300 text-center group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-purple-100 rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 group-hover:text-purple-600 transition-colors">Huurwoningen</h3>
                    <p class="text-gray-600">Vind je perfecte huurwoning via corporaties en particulier</p>
                </div>
            </a>
            <a href="{{ route('search') }}" class="relative bg-gradient-to-br from-cyan-50 to-white rounded-2xl p-8 border-2 border-cyan-100 hover:border-cyan-300 hover:shadow-xl transition-all duration-300 text-center group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-100 rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="bg-gradient-to-br from-cyan-500 to-blue-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 group-hover:text-cyan-600 transition-colors">Bestaande koopwoningen</h3>
                    <p class="text-gray-600">Zoek in het complete aanbod van koopwoningen</p>
                </div>
            </a>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <a href="{{ route('mortgage.calculator') }}" class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 group">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900 group-hover:text-green-600 transition-colors">Bereken je maandlasten</h3>
                <p class="text-gray-600 mb-6">Bereken eenvoudig je hypotheeklasten per maand met actuele rentetarieven</p>
                <span class="inline-flex items-center text-green-600 hover:text-green-700 font-semibold group">
                    Start calculator
                    <svg class="w-5 h-5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            </a>
            <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                <div class="bg-gradient-to-br from-orange-500 to-red-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Bereken je woningwaarde</h3>
                <p class="text-gray-600 mb-6">Krijg direct inzicht in de waarde van je woning met onze waardecheck</p>
                <input type="text" placeholder="Postcode" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl mb-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                <input type="text" placeholder="Huisnummer" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl mb-4 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                <button class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition-all duration-200">
                    Waarde berekenen
                </button>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                <div class="bg-gradient-to-br from-purple-500 to-pink-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Plan afspraak met makelaar</h3>
                <p class="text-gray-600 mb-6">Laat je begeleiden door een erkende makelaar uit ons netwerk</p>
                <input type="text" placeholder="Naam" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl mb-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                <input type="email" placeholder="E-mailadres" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl mb-4 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                <button class="w-full bg-gradient-to-r from-purple-500 to-pink-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition-all duration-200">
                    Afspraak maken
                </button>
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6">Informatie & inspiratie</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300 group">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=600&h=400&fit=crop" alt="Nieuwbouw tips" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2 text-gray-900">Tips voor het kopen van een nieuwbouwwoning</h3>
                        <p class="text-gray-600 text-sm mb-4">Ontdek waar je op moet letten bij de aankoop van een nieuwbouwwoning</p>
                        <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold inline-flex items-center">
                            Lees meer
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300 group">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=600&h=400&fit=crop" alt="Energielabel" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2 text-gray-900">Duurzaam wonen: energielabels uitgelegd</h3>
                        <p class="text-gray-600 text-sm mb-4">Alles wat je moet weten over energielabels en duurzaamheid</p>
                        <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold inline-flex items-center">
                            Lees meer
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300 group">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1460317442991-0ec209397118?w=600&h=400&fit=crop" alt="Woningmarkt 2025" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2 text-gray-900">De woningmarkt in 2025</h3>
                        <p class="text-gray-600 text-sm mb-4">Trends en ontwikkelingen op de Nederlandse woningmarkt</p>
                        <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold inline-flex items-center">
                            Lees meer
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection
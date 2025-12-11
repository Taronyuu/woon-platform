@extends('layouts.app')

@section('title', 'Mijn Account - Oxxen.nl')

@section('meta')
<meta name="robots" content="noindex, nofollow">
@endsection

@section('content')
<div x-data="searchProfileManager()" class="container mx-auto px-4 py-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Welkom, {{ auth()->user()->first_name ?? auth()->user()->email }}</h1>
        <p class="text-gray-600">Beheer je account, zoekprofielen en favoriete woningen</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <aside class="lg:col-span-1">
            <nav class="bg-white rounded-lg shadow">
                <a href="#gegevens" class="block px-6 py-4 border-l-4 border-blue-600 bg-blue-50 text-blue-600 font-semibold">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Mijn gegevens
                </a>
                <a href="#zoekprofielen" class="block px-6 py-4 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300 text-gray-700">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Zoekprofielen
                    <span class="float-right bg-blue-600 text-white text-xs px-2 py-1 rounded-full">{{ $searchProfiles->count() }}</span>
                </a>
                <a href="#favorieten" class="block px-6 py-4 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300 text-gray-700">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    Favorieten
                    <span class="float-right bg-blue-600 text-white text-xs px-2 py-1 rounded-full">{{ $favorites->count() }}</span>
                </a>
                <a href="#notificaties" class="block px-6 py-4 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300 text-gray-700">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    Notificaties
                </a>
            </nav>
        </aside>

        <main class="lg:col-span-3">

            <section id="gegevens" class="bg-white rounded-lg shadow p-8 mb-6">
                <h2 class="text-2xl font-bold mb-6">Mijn gegevens</h2>

                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Voornaam</label>
                            <input type="text" name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('first_name') border-red-500 @enderror">
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Achternaam</label>
                            <input type="text" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('last_name') border-red-500 @enderror">
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">E-mailadres</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Telefoonnummer</label>
                            <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button" onclick="window.location.reload()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Annuleren
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Opslaan
                        </button>
                    </div>
                </form>
            </section>

            <section id="zoekprofielen" class="bg-white rounded-lg shadow p-8 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold">Zoekprofielen</h2>
                    <button
                        @click="openModal()"
                        :disabled="profiles.length >= 5"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nieuw profiel
                    </button>
                </div>

                <p class="text-gray-600 text-sm mb-6">Maak zoekprofielen aan om e-mail alerts te ontvangen wanneer er nieuwe woningen zijn die matchen met je zoekcriteria. Je kunt maximaal 5 profielen aanmaken.</p>

                <div x-show="profiles.length === 0" class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-gray-600 mb-4">Je hebt nog geen zoekprofielen</p>
                    <button @click="openModal()" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Maak je eerste zoekprofiel
                    </button>
                </div>

                <div x-show="profiles.length > 0" class="space-y-4">
                    <template x-for="profile in profiles" :key="profile.id">
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h3 class="font-semibold text-lg" x-text="profile.name"></h3>
                                        <span
                                            x-show="profile.is_active"
                                            class="ml-2 px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full"
                                        >Actief</span>
                                        <span
                                            x-show="!profile.is_active"
                                            class="ml-2 px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded-full"
                                        >Inactief</span>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <span x-show="profile.transaction_type" class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">
                                            <span x-text="profile.transaction_type === 'sale' ? 'Koop' : 'Huur'"></span>
                                        </span>
                                        <span x-show="profile.cities && profile.cities.length > 0" class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded">
                                            <span x-text="profile.cities ? profile.cities.join(', ') : ''"></span>
                                        </span>
                                        <span x-show="profile.min_price || profile.max_price" class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">
                                            <span x-text="formatPriceRange(profile.min_price, profile.max_price)"></span>
                                        </span>
                                        <span x-show="profile.min_surface || profile.max_surface" class="px-2 py-1 text-xs bg-orange-100 text-orange-800 rounded">
                                            <span x-text="formatSurfaceRange(profile.min_surface, profile.max_surface)"></span>
                                        </span>
                                        <span x-show="profile.min_bedrooms" class="px-2 py-1 text-xs bg-pink-100 text-pink-800 rounded">
                                            <span x-text="profile.min_bedrooms + '+ slaapkamers'"></span>
                                        </span>
                                        <span x-show="profile.energy_label" class="px-2 py-1 text-xs bg-teal-100 text-teal-800 rounded">
                                            Energielabel <span x-text="profile.energy_label"></span>+
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <button
                                        @click="toggleActive(profile)"
                                        class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100"
                                        :title="profile.is_active ? 'Deactiveren' : 'Activeren'"
                                    >
                                        <svg x-show="profile.is_active" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="!profile.is_active" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                        </svg>
                                    </button>
                                    <button
                                        @click="editProfile(profile)"
                                        class="p-2 text-gray-400 hover:text-blue-600 rounded-lg hover:bg-blue-50"
                                        title="Bewerken"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button
                                        @click="deleteProfile(profile)"
                                        class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50"
                                        title="Verwijderen"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <section id="favorieten" class="bg-white rounded-lg shadow p-8 mb-6">
                <h2 class="text-2xl font-bold mb-6">Mijn favorieten</h2>

                @if($favorites->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($favorites as $property)
                            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                                <div class="relative h-48 overflow-hidden">
                                    @if($property->main_image)
                                        <img src="{{ $property->main_image }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="bg-gray-300 h-full flex items-center justify-center">
                                            <span class="text-gray-600">Geen foto beschikbaar</span>
                                        </div>
                                    @endif
                                    <form action="{{ route('property.unfavorite', $property) }}" method="POST" class="absolute top-4 right-4">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-white p-2 rounded-full shadow-lg hover:bg-gray-100">
                                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-lg mb-2">{{ $property->title }}</h3>
                                    <p class="font-semibold text-blue-600 mb-1">{{ $property->formatted_price }}</p>
                                    <p class="text-gray-600 text-sm mb-1">{{ $property->full_address }}</p>
                                    <p class="text-gray-500 text-xs">
                                        @if($property->bedrooms)
                                            {{ $property->bedrooms }} kamers
                                        @endif
                                        @if($property->bedrooms && $property->surface)
                                            •
                                        @endif
                                        @if($property->surface)
                                            {{ $property->surface }} m²
                                        @endif
                                    </p>
                                    <a href="{{ route('property.show', $property) }}" class="text-blue-600 text-sm hover:text-blue-700 mt-2 inline-block">
                                        Bekijk woning →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <p class="text-gray-600 mb-4">Je hebt nog geen favoriete woningen</p>
                        <a href="{{ route('search') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                            Bekijk woningen
                        </a>
                    </div>
                @endif
            </section>

            <section id="notificaties" class="bg-white rounded-lg shadow p-8">
                <h2 class="text-2xl font-bold mb-6">Notificaties</h2>

                @if(session('notification_success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                        {{ session('notification_success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.notifications.update') }}">
                    @csrf

                    <div class="space-y-4">

                        <div class="flex items-start justify-between py-4 border-b border-gray-200">
                            <div class="flex-1">
                                <h3 class="font-semibold mb-1">E-mail alerts voor nieuwe woningen</h3>
                                <p class="text-sm text-gray-600">Ontvang direct een e-mail wanneer er nieuwe woningen zijn die matchen met je zoekprofielen</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_new_properties" value="1" {{ auth()->user()->notify_new_properties ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-start justify-between py-4 border-b border-gray-200">
                            <div class="flex-1">
                                <h3 class="font-semibold mb-1">Prijswijzigingen favorieten</h3>
                                <p class="text-sm text-gray-600">Krijg een melding wanneer de prijs van een favoriet gewijzigd is</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_price_changes" value="1" {{ auth()->user()->notify_price_changes ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-start justify-between py-4 border-b border-gray-200">
                            <div class="flex-1">
                                <h3 class="font-semibold mb-1">Nieuwsbrief</h3>
                                <p class="text-sm text-gray-600">Ontvang wekelijks tips, trends en inspiratie over wonen</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_newsletter" value="1" {{ auth()->user()->notify_newsletter ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-start justify-between py-4 border-b border-gray-200">
                            <div class="flex-1">
                                <h3 class="font-semibold mb-1">Marketing e-mails</h3>
                                <p class="text-sm text-gray-600">Ontvang aanbiedingen en acties van Oxxen.nl en partners</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_marketing" value="1" {{ auth()->user()->notify_marketing ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Opslaan
                        </button>
                    </div>
                </form>
            </section>

        </main>

    </div>

    <div
        x-show="showModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div
                x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="closeModal()"
            ></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                x-show="showModal"
                @click.stop
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative z-10 inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
            >
                <div>
                    <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" x-text="editingProfile ? 'Zoekprofiel bewerken' : 'Nieuw zoekprofiel'"></h3>

                    <div x-show="modalError" class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm" x-text="modalError"></div>

                    <form @submit.prevent="saveProfile()">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Naam *</label>
                                <input
                                    type="text"
                                    x-model="form.name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    placeholder="Bijv. Appartement Amsterdam"
                                    required
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <select x-model="form.transaction_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="">Alle</option>
                                    <option value="sale">Koop</option>
                                    <option value="rent">Huur</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Steden</label>
                                <input
                                    type="text"
                                    x-model="citiesInput"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    placeholder="Bijv. Amsterdam, Rotterdam, Utrecht"
                                >
                                <p class="text-xs text-gray-500 mt-1">Scheid steden met een komma</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Min. prijs</label>
                                    <input
                                        type="number"
                                        x-model="form.min_price"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                        placeholder="0"
                                        min="0"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Max. prijs</label>
                                    <input
                                        type="number"
                                        x-model="form.max_price"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                        placeholder="Geen limiet"
                                        min="0"
                                    >
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Min. oppervlakte (m²)</label>
                                    <input
                                        type="number"
                                        x-model="form.min_surface"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                        placeholder="0"
                                        min="0"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Max. oppervlakte (m²)</label>
                                    <input
                                        type="number"
                                        x-model="form.max_surface"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                        placeholder="Geen limiet"
                                        min="0"
                                    >
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Min. slaapkamers</label>
                                <select x-model="form.min_bedrooms" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="">Geen voorkeur</option>
                                    <option value="1">1+</option>
                                    <option value="2">2+</option>
                                    <option value="3">3+</option>
                                    <option value="4">4+</option>
                                    <option value="5">5+</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Min. energielabel</label>
                                <select x-model="form.energy_label" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="">Geen voorkeur</option>
                                    <option value="A+++">A+++</option>
                                    <option value="A++">A++</option>
                                    <option value="A+">A+</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button
                                type="button"
                                @click="closeModal()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
                            >
                                Annuleren
                            </button>
                            <button
                                type="submit"
                                :disabled="saving"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                            >
                                <span x-show="!saving" x-text="editingProfile ? 'Opslaan' : 'Aanmaken'"></span>
                                <span x-show="saving">Bezig...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function searchProfileManager() {
    return {
        profiles: @json($searchProfiles),
        showModal: false,
        editingProfile: null,
        saving: false,
        modalError: '',
        citiesInput: '',
        form: {
            name: '',
            transaction_type: '',
            cities: [],
            min_price: '',
            max_price: '',
            min_surface: '',
            max_surface: '',
            min_bedrooms: '',
            property_type: '',
            energy_label: '',
            is_active: true
        },

        openModal() {
            this.editingProfile = null;
            this.resetForm();
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.editingProfile = null;
            this.modalError = '';
            this.resetForm();
        },

        resetForm() {
            this.form = {
                name: '',
                transaction_type: '',
                cities: [],
                min_price: '',
                max_price: '',
                min_surface: '',
                max_surface: '',
                min_bedrooms: '',
                property_type: '',
                energy_label: '',
                is_active: true
            };
            this.citiesInput = '';
        },

        editProfile(profile) {
            this.editingProfile = profile;
            this.form = { ...profile };
            this.citiesInput = profile.cities ? profile.cities.join(', ') : '';
            this.showModal = true;
        },

        async saveProfile() {
            this.saving = true;
            this.modalError = '';

            this.form.cities = this.citiesInput
                .split(',')
                .map(c => c.trim())
                .filter(c => c.length > 0);

            const url = this.editingProfile
                ? `/account/zoekprofielen/${this.editingProfile.id}`
                : '/account/zoekprofielen';

            const method = this.editingProfile ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });

                const data = await response.json();

                if (data.success) {
                    if (this.editingProfile) {
                        const index = this.profiles.findIndex(p => p.id === this.editingProfile.id);
                        if (index !== -1) {
                            this.profiles[index] = data.profile;
                        }
                    } else {
                        this.profiles.unshift(data.profile);
                    }
                    this.closeModal();
                } else {
                    this.modalError = data.message || 'Er is een fout opgetreden.';
                }
            } catch (error) {
                this.modalError = 'Er is een fout opgetreden. Probeer het opnieuw.';
            }

            this.saving = false;
        },

        async toggleActive(profile) {
            try {
                const response = await fetch(`/account/zoekprofielen/${profile.id}/toggle`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    profile.is_active = data.is_active;
                }
            } catch (error) {
                console.error('Toggle failed:', error);
            }
        },

        async deleteProfile(profile) {
            if (!confirm('Weet je zeker dat je dit zoekprofiel wilt verwijderen?')) {
                return;
            }

            try {
                const response = await fetch(`/account/zoekprofielen/${profile.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.profiles = this.profiles.filter(p => p.id !== profile.id);
                }
            } catch (error) {
                console.error('Delete failed:', error);
            }
        },

        formatPriceRange(min, max) {
            const formatPrice = (price) => {
                if (!price) return null;
                return new Intl.NumberFormat('nl-NL', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(price);
            };

            if (min && max) {
                return `${formatPrice(min)} - ${formatPrice(max)}`;
            } else if (min) {
                return `Vanaf ${formatPrice(min)}`;
            } else if (max) {
                return `Tot ${formatPrice(max)}`;
            }
            return '';
        },

        formatSurfaceRange(min, max) {
            if (min && max) {
                return `${min} - ${max} m²`;
            } else if (min) {
                return `Vanaf ${min} m²`;
            } else if (max) {
                return `Tot ${max} m²`;
            }
            return '';
        }
    }
}
</script>
@endpush
@endsection

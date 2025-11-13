@extends('layouts.app')

@section('title', 'Mijn Account - Wooon.nl')

@section('content')


    <div class="container mx-auto px-4 py-8">

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
                    <a href="#favorieten" class="block px-6 py-4 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300 text-gray-700">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Favorieten
                        <span class="float-right bg-blue-600 text-white text-xs px-2 py-1 rounded-full">5</span>
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
                                        <h3 class="font-semibold text-lg mb-1">{{ $property->formatted_price }}</h3>
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
                                    <p class="text-sm text-gray-600">Ontvang aanbiedingen en acties van Wooon.nl en partners</p>
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
    </div>

@endsection

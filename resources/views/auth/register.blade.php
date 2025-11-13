@extends('layouts.app')

@section('title', 'Registreren - Wooon.nl')

@section('content')


    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold mb-2">Registreren</h1>
                <p class="text-gray-600">Maak een account aan om je favoriete woningen op te slaan</p>
            </div>

            <div class="bg-white rounded-lg shadow p-8">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-4">Inloggegevens</h2>

                        <div class="space-y-4">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mailadres *</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('email') border-red-500 @enderror"
                                >
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Wachtwoord *</label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('password') border-red-500 @enderror"
                                >
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Bevestig wachtwoord *</label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 border-t pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold">Persoonlijke gegevens</h2>
                            <span class="text-sm text-gray-500">(optioneel)</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Voornaam</label>
                                <input
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    value="{{ old('first_name') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                >
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Achternaam</label>
                                <input
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    value="{{ old('last_name') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                >
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Telefoonnummer</label>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                >
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adres</label>
                                <input
                                    type="text"
                                    id="address"
                                    name="address"
                                    value="{{ old('address') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                >
                            </div>

                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Postcode</label>
                                <input
                                    type="text"
                                    id="postal_code"
                                    name="postal_code"
                                    value="{{ old('postal_code') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                >
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Plaats</label>
                                <input
                                    type="text"
                                    id="city"
                                    name="city"
                                    value="{{ old('city') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <p class="text-sm text-gray-600">
                            Heb je al een account?
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold">Inloggen</a>
                        </p>
                        <button
                            type="submit"
                            class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold"
                        >
                            Account aanmaken
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@extends('layouts.app')

@section('title', 'Wachtwoord vergeten - Oxxen.nl')

@section('meta')
<meta name="robots" content="noindex, nofollow">
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Oxxen.nl</span>
            </a>
            <h2 class="mt-6 text-2xl font-bold text-gray-900">Wachtwoord vergeten?</h2>
            <p class="mt-2 text-gray-600">Geen probleem. Vul je e-mailadres in en we sturen je een link om je wachtwoord te herstellen.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if(session('status'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mailadres</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                        placeholder="naam@voorbeeld.nl"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Verstuur herstel-link
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                    Terug naar inloggen
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

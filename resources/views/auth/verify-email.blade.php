@extends('layouts.app')

@section('title', 'Bevestig je e-mailadres - Wooon.nl')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Wooon.nl</span>
            </a>
            <h2 class="mt-6 text-2xl font-bold text-gray-900">Bevestig je e-mailadres</h2>
            <p class="mt-2 text-gray-600">We hebben een verificatielink naar <strong>{{ auth()->user()->email }}</strong> gestuurd.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if(session('status'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="text-center">
                <div class="mb-6">
                    <svg class="w-16 h-16 mx-auto text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>

                <p class="text-gray-600 mb-6">
                    Klik op de link in de e-mail om je account te activeren. Controleer ook je spam-map als je de e-mail niet kunt vinden.
                </p>

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Verstuur opnieuw
                    </button>
                </form>

                <div class="mt-6">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-800">
                            Uitloggen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

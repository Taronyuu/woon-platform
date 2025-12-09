@extends('layouts.app')

@section('title', 'Over Oxxen.nl')

@section('content')
<div class="bg-gradient-to-r from-orange-500 to-red-500 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-white">Over Oxxen.nl</h1>
        <p class="text-white/80 mt-2">Het complete onafhankelijke woonplatform voor Nederland</p>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Onze missie</h2>
            <p class="text-gray-600 text-lg leading-relaxed">
                Oxxen.nl maakt het zoeken naar een nieuwe woning eenvoudig en overzichtelijk. Als onafhankelijk platform verzamelen wij woningen van alle bronnen op één plek: nieuwbouwprojecten, huurwoningen en bestaande koopwoningen. Zo heeft u altijd het complete overzicht en hoeft u niet meer meerdere websites te bezoeken.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Alle woningen</h3>
                <p class="text-gray-600 text-sm">Nieuwbouw, huur en koop op één platform</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Slim zoeken</h3>
                <p class="text-gray-600 text-sm">Verfijnde filters en zoekprofielen</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Direct op de hoogte</h3>
                <p class="text-gray-600 text-sm">Meldingen bij nieuwe woningen</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Waarom Oxxen.nl?</h2>
            <div class="space-y-4 text-gray-600">
                <p>
                    De Nederlandse woningmarkt is complex en versnipperd. Woningen staan verspreid over tientallen websites van makelaars, projectontwikkelaars en verhuurders. Oxxen.nl brengt al deze woningen samen op één overzichtelijk platform.
                </p>
                <p>
                    Of u nu een starter bent die zijn eerste huurwoning zoekt, een gezin dat een ruimer koophuis wil, of iemand die geïnteresseerd is in een nieuwbouwproject - bij Oxxen.nl vindt u het allemaal.
                </p>
                <p>
                    Met handige functies zoals zoekprofielen en meldingen mist u nooit meer uw droomwoning. Sla uw favoriete woningen op, vergelijk ze met elkaar en neem direct contact op met de verkopende partij.
                </p>
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-lg p-8 text-white text-center">
            <h2 class="text-2xl font-bold mb-4">Start vandaag nog met zoeken</h2>
            <p class="text-white/90 mb-6">Maak gratis een account aan en ontvang meldingen bij nieuwe woningen</p>
            <a href="{{ route('register') }}" class="inline-block bg-white text-orange-500 font-semibold px-8 py-3 rounded-lg hover:bg-gray-100 transition-colors">
                Gratis registreren
            </a>
        </div>
    </div>
</div>
@endsection

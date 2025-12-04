@extends('layouts.app')

@section('title', 'Contact - Wooon.nl')

@section('content')
<div class="bg-gradient-to-r from-orange-500 to-red-500 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-white">Contact</h1>
        <p class="text-white/80 mt-2">Wij helpen u graag verder</p>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white rounded-lg shadow-sm p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Neem contact op</h2>

                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">E-mail</h3>
                            <p class="text-gray-600">Algemene vragen</p>
                            <a href="mailto:info@wooon.nl" class="text-orange-500 hover:text-orange-600">info@wooon.nl</a>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Telefoon</h3>
                            <p class="text-gray-600">Ma-vr: 09:00 - 17:00</p>
                            <a href="tel:+31201234567" class="text-orange-500 hover:text-orange-600">020-1234567</a>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Adres</h3>
                            <p class="text-gray-600">
                                Wooon.nl B.V.<br>
                                Herengracht 100<br>
                                1015 BS Amsterdam
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Veelgestelde vragen</h2>

                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Hoe maak ik een account aan?</h3>
                        <p class="text-gray-600 text-sm">Klik rechtsboven op 'Registreren' en vul uw e-mailadres en wachtwoord in. U ontvangt een verificatie-e-mail om uw account te activeren.</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Wat zijn zoekprofielen?</h3>
                        <p class="text-gray-600 text-sm">Met zoekprofielen kunt u uw zoekcriteria opslaan. U ontvangt automatisch een melding wanneer er nieuwe woningen zijn die aan uw criteria voldoen.</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Hoe neem ik contact op met een makelaar?</h3>
                        <p class="text-gray-600 text-sm">Op de detailpagina van elke woning vindt u de contactgegevens van de verkopende partij of makelaar.</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Is Wooon.nl gratis?</h3>
                        <p class="text-gray-600 text-sm">Ja, het gebruik van Wooon.nl is volledig gratis voor woningzoekers. U kunt onbeperkt zoeken, favorieten opslaan en zoekprofielen aanmaken.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-8 mt-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Voor makelaars en professionals</h2>
            <p class="text-gray-600 mb-4">
                Bent u makelaar of projectontwikkelaar en wilt u uw woningen op Wooon.nl plaatsen? Neem contact met ons op voor meer informatie over onze partnermogelijkheden.
            </p>
            <a href="mailto:partners@wooon.nl" class="inline-flex items-center text-orange-500 hover:text-orange-600 font-medium">
                partners@wooon.nl
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Privacybeleid - Oxxen.nl')
@section('meta_description', 'Lees het privacybeleid van Oxxen.nl. Wij respecteren uw privacy en beschermen uw persoonsgegevens conform de AVG.')

@section('canonical')
<link rel="canonical" href="{{ route('privacy') }}">
@endsection

@section('structured-data')
@php
$breadcrumbItems = [
    ['name' => 'Home', 'url' => route('home')],
    ['name' => 'Privacybeleid', 'url' => route('privacy')]
];
@endphp
<x-seo.breadcrumb-schema :items="$breadcrumbItems" />
@endsection

@section('meta')
<meta name="robots" content="noindex, follow">
<meta property="og:type" content="website">
<meta property="og:title" content="Privacybeleid - Oxxen.nl">
<meta property="og:description" content="Lees het privacybeleid van Oxxen.nl.">
<meta property="og:url" content="{{ route('privacy') }}">
@endsection

@section('content')
<div class="bg-gradient-to-r from-orange-500 to-red-500 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-white">Privacybeleid</h1>
        <p class="text-white/80 mt-2">Laatst bijgewerkt: {{ now()->format('d F Y') }}</p>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm p-8">
        <div class="prose prose-lg max-w-none">
            <h2>1. Inleiding</h2>
            <p>Oxxen.nl respecteert de privacy van alle gebruikers van onze website en draagt er zorg voor dat de persoonlijke informatie die u ons verschaft vertrouwelijk wordt behandeld. Wij gebruiken uw gegevens om bezoeken aan onze website zo gemakkelijk mogelijk te maken en om u te voorzien van relevante woninginformatie.</p>

            <h2>2. Welke gegevens verzamelen wij?</h2>
            <p>Wanneer u zich registreert op Oxxen.nl, vragen wij u om de volgende gegevens:</p>
            <ul>
                <li>Naam en e-mailadres</li>
                <li>Telefoonnummer (optioneel)</li>
                <li>Adresgegevens (optioneel)</li>
                <li>Zoekcriteria en voorkeuren voor woningen</li>
            </ul>

            <h2>3. Waarvoor gebruiken wij uw gegevens?</h2>
            <p>Uw gegevens worden gebruikt voor de volgende doeleinden:</p>
            <ul>
                <li>Het aanmaken en beheren van uw account</li>
                <li>Het opslaan van uw favoriete woningen</li>
                <li>Het versturen van meldingen over nieuwe woningen die passen bij uw zoekprofiel</li>
                <li>Het verbeteren van onze dienstverlening</li>
                <li>Het versturen van nieuwsbrieven (indien u zich hiervoor heeft aangemeld)</li>
            </ul>

            <h2>4. Delen van gegevens</h2>
            <p>Oxxen.nl verkoopt uw gegevens niet aan derden. Wij kunnen uw gegevens delen met:</p>
            <ul>
                <li>Makelaars en verkopende partijen wanneer u interesse toont in een woning</li>
                <li>Technische dienstverleners die ons helpen bij het beheren van de website</li>
            </ul>

            <h2>5. Beveiliging</h2>
            <p>Wij nemen passende beveiligingsmaatregelen om misbruik van en ongeautoriseerde toegang tot uw persoonsgegevens te beperken. Zo zorgen wij dat alleen de noodzakelijke personen toegang hebben tot de gegevens en dat de toegang tot de gegevens afgeschermd is.</p>

            <h2>6. Cookies</h2>
            <p>Oxxen.nl maakt gebruik van cookies om uw gebruikerservaring te verbeteren. Een cookie is een klein tekstbestand dat bij het eerste bezoek aan deze website wordt opgeslagen op uw computer, tablet of smartphone. Lees meer over ons cookiebeleid in onze cookieverklaring.</p>

            <h2>7. Uw rechten</h2>
            <p>U heeft het recht om uw persoonsgegevens in te zien, te corrigeren of te verwijderen. Daarnaast heeft u het recht om uw eventuele toestemming voor de gegevensverwerking in te trekken. U kunt een verzoek hiertoe sturen naar privacy@oxxen.nl.</p>

            <h2>8. Contact</h2>
            <p>Indien u vragen heeft over dit privacybeleid, kunt u contact met ons opnemen via:</p>
            <p>
                E-mail: privacy@oxxen.nl<br>
                Telefoon: 020-1234567
            </p>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Algemene Voorwaarden - Oxxen.nl')

@section('content')
<div class="bg-gradient-to-r from-orange-500 to-red-500 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-white">Algemene Voorwaarden</h1>
        <p class="text-white/80 mt-2">Laatst bijgewerkt: {{ now()->format('d F Y') }}</p>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm p-8">
        <div class="prose prose-lg max-w-none">
            <h2>1. Definities</h2>
            <p>In deze algemene voorwaarden wordt verstaan onder:</p>
            <ul>
                <li><strong>Oxxen.nl:</strong> het online woonplatform gevestigd te Amsterdam</li>
                <li><strong>Gebruiker:</strong> iedere natuurlijke of rechtspersoon die gebruik maakt van de diensten van Oxxen.nl</li>
                <li><strong>Diensten:</strong> alle door Oxxen.nl aangeboden functionaliteiten, waaronder het zoeken naar woningen, opslaan van favorieten, en het aanmaken van zoekprofielen</li>
            </ul>

            <h2>2. Toepasselijkheid</h2>
            <p>Deze algemene voorwaarden zijn van toepassing op elk gebruik van de website en diensten van Oxxen.nl. Door gebruik te maken van onze website, accepteert u deze voorwaarden.</p>

            <h2>3. Account en registratie</h2>
            <p>Om bepaalde functies te gebruiken, dient u zich te registreren. U bent verantwoordelijk voor:</p>
            <ul>
                <li>Het verstrekken van juiste en actuele informatie</li>
                <li>Het geheim houden van uw inloggegevens</li>
                <li>Alle activiteiten die onder uw account plaatsvinden</li>
            </ul>

            <h2>4. Gebruik van de dienst</h2>
            <p>Het is niet toegestaan om:</p>
            <ul>
                <li>De website te gebruiken voor onwettige doeleinden</li>
                <li>Geautomatiseerde systemen te gebruiken om data van de website te verzamelen</li>
                <li>Valse of misleidende informatie te verstrekken</li>
                <li>De werking van de website te verstoren of te beschadigen</li>
            </ul>

            <h2>5. Intellectueel eigendom</h2>
            <p>Alle inhoud op Oxxen.nl, inclusief maar niet beperkt tot teksten, afbeeldingen, logo's en software, is eigendom van Oxxen.nl of haar licentiegevers en is beschermd door auteursrecht en andere intellectuele eigendomsrechten.</p>

            <h2>6. Aansprakelijkheid</h2>
            <p>Oxxen.nl spant zich in om accurate en actuele informatie te verstrekken, maar kan geen garanties geven over:</p>
            <ul>
                <li>De juistheid of volledigheid van de woninginformatie</li>
                <li>De beschikbaarheid van getoonde woningen</li>
                <li>De ononderbroken werking van de website</li>
            </ul>
            <p>Oxxen.nl is niet aansprakelijk voor schade die voortvloeit uit het gebruik van de website of diensten, voor zover wettelijk toegestaan.</p>

            <h2>7. Privacy</h2>
            <p>Het verwerken van persoonsgegevens gebeurt in overeenstemming met ons privacybeleid. Lees ons <a href="{{ route('privacy') }}">privacybeleid</a> voor meer informatie.</p>

            <h2>8. Wijzigingen</h2>
            <p>Oxxen.nl behoudt zich het recht voor om deze algemene voorwaarden te allen tijde te wijzigen. Wijzigingen worden op deze pagina gepubliceerd en zijn direct van kracht na publicatie.</p>

            <h2>9. Toepasselijk recht</h2>
            <p>Op deze algemene voorwaarden en het gebruik van Oxxen.nl is Nederlands recht van toepassing. Geschillen worden voorgelegd aan de bevoegde rechter te Amsterdam.</p>

            <h2>10. Contact</h2>
            <p>Voor vragen over deze voorwaarden kunt u contact met ons opnemen via:</p>
            <p>
                E-mail: info@oxxen.nl<br>
                Telefoon: 020-1234567
            </p>
        </div>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berichten - Wooon.nl</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>
<body class="bg-gray-50">

    @include('partials.header')

    <main class="container mx-auto px-4 py-8">

        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-2">Berichten</h1>
            <p class="text-gray-600">Communiceer met klanten en prospects over woningen</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-1 bg-white rounded-lg shadow">
                <div class="p-4 border-b border-gray-200">
                    <div class="relative">
                        <input type="text" placeholder="Zoek conversaties..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <div class="flex space-x-2 mt-3">
                        <button class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg">Alle</button>
                        <button class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">Ongelezen</button>
                        <button class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">Archief</button>
                    </div>
                </div>

                <div class="divide-y divide-gray-200 overflow-y-auto" style="max-height: 600px">

                    <div class="p-4 hover:bg-blue-50 cursor-pointer bg-blue-50 border-l-4 border-blue-600">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    JD
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Jan de Vries</h3>
                                    <p class="text-xs text-gray-500">Appartement Amsterdam</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-gray-500">10:32</span>
                                <div class="w-2 h-2 bg-blue-600 rounded-full mt-1 ml-auto"></div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 truncate">Wanneer kan ik langskomen voor een bezichtiging?</p>
                    </div>

                    <div class="p-4 hover:bg-gray-50 cursor-pointer">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    MB
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Maria Bakker</h3>
                                    <p class="text-xs text-gray-500">Villa Utrecht</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">Gisteren</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate">Bedankt voor de informatie! Ik ga er over nadenken.</p>
                    </div>

                    <div class="p-4 hover:bg-gray-50 cursor-pointer">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    PT
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Peter Timmers</h3>
                                    <p class="text-xs text-gray-500">Woning Rotterdam</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-gray-500">2 dagen</span>
                                <div class="w-2 h-2 bg-blue-600 rounded-full mt-1 ml-auto"></div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 truncate">Is er parkeergelegenheid bij de woning?</p>
                    </div>

                    <div class="p-4 hover:bg-gray-50 cursor-pointer">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    SK
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Sophie Koster</h3>
                                    <p class="text-xs text-gray-500">Nieuwbouw Den Haag</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">3 dagen</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate">Wat zijn de maandelijkse servicekosten?</p>
                    </div>

                    <div class="p-4 hover:bg-gray-50 cursor-pointer">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    LV
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Lars van Dijk</h3>
                                    <p class="text-xs text-gray-500">Studio Leiden</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">1 week</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate">Perfect! Ik kom graag kijken volgende week.</p>
                    </div>

                    <div class="p-4 hover:bg-gray-50 cursor-pointer">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    EH
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Emma Hendriks</h3>
                                    <p class="text-xs text-gray-500">Eengezinswoning Haarlem</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-gray-500">1 week</span>
                                <div class="w-2 h-2 bg-blue-600 rounded-full mt-1 ml-auto"></div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 truncate">Zijn huisdieren toegestaan in deze woning?</p>
                    </div>

                </div>
            </div>

            <div class="lg:col-span-2 bg-white rounded-lg shadow flex flex-col" style="height: 700px">

                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                JD
                            </div>
                            <div>
                                <h2 class="font-semibold text-lg">Jan de Vries</h2>
                                <p class="text-sm text-gray-500">Modern appartement - Amsterdam Oost - € 425.000</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg" title="Markeer als gelezen">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                            <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg" title="Archiveer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                </svg>
                            </button>
                            <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg" title="Meer opties">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-4">

                    <div class="flex justify-start">
                        <div class="max-w-md">
                            <div class="bg-gray-100 rounded-lg p-4">
                                <p class="text-sm text-gray-800">Goedemiddag, ik ben geïnteresseerd in dit appartement. Is het mogelijk om volgende week een bezichtiging in te plannen?</p>
                            </div>
                            <span class="text-xs text-gray-500 mt-1 ml-2">Gisteren om 14:23</span>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <div class="max-w-md">
                            <div class="bg-blue-600 text-white rounded-lg p-4">
                                <p class="text-sm">Goedemiddag Jan! Wat leuk dat u interesse heeft. Volgende week heb ik nog tijd op dinsdag en donderdag. Welke dag zou u het beste schikken?</p>
                            </div>
                            <div class="flex items-center justify-end space-x-2 mt-1 mr-2">
                                <span class="text-xs text-gray-500">Gisteren om 15:45</span>
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-start">
                        <div class="max-w-md">
                            <div class="bg-gray-100 rounded-lg p-4">
                                <p class="text-sm text-gray-800">Donderdag zou perfect zijn! Rond 17:00 uur?</p>
                            </div>
                            <span class="text-xs text-gray-500 mt-1 ml-2">Gisteren om 16:12</span>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <div class="max-w-md">
                            <div class="bg-blue-600 text-white rounded-lg p-4">
                                <p class="text-sm">Prima! Donderdag 17:00 uur staat genoteerd. Het adres is Oosterpark 42. Heeft u nog specifieke vragen die ik alvast kan beantwoorden?</p>
                            </div>
                            <div class="flex items-center justify-end space-x-2 mt-1 mr-2">
                                <span class="text-xs text-gray-500">Gisteren om 16:30</span>
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-start">
                        <div class="max-w-md">
                            <div class="bg-gray-100 rounded-lg p-4">
                                <p class="text-sm text-gray-800">Wanneer kan ik langskomen voor een bezichtiging?</p>
                            </div>
                            <span class="text-xs text-gray-500 mt-1 ml-2">Vandaag om 10:32</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-center py-4">
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse"></div>
                            <span>Jan is aan het typen...</span>
                        </div>
                    </div>

                </div>

                <div class="border-t border-gray-200 p-4">

                    <div class="mb-3">
                        <div class="flex space-x-2">
                            <button class="px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                                📅 Plan bezichtiging
                            </button>
                            <button class="px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                                📄 Stuur documentatie
                            </button>
                            <button class="px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                                💶 Maak bod
                            </button>
                        </div>
                    </div>

                    <div class="flex items-end space-x-3">
                        <button class="p-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                        </button>
                        <button class="p-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                        <textarea rows="2" placeholder="Typ een bericht..." class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none"></textarea>
                        <button class="p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mt-2 text-xs text-gray-500">
                        💡 Tip: Gebruik snelkoppelingen om snel bezichtigingen te plannen of documenten te versturen
                    </div>
                </div>

            </div>

        </div>

        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="font-semibold text-blue-900 mb-2">Berichten functionaliteit</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Alle berichten worden automatisch gearchiveerd voor juridische doeleinden</li>
                        <li>• Klanten ontvangen automatisch een email notificatie bij nieuwe berichten</li>
                        <li>• Gebruik sjablonen voor veelvoorkomende vragen (Instellingen → Berichtsjablonen)</li>
                        <li>• Vermelding van gevoelige informatie (bijv. financiële details) wordt niet aanbevolen via berichten</li>
                        <li>• Bij afwezigheid kunt u een automatisch antwoord instellen</li>
                    </ul>
                </div>
            </div>
        </div>

    </main>

    <footer class="bg-gray-900 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center text-gray-400">
                <p>&copy; 2025 Wooon.nl - Alle rechten voorbehouden</p>
            </div>
        </div>
    </footer>

</body>
</html>
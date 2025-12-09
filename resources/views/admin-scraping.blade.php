<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraping Configuratie - Oxxen.nl Admin</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>
<body class="bg-gray-50">

    @include('partials.header')

    <main class="container mx-auto px-4 py-8">

        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-2">Scraping Configuratie</h1>
            <p class="text-gray-600">Beheer scraping bronnen per woningtype en controleer data kwaliteit</p>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <h3 class="font-semibold text-yellow-900 mb-1">Juridische waarschuwing</h3>
                    <p class="text-sm text-yellow-800">Zorg ervoor dat alle scraping activiteiten voldoen aan de algemene voorwaarden en robots.txt van de doelwebsites. Raadpleeg juridisch advies indien nodig.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="relative bg-gradient-to-br from-green-500 via-green-600 to-green-700 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl hover:scale-105 transition-all duration-300 group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Actieve scrapers</span>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                            <div class="w-3 h-3 bg-white rounded-full animate-pulse"></div>
                        </div>
                    </div>
                    <div class="text-4xl font-extrabold mb-1">12/15</div>
                    <div class="flex items-center text-xs">
                        <span class="bg-white/30 px-2 py-1 rounded-full font-semibold">● 80% operationeel</span>
                    </div>
                </div>
            </div>
            <div class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl hover:scale-105 transition-all duration-300 group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Gescrapet vandaag</span>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-extrabold mb-1">387</div>
                    <div class="flex items-center text-xs">
                        <span class="bg-white/30 px-2 py-1 rounded-full font-semibold">↗ nieuwe woningen</span>
                    </div>
                </div>
            </div>
            <div class="relative bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl hover:scale-105 transition-all duration-300 group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Wachtrij</span>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-extrabold mb-1">1,243</div>
                    <div class="flex items-center text-xs">
                        <span class="bg-white/30 px-2 py-1 rounded-full font-semibold">⏳ in behandeling</span>
                    </div>
                </div>
            </div>
            <div class="relative bg-gradient-to-br from-red-500 via-red-600 to-red-700 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl hover:scale-105 transition-all duration-300 group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Errors (24u)</span>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-extrabold mb-1">28</div>
                    <div class="flex items-center text-xs">
                        <span class="bg-white/30 px-2 py-1 rounded-full font-semibold">⚠ Review benodigd</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow mb-8">
            <div class="border-b border-gray-200">
                <nav class="flex">
                    <button class="px-6 py-4 text-blue-600 border-b-2 border-blue-600 font-semibold">Alle types</button>
                    <button class="px-6 py-4 text-gray-600 hover:text-blue-600">Koop</button>
                    <button class="px-6 py-4 text-gray-600 hover:text-blue-600">Huur</button>
                    <button class="px-6 py-4 text-gray-600 hover:text-blue-600">Nieuwbouw</button>
                </nav>
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <input type="text" placeholder="Zoek website of bron..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">Alle statussen</option>
                            <option value="active">Actief</option>
                            <option value="paused">Gepauzeerd</option>
                            <option value="error">Fout</option>
                        </select>
                    </div>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        + Nieuwe scraper
                    </button>
                </div>

                <div class="space-y-4">

                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-start space-x-4">
                                <div class="bg-green-100 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold">Funda.nl</h3>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Actief</span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Koop</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3">https://www.funda.nl/koop/heel-nederland/</p>
                                    <div class="flex items-center space-x-6 text-sm">
                                        <span class="text-gray-600">
                                            <span class="font-semibold text-gray-800">4,234</span> woningen
                                        </span>
                                        <span class="text-gray-600">
                                            Laatste run: <span class="font-semibold text-gray-800">15 min geleden</span>
                                        </span>
                                        <span class="text-green-600 font-semibold">
                                            +124 vandaag
                                        </span>
                                        <span class="text-gray-600">
                                            Interval: <span class="font-semibold text-gray-800">30 min</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">Configureren</button>
                                <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">Logs</button>
                                <button class="px-4 py-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition">Pauzeren</button>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Selector strategie:</span>
                                    <p class="text-gray-800 mt-1">CSS + XPath</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Rate limiting:</span>
                                    <p class="text-gray-800 mt-1">2 requests/sec</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">User agent:</span>
                                    <p class="text-gray-800 mt-1">Rotating</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Proxy:</span>
                                    <p class="text-gray-800 mt-1">Pool (10 IPs)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-start space-x-4">
                                <div class="bg-green-100 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold">Pararius.nl</h3>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Actief</span>
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">Huur</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3">https://www.pararius.nl/huurwoningen</p>
                                    <div class="flex items-center space-x-6 text-sm">
                                        <span class="text-gray-600">
                                            <span class="font-semibold text-gray-800">1,842</span> woningen
                                        </span>
                                        <span class="text-gray-600">
                                            Laatste run: <span class="font-semibold text-gray-800">8 min geleden</span>
                                        </span>
                                        <span class="text-green-600 font-semibold">
                                            +43 vandaag
                                        </span>
                                        <span class="text-gray-600">
                                            Interval: <span class="font-semibold text-gray-800">45 min</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">Configureren</button>
                                <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">Logs</button>
                                <button class="px-4 py-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition">Pauzeren</button>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Selector strategie:</span>
                                    <p class="text-gray-800 mt-1">CSS</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Rate limiting:</span>
                                    <p class="text-gray-800 mt-1">1 request/2sec</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">User agent:</span>
                                    <p class="text-gray-800 mt-1">Static</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Proxy:</span>
                                    <p class="text-gray-800 mt-1">Direct</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-start space-x-4">
                                <div class="bg-green-100 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold">NieuweBouwPortal.nl</h3>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Actief</span>
                                        <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full">Nieuwbouw</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3">https://www.nieuwbouwportal.nl/projecten</p>
                                    <div class="flex items-center space-x-6 text-sm">
                                        <span class="text-gray-600">
                                            <span class="font-semibold text-gray-800">312</span> projecten
                                        </span>
                                        <span class="text-gray-600">
                                            Laatste run: <span class="font-semibold text-gray-800">32 min geleden</span>
                                        </span>
                                        <span class="text-green-600 font-semibold">
                                            +6 vandaag
                                        </span>
                                        <span class="text-gray-600">
                                            Interval: <span class="font-semibold text-gray-800">60 min</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">Configureren</button>
                                <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">Logs</button>
                                <button class="px-4 py-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition">Pauzeren</button>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Selector strategie:</span>
                                    <p class="text-gray-800 mt-1">CSS + API</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Rate limiting:</span>
                                    <p class="text-gray-800 mt-1">1 request/3sec</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">User agent:</span>
                                    <p class="text-gray-800 mt-1">Rotating</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Proxy:</span>
                                    <p class="text-gray-800 mt-1">Pool (5 IPs)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border border-red-200 bg-red-50 rounded-lg p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-start space-x-4">
                                <div class="bg-red-100 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-red-900">Kamernet.nl</h3>
                                        <span class="px-2 py-1 bg-red-200 text-red-900 text-xs font-semibold rounded-full">Error</span>
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">Huur</span>
                                    </div>
                                    <p class="text-sm text-red-800 mb-3">https://kamernet.nl/huren</p>
                                    <div class="flex items-center space-x-6 text-sm">
                                        <span class="text-red-800">
                                            <span class="font-semibold">623</span> woningen
                                        </span>
                                        <span class="text-red-800">
                                            Laatste run: <span class="font-semibold">3 uur geleden</span>
                                        </span>
                                        <span class="text-red-800 font-semibold">
                                            ⚠ Anti-scraping detected
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">Configureren</button>
                                <button class="px-4 py-2 text-red-600 hover:bg-red-100 rounded-lg transition">Details</button>
                                <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">Retry</button>
                            </div>
                        </div>
                        <div class="bg-red-100 rounded-lg p-4">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-red-700 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-red-800">
                                    <p class="font-semibold">CAPTCHA detected - manual intervention required</p>
                                    <p class="mt-1">Website heeft anti-bot detectie geïmplementeerd. Mogelijk nodig: headless browser strategie of andere aanpak.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-start space-x-4">
                                <div class="bg-gray-200 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-600">Jaap.nl</h3>
                                        <span class="px-2 py-1 bg-gray-300 text-gray-700 text-xs font-semibold rounded-full">Gepauzeerd</span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Koop</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mb-3">https://www.jaap.nl/</p>
                                    <div class="flex items-center space-x-6 text-sm">
                                        <span class="text-gray-500">
                                            <span class="font-semibold">2,145</span> woningen
                                        </span>
                                        <span class="text-gray-500">
                                            Laatste run: <span class="font-semibold">2 dagen geleden</span>
                                        </span>
                                        <span class="text-gray-500">
                                            Gepauzeerd door: Admin
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-4 py-2 text-green-600 hover:bg-green-50 rounded-lg transition">Hervatten</button>
                                <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">Configureren</button>
                                <button class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">Verwijderen</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold">Data kwaliteit controle</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-700">Complete data (alle verplichte velden)</span>
                                <span class="text-sm font-semibold text-green-600">87%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: 87%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-700">Foto's aanwezig</span>
                                <span class="text-sm font-semibold text-green-600">92%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: 92%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-700">Duurzaamheid info</span>
                                <span class="text-sm font-semibold text-yellow-600">64%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-600 h-2 rounded-full" style="width: 64%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-700">Plattegronden</span>
                                <span class="text-sm font-semibold text-red-600">42%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-red-600 h-2 rounded-full" style="width: 42%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-semibold text-blue-900 mb-2 text-sm">Review queue</h3>
                        <p class="text-sm text-blue-800 mb-3">
                            <span class="font-bold">124</span> gescrapet woningen wachten op handmatige review voordat ze gepubliceerd worden
                        </p>
                        <button class="text-sm text-blue-600 hover:text-blue-700 font-semibold">
                            Bekijk review queue →
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold">Kenmerken extractie</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">Configureer welke kenmerken per woningtype moeten worden gescrapet</p>

                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-gray-800">Verplichte velden</span>
                                <span class="text-xs text-gray-500">Voor alle types</span>
                            </div>
                            <div class="space-y-1 text-sm text-gray-700">
                                <div class="flex items-center justify-between py-1">
                                    <span>• Adres & postcode</span>
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center justify-between py-1">
                                    <span>• Prijs</span>
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center justify-between py-1">
                                    <span>• Woonoppervlakte</span>
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center justify-between py-1">
                                    <span>• Aantal kamers</span>
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center justify-between py-1">
                                    <span>• Omschrijving</span>
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-gray-800">Optionele velden</span>
                                <span class="text-xs text-gray-500">Best-effort</span>
                            </div>
                            <div class="space-y-1 text-sm text-gray-700">
                                <div class="flex items-center justify-between py-1">
                                    <span>• Energielabel</span>
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center justify-between py-1">
                                    <span>• Bouwjaar</span>
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center justify-between py-1">
                                    <span>• Tuin/balkon</span>
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center justify-between py-1">
                                    <span>• Parkeren</span>
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button class="w-full text-blue-600 hover:text-blue-700 text-sm font-semibold">
                                Kenmerken configureren →
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <footer class="bg-gray-900 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center text-gray-400">
                <p>&copy; 2025 Oxxen.nl - Alle rechten voorbehouden</p>
            </div>
        </div>
    </footer>

</body>
</html>
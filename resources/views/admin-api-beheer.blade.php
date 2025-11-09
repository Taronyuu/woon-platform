<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Connecties Beheer - Wooon.nl Admin</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>
<body class="bg-gray-50">

    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="text-2xl font-bold text-blue-600">Wooon.nl Admin</div>
                <nav class="hidden md:flex space-x-6">
                    <a href="#" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                    <a href="{{ route('admin.api') }}" class="text-blue-600 font-semibold">API Connecties</a>
                    <a href="{{ route('admin.scraping') }}" class="text-gray-700 hover:text-blue-600">Scraping</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Gebruikers</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Systeem</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Admin</span>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Uitloggen</a>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">

        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-2">API Connecties Beheer</h1>
            <p class="text-gray-600">Beheer en monitor alle externe API-koppelingen voor woningdata</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="relative bg-gradient-to-br from-green-500 via-green-600 to-green-700 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl hover:scale-105 transition-all duration-300 group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Actieve connecties</span>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                            <div class="w-3 h-3 bg-white rounded-full animate-pulse"></div>
                        </div>
                    </div>
                    <div class="text-4xl font-extrabold mb-1">5/6</div>
                    <div class="flex items-center text-xs">
                        <span class="bg-white/30 px-2 py-1 rounded-full font-semibold">● 83% operationeel</span>
                    </div>
                </div>
            </div>
            <div class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl hover:scale-105 transition-all duration-300 group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Totaal woningen</span>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-extrabold mb-1">12,487</div>
                    <div class="flex items-center text-xs">
                        <span class="bg-white/30 px-2 py-1 rounded-full font-semibold">↗ +487 deze week</span>
                    </div>
                </div>
            </div>
            <div class="relative bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl hover:scale-105 transition-all duration-300 group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Laatste sync</span>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-extrabold mb-1">5 min</div>
                    <div class="flex items-center text-xs">
                        <span class="bg-white/30 px-2 py-1 rounded-full font-semibold">⚡ geleden</span>
                    </div>
                </div>
            </div>
            <div class="relative bg-gradient-to-br from-red-500 via-red-600 to-red-700 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl hover:scale-105 transition-all duration-300 group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Fouten (24u)</span>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-extrabold mb-1">3</div>
                    <div class="flex items-center text-xs">
                        <span class="bg-white/30 px-2 py-1 rounded-full font-semibold">⚠ Aandacht vereist</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold">API Providers</h2>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        + Nieuwe connectie
                    </button>
                </div>
            </div>

            <div class="divide-y divide-gray-200">

                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-4">
                            <div class="bg-green-100 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center space-x-3">
                                    <h3 class="text-lg font-semibold">VBO / Makelaars API</h3>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Actief</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">Bestaande koopwoningen via makelaars</p>
                                <div class="flex items-center space-x-4 mt-3 text-sm">
                                    <span class="text-gray-600">
                                        <span class="font-semibold text-gray-800">8,542</span> woningen
                                    </span>
                                    <span class="text-gray-600">
                                        Laatste sync: <span class="font-semibold text-gray-800">5 min geleden</span>
                                    </span>
                                    <span class="text-green-600 font-semibold">
                                        +42 vandaag
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">Configureren</button>
                            <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">Logs</button>
                            <button class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">Deactiveren</button>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">API Endpoint:</span>
                                <p class="font-mono text-xs text-gray-800 mt-1">https://api.vbo.nl/v2/listings</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Sync interval:</span>
                                <p class="text-gray-800 mt-1">Elke 15 minuten</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Rate limit:</span>
                                <p class="text-gray-800 mt-1">1000 calls/uur</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-4">
                            <div class="bg-green-100 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center space-x-3">
                                    <h3 class="text-lg font-semibold">Woningnet.nl</h3>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Actief</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">Sociale huurwoningen via woningcorporaties</p>
                                <div class="flex items-center space-x-4 mt-3 text-sm">
                                    <span class="text-gray-600">
                                        <span class="font-semibold text-gray-800">2,134</span> woningen
                                    </span>
                                    <span class="text-gray-600">
                                        Laatste sync: <span class="font-semibold text-gray-800">12 min geleden</span>
                                    </span>
                                    <span class="text-green-600 font-semibold">
                                        +8 vandaag
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">Configureren</button>
                            <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">Logs</button>
                            <button class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">Deactiveren</button>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">API Endpoint:</span>
                                <p class="font-mono text-xs text-gray-800 mt-1">https://api.woningnet.nl/v1/properties</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Sync interval:</span>
                                <p class="text-gray-800 mt-1">Elke 30 minuten</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Rate limit:</span>
                                <p class="text-gray-800 mt-1">500 calls/uur</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-4">
                            <div class="bg-green-100 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center space-x-3">
                                    <h3 class="text-lg font-semibold">ADEES</h3>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Actief</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">Huurwoningen via corporaties (standaard protocol)</p>
                                <div class="flex items-center space-x-4 mt-3 text-sm">
                                    <span class="text-gray-600">
                                        <span class="font-semibold text-gray-800">945</span> woningen
                                    </span>
                                    <span class="text-gray-600">
                                        Laatste sync: <span class="font-semibold text-gray-800">8 min geleden</span>
                                    </span>
                                    <span class="text-green-600 font-semibold">
                                        +3 vandaag
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">Configureren</button>
                            <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">Logs</button>
                            <button class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">Deactiveren</button>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Protocol:</span>
                                <p class="text-gray-800 mt-1">ADEES XML Feed</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Sync interval:</span>
                                <p class="text-gray-800 mt-1">Elk uur</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Format:</span>
                                <p class="text-gray-800 mt-1">XML</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-4">
                            <div class="bg-green-100 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center space-x-3">
                                    <h3 class="text-lg font-semibold">Kolibri</h3>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Actief</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">Makelaars software integratie</p>
                                <div class="flex items-center space-x-4 mt-3 text-sm">
                                    <span class="text-gray-600">
                                        <span class="font-semibold text-gray-800">645</span> woningen
                                    </span>
                                    <span class="text-gray-600">
                                        Laatste sync: <span class="font-semibold text-gray-800">3 min geleden</span>
                                    </span>
                                    <span class="text-green-600 font-semibold">
                                        +12 vandaag
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">Configureren</button>
                            <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">Logs</button>
                            <button class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">Deactiveren</button>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">API Endpoint:</span>
                                <p class="font-mono text-xs text-gray-800 mt-1">https://api.kolibri.net/properties</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Sync interval:</span>
                                <p class="text-gray-800 mt-1">Elke 10 minuten</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Rate limit:</span>
                                <p class="text-gray-800 mt-1">200 calls/uur</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-4">
                            <div class="bg-green-100 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center space-x-3">
                                    <h3 class="text-lg font-semibold">Mijnhuiszaken.nl</h3>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Actief</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">Nieuwbouw projecten en ontwikkelingen</p>
                                <div class="flex items-center space-x-4 mt-3 text-sm">
                                    <span class="text-gray-600">
                                        <span class="font-semibold text-gray-800">221</span> projecten
                                    </span>
                                    <span class="text-gray-600">
                                        Laatste sync: <span class="font-semibold text-gray-800">28 min geleden</span>
                                    </span>
                                    <span class="text-green-600 font-semibold">
                                        +2 vandaag
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">Configureren</button>
                            <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">Logs</button>
                            <button class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">Deactiveren</button>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">API Endpoint:</span>
                                <p class="font-mono text-xs text-gray-800 mt-1">https://api.mijnhuiszaken.nl/projects</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Sync interval:</span>
                                <p class="text-gray-800 mt-1">Elke 45 minuten</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Rate limit:</span>
                                <p class="text-gray-800 mt-1">300 calls/uur</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-gray-50">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-4">
                            <div class="bg-gray-200 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center space-x-3">
                                    <h3 class="text-lg font-semibold text-gray-500">Move.nl</h3>
                                    <span class="px-2 py-1 bg-gray-200 text-gray-700 text-xs font-semibold rounded-full">Inactief</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Verhuisservices integratie (optioneel)</p>
                                <div class="flex items-center space-x-4 mt-3 text-sm">
                                    <span class="text-gray-500">
                                        Nog niet geconfigureerd
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">Activeren</button>
                            <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">Configureren</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold">Recente sync activiteit</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-sm">VBO / Makelaars API</span>
                            <span class="text-xs text-gray-500">5 min geleden</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm text-gray-600">42 nieuwe woningen toegevoegd</span>
                        </div>
                    </div>
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-sm">Kolibri</span>
                            <span class="text-xs text-gray-500">3 min geleden</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm text-gray-600">12 woningen bijgewerkt</span>
                        </div>
                    </div>
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-sm">ADEES</span>
                            <span class="text-xs text-gray-500">8 min geleden</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm text-gray-600">3 nieuwe huurwoningen</span>
                        </div>
                    </div>
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-sm">Woningnet.nl</span>
                            <span class="text-xs text-gray-500">12 min geleden</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm text-gray-600">8 woningen toegevoegd</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold">Recente errors</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-sm">VBO / Makelaars API</span>
                            <span class="text-xs text-gray-500">2 uur geleden</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <svg class="w-4 h-4 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <div>
                                <span class="text-sm text-gray-600">Rate limit exceeded (1025/1000)</span>
                                <p class="text-xs text-gray-500 mt-1">Sync vertraagd naar volgende interval</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-sm">Mijnhuiszaken.nl</span>
                            <span class="text-xs text-gray-500">5 uur geleden</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <svg class="w-4 h-4 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div>
                                <span class="text-sm text-gray-600">Incomplete data ontvangen</span>
                                <p class="text-xs text-gray-500 mt-1">3 projecten missen verplichte velden</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-sm">Kolibri</span>
                            <span class="text-xs text-gray-500">Gisteren</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <svg class="w-4 h-4 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <div>
                                <span class="text-sm text-gray-600">Connection timeout</span>
                                <p class="text-xs text-gray-500 mt-1">API niet bereikbaar na 30s</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 text-center text-gray-500 text-sm">
                        <a href="#" class="text-blue-600 hover:text-blue-700">Bekijk alle logs →</a>
                    </div>
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
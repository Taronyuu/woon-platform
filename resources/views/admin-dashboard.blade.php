<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Wooon.nl</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>
<body class="bg-gray-50">

    @include('partials.header')

    <main class="container mx-auto px-4 py-8">

        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-2">Platform Dashboard</h1>
            <p class="text-gray-600">Overzicht van platformprestaties en systeem status</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
                    <div class="text-4xl font-extrabold mb-1">12,847</div>
                    <div class="flex items-center text-xs">
                        <span class="bg-white/30 px-2 py-1 rounded-full font-semibold">↗ +487 deze week</span>
                    </div>
                </div>
            </div>
            <div class="relative bg-gradient-to-br from-green-500 via-green-600 to-emerald-700 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl hover:scale-105 transition-all duration-300 group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Actieve gebruikers</span>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-extrabold mb-1">3,421</div>
                    <div class="flex items-center text-xs">
                        <span class="bg-white/30 px-2 py-1 rounded-full font-semibold">● 832 online nu</span>
                    </div>
                </div>
            </div>
            <div class="relative bg-gradient-to-br from-purple-500 via-purple-600 to-pink-600 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl hover:scale-105 transition-all duration-300 group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Makelaars</span>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-extrabold mb-1">284</div>
                    <div class="flex items-center text-xs">
                        <span class="bg-white/30 px-2 py-1 rounded-full font-semibold">↗ +8 deze maand</span>
                    </div>
                </div>
            </div>
            <div class="relative bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-600 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl hover:scale-105 transition-all duration-300 group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Systeem status</span>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                            <div class="w-3 h-3 bg-white rounded-full animate-pulse"></div>
                        </div>
                    </div>
                    <div class="text-2xl font-extrabold mb-1">Operationeel</div>
                    <div class="flex items-center text-xs">
                        <span class="bg-white/30 px-2 py-1 rounded-full font-semibold">⚡ Uptime: 99.97%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

            <div class="lg:col-span-2 bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold">Platform activiteit</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg">24 uur</button>
                            <button class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">7 dagen</button>
                            <button class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">30 dagen</button>
                        </div>
                    </div>
                    <div class="h-64 flex items-end justify-between space-x-2">
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-600 rounded-t" style="height: 65%"></div>
                            <span class="text-xs text-gray-500 mt-2">Ma</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-600 rounded-t" style="height: 78%"></div>
                            <span class="text-xs text-gray-500 mt-2">Di</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-600 rounded-t" style="height: 82%"></div>
                            <span class="text-xs text-gray-500 mt-2">Wo</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-600 rounded-t" style="height: 70%"></div>
                            <span class="text-xs text-gray-500 mt-2">Do</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-600 rounded-t" style="height: 88%"></div>
                            <span class="text-xs text-gray-500 mt-2">Vr</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-400 rounded-t" style="height: 45%"></div>
                            <span class="text-xs text-gray-500 mt-2">Za</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-400 rounded-t" style="height: 38%"></div>
                            <span class="text-xs text-gray-500 mt-2">Zo</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-200">
                        <div>
                            <span class="text-sm text-gray-600">Pageviews</span>
                            <p class="text-2xl font-bold text-gray-800">47,382</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Zoekopdrachten</span>
                            <p class="text-2xl font-bold text-gray-800">12,847</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Leads gegenereerd</span>
                            <p class="text-2xl font-bold text-gray-800">1,284</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold">Data bronnen</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-700">API Connecties</span>
                                <span class="text-sm font-semibold text-gray-800">8,542</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 66%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-700">Scraping</span>
                                <span class="text-sm font-semibold text-gray-800">3,142</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: 24%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-700">Handmatig</span>
                                <span class="text-sm font-semibold text-gray-800">1,163</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: 10%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold mb-3">Per type</h3>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">Koop</span>
                                <span class="text-sm font-semibold text-gray-800">8,542 (67%)</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">Huur</span>
                                <span class="text-sm font-semibold text-gray-800">3,142 (24%)</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">Nieuwbouw</span>
                                <span class="text-sm font-semibold text-gray-800">1,163 (9%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold">Recente activiteit</h2>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-700">Bekijk alles →</a>
                    </div>
                </div>
                <div class="divide-y divide-gray-200">
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-start space-x-3">
                            <div class="bg-green-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-800"><span class="font-semibold">VBO API</span> - 42 nieuwe woningen toegevoegd</p>
                                <span class="text-xs text-gray-500">5 minuten geleden</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-start space-x-3">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-800"><span class="font-semibold">Nieuwe makelaar</span> - Van der Berg Makelaardij geregistreerd</p>
                                <span class="text-xs text-gray-500">12 minuten geleden</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-start space-x-3">
                            <div class="bg-purple-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-800"><span class="font-semibold">Systeem update</span> - Nieuwe versie 2.4.1 succesvol geïnstalleerd</p>
                                <span class="text-xs text-gray-500">1 uur geleden</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-start space-x-3">
                            <div class="bg-yellow-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-800"><span class="font-semibold">Scraping waarschuwing</span> - Kamernet.nl: CAPTCHA gedetecteerd</p>
                                <span class="text-xs text-gray-500">2 uur geleden</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-start space-x-3">
                            <div class="bg-red-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-800"><span class="font-semibold">API Error</span> - Kolibri connectie timeout na 30s</p>
                                <span class="text-xs text-gray-500">3 uur geleden</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold">Systeem gezondheid</h2>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Gezond</span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-700 flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                Database
                            </span>
                            <span class="text-xs text-gray-500">Response: 12ms</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 97%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-700 flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                API Gateway
                            </span>
                            <span class="text-xs text-gray-500">Response: 45ms</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 94%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-700 flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                Zoekindex
                            </span>
                            <span class="text-xs text-gray-500">Response: 23ms</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 96%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-700 flex items-center">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                Email service
                            </span>
                            <span class="text-xs text-gray-500">Queue: 142 berichten</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: 78%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-700 flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                Opslag
                            </span>
                            <span class="text-xs text-gray-500">342 GB / 500 GB</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 68%"></div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs text-gray-500">CPU gebruik</span>
                                <p class="text-lg font-bold text-gray-800">34%</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">Memory gebruik</span>
                                <p class="text-lg font-bold text-gray-800">58%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold">Populaire zoektermen</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Amsterdam</span>
                            <span class="text-sm font-semibold text-gray-800">1,842</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Rotterdam</span>
                            <span class="text-sm font-semibold text-gray-800">1,234</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Utrecht</span>
                            <span class="text-sm font-semibold text-gray-800">987</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Den Haag</span>
                            <span class="text-sm font-semibold text-gray-800">745</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Eindhoven</span>
                            <span class="text-sm font-semibold text-gray-800">623</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold">Top makelaars</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                    1
                                </div>
                                <span class="text-sm text-gray-700">De Makelaars Amsterdam</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-800">842</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-gray-300 to-gray-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                    2
                                </div>
                                <span class="text-sm text-gray-700">Van der Berg</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-800">634</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                    3
                                </div>
                                <span class="text-sm text-gray-700">Huis & Hypotheek</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-800">567</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-gray-200 to-gray-400 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                    4
                                </div>
                                <span class="text-sm text-gray-700">Wonen073</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-800">489</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-gray-200 to-gray-400 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                    5
                                </div>
                                <span class="text-sm text-gray-700">Vastgoed Partners</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-800">412</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold">Snelle acties</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <button class="w-full flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span class="text-sm font-semibold text-gray-800">Nieuwe gebruiker</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <button class="w-full flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-semibold text-gray-800">Review queue</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <button class="w-full flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm font-semibold text-gray-800">Systeeminstellingen</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <button class="w-full flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="text-sm font-semibold text-gray-800">Rapportages</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <button class="w-full flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span class="text-sm font-semibold text-gray-800">Bekijk logs</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
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
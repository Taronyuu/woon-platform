<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoekresultaten - Wooon.nl</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>
<body class="bg-gray-50">

    <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Wooon.nl</a>
                <nav class="hidden md:flex space-x-6">
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Koop</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Huur</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Nieuwbouw</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Over ons</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('account.consumer') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Inloggen</a>
                    <a href="#" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2.5 rounded-xl hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold">Account aanmaken</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">

        <div class="bg-white/95 backdrop-blur-lg rounded-2xl p-6 shadow-xl mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" placeholder="Plaats of postcode" value="Amsterdam" class="px-5 py-4 border-2 border-gray-200 rounded-xl text-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                <select class="px-5 py-4 border-2 border-gray-200 rounded-xl text-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                    <option>Koop</option>
                    <option>Huur</option>
                    <option>Nieuwbouw</option>
                </select>
                <input type="text" placeholder="Prijsrange" value="€ 200.000 - € 500.000" class="px-5 py-4 border-2 border-gray-200 rounded-xl text-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
            </div>
            <button class="w-full mt-5 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-bold text-lg hover:shadow-2xl hover:scale-[1.02] transition-all duration-200">
                🔍 Zoeken
            </button>
        </div>

        <div class="flex flex-col md:flex-row gap-6">

            <aside class="w-full md:w-64 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="font-bold text-lg mb-4 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Filters</h3>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" checked class="rounded text-purple-600 focus:ring-purple-500">
                                <span class="ml-2 text-gray-700">Koop</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-purple-600 focus:ring-purple-500">
                                <span class="ml-2 text-gray-700">Huur</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-purple-600 focus:ring-purple-500">
                                <span class="ml-2 text-gray-700">Nieuwbouw</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prijsrange</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" placeholder="Min" class="px-3 py-2 border-2 border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                            <input type="text" placeholder="Max" class="px-3 py-2 border-2 border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                        </div>
                    </div>

                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Oppervlakte</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" placeholder="Min m²" class="px-3 py-2 border-2 border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                            <input type="text" placeholder="Max m²" class="px-3 py-2 border-2 border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                        </div>
                    </div>

                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Aantal kamers</label>
                        <select class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                            <option>Maakt niet uit</option>
                            <option>1+ kamers</option>
                            <option>2+ kamers</option>
                            <option>3+ kamers</option>
                            <option>4+ kamers</option>
                            <option>5+ kamers</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Duurzaamheid</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-purple-600 focus:ring-purple-500">
                                <span class="ml-2 text-gray-700 text-sm">Energielabel A+</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-purple-600 focus:ring-purple-500">
                                <span class="ml-2 text-gray-700 text-sm">Zonnepanelen</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-purple-600 focus:ring-purple-500">
                                <span class="ml-2 text-gray-700 text-sm">Isolatie</span>
                            </label>
                        </div>
                    </div>

                    <button class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-3 rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition-all duration-200">
                        Filters toepassen
                    </button>
                    <button class="w-full mt-2 text-gray-600 px-4 py-2 rounded-xl border-2 border-gray-200 hover:bg-gray-50 transition-all">
                        Reset filters
                    </button>
                </div>
            </aside>

            <main class="flex-1">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-2xl font-bold">247 woningen in Amsterdam</h1>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option>Sorteren op relevantie</option>
                        <option>Prijs: laag naar hoog</option>
                        <option>Prijs: hoog naar laag</option>
                        <option>Oppervlakte: klein naar groot</option>
                        <option>Nieuwste eerst</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <a href="{{ route('property.show', 1) }}" class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 group border border-gray-100">
                        <div class="relative h-56 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=600&h=400&fit=crop" alt="Appartement" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="absolute top-3 right-3">
                                <span class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg">✨ Nieuw</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent font-bold text-2xl">€ 425.000</h3>
                                <button class="text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-gray-700 font-medium mb-2">Keizersgracht 123, Amsterdam</p>
                            <div class="flex items-center space-x-3 text-sm text-gray-600 mb-4">
                                <span class="bg-gray-50 px-3 py-1.5 rounded-lg">🏠 Appartement</span>
                                <span class="bg-gray-50 px-3 py-1.5 rounded-lg">3 kamers</span>
                                <span class="bg-gray-50 px-3 py-1.5 rounded-lg">85 m²</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-blue-600 font-semibold group-hover:text-purple-600 transition-colors">Meer info →</span>
                                <span class="text-gray-500">2 dagen geleden</span>
                            </div>
                        </div>
                    </a>

                    <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 group border border-gray-100">
                        <div class="relative h-56 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=600&h=400&fit=crop" alt="Huurwoning" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent font-bold text-2xl">€ 1.250 / maand</h3>
                                <button class="text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-gray-700 font-medium mb-2">Prinsengracht 456, Amsterdam</p>
                            <div class="flex items-center space-x-3 text-sm text-gray-600 mb-4">
                                <span class="bg-gray-50 px-3 py-1.5 rounded-lg">🏘️ Huurwoning</span>
                                <span class="bg-gray-50 px-3 py-1.5 rounded-lg">4 kamers</span>
                                <span class="bg-gray-50 px-3 py-1.5 rounded-lg">110 m²</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-blue-600 font-semibold group-hover:text-purple-600 transition-colors">Meer info →</span>
                                <span class="text-gray-500">5 dagen geleden</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 group border border-gray-100">
                        <div class="relative h-56 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=600&h=400&fit=crop" alt="Nieuwbouw" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="absolute top-3 right-3">
                                <span class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg">💎 Nieuwbouw</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent font-bold text-2xl">€ 385.000</h3>
                                <button class="text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-gray-600 mb-2">Nieuw Noord, Amsterdam</p>
                            <p class="text-gray-500 text-sm mb-4">Nieuwbouw • 2 kamers • 65 m² • Energielabel A+++</p>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-blue-600 font-semibold">Meer info →</span>
                                <span class="text-gray-500">Vandaag</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                        <div class="bg-gray-300 h-64 flex items-center justify-center">
                            <span class="text-gray-600">Woning foto</span>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold text-xl">€ 550.000</h3>
                                <button class="text-gray-400 hover:text-red-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-gray-600 mb-2">De Pijp, Amsterdam</p>
                            <p class="text-gray-500 text-sm mb-4">Woonhuis • 5 kamers • 135 m² • Energielabel C</p>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-blue-600 font-semibold">Meer info →</span>
                                <span class="text-gray-500">1 week geleden</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                        <div class="bg-gray-300 h-64 flex items-center justify-center">
                            <span class="text-gray-600">Woning foto</span>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold text-xl">€ 895 / maand</h3>
                                <button class="text-gray-400 hover:text-red-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-gray-600 mb-2">Oost, Amsterdam</p>
                            <p class="text-gray-500 text-sm mb-4">Huurwoning • 2 kamers • 60 m² • Energielabel B</p>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-blue-600 font-semibold">Meer info →</span>
                                <span class="text-gray-500">3 dagen geleden</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                        <div class="bg-gray-300 h-64 flex items-center justify-center">
                            <span class="text-gray-600">Woning foto</span>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold text-xl">€ 475.000</h3>
                                <button class="text-gray-400 hover:text-red-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-gray-600 mb-2">West, Amsterdam</p>
                            <p class="text-gray-500 text-sm mb-4">Appartement • 4 kamers • 95 m² • Energielabel A</p>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-blue-600 font-semibold">Meer info →</span>
                                <span class="text-gray-500">4 dagen geleden</span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex justify-center">
                    <nav class="flex items-center space-x-2">
                        <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
                            Vorige
                        </button>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold">1</button>
                        <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">2</button>
                        <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">3</button>
                        <span class="px-4 py-2">...</span>
                        <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">15</button>
                        <button class="px-4 py-2 border border-gray-300 rounded-lg text-blue-600 hover:bg-gray-50">
                            Volgende
                        </button>
                    </nav>
                </div>
            </main>

        </div>
    </div>

    <footer class="bg-gray-900 text-white mt-12">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-xl font-bold mb-4">Wooon.nl</h4>
                    <p class="text-gray-400">Het complete onafhankelijke woonplatform voor Nederland</p>
                </div>
                <div>
                    <h5 class="font-semibold mb-4">Over ons</h5>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Over Wooon</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                        <li><a href="#" class="hover:text-white">Werken bij</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-semibold mb-4">Voor professionals</h5>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('realtor.dashboard') }}" class="hover:text-white">Makelaars</a></li>
                        <li><a href="#" class="hover:text-white">API-partners</a></li>
                        <li><a href="#" class="hover:text-white">Adverteren</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-semibold mb-4">Juridisch</h5>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Privacy</a></li>
                        <li><a href="#" class="hover:text-white">Algemene voorwaarden</a></li>
                        <li><a href="#" class="hover:text-white">Cookie beleid</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Wooon.nl - Alle rechten voorbehouden</p>
            </div>
        </div>
    </footer>

</body>
</html>
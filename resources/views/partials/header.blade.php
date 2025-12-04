<header x-data="{ mobileMenuOpen: false }" class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Wooon.nl</a>
            <nav class="hidden md:flex space-x-6">
                <a href="{{ route('search', ['type' => 'sale']) }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Koop</a>
                <a href="{{ route('search', ['type' => 'rent']) }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Huur</a>
                <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Nieuwbouw</a>
                <a href="{{ route('mortgage.calculator') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Maandlasten</a>
            </nav>
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <a href="{{ route('account.consumer') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Mijn account</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Uitloggen</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Inloggen</a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2.5 rounded-xl hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold">Account aanmaken</a>
                @endauth
            </div>
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-700 hover:text-blue-600 transition-colors">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         x-cloak
         class="md:hidden bg-white border-t border-gray-200 shadow-lg">
        <nav class="container mx-auto px-4 py-4 space-y-2">
            <a href="{{ route('search', ['type' => 'sale']) }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-lg font-medium transition-colors">Koop</a>
            <a href="{{ route('search', ['type' => 'rent']) }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-lg font-medium transition-colors">Huur</a>
            <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-lg font-medium transition-colors">Nieuwbouw</a>
            <a href="{{ route('mortgage.calculator') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-lg font-medium transition-colors">Maandlasten</a>
            <div class="border-t border-gray-200 pt-4 mt-4 space-y-2">
                @auth
                    <a href="{{ route('account.consumer') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-lg font-medium transition-colors">Mijn account</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-lg font-medium transition-colors">Uitloggen</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-lg font-medium transition-colors">Inloggen</a>
                    <a href="{{ route('register') }}" class="block px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold text-center">Account aanmaken</a>
                @endauth
            </div>
        </nav>
    </div>
</header>

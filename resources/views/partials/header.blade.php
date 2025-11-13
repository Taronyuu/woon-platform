<header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Wooon.nl</a>
            <nav class="hidden md:flex space-x-6">
                <a href="{{ route('search', ['type' => 'sale']) }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Koop</a>
                <a href="{{ route('search', ['type' => 'rent']) }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Huur</a>
                <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Nieuwbouw</a>
                <a href="{{ route('mortgage.calculator') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Maandlasten</a>
            </nav>
            <div class="flex items-center space-x-4">
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
        </div>
    </div>
</header>

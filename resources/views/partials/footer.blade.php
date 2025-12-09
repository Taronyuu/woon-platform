<footer class="bg-gray-900 text-white mt-12">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h4 class="text-xl font-bold mb-4">Oxxen.nl</h4>
                <p class="text-gray-400">Het complete onafhankelijke woonplatform voor Nederland</p>
            </div>
            <div>
                <h5 class="font-semibold mb-4">Over ons</h5>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('about') }}" class="hover:text-white">Over Oxxen</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white">Contact</a></li>
                </ul>
            </div>
            <div>
                <h5 class="font-semibold mb-4">Voor professionals</h5>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('realtor.dashboard') }}" class="hover:text-white">Makelaars</a></li>
                </ul>
            </div>
            <div>
                <h5 class="font-semibold mb-4">Juridisch</h5>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('privacy') }}" class="hover:text-white">Privacy</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-white">Algemene voorwaarden</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2025 Oxxen.nl - Alle rechten voorbehouden</p>
        </div>
    </div>
</footer>

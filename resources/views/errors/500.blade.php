<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Er ging iets mis - Wooon.nl</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    @include('partials.header')

    <main class="flex-1 flex items-center justify-center py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-lg mx-auto text-center">
                <div class="text-8xl font-bold bg-gradient-to-r from-orange-500 to-red-500 bg-clip-text text-transparent mb-4">
                    500
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Er ging iets mis</h1>
                <p class="text-gray-600 mb-8">
                    Onze excuses, er is een fout opgetreden aan onze kant. Probeer het later opnieuw of ga terug naar de homepage.
                </p>

                <div class="flex justify-center gap-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Naar homepage
                    </a>
                </div>

                <p class="text-sm text-gray-500 mt-8">
                    Als dit probleem aanhoudt, neem dan contact met ons op via <a href="mailto:info@wooon.nl" class="text-orange-500 hover:text-orange-600">info@wooon.nl</a>
                </p>
            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>

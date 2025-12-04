<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina niet gevonden - Wooon.nl</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    @include('partials.header')

    <main class="flex-1 flex items-center justify-center py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-lg mx-auto text-center">
                <div class="text-8xl font-bold bg-gradient-to-r from-orange-500 to-red-500 bg-clip-text text-transparent mb-4">
                    404
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Pagina niet gevonden</h1>
                <p class="text-gray-600 mb-8">
                    De pagina die je zoekt bestaat niet of is verplaatst. Misschien kun je de woning vinden via onze zoekpagina.
                </p>

                <form action="{{ route('search') }}" method="GET" class="mb-6">
                    <div class="flex">
                        <input type="text" name="q" placeholder="Zoek een plaats of woning..." class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none">
                        <button type="submit" class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-r-lg hover:from-orange-600 hover:to-red-600 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="flex justify-center gap-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-orange-500 hover:text-orange-600 font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Naar homepage
                    </a>
                    <a href="{{ route('search') }}" class="inline-flex items-center text-orange-500 hover:text-orange-600 font-medium">
                        Alle woningen
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>

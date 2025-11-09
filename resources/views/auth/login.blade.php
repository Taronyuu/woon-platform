<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen - Wooon.nl</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>
<body class="bg-gray-50">

    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">Wooon.nl</a>
                <nav class="hidden md:flex space-x-6">
                    <a href="#" class="text-gray-700 hover:text-blue-600">Koop</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Huur</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Nieuwbouw</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Over ons</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('register') }}" class="text-gray-700 hover:text-blue-600">Registreren</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold mb-2">Inloggen</h1>
                <p class="text-gray-600">Log in op je Wooon.nl account</p>
            </div>

            <div class="bg-white rounded-lg shadow p-8">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mailadres</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('email') border-red-500 @enderror"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Wachtwoord</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('password') border-red-500 @enderror"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            >
                            <label for="remember" class="ml-2 text-sm text-gray-700">
                                Onthoud mij
                            </label>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button
                            type="submit"
                            class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold"
                        >
                            Inloggen
                        </button>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Nog geen account?
                            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold">Registreren</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Oxxen.nl - Vind je ideale woning')</title>
    <meta name="description" content="@yield('meta_description', 'Oxxen.nl - Het complete onafhankelijke woonplatform voor Nederland. Vind je ideale koop- of huurwoning.')">
    @yield('meta')
    <meta property="og:site_name" content="Oxxen.nl">
    <meta property="og:locale" content="nl_NL">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50">

    @include('partials.header')

    @auth
        @if(!auth()->user()->hasVerifiedEmail() && !request()->routeIs('verification.*') && !request()->routeIs('logout'))
            <div class="bg-amber-50 border-b border-amber-200">
                <div class="container mx-auto px-4 py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span class="text-amber-800 text-sm">Je e-mailadres is nog niet geverifieerd. Controleer je inbox of <a href="{{ route('verification.notice') }}" class="font-semibold underline">vraag een nieuwe link aan</a>.</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    @yield('content')

    @include('partials.footer')

    @stack('scripts')
</body>
</html>

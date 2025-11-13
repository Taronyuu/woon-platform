<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Wooon.nl - Vind je ideale woning')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50">

    @include('partials.header')

    @yield('content')

    @include('partials.footer')

    @stack('scripts')
</body>
</html>

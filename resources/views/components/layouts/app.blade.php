<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> {{ config('app.name') }}</title>
    <meta name="description" content="{{ $description ?? 'DoxMe — Secure anonymous intelligence sharing platform.' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="min-h-screen flex flex-col bg-black text-gray-400 font-sans selection:bg-red-600/30">

        <x-navbar />

        <main class="grow flex flex-col items-center pt-[6vh] px-4">
            {{ $slot }}
        </main>

        <x-footer />

    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'PasteBin') }} — DoxMe</title>
    <meta name="description" content="{{ $description ?? 'Fast, minimal, and privacy-friendly pastebin. Share code and text anonymously or as a registered user.' }}">
    <meta name="robots" content="index, follow">

    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-zinc-950 text-zinc-100 font-inter antialiased min-h-screen flex flex-col">

    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-zinc-950/90 backdrop-blur border-b border-zinc-800">
        <div class="max-w-6xl mx-auto px-4 h-14 flex items-center justify-between gap-4">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-lg tracking-tight text-white">
                <span class="text-violet-400">⟨/⟩</span>
                <span>DoxMe</span>
            </a>

            <!-- Nav Links -->
            <div class="flex items-center gap-1 text-sm">
                <a href="{{ route('paste.create') }}" id="nav-new-paste"
                   class="px-3 py-1.5 bg-violet-600 hover:bg-violet-500 text-white rounded-md font-medium transition-colors">
                    + New Paste
                </a>
                <a href="{{ route('paste.search') }}" id="nav-search"
                   class="px-3 py-1.5 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-md transition-colors">
                    Search
                </a>
                <a href="{{ route('paste.trending') }}" id="nav-trending"
                   class="px-3 py-1.5 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-md transition-colors">
                    🔥 Trending
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" id="nav-dashboard"
                       class="px-3 py-1.5 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-md transition-colors">
                        Dashboard
                    </a>
                    <div class="relative group ml-1">
                        <button class="flex items-center gap-2 px-2 py-1.5 rounded-md hover:bg-zinc-800 transition-colors">
                            <img src="{{ auth()->user()->avatar_url }}"
                                 alt="{{ auth()->user()->display_name }}"
                                 class="w-6 h-6 rounded-full object-cover">
                            <span class="text-zinc-300 text-xs hidden sm:inline">
                                {{ auth()->user()->role_badge }}
                                {{ auth()->user()->display_name }}
                            </span>
                        </button>
                        <div class="absolute right-0 top-full mt-1 w-44 bg-zinc-900 border border-zinc-700 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white rounded-t-lg">My Pastes</a>
                            <a href="{{ route('dashboard') }}?tab=bookmarks" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white">Bookmarks</a>
                            <div class="border-t border-zinc-700 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-zinc-800 rounded-b-lg">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" id="nav-login"
                       class="px-3 py-1.5 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-md transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" id="nav-register"
                       class="px-3 py-1.5 border border-zinc-700 hover:border-violet-500 text-zinc-300 hover:text-violet-300 rounded-md transition-colors">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="bg-emerald-900/50 border-b border-emerald-700 text-emerald-300 px-4 py-2 text-sm text-center" id="flash-success">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-900/50 border-b border-red-700 text-red-300 px-4 py-2 text-sm text-center" id="flash-error">
        {{ session('error') }}
    </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="border-t border-zinc-800 mt-12 py-8">
        <div class="max-w-6xl mx-auto px-4 text-center text-xs text-zinc-600 space-y-1">
            <p>
                <a href="{{ route('home') }}" class="hover:text-zinc-400">DoxMe</a> ·
                <a href="{{ route('paste.create') }}" class="hover:text-zinc-400">New Paste</a> ·
                <a href="{{ route('paste.trending') }}" class="hover:text-zinc-400">Trending</a> ·
                <a href="{{ route('paste.search') }}" class="hover:text-zinc-400">Search</a>
            </p>
            <p>Simple · Fast · Tor-Friendly</p>
        </div>
    </footer>

    @livewireScripts
    <script>
    // Auto-dismiss flash messages
    setTimeout(() => {
        document.getElementById('flash-success')?.remove();
        document.getElementById('flash-error')?.remove();
    }, 5000);
    </script>
</body>
</html>

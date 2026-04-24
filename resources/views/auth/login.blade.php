<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — DoxMe</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-inter min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-white">
                <span class="text-violet-400">⟨/⟩</span> DoxMe
            </a>
            <p class="text-zinc-500 text-sm mt-1">Sign in to your account</p>
        </div>

        <!-- Card -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 shadow-2xl">
            @if($errors->any())
            <div class="mb-4 p-3 bg-red-900/40 border border-red-800 rounded-lg text-red-300 text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-300 mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2.5 text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors"
                           placeholder="you@example.com" required autofocus>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-zinc-300 mb-1.5">Password</label>
                    <input type="password" name="password" id="password"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2.5 text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors"
                           placeholder="••••••••" required>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-zinc-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-zinc-600 bg-zinc-800 text-violet-600 focus:ring-violet-500">
                        Remember me
                    </label>
                </div>

                <button type="submit" id="login-submit"
                        class="w-full py-2.5 bg-violet-600 hover:bg-violet-500 text-white font-semibold rounded-lg transition-colors">
                    Sign In
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-zinc-500 mt-6">
            No account?
            <a href="{{ route('register') }}" class="text-violet-400 hover:text-violet-300">Register here</a>
        </p>
        <p class="text-center text-xs text-zinc-600 mt-2">
            <a href="{{ route('paste.create') }}" class="hover:text-zinc-400">Continue as guest →</a>
        </p>
    </div>

</body>
</html>

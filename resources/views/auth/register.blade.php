<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — DoxMe</title>
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
            <p class="text-zinc-500 text-sm mt-1">Create a free account</p>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 shadow-2xl">
            @if($errors->any())
            <div class="mb-4 p-3 bg-red-900/40 border border-red-800 rounded-lg text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <p class="text-red-300">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-zinc-300 mb-1.5">Display Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2.5 text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors"
                           placeholder="Your Name" required>
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-zinc-300 mb-1.5">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2.5 text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors font-mono"
                           placeholder="unique_handle" required>
                    <p class="text-xs text-zinc-600 mt-1">Letters, numbers, underscores, hyphens only</p>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-300 mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2.5 text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors"
                           placeholder="you@example.com" required>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-zinc-300 mb-1.5">Password</label>
                    <input type="password" name="password" id="password"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2.5 text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors"
                           placeholder="Min. 8 characters" required>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-zinc-300 mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2.5 text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors"
                           placeholder="••••••••" required>
                </div>

                <button type="submit" id="register-submit"
                        class="w-full py-2.5 bg-violet-600 hover:bg-violet-500 text-white font-semibold rounded-lg transition-colors">
                    Create Account
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-zinc-500 mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-violet-400 hover:text-violet-300">Sign in</a>
        </p>
    </div>

</body>
</html>

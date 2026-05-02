<x-layouts.app title="Sign In">
    <div class="w-full max-w-sm px-4">

        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black tracking-tighter text-white font-saira uppercase">
                Sign In
            </h1>
            <p class="mt-1 text-xs text-gray-600">
                Enter your credentials to continue
            </p>
        </div>
        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-6 px-4 py-3 border border-green-900 rounded-lg bg-green-950/40">
                <p class="text-xs text-green-500 font-semibold">
                    {{ session('success') }}
                </p>
            </div>
        @endif
        {{-- Session / Auth Errors --}}
        @if ($errors->any())
            <div class="mb-6 px-4 py-3 border border-red-900 rounded-lg bg-red-950/40">
                <p class="text-xs text-red-500 font-semibold">{{ $errors->first() }}</p>
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('login.post') }}" method="POST" class="space-y-5" autocomplete="off">
            @csrf

            {{-- Username --}}
            <div class="space-y-1.5">
                <label for="username" class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">
                    Username
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-700 pointer-events-none">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </span>
                    <input id="username" name="username" type="text" required autofocus value="{{ old('username') }}"
                        placeholder="yourname"
                        class="w-full pl-10 pr-4 py-3  border border-gray-800 rounded-lg text-gray-200 text-sm placeholder-gray-700 focus:border-red-700 focus:outline-none @error('username') border-red-900 @enderror">
                </div>

            </div>

            {{-- Password --}}
            <div class="space-y-1.5">
                <div class="flex justify-between items-center">
                    <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">
                        Password
                    </label>
                    <a href="#" class="text-[11px] text-red-800 hover:text-red-600 font-semibold">Forgot?</a>
                </div>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-700 pointer-events-none">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0110 0v4" />
                        </svg>
                    </span>
                    <input id="password" name="password" type="password" required placeholder="••••••••"
                        class="w-full pl-10 pr-4 py-3  border border-gray-800 rounded-lg text-gray-200 text-sm placeholder-gray-700 focus:border-red-700 focus:outline-none @error('password') border-red-900 @enderror">
                </div>

            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-3 bg-red-700 hover:bg-red-600 text-white text-sm font-bold rounded-lg uppercase tracking-widest">
                Sign In
            </button>
        </form>

        {{-- Footer link --}}
        <p class="mt-6 text-center text-xs text-gray-700">
            No account?
            <a href="{{ route('register') }}" class="text-red-700 hover:text-red-500 font-semibold">Create one</a>
        </p>

    </div>
</x-layouts.app>
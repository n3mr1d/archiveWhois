<x-layouts.app title="Register">
    <div class="w-full max-w-md px-4 py-6">

        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black tracking-tighter text-white font-saira uppercase">
                Create Account
            </h1>
            <p class="mt-1 text-xs text-gray-600">
                Join the platform and start contributing
            </p>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 px-4 py-3 border border-red-900 rounded-lg bg-red-950/40">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-xs text-red-500 font-semibold">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('register.post') }}" method="POST" class="space-y-5" autocomplete="off">
            @csrf

            {{-- Username --}}
            <div class="space-y-1.5">
                <label for="username" class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">
                    Username <span class="text-red-700">*</span>
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
                        placeholder="johndoe"
                        class="w-full pl-10 pr-4 py-3  border border-gray-800 rounded-lg text-gray-200 text-sm placeholder-gray-700 focus:border-red-700 focus:outline-none @error('username') border-red-900 @enderror">
                </div>
                @error('username')
                    <p class="text-[11px] text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">
                    Email <span class="text-red-700">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-700 pointer-events-none">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                    </span>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}"
                        placeholder="john@example.com"
                        class="w-full pl-10 pr-4 py-3  border border-gray-800 rounded-lg text-gray-200 text-sm placeholder-gray-700 focus:border-red-700 focus:outline-none @error('email') border-red-900 @enderror">
                </div>
                @error('email')
                    <p class="text-[11px] text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password + Confirm side-by-side --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Password --}}
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">
                        Password <span class="text-red-700">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-700 pointer-events-none">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0110 0v4" />
                            </svg>
                        </span>
                        <input id="password" name="password" type="password" required placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-3 border border-gray-800 rounded-lg text-gray-200 text-sm placeholder-gray-700 focus:border-red-700 focus:outline-none @error('password') border-red-900 @enderror">
                    </div>
                    @error('password')
                        <p class="text-[11px] text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="space-y-1.5">
                    <label for="password_confirmation"
                        class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">
                        Confirm <span class="text-red-700">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-700 pointer-events-none">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </span>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-3  border border-gray-800 rounded-lg text-gray-200 text-sm placeholder-gray-700 focus:border-red-700 focus:outline-none">
                    </div>
                </div>

            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-3 bg-red-700 hover:bg-red-600 text-white text-sm font-bold rounded-lg uppercase tracking-widest">
                Create Account
            </button>

        </form>

        {{-- Footer link --}}
        <p class="mt-6 text-center text-xs text-gray-700">
            Already have an account?
            <a href="{{ route('login') }}" class="text-red-700 hover:text-red-500 font-semibold">Sign in</a>
        </p>

    </div>
</x-layouts.app>
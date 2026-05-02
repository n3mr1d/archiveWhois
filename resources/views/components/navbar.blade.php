<nav class="fixed top-0 left-0 right-0 bg-black z-50 border-b border-white/10" id="main-navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ url('/') }}"
                    class="text-[11px] font-black uppercase tracking-[0.2em] {{ request()->routeIs('home') ? 'text-red-500' : 'text-gray-400 hover:text-white transition-colors' }}">
                    Home
                </a>
                <a href="{{ route('search.index') }}"
                    class="text-[11px] font-black uppercase tracking-[0.2em] {{ request()->routeIs('search.index') ? 'text-red-500' : 'text-gray-400 hover:text-white transition-colors' }}">
                    Explore
                </a>
                <a href="{{ route('pastebin.create') }}"
                    class="text-[11px] font-black uppercase tracking-[0.2em] {{ request()->routeIs('pastebin.create') ? 'text-red-500' : 'text-gray-400 hover:text-white transition-colors' }}">
                    Contribute
                </a>
            </div>

            {{-- Action Buttons --}}
            <div class="hidden md:flex items-center gap-5">

                @auth
                    <div class="flex items-center gap-3">
                        <span
                            class="text-[10px] font-black text-gray-600 uppercase tracking-widest">{{ Auth::user()->name }}</span>
                        <a href="/profile"
                            class="h-8 w-8 rounded-full bg-gradient-to-tr from-red-600 to-red-900 border border-white/10 flex items-center justify-center text-white font-black text-[10px] shadow-lg shadow-red-900/20">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </a>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="text-[10px] font-black uppercase tracking-[0.2em] text-white/70 hover:text-red-500 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-6 py-2.5 rounded-full bg-red-600 text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-red-600/40 hover:bg-red-500 hover:-translate-y-0.5 transition-all duration-300">
                        Join
                    </a>
                @endauth
            </div>

            {{-- Mobile Menu Button --}}
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-gray-400 hover:text-white">
                    <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                    <svg x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="display: none;">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        class="md:hidden border-t border-white/5 bg-black/98 backdrop-blur-3xl" style="display: none;">
        <div class="px-6 pt-4 pb-8 space-y-2">
            <a href="{{ route('search.index') }}"
                class="block py-4 text-sm font-black uppercase tracking-widest text-white border-b border-white/5">Search</a>
            <a href="{{ route('pastebin.create') }}"
                class="block py-4 text-sm font-black uppercase tracking-widest text-gray-400 hover:text-white border-b border-white/5">Contribute</a>
            <div class="pt-6 flex flex-col gap-4">
                @auth
                    <a href="/profile" class="text-xs font-black uppercase tracking-widest text-gray-400">Profile
                        Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-black uppercase tracking-widest text-red-500">Sign
                            Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="py-4 text-sm font-black uppercase tracking-widest text-white border border-white/10 rounded-2xl text-center">Login</a>
                    <a href="{{ route('register') }}"
                        class="py-4 text-sm font-black uppercase tracking-widest text-white bg-red-600 rounded-2xl text-center">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Space for the fixed navbar (only on non-home pages or when not transparent) --}}
@if(!request()->routeIs('home'))
    <div class="h-20"></div>
@endif
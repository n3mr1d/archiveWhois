<x-layouts.app title="Protected Paste">
    <div class="w-full max-w-md mx-auto px-4 py-20 flex flex-col items-center text-center gap-6">

        {{-- Lock Icon --}}
        <div class="w-20 h-20 rounded-full bg-blue-950/20 border border-blue-900/30 flex items-center justify-center">
            <svg class="w-8 h-8 text-blue-500/70" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </div>

        <div>
            <h1 class="text-xl font-black text-white mb-2">Password Protected</h1>
            <p class="text-gray-600 text-sm">
                <span class="text-white font-bold">{{ $pastebin->title }}</span>
                is protected. Enter the password to view.
            </p>
        </div>

        @if(session('error'))
        <div class="w-full bg-red-950/20 border border-red-900/40 rounded-xl px-4 py-3 text-red-400 text-xs font-bold">
            {{ session('error') }}
        </div>
        @endif

        <form action="" method="POST" class="w-full space-y-3">
            @csrf
            <input type="password" name="paste_password" required autofocus
                class="w-full bg-gray-950 border border-gray-800 focus:border-blue-600/50 rounded-xl px-4 py-3 text-white text-sm placeholder-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-600/5 transition-all text-center tracking-widest"
                placeholder="Enter password...">
            <button type="submit"
                class="w-full px-8 py-3 bg-blue-950/20 border border-blue-900/40 hover:bg-blue-950/40 hover:border-blue-700/50 text-blue-400 font-black uppercase tracking-widest rounded-xl text-xs transition-all active:scale-95">
                Unlock Paste
            </button>
        </form>

        <a href="{{ url('/') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-700 hover:text-red-600 transition-colors">
            ← Back to Home
        </a>
    </div>
</x-layouts.app>

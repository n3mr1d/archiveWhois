<footer class="bg-black w-full border-t flex sm:flex-col border-gray-900 py-5">
    <div class="mx-10 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-8">
            <!-- Brand & Security -->
            <div class="flex flex-col items-center md:items-start gap-1">
                <div class="flex items-center gap-3 mb-1">
                    <x-logo class="w-8 h-8 opacity-90" />
                    <a href="/"
                        class="text-xl font-black tracking-tighter text-white hover:text-red-600 transition-colors">
                        DOX<span class="text-red-600">ME</span>
                    </a>
                </div>
                <p class="text-[9px] text-red-700/80 font-bold uppercase tracking-widest">
                    &copy; {{ date('Y') }} Search Engine For Find Information Someone.
                </p>
            </div>

            <!-- Navigation Links -->
            <nav class="flex flex-wrap justify-center gap-x-8 gap-y-2">
                <a href="/"
                    class="text-[11px] font-black uppercase tracking-widest text-gray-500 hover:text-white transition-colors">Archive</a>
                <a href="{{ route('pastebin.create') }}"
                    class="text-[11px] font-black uppercase tracking-widest text-gray-500 hover:text-white transition-colors">Create</a>
                <a href="#"
                    class="text-[11px] font-black uppercase tracking-widest text-gray-500 hover:text-white transition-colors">Terms</a>
                <a href="#"
                    class="text-[11px] font-black uppercase tracking-widest text-gray-500 hover:text-white transition-colors">Privacy</a>
                <a href="#"
                    class="text-[11px] font-black uppercase tracking-widest text-gray-500 hover:text-white transition-colors">Contact</a>
            </nav>

            <!-- Metadata & Tor Info -->

        </div>
    </div>
</footer>

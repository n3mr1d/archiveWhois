<x-layouts.app>
    <x-slot:title>DoxMe — Share Code. Simply.</x-slot:title>

    <!-- Hero Section -->
    <div class="max-w-6xl mx-auto px-4 pt-12 pb-8">
        <div class="text-center mb-10">
            <h1 class="text-4xl sm:text-5xl font-bold tracking-tight text-white mb-3">
                Share Code. <span class="text-violet-400">Simply.</span>
            </h1>
            <p class="text-zinc-400 text-lg max-w-xl mx-auto">
                Fast, minimal pastebin. No account required. Tor-friendly.
            </p>
            <div class="flex items-center justify-center gap-3 mt-6">
                <a href="{{ route('paste.create') }}" id="hero-create-btn"
                   class="px-6 py-3 bg-violet-600 hover:bg-violet-500 text-white font-semibold rounded-lg transition-colors">
                    + Create Paste
                </a>
                <a href="{{ route('paste.search') }}" id="hero-search-btn"
                   class="px-6 py-3 border border-zinc-700 hover:border-zinc-500 text-zinc-300 hover:text-white rounded-lg transition-colors">
                    Search Pastes
                </a>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="flex items-center justify-center gap-8 text-sm text-zinc-500 mb-12">
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block"></span>
                Anonymous uploads
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-violet-400 inline-block"></span>
                Password protection
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-blue-400 inline-block"></span>
                20+ languages
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Pastes -->
            <div class="lg:col-span-2">
                <h2 class="text-xs font-semibold uppercase tracking-widest text-zinc-500 mb-4">Recent Public Pastes</h2>
                @if($recent->isEmpty())
                    <div class="text-center py-16 text-zinc-600">
                        <p class="text-4xl mb-3">📋</p>
                        <p>No pastes yet. Be the first!</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($recent as $paste)
                        <a href="{{ route('paste.show', $paste->slug) }}"
                           class="flex items-center gap-3 p-3 bg-zinc-900 border border-zinc-800 hover:border-zinc-600 rounded-lg transition-colors group"
                           id="paste-item-{{ $paste->slug }}">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="text-sm font-medium text-zinc-200 group-hover:text-white truncate">
                                        {{ $paste->title_display }}
                                    </span>
                                    @if($paste->isPasswordProtected())
                                        <span class="text-xs text-yellow-500" title="Password protected">🔒</span>
                                    @endif
                                    @if($paste->visibility === 'unlisted')
                                        <span class="text-[10px] px-1 py-px bg-zinc-700 text-zinc-400 rounded">unlisted</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 text-xs text-zinc-500">
                                    <span class="border border-zinc-700 rounded px-1 py-px font-mono text-[10px]">
                                        {{ $paste->language }}
                                    </span>
                                    <span>by {{ $paste->author_name }}</span>
                                    <span>·</span>
                                    <span>{{ $paste->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="text-xs text-zinc-600 flex items-center gap-1 shrink-0">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ number_format($paste->view_count) }}
                            </div>
                        </a>
                        @endforeach
                    </div>
                    <a href="{{ route('paste.search') }}" class="block text-center mt-4 text-sm text-violet-400 hover:text-violet-300">
                        Browse all pastes →
                    </a>
                @endif
            </div>

            <!-- Trending Sidebar -->
            <div>
                <h2 class="text-xs font-semibold uppercase tracking-widest text-zinc-500 mb-4">🔥 Trending This Week</h2>
                <livewire:trending-pastes :limit="6" />
            </div>
        </div>
    </div>
</x-layouts.app>

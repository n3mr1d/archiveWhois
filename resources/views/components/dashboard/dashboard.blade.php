<div>
    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
        @foreach([
            ['label' => 'Total Pastes', 'value' => $stats['total_pastes'], 'icon' => '📋'],
            ['label' => 'Public', 'value' => $stats['public_pastes'], 'icon' => '🌍'],
            ['label' => 'Total Views', 'value' => number_format($stats['total_views']), 'icon' => '👁️'],
            ['label' => 'Bookmarks', 'value' => $stats['total_bookmarks'], 'icon' => '🔖'],
        ] as $stat)
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4 text-center">
            <div class="text-2xl mb-1">{{ $stat['icon'] }}</div>
            <div class="text-xl font-bold text-white">{{ $stat['value'] }}</div>
            <div class="text-xs text-zinc-500">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>

    <!-- Tabs -->
    <div class="flex items-center gap-1 border-b border-zinc-800 mb-6">
        <button wire:click="setTab('my-pastes')" id="tab-my-pastes"
                class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors {{ $tab === 'my-pastes' ? 'text-violet-400 border-b-2 border-violet-500 -mb-px' : 'text-zinc-500 hover:text-zinc-300' }}">
            My Pastes
        </button>
        <button wire:click="setTab('bookmarks')" id="tab-bookmarks"
                class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors {{ $tab === 'bookmarks' ? 'text-violet-400 border-b-2 border-violet-500 -mb-px' : 'text-zinc-500 hover:text-zinc-300' }}">
            Bookmarks
        </button>
    </div>

    @if($tab === 'my-pastes')
    <!-- Filters -->
    <div class="flex items-center gap-3 mb-4 flex-wrap">
        <input type="text" wire:model.live.debounce.300ms="search" id="dashboard-search"
               class="bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-1.5 text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-violet-500 w-48"
               placeholder="Search your pastes...">
        <select wire:model.live="visibility" id="dashboard-visibility"
                class="bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-1.5 text-sm text-zinc-300 focus:outline-none focus:border-violet-500">
            <option value="">All visibility</option>
            <option value="public">Public</option>
            <option value="unlisted">Unlisted</option>
            <option value="private">Private</option>
        </select>
        <a href="{{ route('paste.create') }}" id="dashboard-new-paste"
           class="px-4 py-1.5 bg-violet-600 hover:bg-violet-500 text-white text-sm rounded-lg transition-colors ml-auto">
            + New Paste
        </a>
    </div>

    @if($myPastes->isEmpty())
    <div class="text-center py-16 text-zinc-600">
        <p class="text-4xl mb-3">📋</p>
        <p>No pastes yet.</p>
        <a href="{{ route('paste.create') }}" class="text-violet-400 hover:text-violet-300 text-sm mt-2 inline-block">Create your first paste →</a>
    </div>
    @else
    <div class="space-y-2">
        @foreach($myPastes as $paste)
        <div class="flex items-center gap-3 p-3 bg-zinc-900 border border-zinc-800 hover:border-zinc-700 rounded-lg group" id="dash-paste-{{ $paste->slug }}">
            <div class="flex-1 min-w-0">
                <a href="{{ route('paste.show', $paste->slug) }}"
                   class="text-sm font-medium text-zinc-200 group-hover:text-white hover:text-violet-300 truncate block">
                    {{ $paste->title_display }}
                </a>
                <div class="flex items-center gap-2 text-xs text-zinc-600 mt-0.5">
                    <span class="font-mono border border-zinc-800 rounded px-1">{{ $paste->language }}</span>
                    <span>{{ $paste->created_at->diffForHumans() }}</span>
                    <span class="flex items-center gap-0.5">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ number_format($paste->view_count) }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <!-- Visibility Badge -->
                <span class="text-xs px-2 py-0.5 rounded {{ $paste->visibility === 'public' ? 'bg-emerald-900/40 text-emerald-400' : ($paste->visibility === 'private' ? 'bg-red-900/40 text-red-400' : 'bg-zinc-700 text-zinc-400') }}">
                    {{ $paste->visibility }}
                </span>

                <!-- Quick Visibility Toggle -->
                <div class="relative group/vis">
                    <button class="p-1 text-zinc-600 hover:text-zinc-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                    </button>
                    <div class="hidden group-hover/vis:block absolute right-0 top-6 w-36 bg-zinc-800 border border-zinc-700 rounded-lg shadow-xl z-10 text-xs">
                        <a href="{{ route('paste.show', $paste->slug) }}" class="block px-3 py-2 hover:bg-zinc-700">View</a>
                        <button wire:click="toggleVisibility({{ $paste->id }}, 'public')" class="w-full text-left px-3 py-2 hover:bg-zinc-700">Make Public</button>
                        <button wire:click="toggleVisibility({{ $paste->id }}, 'unlisted')" class="w-full text-left px-3 py-2 hover:bg-zinc-700">Make Unlisted</button>
                        <button wire:click="toggleVisibility({{ $paste->id }}, 'private')" class="w-full text-left px-3 py-2 hover:bg-zinc-700">Make Private</button>
                        <button wire:click="deletePaste({{ $paste->id }})"
                                wire:confirm="Delete '{{ addslashes($paste->title_display) }}'?"
                                class="w-full text-left px-3 py-2 text-red-400 hover:bg-zinc-700 rounded-b-lg">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $myPastes->links() }}</div>
    @endif

    @else
    <!-- Bookmarks Tab -->
    @if($bookmarks->isEmpty())
    <div class="text-center py-16 text-zinc-600">
        <p class="text-4xl mb-3">🔖</p>
        <p>No bookmarks yet.</p>
        <a href="{{ route('paste.search') }}" class="text-violet-400 hover:text-violet-300 text-sm mt-2 inline-block">Browse pastes →</a>
    </div>
    @else
    <div class="space-y-2">
        @foreach($bookmarks as $paste)
        <a href="{{ route('paste.show', $paste->slug) }}"
           class="flex items-center gap-3 p-3 bg-zinc-900 border border-zinc-800 hover:border-zinc-700 rounded-lg group"
           id="bookmark-paste-{{ $paste->slug }}">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-zinc-200 group-hover:text-white truncate">{{ $paste->title_display }}</p>
                <div class="flex items-center gap-2 text-xs text-zinc-600 mt-0.5">
                    <span class="font-mono border border-zinc-800 rounded px-1">{{ $paste->language }}</span>
                    <span>by {{ $paste->author_name }}</span>
                    <span>{{ $paste->created_at->diffForHumans() }}</span>
                </div>
            </div>
            <svg class="w-4 h-4 text-yellow-500 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
            </svg>
        </a>
        @endforeach
    </div>
    <div class="mt-4">{{ $bookmarks->links() }}</div>
    @endif
    @endif
</div>
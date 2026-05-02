<x-layouts.app title="{{ $query ? $query . ' — ' : '' }}Search">
    <div class="w-full max-w-5xl mx-auto px-4">
        
        {{-- ===== SEARCH FILTERS & INPUT (Sub-header) ===== --}}
        <div class="sticky top-20 z-40 bg-black/90 backdrop-blur-lg border-b border-gray-900/80 -mx-4 px-4 py-4 mb-6">
            <div class="flex flex-col gap-4">
                {{-- Search Form --}}
                <form action="{{ route('search.index') }}" method="GET"
                    class="flex items-center gap-2 w-full"
                    id="search-header-form" autocomplete="off">

                    <div class="relative flex-1 group" id="search-header-container">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-red-600 transition-colors pointer-events-none z-10">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="q"
                            id="header-search-input"
                            value="{{ $query }}"
                            class="w-full pl-12 pr-12 py-3 bg-gray-950 border border-gray-800 text-white text-base rounded-2xl placeholder-gray-700 focus:border-red-600/50 focus:ring-4 focus:ring-red-600/5 focus:outline-none transition-all shadow-xl"
                            placeholder="Search identities, intel, archives..."
                            autofocus
                        >
                        @if($query)
                        <a href="{{ route('search.index') }}"
                           class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 hover:text-red-600 transition-colors">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M18 6 6 18M6 6l12 12"/>
                            </svg>
                        </a>
                        @endif

                        {{-- Suggestions --}}
                        <div id="header-suggestions"
                            class="absolute top-full left-0 right-0 mt-2 bg-gray-950 border border-gray-800 rounded-2xl shadow-2xl overflow-hidden z-50 hidden">
                            <div id="header-suggestions-content" class="divide-y divide-gray-900/50"></div>
                        </div>
                    </div>

                    <button type="submit"
                        class="px-6 py-3 bg-red-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all hover:bg-red-500 hover:-translate-y-0.5 shadow-lg shadow-red-600/20 hidden sm:block">
                        Search
                    </button>
                </form>

                {{-- Filter Tabs --}}
                @if($query)
                <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide">
                    @php
                    $filters = [
                        'all'         => ['All',         'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        'title'       => ['Title',        'M7 7h10M7 12h10m-7 5h7'],
                        'description' => ['Description',  'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'tags'        => ['Tags',          'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                    ];
                    @endphp

                    @foreach($filters as $key => [$label, $icon])
                    <a href="{{ route('search.index') }}?{{ http_build_query(array_merge(request()->query(), ['filter' => $key])) }}"
                       class="flex items-center gap-2 px-4 py-2 text-[10px] font-black uppercase tracking-widest whitespace-nowrap rounded-xl border transition-all
                              {{ $filter === $key ? 'bg-red-600/10 border-red-600/50 text-red-500' : 'bg-gray-950 border-gray-900 text-gray-600 hover:text-gray-400 hover:border-gray-800' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="{{ $icon }}"/>
                        </svg>
                        {{ $label }}
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ===== RESULTS SECTION ===== --}}
        <div class="pb-20">
            @if(!$query)
                {{-- Empty State --}}
                <div class="flex flex-col items-center text-center py-20 gap-8">
                    <div class="relative">
                        <div class="absolute inset-0 rounded-full blur-[100px] bg-red-600/20 scale-150 animate-pulse"></div>
                        <svg class="relative w-24 h-24 text-gray-900" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                        </svg>
                    </div>
                    <div class="max-w-xl">
                        <h2 class="text-3xl font-black text-white mb-4 tracking-tight uppercase italic">Intelligence <span class="text-red-600">Database</span></h2>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            Access the vault of DoxMe intelligence. Our search engine indexes over {{ number_format(\App\Models\Pastebin::public()->count()) }} encrypted records and public archives.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8 w-full max-w-3xl text-left">
                        @foreach([
                            ['🔥', 'Trending Now', 'Check out the most viewed intel from the last 24 hours.'],
                            ['🛡️', 'Secure Archive', 'Encrypted data sharing with automated expiry protocols.'],
                            ['⚡', 'Global Search', 'Instant access to indexed identities and leaked intel.']
                        ] as [$emoji, $title, $desc])
                        <div class="bg-gradient-to-br from-gray-950 to-black border border-white/5 rounded-2xl p-5 hover:border-red-900/30 transition-colors">
                            <div class="text-2xl mb-3">{{ $emoji }}</div>
                            <h4 class="text-white text-xs font-black uppercase tracking-widest mb-2">{{ $title }}</h4>
                            <p class="text-gray-600 text-[11px] leading-relaxed">{{ $desc }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Trending --}}
                @if($trending->isNotEmpty())
                <section class="mt-4">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xs font-black uppercase tracking-[0.3em] text-gray-600 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-red-600 rounded-full"></span>
                            Live Trending Intel
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($trending as $paste)
                        @include('search._paste-card', ['paste' => $paste, 'query' => ''])
                        @endforeach
                    </div>
                </section>
                @endif
            @else
                {{-- Meta Info --}}
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">
                        Found <span class="text-white">{{ number_format($totalCount) }}</span> records <span class="mx-2 text-gray-800">/</span> <span class="text-red-500">{{ $searchTime }}ms</span>
                    </p>

                    <div class="flex items-center gap-2">
                        @foreach(['relevance' => 'Relevance', 'date' => 'Latest', 'views' => 'Popular'] as $key => $label)
                        <a href="{{ route('search.index') }}?{{ http_build_query(array_merge(request()->query(), ['sort' => $key])) }}"
                           class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest transition-all border
                                  {{ $sort === $key ? 'bg-red-600 border-red-600 text-white' : 'bg-transparent border-gray-900 text-gray-600 hover:border-gray-700' }}">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Results --}}
                @if($results->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($results as $paste)
                        @include('search._paste-card', ['paste' => $paste, 'query' => $query])
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($results->hasPages())
                    <div class="mt-12 flex flex-col items-center gap-6">
                        <div class="flex items-center gap-2">
                            @if(!$results->onFirstPage())
                                <a href="{{ $results->previousPageUrl() }}" class="px-5 py-3 bg-gray-950 border border-gray-900 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-white transition-colors">Prev</a>
                            @endif
                            
                            @foreach($results->getUrlRange(max(1, $results->currentPage()-2), min($results->lastPage(), $results->currentPage()+2)) as $page => $url)
                                <a href="{{ url }}" class="w-11 h-11 flex items-center justify-center rounded-2xl text-xs font-black transition-all {{ $page === $results->currentPage() ? 'bg-red-600 text-white' : 'bg-gray-950 border border-gray-900 text-gray-600 hover:text-white' }}">{{ $page }}</a>
                            @endforeach

                            @if($results->hasMorePages())
                                <a href="{{ $results->nextPageUrl() }}" class="px-5 py-3 bg-gray-950 border border-gray-900 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-white transition-colors">Next</a>
                            @endif
                        </div>
                    </div>
                    @endif
                @else
                    <div class="flex flex-col items-center text-center py-20 gap-6">
                        <div class="w-20 h-20 rounded-3xl bg-gray-950 border border-gray-900 flex items-center justify-center rotate-3">
                            <svg class="w-8 h-8 text-gray-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M10 10l4 4m0-4l-4 4m9-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-black text-gray-600 uppercase tracking-tight">No intelligence found for <span class="text-red-900">"{{ $query }}"</span></h2>
                        <p class="text-gray-700 text-sm max-w-md">The archives contain no matching records. Try broader terms or check the trending section for related intel.</p>
                        
                        @if($suggestions->isNotEmpty())
                            <div class="mt-4 flex flex-wrap justify-center gap-2">
                                @foreach($suggestions as $s)
                                    <a href="{{ route('search.index') }}?q={{ urlencode($s) }}" class="px-4 py-2 bg-gray-950 border border-gray-900 rounded-xl text-xs font-bold text-gray-500 hover:text-red-500 transition-colors">{{ $s }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- ===== SCRIPTS ===== --}}
    <script>
    (function() {
        const input = document.getElementById('header-search-input');
        const suggestions = document.getElementById('header-suggestions');
        const content = document.getElementById('header-suggestions-content');
        let timer = null;

        function render(data, q) {
            content.innerHTML = '';
            const { pastes = [], tags = [] } = data;
            if (pastes.length === 0 && tags.length === 0) { suggestions.classList.add('hidden'); return; }

            pastes.forEach(p => {
                const a = document.createElement('a');
                a.href = p.url;
                a.className = 'flex items-center gap-3 px-5 py-4 hover:bg-gray-900/60 transition-all';
                a.innerHTML = `<div class="w-1.5 h-1.5 bg-red-600 rounded-full"></div><div><p class="text-white text-xs font-bold truncate">${p.title}</p><p class="text-gray-600 text-[10px] truncate">${p.description || ''}</p></div>`;
                content.appendChild(a);
            });

            suggestions.classList.remove('hidden');
        }

        input?.addEventListener('input', (e) => {
            const val = e.target.value.trim();
            clearTimeout(timer);
            if (val.length < 2) { suggestions.classList.add('hidden'); return; }
            timer = setTimeout(() => {
                fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(val)}`)
                    .then(r => r.json()).then(d => render(d, val));
            }, 200);
        });

        document.addEventListener('click', e => { if (!input?.contains(e.target)) suggestions?.classList.add('hidden'); });
    })();
    </script>
</x-layouts.app>

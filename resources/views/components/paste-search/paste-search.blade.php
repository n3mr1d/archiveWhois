<div>
    <!-- Search Bar -->
    <div class="relative mb-6">
        <input type="text" wire:model.live.debounce.400ms="query" id="search-input"
               class="w-full bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 pl-11 text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors text-base"
               placeholder="Search pastes by title, description, or content..."
               autofocus>
        <svg class="absolute left-3.5 top-3.5 w-5 h-5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
        </svg>
        @if($query)
        <button type="button" wire:click="$set('query', '')" class="absolute right-3 top-3 text-zinc-500 hover:text-zinc-300">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        @endif
    </div>

    <!-- Filters -->
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <span class="text-xs text-zinc-500 uppercase tracking-wider">Language:</span>
        <select wire:model.live="language" id="search-language-filter"
                class="bg-zinc-900 border border-zinc-700 rounded-lg px-2 py-1 text-sm text-zinc-300 focus:outline-none focus:border-violet-500">
            <option value="">All Languages</option>
            @foreach(['php','javascript','typescript','python','ruby','go','rust','java','c','cpp','csharp','html','css','sql','bash','json','yaml','xml','markdown'] as $lang)
                <option value="{{ $lang }}">{{ strtoupper($lang) }}</option>
            @endforeach
        </select>
    </div>

    <!-- Results -->
    @if(strlen($query) < 2 && empty($query))
        <div class="text-center py-20 text-zinc-600">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
            </svg>
            <p>Type at least 2 characters to search</p>
        </div>
    @elseif($results->isEmpty())
        <div class="text-center py-20 text-zinc-600">
            <p class="text-4xl mb-3">🔍</p>
            <p>No results for <strong class="text-zinc-400">"{{ $query }}"</strong></p>
        </div>
    @else
        <div class="mb-3 text-sm text-zinc-500">
            {{ $results->total() }} result{{ $results->total() !== 1 ? 's' : '' }} for
            <strong class="text-zinc-300">"{{ $query }}"</strong>
        </div>

        <div class="space-y-3">
            @foreach($results as $paste)
            <a href="{{ route('paste.show', $paste->slug) }}"
               class="block p-4 bg-zinc-900 border border-zinc-800 hover:border-zinc-600 rounded-xl transition-colors group"
               id="search-result-{{ $paste->slug }}">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <!-- Title with highlight -->
                        <h3 class="font-semibold text-zinc-100 group-hover:text-white mb-1 truncate">
                            {!! $this->highlight($paste->title_display, $query) !!}
                        </h3>

                        @if($paste->description)
                        <p class="text-sm text-zinc-400 mb-2 line-clamp-1">
                            {!! $this->highlight($paste->description, $query) !!}
                        </p>
                        @endif

                        <!-- Content snippet -->
                        @php
                            $lower = strtolower($paste->content);
                            $pos = strpos($lower, strtolower($query));
                            $snippet = $pos !== false
                                ? substr($paste->content, max(0, $pos - 60), 200)
                                : substr($paste->content, 0, 150);
                            $snippet = ltrim($snippet, "\n\r");
                        @endphp
                        <p class="text-xs font-mono text-zinc-500 line-clamp-2 bg-zinc-950 rounded px-2 py-1">
                            {!! $this->highlight($snippet, $query) !!}
                        </p>
                    </div>

                    <div class="text-right shrink-0 text-xs text-zinc-600 space-y-1">
                        <div class="border border-zinc-700 rounded px-1.5 py-px font-mono">{{ $paste->language }}</div>
                        <div class="flex items-center gap-1 justify-end">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            {{ number_format($paste->view_count) }}
                        </div>
                        <div>{{ $paste->created_at->diffForHumans(null, true) }}</div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        @if($results->hasMorePages())
        <div class="mt-6 text-center">
            <button wire:click="loadMore" id="search-load-more"
                    class="px-6 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 rounded-lg text-sm transition-colors">
                Load more results
            </button>
        </div>
        @endif
    @endif

    <div wire:loading wire:target="query, language" class="text-center py-8 text-zinc-600">
        <div class="inline-block w-5 h-5 border-2 border-zinc-700 border-t-violet-500 rounded-full animate-spin"></div>
    </div>
</div>

<style>
.search-highlight {
    background-color: rgba(167, 139, 250, 0.25);
    color: #c4b5fd;
    border-radius: 2px;
    padding: 0 1px;
    font-weight: 600;
}
</style>
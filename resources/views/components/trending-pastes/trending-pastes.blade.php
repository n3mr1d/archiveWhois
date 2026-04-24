<div>
@if($trending->isEmpty())
    <div class="text-center py-8 text-zinc-600 text-sm">
        <p>No trending pastes yet.</p>
    </div>
@else
    <div class="space-y-2">
        @foreach($trending as $i => $paste)
        <a href="{{ route('paste.show', $paste->slug) }}"
           class="flex items-center gap-3 p-2.5 hover:bg-zinc-800/50 rounded-lg transition-colors group"
           id="trending-item-{{ $paste->slug }}">
            <span class="text-xs font-bold text-zinc-700 w-5 text-center shrink-0">
                {{ $loop->iteration <= 3 ? ['🥇','🥈','🥉'][$loop->index] : $loop->iteration }}
            </span>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-zinc-300 group-hover:text-white truncate">
                    {{ $paste->title_display }}
                </p>
                <p class="text-xs text-zinc-600 flex items-center gap-1">
                    <span class="font-mono border border-zinc-800 rounded px-1">{{ $paste->language }}</span>
                    <span class="flex items-center gap-0.5">
                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ number_format($paste->view_count) }}
                    </span>
                </p>
            </div>
        </a>
        @endforeach
    </div>
@endif
</div>
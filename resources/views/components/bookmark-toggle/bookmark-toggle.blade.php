<div>
    <button type="button" wire:click="toggle" id="bookmark-btn-{{ $pasteId }}"
            title="{{ $bookmarked ? 'Remove bookmark' : 'Add bookmark' }}"
            class="px-3 py-1.5 text-sm rounded-lg transition-colors flex items-center gap-1.5 {{ $bookmarked ? 'bg-yellow-900/40 text-yellow-400 hover:bg-yellow-900/60' : 'bg-zinc-800 hover:bg-zinc-700 text-zinc-400 hover:text-yellow-400' }}">
        <svg class="w-4 h-4" fill="{{ $bookmarked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
        </svg>
        {{ $bookmarked ? 'Bookmarked' : 'Bookmark' }}
    </button>
</div>
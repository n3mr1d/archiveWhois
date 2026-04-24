<div>
@if(!$passwordVerified)
    <!-- Password Gate -->
    <div class="max-w-sm mx-auto mt-20 text-center">
        <div class="text-4xl mb-4">🔒</div>
        <h2 class="text-xl font-semibold text-white mb-2">Password Protected</h2>
        <p class="text-zinc-400 text-sm mb-6">This paste requires a password to view.</p>

        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6">
            <input type="password" wire:model="enteredPassword"
                   wire:keydown.enter="verifyPassword"
                   class="w-full bg-zinc-800 border {{ $wrongPassword ? 'border-red-600' : 'border-zinc-700' }} rounded-lg px-3 py-2.5 text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-violet-500 transition-colors mb-3"
                   placeholder="Enter password..." autofocus id="paste-password-input">
            @if($wrongPassword)
            <p class="text-red-400 text-sm mb-3">Incorrect password.</p>
            @endif
            <button type="button" wire:click="verifyPassword"
                    id="paste-password-btn"
                    class="w-full py-2.5 bg-violet-600 hover:bg-violet-500 text-white font-semibold rounded-lg transition-colors">
                Unlock Paste
            </button>
        </div>
    </div>
@else
    <!-- Paste Content -->
    <div>
        <!-- Header -->
        <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white mb-1">{{ $paste->title_display }}</h1>
                @if($paste->description)
                <p class="text-zinc-400 text-sm">{{ $paste->description }}</p>
                @endif
                <div class="flex flex-wrap items-center gap-2 mt-2 text-xs text-zinc-500">
                    <span class="border border-zinc-700 rounded px-1.5 py-px font-mono">{{ $paste->language }}</span>
                    <span>by
                        @if($paste->user)
                            <span class="text-zinc-300">{{ $paste->user->role_badge }} {{ $paste->user->display_name }}</span>
                        @else
                            <span class="text-zinc-400">{{ $paste->guest_name ?? 'Anonymous' }}</span>
                        @endif
                    </span>
                    <span>·</span>
                    <span>{{ $paste->created_at->diffForHumans() }}</span>
                    <span>·</span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        {{ number_format($paste->view_count) }} views
                    </span>
                    @if($paste->visibility !== 'public')
                    <span class="border border-zinc-700 rounded px-1.5 py-px">
                        {{ $paste->visibility === 'private' ? '🔒 Private' : '🔗 Unlisted' }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Copy -->
                <button type="button" id="copy-btn"
                        onclick="copyPaste()"
                        class="px-3 py-1.5 text-sm bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white rounded-lg transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Copy
                </button>

                <!-- Raw -->
                <a href="{{ route('paste.raw', $paste->slug) }}" id="raw-btn" target="_blank"
                   class="px-3 py-1.5 text-sm bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white rounded-lg transition-colors">
                    Raw
                </a>

                <!-- Bookmark -->
                <livewire:bookmark-toggle :pasteId="$paste->id" :key="'bm-'.$paste->id" />

                <!-- Owner Actions -->
                @if(auth()->check() && ($paste->user_id === auth()->id() || auth()->user()->isAdmin()))
                <a href="{{ route('paste.create') }}?fork={{ $paste->slug }}" id="fork-btn"
                   class="px-3 py-1.5 text-sm bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white rounded-lg transition-colors">
                    Fork
                </a>
                <form method="POST" action="/pastes/{{ $paste->slug }}" onsubmit="return confirm('Delete this paste?')">
                    @csrf @method('DELETE')
                    <button type="submit" id="delete-paste-btn"
                            class="px-3 py-1.5 text-sm bg-red-900/50 hover:bg-red-800 text-red-400 hover:text-red-300 rounded-lg transition-colors">
                        Delete
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Code Block -->
        <div class="relative">
            <pre id="paste-content" class="bg-zinc-950 border border-zinc-800 rounded-xl p-4 sm:p-6 overflow-x-auto text-sm font-mono text-zinc-200 leading-relaxed whitespace-pre-wrap break-words max-h-[70vh] overflow-y-auto">{{ $paste->content }}</pre>
        </div>

        <!-- Comment Section -->
        <div class="mt-10">
            <livewire:comment-section :paste="$paste" :key="'comments-'.$paste->id" />
        </div>
    </div>
@endif

<script>
function copyPaste() {
    const content = document.getElementById('paste-content').innerText;
    navigator.clipboard.writeText(content).then(() => {
        const btn = document.getElementById('copy-btn');
        const orig = btn.innerHTML;
        btn.innerHTML = '✓ Copied!';
        btn.classList.add('text-emerald-400');
        setTimeout(() => { btn.innerHTML = orig; btn.classList.remove('text-emerald-400'); }, 2000);
    });
}
</script>
</div>
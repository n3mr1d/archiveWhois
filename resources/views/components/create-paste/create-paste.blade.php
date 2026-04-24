<div class="space-y-6" wire:id="{{ $this->getId() }}">

    <!-- Title & Description -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="paste-title" class="block text-sm font-medium text-zinc-300 mb-1.5">Title <span class="text-zinc-600">(optional)</span></label>
            <input type="text" id="paste-title" wire:model="title"
                   class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2.5 text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors"
                   placeholder="Untitled Paste">
            @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="paste-language" class="block text-sm font-medium text-zinc-300 mb-1.5">Language</label>
            <select id="paste-language" wire:model="language"
                    class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2.5 text-zinc-100 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors">
                @foreach($languages as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label for="paste-description" class="block text-sm font-medium text-zinc-300 mb-1.5">Description <span class="text-zinc-600">(optional)</span></label>
        <input type="text" id="paste-description" wire:model="description"
               class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2.5 text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-violet-500 transition-colors"
               placeholder="Brief description...">
    </div>

    <!-- Content Editor -->
    <div>
        <label for="paste-content" class="block text-sm font-medium text-zinc-300 mb-1.5">
            Content <span class="text-red-400">*</span>
        </label>
        <textarea id="paste-content" wire:model="content" rows="16"
                  class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-3 text-zinc-100 font-mono text-sm placeholder-zinc-700 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors resize-y leading-relaxed"
                  placeholder="Paste your code or text here..." spellcheck="false"></textarea>
        @error('content') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        <p class="text-xs text-zinc-600 mt-1">{{ strlen($content) }} / 512,000 characters</p>
    </div>

    <!-- Options Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Visibility -->
        <div>
            <label for="paste-visibility" class="block text-sm font-medium text-zinc-300 mb-1.5">Visibility</label>
            <select id="paste-visibility" wire:model="visibility"
                    class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2.5 text-zinc-100 focus:outline-none focus:border-violet-500 transition-colors">
                <option value="public">🌍 Public</option>
                <option value="unlisted">🔗 Unlisted</option>
                <option value="private">🔒 Private (requires login)</option>
            </select>
        </div>

        <!-- Password -->
        <div>
            <label for="paste-password" class="block text-sm font-medium text-zinc-300 mb-1.5">
                Password <span class="text-zinc-600">(optional)</span>
            </label>
            <input type="{{ $showPassword ? 'text' : 'password' }}" id="paste-password" wire:model="password"
                   class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2.5 text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-violet-500 transition-colors"
                   placeholder="Leave blank for no password">
            <button type="button" wire:click="$toggle('showPassword')"
                    class="text-xs text-zinc-500 hover:text-zinc-300 mt-1">
                {{ $showPassword ? 'Hide' : 'Show' }} password
            </button>
        </div>

        <!-- Guest Name (only if not logged in) -->
        @guest
        <div>
            <label for="paste-guest" class="block text-sm font-medium text-zinc-300 mb-1.5">Your Name <span class="text-zinc-600">(optional)</span></label>
            <input type="text" id="paste-guest" wire:model="guestName"
                   class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2.5 text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-violet-500 transition-colors"
                   placeholder="Anonymous">
        </div>
        @endguest
    </div>

    @guest
    <div class="p-3 bg-zinc-900 border border-zinc-800 rounded-lg text-xs text-zinc-500">
        💡 <a href="{{ route('register') }}" class="text-violet-400 hover:text-violet-300">Register</a> or
        <a href="{{ route('login') }}" class="text-violet-400 hover:text-violet-300">login</a>
        to manage your pastes, bookmark others, and leave comments.
    </div>
    @endguest

    <!-- Submit -->
    <div class="flex items-center gap-3">
        <button type="button" wire:click="submit" wire:loading.attr="disabled"
                id="paste-submit-btn"
                class="px-6 py-2.5 bg-violet-600 hover:bg-violet-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold rounded-lg transition-colors">
            <span wire:loading.remove>Create Paste</span>
            <span wire:loading>Creating...</span>
        </button>
        <a href="{{ route('home') }}" class="text-sm text-zinc-500 hover:text-zinc-300">Cancel</a>
    </div>
</div>
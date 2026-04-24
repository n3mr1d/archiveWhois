<div>
    <h2 class="text-base font-semibold text-white mb-4 flex items-center gap-2">
        <svg class="w-4 h-4 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        Comments
        <span class="text-xs text-zinc-600 font-normal">({{ $comments->count() }})</span>
    </h2>

    <!-- Comment Form -->
    @auth
    <div class="mb-6">
        @if($replyTo)
        <div class="mb-2 flex items-center gap-2 text-xs text-zinc-500">
            <span>Replying to <strong class="text-zinc-300">{{ $replyToName }}</strong></span>
            <button wire:click="cancelReply" class="text-zinc-600 hover:text-zinc-400">✕ Cancel</button>
        </div>
        @endif

        <div class="flex gap-3">
            <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-8 h-8 rounded-full shrink-0 mt-0.5">
            <div class="flex-1">
                <textarea wire:model="comment" id="comment-input" rows="2"
                          class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-100 text-sm placeholder-zinc-600 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors resize-none"
                          placeholder="Write a comment..."></textarea>
                @error('comment') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                <div class="mt-2 flex items-center gap-2">
                    <button type="button" wire:click="postComment" id="comment-submit"
                            class="px-4 py-1.5 bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium rounded-lg transition-colors">
                        Post
                    </button>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="mb-6 p-3 bg-zinc-900 border border-zinc-800 rounded-lg text-sm text-zinc-500">
        <a href="{{ route('login') }}" class="text-violet-400 hover:text-violet-300">Sign in</a> to leave a comment.
    </div>
    @endauth

    <!-- Comments List -->
    <div class="space-y-4" id="comments-list">
        @forelse($comments as $comment)
        <div class="flex gap-3" id="comment-{{ $comment->id }}">
            <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->display_name }}"
                 class="w-8 h-8 rounded-full shrink-0 mt-0.5">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-sm font-medium text-zinc-200">
                        {{ $comment->user->role_badge }} {{ $comment->user->display_name }}
                    </span>
                    <span class="text-xs text-zinc-600">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-zinc-300 whitespace-pre-wrap">{{ $comment->content }}</p>

                <!-- Actions -->
                <div class="flex items-center gap-3 mt-1">
                    @auth
                    <button wire:click="setReply({{ $comment->id }}, '{{ $comment->user->display_name }}')"
                            class="text-xs text-zinc-600 hover:text-zinc-400">
                        Reply
                    </button>
                    @if(auth()->id() === $comment->user_id || auth()->user()?->isAdmin())
                    <button wire:click="deleteComment({{ $comment->id }})"
                            wire:confirm="Delete this comment?"
                            class="text-xs text-red-700 hover:text-red-500">
                        Delete
                    </button>
                    @endif
                    @endauth
                </div>

                <!-- Replies -->
                @if($comment->replies->isNotEmpty())
                <div class="mt-3 space-y-3 pl-4 border-l border-zinc-800">
                    @foreach($comment->replies as $reply)
                    <div class="flex gap-2" id="comment-{{ $reply->id }}">
                        <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->user->display_name }}"
                             class="w-6 h-6 rounded-full shrink-0 mt-0.5">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="text-xs font-medium text-zinc-200">
                                    {{ $reply->user->role_badge }} {{ $reply->user->display_name }}
                                </span>
                                <span class="text-xs text-zinc-600">{{ $reply->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-zinc-300 whitespace-pre-wrap">{{ $reply->content }}</p>
                            @auth
                            @if(auth()->id() === $reply->user_id || auth()->user()?->isAdmin())
                            <button wire:click="deleteComment({{ $reply->id }})"
                                    wire:confirm="Delete this reply?"
                                    class="text-xs text-red-700 hover:text-red-500 mt-0.5">
                                Delete
                            </button>
                            @endif
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-zinc-600 text-sm" id="no-comments">
            No comments yet. Be the first!
        </div>
        @endforelse
    </div>
</div>
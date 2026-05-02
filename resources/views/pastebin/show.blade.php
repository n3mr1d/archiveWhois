<x-layouts.app title="{{ $pastebin->title }}">
    <div class="w-full max-w-4xl mx-auto px-4 pb-16">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 mb-6 text-[10px] font-black uppercase tracking-widest text-gray-700">
            <a href="{{ url('/') }}" class="hover:text-gray-500 transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('search.index') }}" class="hover:text-gray-500 transition-colors">Archive</a>
            <span>/</span>
            <span class="text-gray-600">{{ \Illuminate\Support\Str::limit($pastebin->title, 30) }}</span>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">

            {{-- ===== MAIN CONTENT ===== --}}
            <article class="flex-1 min-w-0">

                {{-- Profile Banner --}}
                @if($pastebin->banner_path && $pastebin->banner_path !== 'profile_anonymous.png')
                <div class="mb-6 rounded-2xl overflow-hidden border border-gray-900 max-h-48">
                    <img src="{{ asset('storage/' . $pastebin->banner_path) }}"
                         alt="{{ $pastebin->title }}"
                         class="w-full h-48 object-cover">
                </div>
                @endif

                {{-- Title Block --}}
                <div class="mb-6">
                    <div class="flex flex-wrap items-start gap-2 mb-3">
                        <h1 class="text-2xl font-black text-white leading-tight flex-1">
                            {{ $pastebin->title }}
                        </h1>
                        @if($pastebin->isPasswordProtected())
                        <span class="px-2 py-1 bg-blue-950/30 border border-blue-900/30 text-blue-500 text-[9px] font-black uppercase tracking-widest rounded-lg">🔒 Protected</span>
                        @endif
                        @if($pastebin->visibility !== 'public')
                        <span class="px-2 py-1 bg-yellow-950/30 border border-yellow-900/30 text-yellow-500 text-[9px] font-black uppercase tracking-widest rounded-lg">{{ $pastebin->visibility }}</span>
                        @endif
                    </div>

                    @if($pastebin->description)
                    <p class="text-gray-500 text-sm leading-relaxed mb-4">{{ $pastebin->description }}</p>
                    @endif

                    {{-- Tags --}}
                    @if($pastebin->categories->isNotEmpty())
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        @foreach($pastebin->categories as $cat)
                        <a href="{{ route('search.index') }}?q={{ urlencode($cat->name) }}&filter=tags"
                           class="px-2.5 py-0.5 bg-red-950/20 border border-red-900/20 text-red-700 text-[9px] font-bold uppercase tracking-wide rounded hover:bg-red-950/40 hover:text-red-500 transition-all">
                            #{{ $cat->name }}
                        </a>
                        @endforeach
                    </div>
                    @endif

                    {{-- Meta bar --}}
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-[10px] text-gray-700 font-bold uppercase tracking-widest py-3 border-y border-gray-900">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                            {{ $pastebin->author_name }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5z"/>
                            </svg>
                            {{ number_format($pastebin->views) }} views
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            {{ $pastebin->created_at->format('M d, Y') }}
                            <span class="text-gray-800">({{ $pastebin->created_at->diffForHumans() }})</span>
                        </span>
                        @if($pastebin->language)
                        <span class="flex items-center gap-1.5 text-purple-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="m16 18 6-6-6-6M8 6l-6 6 6 6"/>
                            </svg>
                            {{ strtoupper($pastebin->language) }}
                        </span>
                        @endif
                        <span class="ml-auto flex items-center gap-1.5">
                            ~{{ $pastebin->reading_time }} min read
                        </span>
                    </div>
                </div>

                {{-- Content --}}
                <div class="prose prose-invert prose-sm max-w-none
                            prose-headings:font-black prose-headings:tracking-tight
                            prose-a:text-red-400 prose-a:no-underline hover:prose-a:underline
                            prose-code:bg-gray-900 prose-code:border prose-code:border-gray-800 prose-code:rounded prose-code:px-1 prose-code:text-red-300
                            prose-pre:bg-gray-950 prose-pre:border prose-pre:border-gray-800 prose-pre:rounded-xl
                            prose-blockquote:border-l-red-600 prose-blockquote:text-gray-500
                            prose-table:border prose-table:border-gray-800
                            prose-th:bg-gray-900 prose-th:text-gray-300
                            prose-td:border prose-td:border-gray-800
                            prose-img:rounded-xl prose-img:border prose-img:border-gray-800
                            ">
                    {!! app(\League\CommonMark\GithubFlavoredMarkdownConverter::class)->convert($pastebin->content) !!}
                </div>

                {{-- Gallery --}}
                @if($pastebin->galleries->isNotEmpty())
                <div class="mt-8">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1 h-5 bg-gray-700 rounded-full"></div>
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-gray-500">Gallery</h3>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($pastebin->galleries as $gallery)
                        <a href="{{ asset('storage/' . $gallery->image_path) }}" target="_blank" class="group">
                            <img src="{{ asset('storage/' . $gallery->image_path) }}"
                                 alt="Gallery image"
                                 class="w-full h-32 object-cover rounded-xl border border-gray-800 group-hover:border-red-900/50 transition-all">
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="mt-8 pt-6 border-t border-gray-900 flex flex-wrap gap-3">
                    <button onclick="copyUrl()" id="copy-url-btn"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-950 border border-gray-800 hover:border-gray-700 text-gray-500 hover:text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                        </svg>
                        Copy Link
                    </button>
                    <button onclick="copyContent()" id="copy-content-btn"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-950 border border-gray-800 hover:border-gray-700 text-gray-500 hover:text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                        </svg>
                        Copy Raw
                    </button>
                    <a href="{{ route('pastebin.create') }}"
                        class="flex items-center gap-2 px-4 py-2 bg-red-950/20 border border-red-900/30 hover:border-red-600/50 text-red-700 hover:text-red-500 text-xs font-black uppercase tracking-widest rounded-xl transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        New Paste
                    </a>
                    <a href="{{ route('search.index') }}?q={{ urlencode($pastebin->title) }}"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-950 border border-gray-800 hover:border-gray-700 text-gray-500 hover:text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all ml-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                        </svg>
                        Find Similar
                    </a>
                </div>

            </article>

            {{-- ===== SIDEBAR ===== --}}
            <aside class="w-full lg:w-64 shrink-0 space-y-5">

                {{-- Paste Info Card --}}
                <div class="bg-gray-950 border border-gray-900 rounded-xl p-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-600 mb-3">Paste Info</p>
                    <dl class="space-y-2 text-xs">
                        <div class="flex justify-between gap-2">
                            <dt class="text-gray-700 font-bold">ID</dt>
                            <dd class="text-gray-500 font-mono">{{ $pastebin->slug?->slug }}</dd>
                        </div>
                        <div class="flex justify-between gap-2">
                            <dt class="text-gray-700 font-bold">Views</dt>
                            <dd class="text-white font-bold">{{ number_format($pastebin->views) }}</dd>
                        </div>
                        <div class="flex justify-between gap-2">
                            <dt class="text-gray-700 font-bold">Words</dt>
                            <dd class="text-gray-500">{{ number_format($pastebin->word_count) }}</dd>
                        </div>
                        <div class="flex justify-between gap-2">
                            <dt class="text-gray-700 font-bold">Read</dt>
                            <dd class="text-gray-500">~{{ $pastebin->reading_time }} min</dd>
                        </div>
                        <div class="flex justify-between gap-2">
                            <dt class="text-gray-700 font-bold">Expires</dt>
                            <dd class="text-gray-500">{{ $pastebin->expires_at ? $pastebin->expires_at->format('M d, Y') : '∞ Never' }}</dd>
                        </div>
                        <div class="flex justify-between gap-2">
                            <dt class="text-gray-700 font-bold">Status</dt>
                            <dd>
                                <span class="text-green-600 font-bold">● Active</span>
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Recent Pastes --}}
                @if($recentPastes->isNotEmpty())
                <div class="bg-gray-950 border border-gray-900 rounded-xl p-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-600 mb-3">Recent Pastes</p>
                    <div class="space-y-2">
                        @foreach($recentPastes as $recent)
                        <a href="{{ $recent->slug ? route('pastebin.show', $recent->slug->slug) : '#' }}"
                           class="group block hover:bg-gray-900/40 rounded-lg p-2 -mx-2 transition-all">
                            <p class="text-xs text-gray-400 font-bold line-clamp-1 group-hover:text-red-400 transition-colors">
                                {{ $recent->title }}
                            </p>
                            <p class="text-[10px] text-gray-700 mt-0.5 flex items-center gap-1">
                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5z"/>
                                </svg>
                                {{ number_format($recent->views) }}
                                <span class="ml-auto">{{ $recent->created_at->diffForHumans() }}</span>
                            </p>
                        </a>
                        @endforeach
                    </div>
                    <a href="{{ route('search.index') }}" class="block mt-3 text-center text-[10px] font-black uppercase tracking-widest text-gray-700 hover:text-red-600 transition-colors">
                        View all →
                    </a>
                </div>
                @endif

            </aside>
        </div>
    </div>

    {{-- Hidden raw content for copy --}}
    <textarea id="raw-content" class="hidden" aria-hidden="true">{{ $pastebin->content }}</textarea>

    <script>
    function copyUrl() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const btn = document.getElementById('copy-url-btn');
            btn.textContent = '✓ Copied!';
            btn.classList.add('border-green-900', 'text-green-600');
            setTimeout(() => {
                btn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg> Copy Link`;
                btn.classList.remove('border-green-900', 'text-green-600');
            }, 2000);
        });
    }
    function copyContent() {
        const raw = document.getElementById('raw-content').value;
        navigator.clipboard.writeText(raw).then(() => {
            const btn = document.getElementById('copy-content-btn');
            btn.textContent = '✓ Copied!';
            btn.classList.add('border-green-900', 'text-green-600');
            setTimeout(() => {
                btn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg> Copy Raw`;
                btn.classList.remove('border-green-900', 'text-green-600');
            }, 2000);
        });
    }
    </script>
</x-layouts.app>

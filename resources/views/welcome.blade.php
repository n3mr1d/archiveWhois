<x-layouts.app title="Search">

    <div class="w-full max-w-2xl mx-auto px-4 flex flex-col items-center text-center py-16">

        {{-- ── Brand ── --}}
        <div class="mb-10 flex flex-col items-center gap-3">
            <x-logo class="h-14 w-auto" />
            <h1 class="text-6xl font-black tracking-tighter text-white leading-none font-saira">
                DOX<span class="text-red-600">ME</span>
            </h1>
            <p class="text-gray-500 text-xs max-w-sm leading-relaxed">
                Share and Search identities Someone's leak
                <span class="text-red-700">Search · Share </span>
            </p>
        </div>

        {{-- ── Search Form ── --}}
        <form action="{{ route('search.index') }}" method="GET" id="home-search-form" autocomplete="off" class="w-full">
            <div class="relative" id="search-container">

                {{-- Search icon --}}
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 pointer-events-none">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </span>

                {{-- Input --}}
                <input type="text" name="q" id="home-search-input" autofocus
                    placeholder="Search identities, intel, archives…"
                    class="w-full pl-12 pr-12 py-3.5 border border-gray-800 rounded-lg text-white text-sm placeholder-gray-700 focus:border-red-700 focus:outline-none">

                {{-- Clear button --}}
                <button type="button" id="clear-search"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-700 hover:text-red-600 hidden"
                    aria-label="Clear search">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>

                {{-- Suggestions dropdown --}}
                <div id="search-suggestions"
                    class="absolute top-full left-0 right-0 mt-1 bg-gray-950 border border-gray-800 rounded-lg overflow-hidden z-50 hidden">
                    <div id="suggestions-content" class="divide-y divide-gray-900"></div>
                    <div class="px-4 py-2 border-t border-gray-900 flex justify-between items-center">
                        <span class="text-[10px] text-gray-700 font-semibold uppercase tracking-widest">DoxMe
                            Search</span>
                        <span class="text-[10px] text-red-800 font-semibold uppercase tracking-widest">↵ search</span>
                    </div>
                </div>
            </div>

            {{-- Sub-links --}}
            <div class="flex justify-center items-center gap-3 mt-5 text-[11px] uppercase tracking-widest font-bold">
                <a href="{{ route('pastebin.create') }}" class="text-gray-400 hover:text-white">Contribute</a>
                <span class="text-gray-800">|</span>
                <a href="#" class="text-green-700 hover:text-green-500">Advertise</a>
            </div>
        </form>

    </div>

    {{-- ── Live Search Autocomplete ── --}}
    <script>
        (function () {
            var input = document.getElementById('home-search-input');
            var suggestions = document.getElementById('search-suggestions');
            var content = document.getElementById('suggestions-content');
            var clearBtn = document.getElementById('clear-search');
            var timer = null;
            var lastQuery = '';

            function hide() { suggestions.classList.add('hidden'); }
            function show() { suggestions.classList.remove('hidden'); }

            function escapeHtml(str) {
                var d = document.createElement('div');
                d.appendChild(document.createTextNode(str));
                return d.innerHTML;
            }

            function render(data) {
                content.innerHTML = '';
                var pastes = data.pastes || [];
                var tags = data.tags || [];

                if (!pastes.length && !tags.length) { hide(); return; }

                pastes.forEach(function (paste) {
                    var a = document.createElement('a');
                    a.href = paste.url;
                    a.className = 'flex items-start gap-3 px-4 py-3 hover:bg-gray-900 cursor-pointer';
                    a.innerHTML =
                        '<svg class="mt-0.5 shrink-0 text-gray-700" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">' +
                        '<path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>' +
                        '</svg>' +
                        '<div class="flex-1 min-w-0">' +
                        '<p class="text-white text-xs font-semibold truncate">' + escapeHtml(paste.title) + '</p>' +
                        (paste.description ? '<p class="text-gray-600 text-[10px] truncate">' + escapeHtml(paste.description) + '</p>' : '') +
                        '</div>';
                    content.appendChild(a);
                });

                if (tags.length) {
                    var label = document.createElement('div');
                    label.className = 'px-4 py-1.5 text-[9px] font-black uppercase tracking-widest text-gray-700 bg-gray-900/40';
                    label.textContent = 'Tags';
                    content.appendChild(label);

                    tags.forEach(function (tag) {
                        var a = document.createElement('a');
                        a.href = tag.url;
                        a.className = 'flex items-center justify-between px-4 py-2.5 hover:bg-gray-900 cursor-pointer';
                        a.innerHTML =
                            '<span class="text-xs text-gray-400 font-semibold">' + escapeHtml(tag.name) + '</span>' +
                            '<span class="text-[9px] text-gray-700 font-bold uppercase tracking-widest">' + tag.count + ' pastes</span>';
                        content.appendChild(a);
                    });
                }

                show();
            }

            function fetch_(query) {
                if (query.length < 2) { hide(); return; }
                lastQuery = query;
                fetch('{{ route('search.suggestions') }}?q=' + encodeURIComponent(query))
                    .then(function (r) { return r.json(); })
                    .then(function (d) { if (lastQuery === query) render(d); })
                    .catch(hide);
            }

            input.addEventListener('input', function () {
                var val = this.value.trim();
                clearBtn.classList.toggle('hidden', val.length === 0);
                clearTimeout(timer);
                timer = setTimeout(function () { fetch_(val); }, 250);
            });

            clearBtn.addEventListener('click', function () {
                input.value = '';
                clearBtn.classList.add('hidden');
                hide();
                input.focus();
            });

            document.addEventListener('click', function (e) {
                if (!document.getElementById('search-container').contains(e.target)) hide();
            });

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') hide();
            });
        }());
    </script>

</x-layouts.app>
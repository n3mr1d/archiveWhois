<x-layouts.app title="Create Paste">
    <div class="w-full max-w-3xl mx-auto px-4 pb-16">

        {{-- Header --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ url('/') }}" class="text-gray-700 hover:text-red-600 transition-colors">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M19 12H5M12 5l-7 7 7 7" />
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-black text-white tracking-tight">Create Doxing</h1>
                </p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-950/20 border border-red-900/40 rounded-xl p-4">
                <p class="text-red-500 text-xs font-black uppercase tracking-widest mb-2">Validation Errors</p>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-red-400/80 text-xs">• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pastebin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- ===== TITLE & DESCRIPTION ===== --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase tracking-widest text-gray-400">
                        Title <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full bg-gray-950 border border-gray-800 focus:border-red-600/50 rounded-xl px-4 py-2.5 text-white text-sm placeholder-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600/5 transition-all"
                        placeholder="Paste title...">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase tracking-widest text-gray-400">
                        Description <span class="text-gray-700 text-[9px] lowercase italic font-bold">(optional)</span>
                    </label>
                    <input type="text" name="description" value="{{ old('description') }}"
                        class="w-full bg-gray-950 border border-gray-800 focus:border-red-600/50 rounded-xl px-4 py-2.5 text-white text-sm placeholder-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600/5 transition-all"
                        placeholder="Brief summary for search indexing...">
                    <p class="text-[9px] text-gray-700 font-bold uppercase tracking-widest">Indexed by search engine</p>
                </div>
            </div>

            {{-- ===== CONTENT ===== --}}
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label class="text-[11px] font-black uppercase tracking-widest text-gray-400">
                        Content <span class="text-red-600">*</span>
                        <span class="text-red-600/60 ml-2 normal-case text-[9px] font-bold">[Markdown Supported]</span>
                    </label>
                    <div class="flex gap-2 text-[9px] font-black text-gray-700 uppercase tracking-widest">
                        <span class="text-purple-700">Tor-Safe</span>
                        <span>·</span>
                        <span id="char-count">0</span> chars
                    </div>
                </div>
                <textarea name="content" required rows="12" id="content-area"
                    class="w-full bg-gray-950 border border-gray-800 focus:border-red-600/50 rounded-xl px-4 py-3 text-white text-sm leading-relaxed placeholder-gray-800 focus:outline-none focus:ring-2 focus:ring-red-600/5 transition-all font-mono resize-y"
                    placeholder="Enter your content here... (Markdown supported)">{{ old('content') }}</textarea>
            </div>

            {{-- ===== SETTINGS ROW ===== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">




                {{-- Password --}}

            </div>

            {{-- ===== TAGS ===== --}}
            <div class="space-y-1.5 grid grid-cols-2  gap-2">
                <div>
                    <label class="text-[11px] font-black uppercase tracking-widest text-gray-400">
                        Tags
                        <span class="text-red-600/60 ml-1 text-[9px] font-bold">[Max 4, comma separated]</span>
                        <span class="text-gray-700 text-[9px] lowercase italic font-bold ml-1">(optional)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <input type="text" name="categories" value="{{ old('categories') }}" id="tags-input"
                            class="w-full bg-gray-950 border border-gray-800 focus:border-red-600/50 rounded-xl pl-10 pr-4 py-2.5 text-white text-sm placeholder-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600/5 transition-all"
                            placeholder="e.g. hacker, identity, leak, osint">
                    </div>
                    <div id="tags-preview" class="flex flex-wrap gap-1.5 mt-1.5"></div>
                </div>
                <div>
                    <label class="text-[11px] font-black uppercase tracking-widest text-gray-400">Password <span
                            class="text-gray-700 text-[9px] lowercase italic font-bold">(optional)</span></label>
                    <input type="password" name="password"
                        class="w-full bg-gray-950 border border-gray-800 focus:border-red-600/50 rounded-xl px-4 py-2.5 text-white text-sm placeholder-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600/5 transition-all"
                        placeholder="Protect with password">
                </div>
            </div>

            {{-- ===== MEDIA ===== --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase tracking-widest text-gray-400">
                        Profile Photo <span
                            class="text-gray-700 text-[9px] lowercase italic font-bold">(optional)</span>
                    </label>
                    <div class="relative">
                        <input type="file" name="banner" accept="image/*" id="banner-input"
                            class="w-full bg-gray-950 border border-gray-800 hover:border-gray-700 rounded-xl px-4 py-2.5 text-gray-600 text-xs focus:outline-none cursor-pointer transition-all file:mr-3 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-[9px] file:font-black file:uppercase file:tracking-widest file:bg-red-950/40 file:text-red-600 hover:file:bg-red-950/60">
                    </div>
                    <div id="banner-preview" class="hidden mt-2">
                        <img id="banner-preview-img" src="" alt="Preview"
                            class="w-16 h-16 rounded-xl object-cover border border-gray-800">
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase tracking-widest text-gray-400">
                        Gallery
                        <span class="text-red-600/60 ml-1 text-[9px] font-bold">[Multiple]</span>
                        <span class="text-gray-700 text-[9px] lowercase italic font-bold ml-1">(optional)</span>
                    </label>
                    <input type="file" name="gallery[]" multiple accept="image/*"
                        class="w-full bg-gray-950 border border-gray-800 hover:border-gray-700 rounded-xl px-4 py-2.5 text-gray-600 text-xs focus:outline-none cursor-pointer transition-all file:mr-3 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-[9px] file:font-black file:uppercase file:tracking-widest  file:bg-red-950/40 file:text-red-600 hover:file:bg-gray-800">
                    <p class="text-[9px] text-gray-700 font-bold uppercase tracking-widest">Hold CTRL to select multiple
                    </p>
                </div>
            </div>

            {{-- ===== SUBMIT ===== --}}
            <div class="pt-4 border-t w-full border-gray-900 flex flex-col sm:flex-row items-center gap-3">
                <button type="submit" id="submit-btn"
                    class="w-full sm:w-auto px-10 py-3 bg-red-600/10 border border-red-900/60 hover:bg-red-600/20 hover:border-red-600/60 text-red-500 font-black uppercase tracking-widest rounded-xl text-xs transition-all active:scale-95 flex items-center justify-center gap-2">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Publish Now
                </button>


            </div>
        </form>
    </div>

    <script>
        // Character counter
        const contentArea = document.getElementById('content-area');
        const charCount = document.getElementById('char-count');
        function updateCount() {
            charCount.textContent = contentArea.value.length.toLocaleString();
        }
        contentArea.addEventListener('input', updateCount);
        updateCount();

        // Tags preview
        const tagsInput = document.getElementById('tags-input');
        const tagsPreview = document.getElementById('tags-preview');
        tagsInput.addEventListener('input', function () {
            const tags = this.value.split(',').map(t => t.trim()).filter(Boolean).slice(0, 4);
            tagsPreview.innerHTML = tags.map(t =>
                `<span class="px-2 py-0.5 bg-red-950/20 border border-red-900/20 text-red-700 text-[9px] font-bold uppercase tracking-wide rounded">${t}</span>`
            ).join('');
        });

        // Banner preview
        const bannerInput = document.getElementById('banner-input');
        const bannerPreview = document.getElementById('banner-preview');
        const bannerPreviewImg = document.getElementById('banner-preview-img');
        bannerInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    bannerPreviewImg.src = e.target.result;
                    bannerPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</x-layouts.app>

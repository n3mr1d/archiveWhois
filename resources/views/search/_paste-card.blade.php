{{-- Reusable Paste Card for Search Results --}}
@php
    $url = $paste->slug ? route('pastebin.show', $paste->slug->slug) : '#';
    $queryWords = $query ? array_filter(explode(' ', trim($query))) : [];

    function highlightText(string $text, array $words): string {
        if (empty($words)) return e($text);
        $escaped = e($text);
        foreach ($words as $word) {
            if (strlen($word) >= 2) {
                $escaped = preg_replace(
                    '/(' . preg_quote(e($word), '/') . ')/iu',
                    '<mark class="bg-red-600/20 text-red-400 rounded px-0.5 not-italic">$1</mark>',
                    $escaped
                );
            }
        }
        return $escaped;
    }
@endphp

<a href="{{ $url }}"
   class="group block bg-gray-950 border border-gray-900 hover:border-red-900/40 rounded-xl p-5 transition-all duration-200 hover:bg-gray-900/40 hover:-translate-y-0.5 hover:shadow-[0_4px_20px_rgba(220,38,38,0.05)]">

    {{-- URL Breadcrumb --}}
    <div class="flex items-center gap-2 mb-2">
        <div class="w-4 h-4 rounded bg-red-950/40 border border-red-900/30 flex items-center justify-center shrink-0">
            <svg class="w-2.5 h-2.5 text-red-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <span class="text-[10px] text-green-700/80 font-bold truncate">
            doxme.io/p/{{ $paste->slug?->slug ?? 'unknown' }}
        </span>
        @if($paste->visibility !== 'public')
        <span class="px-1.5 py-0.5 bg-yellow-950/30 border border-yellow-900/20 text-yellow-600 text-[8px] font-black uppercase rounded">
            {{ $paste->visibility }}
        </span>
        @endif
        @if($paste->isPasswordProtected())
        <span class="px-1.5 py-0.5 bg-blue-950/30 border border-blue-900/20 text-blue-600 text-[8px] font-black uppercase rounded">
            🔒 Protected
        </span>
        @endif
    </div>

    {{-- Title --}}
    <h3 class="text-base font-bold text-blue-400 group-hover:text-red-400 transition-colors leading-snug mb-1 line-clamp-1">
        {!! highlightText($paste->title, $queryWords) !!}
    </h3>

    {{-- Description --}}
    @if($paste->description)
    <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 mb-3">
        {!! highlightText($paste->description, $queryWords) !!}
    </p>
    @else
    <p class="text-gray-700 text-xs leading-relaxed line-clamp-2 mb-3 italic">
        {!! \Illuminate\Support\Str::limit(strip_tags($paste->content), 160) !!}
    </p>
    @endif

    {{-- Tags --}}
    @if($paste->categories->isNotEmpty())
    <div class="flex flex-wrap gap-1.5 mb-3">
        @foreach($paste->categories->take(5) as $cat)
        <span class="px-2 py-0.5 bg-red-950/20 border border-red-900/20 text-red-700/80 text-[9px] font-bold uppercase tracking-wide rounded">
            {{ $cat->name }}
        </span>
        @endforeach
    </div>
    @endif

    {{-- Meta footer --}}
    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[10px] text-gray-700 font-bold uppercase tracking-widest">
        {{-- Views --}}
        <span class="flex items-center gap-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
            </svg>
            {{ number_format($paste->views) }} views
        </span>

        {{-- Language --}}
        @if($paste->language)
        <span class="flex items-center gap-1">
            <svg class="w-3 h-3 text-purple-700/60" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="m16 18 6-6-6-6M8 6l-6 6 6 6"/>
            </svg>
            {{ $paste->language }}
        </span>
        @endif

        {{-- Date --}}
        <span class="flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            {{ $paste->created_at->format('M d, Y') }}
            <span class="text-gray-800">({{ $paste->created_at->diffForHumans() }})</span>
        </span>

        {{-- Author --}}
        <span class="flex items-center gap-1 ml-auto text-gray-800">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
            {{ $paste->author_name }}
        </span>
    </div>

</a>

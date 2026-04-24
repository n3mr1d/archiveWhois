<x-layouts.app>
    <x-slot:title>Trending Pastes — DoxMe</x-slot:title>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex items-center gap-3 mb-6">
            <h1 class="text-2xl font-bold text-white">🔥 Trending Pastes</h1>
            <span class="text-sm text-zinc-500">Most viewed this week</span>
        </div>
        <livewire:trending-pastes :limit="30" :days="7" />
    </div>
</x-layouts.app>

<x-layouts.app>
    <x-slot:title>Dashboard — DoxMe</x-slot:title>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="flex items-center gap-3 mb-6">
            <img src="{{ auth()->user()->avatar_url }}" alt="avatar" class="w-10 h-10 rounded-full object-cover">
            <div>
                <h1 class="text-xl font-bold text-white">
                    {{ auth()->user()->role_badge }} {{ auth()->user()->display_name }}
                </h1>
                <p class="text-xs text-zinc-500">{{ ucfirst(auth()->user()->role) }}</p>
            </div>
        </div>
        <livewire:dashboard />
    </div>
</x-layouts.app>

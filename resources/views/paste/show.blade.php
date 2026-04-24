<x-layouts.app>
    <x-slot:title>{{ $paste->title_display }} — DoxMe</x-slot:title>
    <x-slot:description>{{ Str::limit($paste->description ?? $paste->content, 155) }}</x-slot:description>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <livewire:paste-show :paste="$paste" />
    </div>
</x-layouts.app>

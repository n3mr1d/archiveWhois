<?php

use App\Models\Paste;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $tab = 'my-pastes'; // my-pastes | bookmarks
    public string $search = '';
    public string $visibility = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedVisibility(): void { $this->resetPage(); }
    public function setTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function deletePaste(int $id): void
    {
        $paste = Paste::findOrFail($id);
        if ($paste->user_id === auth()->id() || auth()->user()?->isAdmin()) {
            $paste->delete();
        }
    }

    public function toggleVisibility(int $id, string $visibility): void
    {
        $paste = Paste::findOrFail($id);
        if ($paste->user_id === auth()->id()) {
            $paste->update(['visibility' => $visibility]);
        }
    }

    public function render()
    {
        $user = auth()->user();

        $myPastes = Paste::where('user_id', $user->id)
            ->when($this->search, fn($q) => $q->where('title', 'LIKE', "%{$this->search}%"))
            ->when($this->visibility, fn($q) => $q->where('visibility', $this->visibility))
            ->latest()
            ->paginate(12);

        $bookmarks = $user->bookmarkedPastes()
            ->with('user')
            ->latest('bookmarks.created_at')
            ->paginate(12);

        return view('components.dashboard.dashboard', [
            'myPastes'  => $myPastes,
            'bookmarks' => $bookmarks,
            'stats'     => [
                'total_pastes'    => Paste::where('user_id', $user->id)->count(),
                'public_pastes'   => Paste::where('user_id', $user->id)->where('visibility', 'public')->count(),
                'total_views'     => Paste::where('user_id', $user->id)->sum('view_count'),
                'total_bookmarks' => $user->bookmarks()->count(),
            ],
        ]);
    }
};
<?php

use App\Models\Paste;
use App\Models\Bookmark;
use Livewire\Component;

new class extends Component
{
    public int $pasteId;
    public bool $bookmarked = false;

    public function mount(int $pasteId): void
    {
        $this->pasteId = $pasteId;
        $this->bookmarked = auth()->check()
            ? Bookmark::where('user_id', auth()->id())->where('paste_id', $pasteId)->exists()
            : false;
    }

    public function toggle(): void
    {
        if (!auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        $existing = Bookmark::where('user_id', auth()->id())
            ->where('paste_id', $this->pasteId)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->bookmarked = false;
        } else {
            Bookmark::create([
                'user_id'  => auth()->id(),
                'paste_id' => $this->pasteId,
            ]);
            $this->bookmarked = true;
        }
    }

    public function render()
    {
        return view('components.bookmark-toggle.bookmark-toggle');
    }
};
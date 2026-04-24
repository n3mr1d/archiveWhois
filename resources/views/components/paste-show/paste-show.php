<?php

use App\Models\Paste;
use App\Models\PasteComment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

new class extends Component
{
    public Paste $paste;
    public bool $passwordVerified = false;
    public string $enteredPassword = '';
    public bool $wrongPassword = false;

    public function mount(Paste $paste): void
    {
        $this->paste = $paste;

        // Auto-verify if no password
        if (!$paste->isPasswordProtected()) {
            $this->passwordVerified = true;
        }

        // Track view
        if ($this->passwordVerified) {
            $this->trackView();
        }
    }

    public function verifyPassword(): void
    {
        if (Hash::check($this->enteredPassword, $this->paste->password)) {
            $this->passwordVerified = true;
            $this->wrongPassword = false;
            $this->trackView();
        } else {
            $this->wrongPassword = true;
        }
    }

    private function trackView(): void
    {
        $this->paste->incrementView(
            auth()->user(),
            request()->ip()
        );
    }

    public function render()
    {
        return view('components.paste-show.paste-show');
    }
};
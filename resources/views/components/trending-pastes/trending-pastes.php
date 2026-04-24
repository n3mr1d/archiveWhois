<?php

use App\Models\Paste;
use Livewire\Component;

new class extends Component
{
    public int $days = 7;
    public int $limit = 10;

    public function render()
    {
        $trending = Paste::trending($this->days)
            ->with('user')
            ->limit($this->limit)
            ->get();

        return view('components.trending-pastes.trending-pastes', [
            'trending' => $trending,
        ]);
    }
};
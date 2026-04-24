<?php

use App\Models\Paste;
use Livewire\Component;
use Livewire\Attributes\Url;

new class extends Component
{
    #[Url(as: 'q')]
    public string $query = '';

    public string $sortBy = 'relevance';
    public string $language = '';

    public int $perPage = 15;
    public int $page = 1;

    public function updatedQuery(): void
    {
        $this->page = 1;
    }

    public function loadMore(): void
    {
        $this->page++;
    }

    public function getResultsProperty()
    {
        if (strlen($this->query) < 2) {
            return collect();
        }

        $q = Paste::search($this->query)
            ->where('visibility', 'public');

        if ($this->language) {
            $q->where('language', $this->language);
        }

        return $q->paginate($this->perPage * $this->page);
    }

    public function highlight(string $text, string $query): string
    {
        if (empty($query)) return e($text);
        $escaped = e($text);
        $escapedQuery = preg_quote(e($query), '/');
        return preg_replace(
            '/(' . $escapedQuery . ')/iu',
            '<mark class="search-highlight">$1</mark>',
            $escaped
        );
    }

    public function render()
    {
        return view('components.paste-search.paste-search', [
            'results' => $this->results,
        ]);
    }
};
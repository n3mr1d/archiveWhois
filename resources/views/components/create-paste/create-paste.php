<?php

use App\Models\Paste;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

new class extends Component
{
    public string $title = '';
    public string $description = '';
    public string $content = '';
    public string $language = 'plaintext';
    public string $visibility = 'public';
    public string $password = '';
    public string $guestName = '';

    public bool $showPassword = false;
    public bool $submitted = false;
    public ?string $pasteSlug = null;

    public array $languages = [
        'plaintext' => 'Plain Text',
        'php' => 'PHP',
        'javascript' => 'JavaScript',
        'typescript' => 'TypeScript',
        'python' => 'Python',
        'ruby' => 'Ruby',
        'go' => 'Go',
        'rust' => 'Rust',
        'java' => 'Java',
        'c' => 'C',
        'cpp' => 'C++',
        'csharp' => 'C#',
        'html' => 'HTML',
        'css' => 'CSS',
        'sql' => 'SQL',
        'bash' => 'Bash',
        'json' => 'JSON',
        'yaml' => 'YAML',
        'xml' => 'XML',
        'markdown' => 'Markdown',
    ];

    protected function rules(): array
    {
        return [
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'content'     => 'required|string|min:1|max:512000',
            'language'    => 'required|string',
            'visibility'  => 'required|in:public,private,unlisted',
            'password'    => 'nullable|string|max:255',
            'guestName'   => 'nullable|string|max:80',
        ];
    }

    public function submit()
    {
        $this->validate();

        $user = auth()->user();

        $paste = Paste::create([
            'user_id'    => $user?->id,
            'title'      => $this->title ?: null,
            'description' => $this->description ?: null,
            'content'    => $this->content,
            'language'   => $this->language,
            'visibility' => $this->visibility,
            'password'   => $this->password ? Hash::make($this->password) : null,
            'guest_name' => !$user ? ($this->guestName ?: 'Anonymous') : null,
            'guest_token' => !$user ? \Illuminate\Support\Str::random(32) : null,
        ]);

        $this->pasteSlug = $paste->slug;
        $this->submitted = true;

        session()->flash('paste_url', route('paste.show', $paste->slug));

        $this->redirect(route('paste.show', $paste->slug), navigate: true);
    }

    public function render()
    {
        return view('components.create-paste.create-paste');
    }
};
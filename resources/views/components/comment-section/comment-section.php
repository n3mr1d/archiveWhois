<?php

use App\Models\Paste;
use App\Models\PasteComment;
use Livewire\Component;

new class extends Component
{
    public Paste $paste;
    public string $comment = '';
    public ?int $replyTo = null;
    public string $replyToName = '';

    public function mount(Paste $paste): void
    {
        $this->paste = $paste;
    }

    protected function rules(): array
    {
        return [
            'comment' => 'required|string|min:2|max:2000',
        ];
    }

    public function postComment(): void
    {
        if (!auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        $this->validate();

        PasteComment::create([
            'paste_id'  => $this->paste->id,
            'user_id'   => auth()->id(),
            'content'   => $this->comment,
            'parent_id' => $this->replyTo,
        ]);

        $this->comment = '';
        $this->replyTo = null;
        $this->replyToName = '';

        $this->paste->refresh();
        $this->dispatch('comment-posted');
    }

    public function setReply(int $commentId, string $name): void
    {
        $this->replyTo = $commentId;
        $this->replyToName = $name;
    }

    public function cancelReply(): void
    {
        $this->replyTo = null;
        $this->replyToName = '';
    }

    public function deleteComment(int $commentId): void
    {
        $comment = PasteComment::findOrFail($commentId);
        $user = auth()->user();

        if (!$user) return;

        if ($comment->user_id === $user->id || $user->isAdmin()) {
            $comment->delete();
            $this->paste->refresh();
        }
    }

    public function render()
    {
        return view('components.comment-section.comment-section', [
            'comments' => $this->paste->comments()->get(),
        ]);
    }
};
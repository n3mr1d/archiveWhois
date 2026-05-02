<?php

namespace App\Models;

use App\Enum\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasteRevision extends Model
{
    protected $fillable = [
        'pastebin_id',
        'user_id',
        'version_number',
        'title',
        'description',
        'edit_summary',
        'is_minor',
        'content',
        'status',
        'reviewed_by',
    ];

    /**
     * The pastebin this revision belongs to.
     */
    public function pastebin(): BelongsTo
    {
        return $this->belongsTo(Pastebin::class);
    }

    /**
     * The user who made this edit.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The person who reviewed/approved this edit.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }

    /**
     * Wikipedia-style approval: Update the main paste and reward the author.
     */
    public function approve(User $reviewer): void
    {
        $this->update([
            'status' => Status::APPROVED,
            'reviewed_by' => $reviewer->id,
        ]);

        // When approved, this content becomes the "Current" version of the Pastebin
        $this->pastebin->update([
            'title' => $this->title ?? $this->pastebin->title,
            'description' => $this->description ?? $this->pastebin->description,
            'content' => $this->content,
        ]);

        // Reward the editor's reputation
        if ($this->author) {
            $points = $this->is_minor ? 1 : 5;
            $this->author->incrementReputation($points);
        }
    }

    /**
     * Reject the revision.
     */
    public function reject(User $reviewer): void
    {
        $this->update([
            'status' => Status::REJECTED,
            'reviewed_by' => $reviewer->id,
        ]);
    }

    /**
     * Rollback the parent pastebin to this specific version.
     */
    public function rollbackToThis(User $admin): void
    {
        $this->pastebin->update([
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
        ]);

        // Create a new revision marking the rollback
        $this->pastebin->revisions()->create([
            'user_id' => $admin->id,
            'version_number' => $this->pastebin->revisions()->max('version_number') + 1,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'edit_summary' => "Rollback to version #{$this->version_number}",
            'status' => Status::APPROVED,
            'reviewed_by' => $admin->id,
        ]);
    }
}

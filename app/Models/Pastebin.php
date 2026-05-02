<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Pastebin extends Model
{
    protected $fillable = [
        'title',
        'user_id',
        'author_name',
        'description',
        'content',
        'banner_path',
        'slug_id',
        'views',
        'language',
        'visibility',
        'expires_at',
        'password',
        'syntax_theme',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'views' => 'integer',
    ];

    /**
     * Relationship with the owner/author of the paste.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope: Only public pastes.
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('visibility', 'public');
    }

    /**
     * Scope: Not expired pastes.
     */
    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope: Search by title and description (full-text style).
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $words = array_filter(explode(' ', trim($term)));

        return $query->where(function ($q) use ($term, $words) {
            // Exact phrase match (higher relevance first via orderByRaw later)
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%")
              ->orWhere('content', 'LIKE', "%{$term}%");

            // Also match individual words
            foreach ($words as $word) {
                if (strlen($word) >= 2) {
                    $q->orWhere('title', 'LIKE', "%{$word}%")
                      ->orWhere('description', 'LIKE', "%{$word}%");
                }
            }

            // Category tag search
            $q->orWhereHas('categories', function ($cq) use ($term, $words) {
                $cq->where('name', 'LIKE', "%{$term}%");
                foreach ($words as $word) {
                    if (strlen($word) >= 2) {
                        $cq->orWhere('name', 'LIKE', "%{$word}%");
                    }
                }
            });
        });
    }

    /**
     * Scope: Trending pastes (most viewed recently).
     */
    public function scopeTrending(Builder $query): Builder
    {
        return $query->public()
                     ->notExpired()
                     ->orderBy('views', 'desc')
                     ->orderBy('created_at', 'desc');
    }

    /**
     * Scope: Recent pastes.
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->public()
                     ->notExpired()
                     ->orderBy('created_at', 'desc');
    }

    /**
     * Increment view count.
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Check if paste is password protected.
     */
    public function isPasswordProtected(): bool
    {
        return !empty($this->password);
    }

    /**
     * Check if paste has expired.
     */
    public function hasExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get word count of content.
     */
    public function getWordCountAttribute(): int
    {
        return str_word_count(strip_tags($this->content));
    }

    /**
     * Get reading time in minutes.
     */
    public function getReadingTimeAttribute(): int
    {
        return max(1, (int) ceil($this->word_count / 200));
    }

    /**
     * Check if a user can edit this pastebin directly.
     */
    public function canBeEditedDirectlyBy(?User $user): bool
    {
        if (!$user) return false;
        if ($user->isAdmin()) return true;
        if ($this->user_id && $this->user_id === $user->id) return true;

        return false;
    }

    /**
     * Check if a user needs approval to edit this pastebin.
     */
    public function requiresApprovalFor(User $user): bool
    {
        return !$this->canBeEditedDirectlyBy($user);
    }

    /**
     * Who can approve revisions for this paste?
     */
    public function canApproveRevisions(User $user): bool
    {
        if ($user->isAdmin()) return true;
        if ($this->user_id && $this->user_id === $user->id) return true;

        return false;
    }

    /**
     * Get the full version history of this paste.
     */
    public function history()
    {
        return $this->revisions()
            ->with('author')
            ->orderBy('version_number', 'desc')
            ->get();
    }

    /**
     * Get only approved revisions.
     */
    public function approvedHistory()
    {
        return $this->revisions()
            ->where('status', 'approved')
            ->orderBy('version_number', 'desc')
            ->get();
    }

    /**
     * Helper to create a new revision.
     */
    public function createRevision(array $data, User $user): PasteRevision
    {
        $nextVersion = ($this->revisions()->max('version_number') ?? 0) + 1;

        return $this->revisions()->create(array_merge($data, [
            'user_id' => $user->id,
            'version_number' => $nextVersion,
        ]));
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(PasteRevision::class);
    }

    /**
     * Get the galleries for the pastebin.
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    /**
     * Get the categories for the pastebin.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get the slug associated with the pastebin.
     */
    public function slug(): BelongsTo
    {
        return $this->belongsTo(Slug::class, 'slug_id');
    }
}

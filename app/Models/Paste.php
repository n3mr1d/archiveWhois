<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Paste extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'user_id',
        'title',
        'description',
        'content',
        'language',
        'visibility',
        'password',
        'is_encrypted',
        'view_count',
        'is_pinned',
        'guest_name',
        'guest_token',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
        'is_pinned' => 'boolean',
        'view_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($paste) {
            if (empty($paste->slug)) {
                $paste->slug = self::generateSlug();
            }
        });
    }

    public static function generateSlug(): string
    {
        do {
            $slug = Str::random(8);
        } while (static::where('slug', $slug)->exists());

        return $slug;
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(PasteComment::class)->whereNull('parent_id')->with('replies.user', 'user');
    }

    public function allComments()
    {
        return $this->hasMany(PasteComment::class);
    }

    public function views()
    {
        return $this->hasMany(PasteView::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function bookmarkedByUsers()
    {
        return $this->belongsToMany(User::class, 'bookmarks');
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopeVisible($query)
    {
        return $query->where('visibility', '!=', 'private');
    }

    public function scopeTrending($query, int $days = 7)
    {
        return $query->public()
            ->where('created_at', '>=', now()->subDays($days))
            ->orderByDesc('view_count');
    }

    // Helpers
    public function isPasswordProtected(): bool
    {
        return !empty($this->password);
    }

    public function isOwnedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->user_id === $user->id;
    }

    public function isAnonymous(): bool
    {
        return is_null($this->user_id);
    }

    public function getTitleDisplayAttribute(): string
    {
        return $this->title ?: 'Untitled Paste';
    }

    public function getUrlAttribute(): string
    {
        return route('paste.show', $this->slug);
    }

    public function getAuthorNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->display_name;
        }
        return $this->guest_name ?: 'Anonymous';
    }

    public function incrementView(?User $user = null, ?string $ip = null): void
    {
        // Debounce: one unique view per IP per paste per day
        $alreadyViewed = PasteView::where('paste_id', $this->id)
            ->where('ip_address', $ip)
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        if (!$alreadyViewed) {
            PasteView::create([
                'paste_id' => $this->id,
                'user_id' => $user?->id,
                'ip_address' => $ip,
            ]);
            $this->increment('view_count');
        }
    }

    // Search scope
    public function scopeSearch($query, string $term)
    {
        $escaped = addslashes($term);
        return $query->selectRaw("
            pastes.*,
            (
                CASE WHEN title LIKE ? THEN 10 ELSE 0 END +
                CASE WHEN description LIKE ? THEN 5 ELSE 0 END +
                CASE WHEN content LIKE ? THEN 1 ELSE 0 END
            ) as relevance_score
        ", ["%{$escaped}%", "%{$escaped}%", "%{$escaped}%"])
        ->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%")
              ->orWhere('content', 'LIKE', "%{$term}%");
        })
        ->orderByDesc('relevance_score');
    }
}

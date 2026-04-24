<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'bio',
        'avatar',
        'is_banned',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
        ];
    }

    // Relationships
    public function pastes()
    {
        return $this->hasMany(Paste::class);
    }

    public function comments()
    {
        return $this->hasMany(PasteComment::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function bookmarkedPastes()
    {
        return $this->belongsToMany(Paste::class, 'bookmarks');
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKing(): bool
    {
        return $this->role === 'king';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    public function getRoleBadgeAttribute(): string
    {
        return match($this->role) {
            'admin' => '🛡️',
            'king'  => '👑',
            default => '',
        };
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->username ?? $this->name;
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=80";
    }
}

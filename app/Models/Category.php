<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'type',
        'slug',
    ];

    /**
     * Get the pastebins associated with this category.
     */
    public function pastebins(): BelongsToMany
    {
        return $this->belongsToMany(Pastebin::class);
    }
}

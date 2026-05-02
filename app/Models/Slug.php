<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slug extends Model
{
    protected $fillable = [
        'slug',
        'view_count',
    ];

    protected $casts = [
        'view_count' => 'integer',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function pastebin()
    {
        return $this->hasOne(Pastebin::class);
    }
}

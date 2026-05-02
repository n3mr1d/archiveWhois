<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    protected $fillable = [
        'pastebin_id',
        'image_path',
        'caption',
        'order',
    ];

    /**
     * Get the pastebin that owns the gallery image.
     */
    public function pastebin(): BelongsTo
    {
        return $this->belongsTo(Pastebin::class);
    }
}

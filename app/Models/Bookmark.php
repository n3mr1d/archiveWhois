<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $fillable = [
        'user_id',
        'paste_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paste()
    {
        return $this->belongsTo(Paste::class);
    }
}

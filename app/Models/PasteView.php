<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasteView extends Model
{
    protected $fillable = [
        'paste_id',
        'user_id',
        'ip_address',
        'user_agent',
    ];

    public function paste()
    {
        return $this->belongsTo(Paste::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasteComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'paste_id',
        'user_id',
        'content',
        'parent_id',
    ];

    public function paste()
    {
        return $this->belongsTo(Paste::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(PasteComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(PasteComment::class, 'parent_id');
    }
}

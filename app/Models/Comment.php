<?php

namespace App\Models;

use App\Traits\Likable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory, Likable;
    protected $fillable = ['comment', 'comment_type', 'user_id', 'type'];

    public function commenter()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}

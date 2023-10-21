<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Commentable;
use App\Traits\Shareable;
use App\Traits\Viewable;
use App\Traits\Likable;

class Post extends Model
{
    use HasFactory, Likable, Commentable, Viewable, Shareable;

    protected $appends = ['image_path'];

    public function category() 
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function getImagePathAttribute()
    {
        return cdn('') . $this->image;
    }
}

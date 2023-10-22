<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Commentable;
use App\Traits\Shareable;
use App\Traits\Viewable;
use App\Traits\Likable;

class Reel extends Model
{
    use HasFactory, Likable, Commentable, Viewable, Shareable;

    protected $appends = ["type", 'image_path', 'user_has_liked'];

    public function getTypeAttribute()
    {
        return 'reel';
    }
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }
    public function getImagePathAttribute()
    {
        return cdn('') . $this->vdo_image;
    }
}

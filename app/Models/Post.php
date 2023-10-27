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

    protected $fillable = ['title', 'desc', 'category_id', 'status', 'meta_title', 'meta_keyword', 'meta_desc', 'image', 'slug'];

    protected $appends = ['image_path', "type", 'user_has_liked'];

    public function getTypeAttribute()
    {
        return 'post';
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function getImagePathAttribute()
    {
        return $this->image;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Reel;
use App\Models\Post;

class Category extends Model
{
    use HasFactory;

    public function reels() 
    {
        return $this->hasMany(Reel::class);
    }

    public function posts() 
    {
        return $this->hasMany(Post::class);
    }
}

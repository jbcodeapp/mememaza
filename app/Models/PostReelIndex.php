<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Post;
use App\Models\Reel;

class PostReelIndex extends Model
{
    use HasFactory;

    protected $table = "posts_reels_indices";

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function reel()
    {
        return $this->belongsTo(Reel::class, 'reel_id', 'id');
    }
}

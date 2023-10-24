<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostObserver
{
    public function created(Post $post)
    {
        DB::table('posts_reels_indices')->insert([
            'post_id' => $post->id,
            'created_at' => $post->created_at,
        ]);
    }
}

<?php

namespace App\Observers;

use App\Models\Reel;
use Illuminate\Support\Facades\DB;

class ReelObserver
{
    public function created(Reel $reel)
    {
        DB::table('posts_reels_indices')->insert([
            'reel_id' => $reel->id,
            'created_at' => $reel->created_at,
        ]);
    }
}

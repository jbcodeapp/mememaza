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

    public function category() 
    {
        return $this->belongsTo(App\Models\Category::class);
    }
}

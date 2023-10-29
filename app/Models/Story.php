<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Commentable;
use App\Traits\Shareable;
use App\Traits\Viewable;
use App\Traits\Likable;

class Story extends Model
{
    use HasFactory, Likable, Commentable, Viewable, Shareable;
}

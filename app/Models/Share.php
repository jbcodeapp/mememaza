<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    use HasFactory;
    
    public function sharer() {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}

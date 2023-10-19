<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;

    public function viewer() {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}

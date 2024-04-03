<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Like extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'type', 'type_id'];

    public function liker() {
        return $this->belongsTo(User::class, 'id');
    }
}

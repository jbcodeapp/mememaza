<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $appends = ['type'];

    public function getTypeAttribute()
    {
        switch ($this->position) {
            case 1:
                return "header";
            case 2:
                return "left";
            case 3:
                return "right";
            case 4:
                return "bottom";
        }
    }
}

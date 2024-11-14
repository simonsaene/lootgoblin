<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;
    protected $fillable = [
        'character_id', 
        'grind_spot_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grindSpot()
    {
        return $this->belongsTo(GrindSpot::class);
    }

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'level',
        'name',
        'class',
        'profile_image',
        'gear_image',
    ];

    public function grindSessions()
    {
        return $this->hasMany(GrindSession::class);
    }
}

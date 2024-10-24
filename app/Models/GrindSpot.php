<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrindSpot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'location', 
        'suggested_level', 
        'suggested_gearscore', 
        'difficulty', 
        'mechanics'
    ];

    public function grindSessions()
    {
        return $this->hasMany(GrindSession::class);
    }

    public function grindSpotItems()
    {
        return $this->hasMany(GrindSpotItem::class);
    }
}

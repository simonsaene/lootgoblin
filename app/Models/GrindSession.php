<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrindSession extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'user_id', 
        'grindspot_id', 
        'loot_image', 
        'video_link', 
        'notes', 
        'is_video_verified', 
        'is_image_verified', 
        'date_created', 
        'date_modified'
    ];

    public function grindSpot()
    {
        return $this->belongsTo(GrindSpot::class);
    }

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}

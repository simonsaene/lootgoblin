<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'grind_spot_id', 
        'user_id',
        'poster_id', 
        'comment'
    ];
    
    public function grindSpot()
    {
        return $this->belongsTo(GrindSpot::class);
    }

    public function poster()
    {
        return $this->belongsTo(User::class, 'poster_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

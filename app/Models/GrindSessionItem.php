<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrindSessionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'grind_session_id', 
        'grind_spot_item_id', 
        'quantity'
    ];

    public function grindSession()
    {
        return $this->belongsTo(GrindSession::class);
    }

    public function grindSpotItem()
    {
        return $this->belongsTo(GrindSpotItem::class);
    }
}

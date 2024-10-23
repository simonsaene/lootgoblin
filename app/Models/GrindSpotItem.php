<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrindSpotItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id', 
        'grindspot_id'
    ];

    public function grindSpot()
    {
        return $this->belongsTo(GrindSpot::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'market_value', 
        'vendor_value',
        'image',
        'is_trash'
    ];

    public function grindSpotItems()
    {
        return $this->hasMany(GrindSpotItem::class);
    }
}

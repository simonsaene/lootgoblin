<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $table = 'status';
    protected $fillable = [
        'user_id',
        'session_id',
        'post_id',
        'status_type',
        'status_start_reason',
        'status_end_reason',
        'date_end'
    ];
    public function grindSession()
    {
        return $this->belongsTo(GrindSession::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

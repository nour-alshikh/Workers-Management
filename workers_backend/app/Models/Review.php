<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id', 'review', 'rate', 'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id', 'client_id'
    ];

    protected $guarded = ['status'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ['content', 'user-id'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

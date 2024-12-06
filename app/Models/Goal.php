<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends Model
{
    //
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    public function match()
    {
        return $this->belongsTo(Matches::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}

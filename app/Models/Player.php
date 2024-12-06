<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    //
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'height',
        'weight',
        'position',
        'jersey_number',
        'team_id',
    ];
    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}


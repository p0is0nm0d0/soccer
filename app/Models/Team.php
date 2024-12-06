<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        "name",
        "founded_year",
        "address",
        "city"
    ];

    
    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function homeMatches()
    {
        return $this->hasMany(Matches::class, 'home_team_id');
    }

    public function awayMatches()
    {
        return $this->hasMany(Matches::class, 'away_team_id');
    }
}

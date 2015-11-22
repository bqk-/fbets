<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class Team extends Model {

    protected $table = 'teams';

    public function gamesHome()
    {
        return $this->belongsToMany('App\Models\Data\Game', 'games', 'team1');
    }

    public function gamesOutside()
    {
        return $this->belongsToMany('App\Models\Data\Game', 'games', 'team2');
    }

    public function sport()
    {
        return $this->hasOne('App\Models\Data\Sport', 'id', 'id_sport');
    }

    public function relations()
    {
        return $this->hasMany('App\Models\Data\TeamRelation', 'local_id', 'id');
    }
}
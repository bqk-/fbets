<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class TeamRelation extends Model {

    protected $table = 'teams_relations';

    public function team()
    {
        return $this->hasOne('App\Models\Data\Team', 'id', 'local_id');
    }

    public function championship()
    {
        return $this->hasOne('App\Models\Data\Championship', 'id', 'championship_id');
    }
}
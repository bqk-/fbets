<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class GameRelation extends Model {

    protected $table = 'games_relations';

    public function game()
    {
        return $this->hasOne('App\Models\Data\Game', 'id', 'local_id');
    }
}
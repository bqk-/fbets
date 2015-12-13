<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class Score extends Model {

    protected $table = 'results';

    public function game(){
        return $this->belongsTo('App\Models\Data\Game', 'id_game');
    }

}
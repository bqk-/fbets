<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class Bet extends Model {

    protected $table = 'bets';

    public function game(){
        return $this->belongsTo('App\Models\Data\Game', 'id_game');
    }

    public function user(){
        return $this->belongsTo('App\Models\Data\User', 'id_user');
    }

}
<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class Game extends Model {

    protected $table = 'games';
    protected $bet = false;
    public $score1 = null;
    public $score2 = null;
    public $score = false;

    public function championship()
    {
        return $this->belongsTo('App\Models\Data\Championship', 'id_championship');
    }

    public function score()
    {
        return $this->hasOne('App\Models\Data\Score', 'id_game', 'id');
    }

    public function bets()
    {
        return $this->hasMany('App\Models\Data\Bet', 'id_game', 'id');
    }

    public function team1()
    {
        return $this->hasOne('App\Models\Data\Team', 'id', 'team1');
    }

    public function team2()
    {
        return $this->hasOne('App\Models\Data\Team', 'id', 'team2');
    }
}
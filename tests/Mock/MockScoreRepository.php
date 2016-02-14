<?php namespace Mock;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MockScoreRepository implements \App\Repositories\Contracts\IScoreRepository
{
    private $scores = array();
    private $id = 1;
    
    public function AddScore($idGame, $scoreTeam1, $scoreTeam2, $state) 
    {
        $s = new \App\Models\Data\Score();
        $s->team1 = $scoreTeam1;
        $s->team2 = $scoreTeam2;
        $s->state = \App\Models\Types\GameStates::NONE;
        $s->id_game = $idGame;
        $s->id = ++$this->id;
        $this->scores[$s->id] = $s;
        
        return $this->id;
    }

    public function GetForGame($id) {
        $collec = new \Illuminate\Database\Eloquent\Collection($this->scores);
        return $collec->first(function ($key, $value) use($id) {
                return $value->id_game == $id;
            });
    }

    public function UpdateBetsForGame($game, $score) {
        
    }

}
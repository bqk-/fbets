<?php namespace Mock;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MockGameRepository implements \App\Repositories\Contracts\IGameRepository
{
    private $games = array();
    private $id = 1;
    
    public function DropGamesForChampionship($id) {
        
    }

    public function Get($id) {
        if(key_exists($id, $this->games)){
            return $this->games[$id];
        }
    }

    public function GetGamesWithNoScore($championship = null) {
        
    }

    public function GetNext7DaysGameAllSport() {
        
    }

    public function GetUserSuggestions($id) {
        
    }

    public function Suggest($sport, $team1, $team2, $event, $date, $userId) {
        
    }

    public function UpdateGameTime($idGame, $getGameTime) {
        
    }

    public function Create($teamh, $teamv, $champId, $date)
    {
        $s = new \App\Models\Data\Game();
        $s->team1 = $teamh;
        $s->team2 = $teamv;
        $s->id_champ = $champId;
        $s->date = $date;    
        $s->id = ++$this->id;
        $this->games[$s->id] = $s;
        
        return $this->id;
    }

    public function CreateRelation($outId, $localId)
    {
        
    }

}
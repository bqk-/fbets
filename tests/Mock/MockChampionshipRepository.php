<?php namespace Mock;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MockChampionshipRepository implements \App\Repositories\Contracts\IChampionshipRepository
{
    private $championships = array();
    private $id = 1;
    
    public function ActivateChampionship($id) {
        
    }

    public function Create($name, $class, $sport) 
    {
        $championship = new \App\Models\Data\Championship();
        $championship->name = $name;
        $championship->type = $class;
        $championship->id_sport = $sport;
        $championship->active = 1;
        $championship->id = ++$this->id;
        $championship->params = array();
        $this->championships[$championship->id] = $championship;

        return $this->id;
    }

    public function Get($id) {
        if(key_exists($id, $this->championships)){
            return $this->championships[$id];
        }
    }

    public function GetAll() {
        
    }

    public function GetAllActive() {
        
    }

    public function GetAllWithGames() {
        
    }

    public function GetWithGamesAndScores($id) {
        
    }

    public function IsActive($id) {
        
    }

    public function UnActivateChampionship($id) {
        
    }

    public function UpdateChampionshipParams($id, $arrayParams) {
        if(key_exists($id, $this->championships)){
            $this->championships[$id]->params = $arrayParams;
        }
    }

    public function HasGames($champId)
    {
        
    }

}


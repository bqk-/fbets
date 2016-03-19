<?php namespace App\Models\Admin\Tournament;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ExternalChampionship implements ITournament
{
    private $ExternalRepository;
    
    public function _construct()
    {
        $this->ExternalRepository = new ExternalRepository();
    }
    
    public function getGameStateFromScore($scoreH, $scoreV)
    {
        return;
    }

    public function getGameTime($extIdGame)
    {
        
    }

    public function getGames()
    {
        
    }

    public function getScore($extIdGame)
    {
        
    }

    public function getTeams()
    {
        
    }

    public function getType()
    {
        
    }

}
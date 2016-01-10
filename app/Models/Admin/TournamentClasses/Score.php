<?php namespace App\Models\Admin\TournamentClasses;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Score
{
    public $TeamHome,
            $TeamVisit,
            $State;
    
    public function __construct($teamh, $teamv, $state)
    {
        $this->TeamHome = $teamh;
        $this->TeamVisit = $teamv;
        $this->State = $state;
    }
}
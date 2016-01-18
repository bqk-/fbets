<?php namespace App\Models\Admin\TournamentClasses;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Game
{
    /**
     * Contains the result of the FileQuery
     * @var int
     */
    public $Id;
            
    /**
     * Contains the result of the FileQuery
     * @var App\Models\Admin\TournamentClasses\Team;
     */
    public $TeamHome;
    
    /**
     * Contains the result of the FileQuery
     * @var App\Models\Admin\TournamentClasses\Team;
     */
    public $TeamVisit;
    
    /**
     * Contains the result of the FileQuery
     * @var string;
     */
    public $Date;
    
    /**
     * Contains the result of the FileQuery
     * @var App\Models\Admin\TournamentClasses\Score;
     */
    public $Score;
    
    public function __construct($id, Team $teamh, Team $teamv, $date, Score $score = null)
    {
        $this->Id = $id;
        $this->TeamHome = $teamh;
        $this->TeamVisit = $teamv;
        $this->Date = $date;
        $this->Score = $score;
    }
    
    public function Score()
    {
        if($this->Score == null)
        {
            return "";
        }
        else
        {
            return $this->Score->TeamHome . " - " . $this->Score->TeamVisit;
        }
    }
}
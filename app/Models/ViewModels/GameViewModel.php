<?php namespace App\Models\ViewModels;

use App\Models\Services\BetRates;
use App\Models\Types\GameStates;
use App\Models\Types\GroupGameStates;
use App\Models\ViewModels\SportViewModel;
use App\Models\ViewModels\TeamViewModel;

class GameViewModel
{
    public $Id;
    public $Team1;
    public $Team2;
    public $Date;
    public $Sport;
    public $UserStatus;
    public $GroupGameStatus;
    public $Rates;
    
    public function __construct($id, 
            TeamViewModel $team1, 
            TeamViewModel $team2, 
            $date, 
            SportViewModel $sport,
            BetRates $rates,
            GameStates $userStatus,
            GroupGameStates ...$groupGameStatus)
    {
        $this->Id = $id;
        $this->Date = $date;
        $this->Team1 = $team1;
        $this->Team2 = $team2;
        $this->Rates = $rates;
        $this->UserStatus = $userStatus;
        $this->GroupGameStatus = $groupGameStatus;
        $this->Sport = $sport;
    }
}
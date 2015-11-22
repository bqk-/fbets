<?php namespace App\Services;

use App\Repositories\Contracts\IBetRepository;
use \Auth;

class BetService
{
    private $_betRepository;

    public function __construct(IBetRepository $betRepository)
    {
        $this->_betRepository = $betRepository;
    }

    public function GetCurrentUserBetsForNext7Days()
    {
        $bets = $this->_betRepository->GetUserIncomingBets(Auth::user()->id, 7);

        $return = array();
        foreach ($bets as $bet)
        {
            $return[$bet->id_game] = $bet;
        }

        return $return;
    }

    public function GetUserPendingBets()
    {
        $bets = $this->_betRepository->GetUserIncomingBets(Auth::user()->id, 0);

        $return = array();
        foreach ($bets as $bet)
        {
            $return[$bet->id_game] = $bet;
        }

        return $return;
    }

    public function Create($idGame, $score1, $score2)
    {
        return $this->_betRepository->Create($score1, $score2, $idGame, Auth::user()->id);
    }

    public function GetUserBetsForChampionship($id)
    {
        $bets = $this->_betRepository->GetAllForUser(Auth::user()->id);

        $return = array();
        foreach ($bets as $bet)
        {
            $return[$bet->id_game] = $bet;
        }

        return $return;
    }

    public function GetTopBettorBetween($min, $max)
    {
        $this->_betRepository->GetTopBettors($min, $max);
    }

    public function GetTopBettorSuperior($min)
    {
        $this->_betRepository->GetTopBettors($min, 0);
    }
}
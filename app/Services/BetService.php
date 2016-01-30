<?php namespace App\Services;

use App\Repositories\Contracts\IBetRepository;
use App\Services\Contracts\ICurrentUser;

class BetService
{
    private $_betRepository;
    private $_currentUser;

    public function __construct(IBetRepository $betRepository, ICurrentUser $currentUser)
    {
        $this->_betRepository = $betRepository;
        $this->_currentUser = $currentUser;
    }

    public function GetCurrentUserBetsForNext7Days()
    {
        $bets = $this->_betRepository->GetUserIncomingBets($this->_currentUser->GetId(), 7);

        $return = array();
        foreach ($bets as $bet)
        {
            $return[$bet->id_game] = $bet;
        }

        return $return;
    }

    public function GetUserPendingBets()
    {
        $bets = $this->_betRepository->GetUserIncomingBets($this->_currentUser->GetId(), 0);

        $return = array();
        foreach ($bets as $bet)
        {
            $return[$bet->id_game] = $bet;
        }

        return $return;
    }

    public function Create($idGame, $score1, $score2)
    {
        return $this->_betRepository->Create($score1, $score2, $idGame, $this->_currentUser->GetId());
    }

    public function GetUserBetsForChampionship()
    {
        $bets = $this->_betRepository->GetAllForUser($this->_currentUser->GetId());

        $return = array();
        foreach ($bets as $bet)
        {
            $return[$bet->id_game] = $bet;
        }

        return $return;
    }

    public function GetTopBettorBetween($min, $max)
    {
        return $this->_betRepository->GetTopBettors($min, $max);
    }

    public function GetTopBettorSuperior($min)
    {
        return $this->_betRepository->GetTopBettors($min, 0);
    }

    public function MarkAsDone($betId)
    {
        $this->_betRepository->MarkAsDone($betId);
    }

}
<?php namespace App\Services;

use App\Repositories\Contracts\IBetRepository;
use App\Services\Contracts\ICurrentUser;
use App\Repositories\Contracts\IGameRepository;
use App\Helpers\DateHelper;

class BetService
{
    private $_betRepository;
    private $_currentUser;
    private $_gameRepository;
    
    public function __construct(IBetRepository $betRepository, 
            ICurrentUser $currentUser, 
            IGameRepository $gameRepository)
    {
        $this->_betRepository = $betRepository;
        $this->_currentUser = $currentUser;
        $this->_gameRepository = $gameRepository;
    }
    
    public function Get($id)
    {
        $b = $this->_betRepository->Get($id);
        if($b == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Cannot get bet: ' . $id);
        }
        
        return $b;
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

    public function Create($idGame, $bet)
    {
        $game = $this->_gameRepository->Get($idGame);
        if($game == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Cannot get game: ' . $idGame);
        }
        
        if(DateHelper::getTimestampFromSqlDate($game->date) <= time())
        {
            throw new \App\Exceptions\InvalidOperationException('Game time passed.');
        }
        
        if($this->GetUserBetForGame($idGame)->count() > 0)
        {
            throw new \App\Exceptions\InvalidOperationException('Already betted on that.');
        }
        
        return $this->_betRepository->Create($bet, $idGame, $this->_currentUser->GetId());
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

    public function MarkAsDone($betId, $status)
    {
        $this->_betRepository->MarkAsDone($betId, $status);
    }
    
    /**
    * @return \App\Models\Services\BetRates
    */
    public function GetRates($gameId)
    {
        $percent = $this->GetPercent($gameId);
        
        $rates = new \App\Models\Services\BetRates($gameId, 0, 0, 0);
        $rates->HomeRate = ($percent->DrawRate + $percent->VisitRate) / 2;
        $rates->DrawRate = ($percent->HomeRate + $percent->VisitRate) / 2;
        $rates->VisitRate = ($percent->DrawRate + $percent->HomeRate) / 2;

        $ratesCorrected = new \App\Models\Services\BetRates($gameId, 
                $rates->HomeRate, 
                $rates->VisitRate, 
                $rates->DrawRate);
        
        if($rates->HomeRate < 0.1)
        {
            $ratesCorrected->VisitRate -= ($rates->VisitRate * (0.1 - $rates->HomeRate)) / 
                    ($rates->VisitRate + $rates->DrawRate);
            $ratesCorrected->DrawRate -= ($rates->DrawRate * (0.1 - $rates->HomeRate)) / 
                    ($rates->VisitRate + $rates->DrawRate);
            $ratesCorrected->HomeRate = 0.1;
        }
        
        if($rates->VisitRate < 0.1)
        {
            $ratesCorrected->HomeRate -= ($rates->HomeRate * (0.1 - $rates->VisitRate)) / 
                    ($rates->HomeRate + $rates->DrawRate);
            $ratesCorrected->DrawRate -= ($rates->DrawRate * (0.1 - $rates->VisitRate)) / 
                    ($rates->HomeRate + $rates->DrawRate);
            $ratesCorrected->VisitRate = 0.1;
        }
        
        if($rates->DrawRate < 0.1)
        {
            $ratesCorrected->VisitRate -= ($rates->VisitRate * (0.1 - $rates->DrawRate)) / 
                    ($rates->HomeRate + $rates->VisitRate);
            $ratesCorrected->HomeRate -= ($rates->HomeRate * (0.1 - $rates->DrawRate)) / 
                    ($rates->HomeRate + $rates->VisitRate);
            $ratesCorrected->DrawRate = 0.1;
        }

        return new \App\Models\Services\BetRates($gameId, 
                round($ratesCorrected->HomeRate, 3),
                round($ratesCorrected->VisitRate, 3),
                round($ratesCorrected->DrawRate, 3));
    }
    
    /**
    * @return \App\Models\Services\BetRates
    */
    public function GetPercent($gameId)
    {
        $bets = $this->_betRepository->GetBetsToProcessOnGame($gameId);
        $total = 0;
        $home = 0;
        $visit = 0;
        $draw = 0;
        
        if($bets->count() == 0)
        {
            return new \App\Models\Services\BetRates($gameId, 0.33, 0.33, 0.33);
        }
        
        foreach ($bets as $bet)
        {
            if($bet->bet == \App\Models\Types\GameStates::HOME)
            {
                $home++;
            }
            else if($bet->bet == \App\Models\Types\GameStates::VISITOR)
            {
                $visit++;
            }
            else if($bet->bet == \App\Models\Types\GameStates::DRAW)
            {
                $draw++;
            }
            else
            {
                throw new \App\Exceptions\InvalidOperationException('What the fuck is this bet (' . $bet->bet . ')');
            }
            
            $total++;
        }
        
        return new \App\Models\Services\BetRates($gameId, $home/$total, $visit/$total, $draw/$total);
    }

    public function GetBetsToProcessOnGame($gameId)
    {
        return $this->_betRepository->GetBetsToProcessOnGame($gameId);
    }

    public function GetUserBetForGame($idGame)
    {
        return $this->_betRepository->GetUserBetForGame($idGame, $this->_currentUser->GetId());
    }

}
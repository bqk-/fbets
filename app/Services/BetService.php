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
        if($percent->HomeRate > 0.8)
        {
            $percent->VisitRate += ($percent->HomeRate - 0.8) / 2;
            $percent->DrawRate += ($percent->HomeRate - 0.8) / 2;
            $percent->HomeRate -= $percent->HomeRate - 0.8;
        }
        
        if($percent->VisitRate > 0.8)
        {
            $percent->HomeRate += ($percent->VisitRate - 0.8) / 2;
            $percent->DrawRate += ($percent->VisitRate - 0.8) / 2;
            $percent->VisitRate -= $percent->VisitRate - 0.8;
        }
        
        if($percent->DrawRate > 0.8)
        {
            $percent->VisitRate += ($percent->DrawRate - 0.8) / 2;
            $percent->HomeRate += ($percent->DrawRate - 0.8) / 2;
            $percent->DrawRate -= $percent->DrawRate - 0.8;
        }

        if($percent->HomeRate < 0.1)
        {
            $percent->VisitRate -= (0.1 - $percent->HomeRate) / 2;
            $percent->DrawRate -= (0.1 - $percent->HomeRate) / 2;
            $percent->HomeRate += 0.1 - $percent->HomeRate;
        }
        
        if($percent->VisitRate < 0.1)
        {
            $percent->HomeRate -= (0.1 - $percent->VisitRate) / 2;
            $percent->DrawRate -= (0.1 - $percent->VisitRate) / 2;
            $percent->VisitRate += 0.1 - $percent->VisitRate;
        }
        
        if($percent->DrawRate < 0.1)
        {
            $percent->VisitRate -= (0.1 - $percent->DrawRate) / 2;
            $percent->HomeRate -= (0.1 - $percent->DrawRate) / 2;
            $percent->DrawRate += 0.1 - $percent->DrawRate;
        }
        
        return $percent;
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
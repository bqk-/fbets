<?php namespace Mock;

use App\Models\Data\Bet;

class MockBetRepository implements \App\Repositories\Contracts\IBetRepository
{
    private $bets = array();
    private $id = 1;
    public function Create($state, $idGame, $userId) 
    {
        $bet = new Bet;
        $bet->id_game = $idGame;
        $bet->id_user = $userId;
        $bet->bet = $state;
        $bet->state = \App\Models\Types\BetStates::WAITING;
        $bet->id = ++$this->id;
        $this->bets[$bet->id] = $bet;
        return $this->id;
    }

    public function Get($id) {
         if(key_exists($id, $this->bets)){
            return $this->bets[$id];
        }
    }

    public function GetAllForUser($id) {
        
    }

    public function GetTopBettors($min, $max) {
        
    }

    public function GetUserIncomingBets($id, $days = 0) {
        
    }

    public function GetBetsToProcessOnGame($gameId)
    {
        $ret = array();
        foreach ($this->bets as $b)
        {
            if($b->state == \App\Models\Types\BetStates::WAITING)
            {
                $ret[] = $b;
            }
        }
        
        return new \Illuminate\Database\Eloquent\Collection($ret);
    }

    public function MarkAsDone($betId, $state)
    {
         if(key_exists($betId, $this->bets)){
            $this->bets[$betId]->state = $state;
        }
    }

    public function GetUserBetForGame($idGame, $userId)
    {
        return new \Illuminate\Database\Eloquent\Collection(array());
    }

}
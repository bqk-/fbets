<?php namespace Mock;

use App\Models\Data\Bet;

class MockBetRepository implements \App\Repositories\Contracts\IBetRepository
{
    private $bets = array();
    
    public function Create($state, $idGame, $userId) {
        $bet = new Bet;
        $bet->id_game = $idGame;
        $bet->id_user = $userId;
        $bet->bet = $state;
        $bet->state = \App\Models\Types\BetStates::WAITING;

        $this->bets[] = $bet;
    }

    public function Get($id) {
        
    }

    public function GetAllForUser($id) {
        
    }

    public function GetTopBettors($min, $max) {
        
    }

    public function GetUserIncomingBets($id, $days = 0) {
        
    }

    public function GetBetsToProcessOnGame($gameId)
    {
        return new \Illuminate\Database\Eloquent\Collection($this->bets);
    }

    public function MarkAsDone($betId, $state)
    {
        
    }

}
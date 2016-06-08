<?php namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Models\Data\Bet;
use \DB;
use App\Repositories\Contracts\IBetRepository;

class BetRepository implements IBetRepository
{
    public function Get($id)
    {
        $bet = Bet::find($id);

        if($bet == null)
        {
            throw new NotFoundException('Bet', 'id', $id);
        }

        return $bet;
    }

    public function GetUserIncomingBets($id, $days = 0)
    {
        $baseQ = Bet::join('games', 'games.id', '=', 'bets.id_game')
            ->where('id_user', '=', $id)
            ->where('games.date', '>', DB::raw('CURDATE()'));

        if($days > 0)
        {
            $baseQ = $baseQ->where('games.date', '<', DB::raw('CURDATE() + INTERVAL ' . $days . ' DAY'));
        }

        return $g = $baseQ->get();
    }

    public function Create($ubet, $idGame, $userId)
    {
        $bet = new Bet;
        $bet->id_game = $idGame;
        $bet->id_user = $userId;
        $bet->bet = $ubet;
        $bet->state = \App\Models\Types\BetStates::WAITING;
        $bet->save();

        return $bet->id;
    }

    public function GetAllForUser($id)
    {
        $bets = Bet::with('game')
            ->where('id_user', '=', $id)
            ->get();

        return $bets;
    }

    public function GetBetsToProcessOnGame($gameId)
    {
        return Bet::where('id_game', '=', $gameId)
                ->where('state', '=', \App\Models\Types\BetStates::WAITING)
                ->get();
    }

    public function MarkAsDone($betId, $state)
    {
        $bet = $this->Get($betId);
        $bet->state = $state;
        $bet->save();
    }

    public function GetUserBetForGame($idGame, $userId)
    {
        return Bet::where('id_game', '=', $idGame)->where('id_user', '=', $userId)->first();
    }

}

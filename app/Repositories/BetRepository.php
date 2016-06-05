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

    public function GetTopBettors($min, $max)
    {
        if($max > 0)
        {
            $users = DB::select(DB::raw('SELECT (SELECT COUNT(id) FROM bets WHERE id_user=b.id_user AND state > 0) as nb, COUNT(b.id)/(SELECT COUNT(id) FROM bets WHERE id_user=b.id_user AND state > 0) as percent,u.* FROM bets as b
                                              LEFT JOIN users as u
                                              ON u.id=b.id_user
                                              WHERE b.state > 0
                                              AND (SELECT COUNT(id) FROM bets WHERE id_user=b.id_user AND
                                              state > 0) >= ' . $min . '
                                              AND (SELECT COUNT(id) FROM bets WHERE id_user=b.id_user AND
                                              state > 0) < ' . $max . '
                                              AND b.state = 1 GROUP BY b.id_user ORDER BY percent DESC'));
        }
        else
        {
            $users = DB::select(DB::raw('SELECT (SELECT COUNT(id) FROM bets WHERE id_user=b.id_user AND state > 0) as nb, COUNT(b.id)/(SELECT COUNT(id) FROM bets WHERE id_user=b.id_user AND state > 0) as percent,u.* FROM bets as b
                                              LEFT JOIN users as u
                                              ON u.id=b.id_user
                                              WHERE b.state > 0
                                              AND (SELECT COUNT(id) FROM bets WHERE id_user=b.id_user AND
                                              state>0) >= ' . $min . '
                                              AND b.state = 1 GROUP BY b.id_user ORDER BY percent DESC'));
        }

        return $users;
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
        return Bet::where('id_game', '=', $idGame)->where('id_user', '=', $userId)->get();
    }

}

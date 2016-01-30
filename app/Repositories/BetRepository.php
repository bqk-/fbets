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
        $baseQ = Bet::join('games', 'bets.id_game', '=', 'games.id')
            ->where('id_user', '=', $id)
            ->where('games.date', '>', DB::raw('CURDATE()'));

        if($days > 0)
        {
            $baseQ = $baseQ->where('games.date', '<', DB::raw('CURDATE() + INTERVAL ' . $days . ' DAY'));
        }

        return $baseQ->get();
    }

    public function Create($score1, $score2, $idGame, $userId)
    {
        $bet = new Bet;
        $bet->id_game = $idGame;
        $bet->id_user = $userId;
        $bet->score1 = $score1;
        $bet->score2 = $score2;
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
            //TODO: rewrite this with Bet model - have fun
            $users = DB::select(DB::raw('SELECT (SELECT COUNT(id) FROM bets WHERE id_user=b.id_user AND processed=1) as nb, COUNT(b.id)/(SELECT COUNT(id) FROM bets WHERE id_user=b.id_user AND processed=1) as percent,u.* FROM bets as b
                                              LEFT JOIN users as u
                                              ON u.id=b.id_user
                                              WHERE b.processed = 1
                                              AND (SELECT COUNT(id) FROM bets WHERE id_user=b.id_user AND
                                              processed=1) >= ' . $min . '
                                              AND (SELECT COUNT(id) FROM bets WHERE id_user=b.id_user AND
                                              processed=1) < ' . $max . '
                                              AND b.outcome = 1 GROUP BY b.id_user ORDER BY percent DESC'));
        }
        else
        {
            $users = DB::select(DB::raw('SELECT (SELECT COUNT(id) FROM bets WHERE id_user=b.id_user AND processed=1) as nb, COUNT(b.id)/(SELECT COUNT(id) FROM bets WHERE id_user=b.id_user AND processed=1) as percent,u.* FROM bets as b
                                              LEFT JOIN users as u
                                              ON u.id=b.id_user
                                              WHERE b.processed = 1
                                              AND (SELECT COUNT(id) FROM bets WHERE id_user=b.id_user AND
                                              processed=1) >= ' . $min . '
                                              AND b.outcome = 1 GROUP BY b.id_user ORDER BY percent DESC'));
        }

        return $users;
    }

    public function GetBetsOnGame($gameId)
    {
        return Bet::where('id_game', '=', $gameId)->get();
    }

    public function MarkAsDone($betId)
    {
        $bet = $this->Get($betId);
        $bet->status = 1;
        $bet->save();
    }

}

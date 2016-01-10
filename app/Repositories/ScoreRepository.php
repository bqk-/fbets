<?php namespace App\Repositories;

use App\Exceptions\MissingArgumentException;
use App\Models\Data\Score;
use App\Repositories\Contracts\IScoreRepository;

class ScoreRepository implements IScoreRepository
{

    public function AddScore($idGame, $scoreTeam1, $scoreTeam2, $state)
    {
        if($scoreTeam1 == '' || $scoreTeam2 == '')
        {
            throw new MissingArgumentException('score');
        }

        $s = new Score();
        $s->id_game = $idGame;
        $s->team1 = $scoreTeam1;
        $s->team2 = $scoreTeam2;
        $s->prediction = $state;
        $s->state = 0;
        $s->save();
        return $s->id;
    }

    public function GetForGame($id)
    {
        $score = Score::where('id_game', '=', $id)->first();

        return $score;
    }

    public function UpdateBetsForGame($game, $score)
    {
        $winnerScore = $score->GetResult();

        $bets = $game->bets();
        $nb = 0;
        foreach ($bets as $bet)
        {
            $points = 0;
            $param = array('outcome' => 0, 'right1' => 0, 'right2' => 0, 'processed' => 1);
            if ($bet->score1 == $bet->score2)
            {
                $winnerBet = 0;
            }
            elseif ($bet->score1 > $bet->score2)
            {
                $winnerBet = 1;
            }
            else
            {
                $winnerBet = 2;
            }

            if ($winnerBet == $winnerScore)
            {
                $points += POINTS_OUTCOME;
                $param['outcome'] = 1;
                if ($bet->score1 == $score->team1 && $bet->score2 == $score->team2)
                {
                    $points += POINTS_BONUS;
                }
            }
            else
            {
                $points -= POINTS_OUTCOME;
            }

            $bet->update($param);

            $bet->user()->increment('points', $points);
            $bet->save();
            $nb++;
        }

        return $nb;
    }
}
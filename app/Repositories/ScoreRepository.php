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
}
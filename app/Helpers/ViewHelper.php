<?php namespace App\Helpers;

use App\Models\Data\Bet;
use App\Models\Data\Score;
use \URL;

class ViewHelper {
    public static function getPointsFromScore(Score $score, Bet $bet)
    {
        if($score === null || $bet === null)
        {
            return '';
        }

        $points = 0;
        if($score->score1 == $score->score2)
            $gagnantScore = 0;
        elseif($score->score1 > $score->score2)
            $gagnantScore = 1;
        else
            $gagnantScore = 2;

        if($bet->score1 == $bet->score2)
            $gagnantBet = 0;
        elseif($bet->score1 > $bet->score2)
            $gagnantBet = 1;
        else
            $gagnantBet = 2;

        if($gagnantBet == $gagnantScore)
            $points+=POINTS_OUTCOME;
        else
            $points-=POINTS_OUTCOME;

        if($points>0)
            return '<span class="text-success">(+'.$points.')</span>';
        elseif($points<0)
            return '<span class="text-danger">('.$points.')</span>';

        return '<span class="text-muted">(+'.$points.')</span>';
    }

    public static function getImagePathFromId($id)
    {
        return URL::to('/') . '/images/i'. $id . '.png';
    }
}

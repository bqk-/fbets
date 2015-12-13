<?php
/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 13/10/15
 * Time: 18:39
 */

namespace App\Helpers;

use App\Exceptions\InvalidArgumentException;
use App\Models\Types\BetStates;
use App\Models\Types\GameStates;

class EnumHelper
{
    public static function ValidBetState($state)
    {
        switch($state)
        {
            case BetStates::LOOSE:
                return BetStates::LOOSE;

            case BetStates::WAITING:
                return BetStates::WAITING;

            case BetStates::WIN:
                return BetStates::WIN;

            default:
                throw new InvalidArgumentException('state', $state);
        }
    }

    public static function GetScoreResult($team1, $team2)
    {
        if($team1 > $team2)
        {
            return GameStates::HOME;
        }

        if($team2 > $team1)
        {
            return GameStates::VISITOR;
        }

        return GameStates::DRAW;
    }
}
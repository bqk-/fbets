<?php namespace App\Repositories\Contracts;

interface IBetRepository
{
    public function Get($id);

    public function GetUserIncomingBets($id, $days = 0);

    public function Create($score1, $score2, $idGame, $userId);

    public function GetAllForUser($id);

    public function GetTopBettors($min, $max);
}
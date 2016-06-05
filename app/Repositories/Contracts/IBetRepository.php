<?php namespace App\Repositories\Contracts;

interface IBetRepository
{

    public function GetUserBetForGame($idGame, $userId);

    public function MarkAsDone($betId, $status);

    public function GetBetsToProcessOnGame($gameId);

    public function Get($id);

    public function GetUserIncomingBets($id, $days = 0);

    public function Create($bet, $idGame, $userId);

    public function GetAllForUser($id);

    public function GetTopBettors($min, $max);
}
<?php namespace App\Repositories\Contracts;

interface IScoreRepository
{
    public function AddScore($idGame, $scoreTeam1, $scoreTeam2);

    public function GetForGame($id);

    public function UpdateBetsForGame($game, $score);
}
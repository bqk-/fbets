<?php namespace App\Repositories\Contracts;

interface IScoreRepository
{
    public function AddScore($idGame, $scoreTeam1, $scoreTeam2, $state);

    public function GetForGame($id);
}
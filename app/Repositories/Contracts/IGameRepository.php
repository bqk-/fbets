<?php namespace App\Repositories\Contracts;

interface IGameRepository
{

    public function Suggest($sport, $team1, $team2, $event, $date, $userId);

    public function GetUserSuggestions($id);

    public function DropGamesForChampionship($id);

    public function GetNext7DaysGameAllSport();

    public function GetGamesWithNoScore($championship = null);

    public function UpdateGameTime($idGame, $getGameTime);

    public function Get($id);
}
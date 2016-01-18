<?php namespace App\Repositories\Contracts;

interface IChampionshipRepository
{

    public function HasGames($champId);

    public function Get($id);

    public function GetAll();

    public function GetAllActive();

    public function Create($name, $class, $sport);

    public function GetAllWithGames();

    public function GetWithGamesAndScores($id);

    public function UpdateChampionshipParams($id, $arrayParams);

    public function ActivateChampionship($id);

    public function UnActivateChampionship($id);

    public function IsActive($id);
}
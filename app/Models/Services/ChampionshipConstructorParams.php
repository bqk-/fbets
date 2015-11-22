<?php namespace App\Models\Services;

use App\Models\Data\Championship;

class ChampionshipConstructorParams
{
    private $arrayParams;
    private $championship;
    private $dbParams;

    public function __construct(Championship $championship, array $arrayParams, array $dbParams)
    {
        $this->arrayParams = $arrayParams;
        $this->championship = $championship;
        $this->dbParams = $dbParams;
    }

    public function GetParams()
    {
            return $this->arrayParams;
    }

    public function GetChampionship()
    {
        return $this->championship;
    }

    public function GetDbParams()
    {
        return $this->dbParams;
    }
}
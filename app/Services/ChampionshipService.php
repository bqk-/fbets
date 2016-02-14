<?php namespace App\Services;

use App\Exceptions\InvalidArgumentException;
use App\Exceptions\MissingArgumentException;
use App\Exceptions\OutOfRangeException;
use App\Repositories\Contracts\IChampionshipRepository;

/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 29/07/15
 * Time: 12:35
 */

class ChampionshipService
{
    private $_championshipRepository;

    public function __construct(IChampionshipRepository $championshipRepository)
    {
        $this->_championshipRepository = $championshipRepository;
    }

    public function Get($id)
    {
        return $this->_championshipRepository->Get($id);
    }

    public function GetAll()
    {
        return $this->_championshipRepository->GetAll();
    }

    public function Create($name, $class, $sport)
    {
        if(empty($name))
        {
            throw new MissingArgumentException('name');
        }

        if(strlen($name) < 3 || strlen($name) > 255)
        {
            throw new OutOfRangeException('name');
        }

        if(!preg_match('#[a-zA-Z0-9 -_]+#', $name))
        {
            throw new InvalidArgumentException('name', $name);
        }

        if(empty($class))
        {
            throw new MissingArgumentException('class');
        }

        if(!is_int($sport) || $sport <= 0)
        {
            throw new InvalidArgumentException('sport', $sport);
        }

        return $this->_championshipRepository->Create($name, $class, $sport);
    }

    public function GetAllWithGames()
    {
        return $this->_championshipRepository->GetAllWithGames();
    }

    public function GetWithGamesAndScores($id)
    {
        return $this->_championshipRepository->GetWithGamesAndScores($id);
    }

    public function HasGames($champId)
    {
        return $this->_championshipRepository->HasGames($champId);
    }

}
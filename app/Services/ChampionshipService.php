<?php namespace App\Services;

use App\Exceptions\InvalidArgumentException;
use App\Exceptions\MissingArgumentException;
use App\Exceptions\OutOfRangeException;
use App\Repositories\Contracts\IChampionshipRepository;
use \DB;
use Illuminate\Contracts\Validation\UnauthorizedException;
use Illuminate\Support\Facades\Auth;

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
        if(!Auth::check())
        {
            throw new UnauthorizedException('Cannot create championship without being logged');
        }

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

    public function UpdateChampionshipParams($id, array $arrayParams)
    {
        if(!Auth::check())
        {
            throw new UnauthorizedException('Cannot create championship without being logged');
        }

        if(!is_array($arrayParams))
        {
            throw new InvalidArgumentException('array of params', $arrayParams);
        }

        $this->_championshipRepository->UpdateChampionshipParams($id, $arrayParams);
    }

    public function ActivateChampionship($id)
    {
        $this->_championshipRepository->ActivateChampionship($id);
    }
}
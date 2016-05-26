<?php namespace App\Repositories;

use App\Exceptions\InvalidOperationException;
use App\Exceptions\NotFoundException;
use App\Repositories\Contracts\IChampionshipRepository;
use App\Models\Data\Championship;


class ChampionshipRepository implements IChampionshipRepository
{

    public function Get($id)
    {
        $championship = Championship::with('games', 'games.team1', 'games.team2')->find($id);

        if($championship == null)
        {
            throw new NotFoundException('Championship',  'id', $id);
        }

        $championship->params = unserialize($championship->params);
        return $championship;
    }

    public function GetAll()
    {
        $championships = Championship::all();

        return $championships;
    }

    public function GetAllActive()
    {
        $championships = Championship::where('active', 1)->get();

        return $championships;
    }
    
    public function Create($name, $class, $sport)
    {
        $championship = new Championship();
        $championship->name = $name;
        $championship->type = $class;
        $championship->id_sport = $sport;
        $championship->params = serialize(array());
        $championship->save();

        return $championship->id;
    }

    public function GetAllWithGames()
    {
        $champs = Championship::with('games.team1')->with('games.team2')->with('sport')
            ->where('active','=',1)
            ->get();

        return $champs;
    }

    public function GetWithGamesAndScores($id)
    {
        $championship = Championship::where('id', $id)->with('games.score')->with('games.team1')->with('games.team2')
            ->with
        ('sport')
            ->first();

        if($championship == null)
        {
            throw new NotFoundException('Championship', 'id', $id);
        }

        return $championship;
    }

    public function UpdateChampionshipParams($id, $arrayParams)
    {
        $championship = $this->Get($id);
        $championship->params = serialize($arrayParams);
        $championship->save();
    }

    public function ActivateChampionship($id)
    {
        $championship = $this->Get($id);
        if($championship->active != 0)
        {
            throw new InvalidOperationException('Cannot activate an active championship');
        }

        $championship->active = 1;
        $championship->params = serialize($championship->params);
        $championship->save();
    }

    public function UnActivateChampionship($id)
    {
        $championship = $this->Get($id);
        if($championship->active != 1)
        {
            throw new InvalidOperationException('Cannot unactivate an unactive championship');
        }

        $championship->active = 0;
        $championship->params = serialize($championship->params);
        $championship->save();
    }

    public function IsActive($id)
    {
        return $this->Get($id)->active == 1;
    }

    public function HasGames($champId)
    {
        $game = Championship::find($champId)->with('games')->first()->games()->first();
        return $game != null;
    }

}



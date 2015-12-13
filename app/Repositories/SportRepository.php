<?php namespace App\Repositories;

use App\Repositories\Contracts\ISportRepository;

class SportRepository implements ISportRepository
{
    public function GetSports()
    {
        return Sport::lists('name', 'id');
    }
    
    public function Get($id)
    {
        $sport = Sport::find($id);

        return $sport;
    }

    public function GetAll() 
    {
        $sports = Sport::all();

        return $sports;
    }

    public function Create($name, $idLogo) 
    {
        $s = new Sport;
        $s->name = $name;
        $s->logo = $idLogo;
        $s->save();
        return $s->id;
    }

}
<?php namespace App\Repositories;

use App\Repositories\Contracts\ITeamRepository;

class TeamRepository implements ITeamRepository
{
    public function GetTeamsForDropdownBySport($sportid) 
    {
        return Team::where('id_sport', '=', $sportId)->lists('name', 'id');
    }
    
    public function GetAllTeamsForDropdown() 
    {
        return Team::lists('name', 'id');
    }

    public function GetTeamsWithRelations($sportId) 
    {
        return Team::where('id_sport', '=', $sportId)->with('relations')->get();
    }

    public function Create($name, $idlogo, $sport)
    {
        $t = new Team();
        $t->name = $name;
        $t->logo = $idlogo;
        $t->id_sport = $sport;
        $t->save();
        return $t->id;
    }
    
    public function RegisterRelation($localId, $outId, $champId)
    {
        $r = new TeamRelation;
        $r->local_id = $localId;
        $r->out_id = $outId;
        $r->championship_id = $champId;
        $r->save();
        return $r->id;
    }
    
    public function GetRelations($id)
    {
        return TeamRelation::where('championship_id', '=', $id)->lists('local_id', 'out_id');
    }
}



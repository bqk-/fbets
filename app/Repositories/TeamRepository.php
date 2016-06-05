<?php namespace App\Repositories;

use App\Repositories\Contracts\ITeamRepository;
use App\Models\Data\Team;
use App\Models\Data\TeamRelation;

class TeamRepository implements ITeamRepository
{
    public function GetTeamsForDropdownBySport($sportId) 
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
    
    public function GetRelation($idExt, $id_champ)
    {
        return TeamRelation::
                where('championship_id', '=', $id_champ)
                ->where('out_id', '=', $idExt)
                ->first();
    }

    public function GetRelations($idChamp)
    {
        return TeamRelation::
                where('championship_id', '=', $idChamp)
                ->lists('local_id', 'out_id');
    }

}



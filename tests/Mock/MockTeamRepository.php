<?php namespace Mock;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MockTeamRepository implements \App\Repositories\Contracts\ITeamRepository
{
    private $teams = array();
    private $relations = array();
    private $id = 1;
    
    public function Create($name, $idlogo, $sport) 
    {
        $t = new \App\Models\Data\Team();
        $t->name = $name;
        $t->logo = $idlogo;
        $t->id_sport = $sport;
        $t->id = ++$this->id;
        $this->teams[$t->id] = $t;
        
        return $t->id;
    }

    public function GetAllTeamsForDropdown() {
        
    }

    public function GetRelations($id) {
        if(key_exists($id, $this->relations)){
            return $this->relations[$id];
        }
    }

    public function GetTeamsForDropdownBySport($sportid) {
        
    }

    public function GetTeamsWithRelations($sportId) {
        
    }

    public function RegisterRelation($localId, $outId, $champId) 
    {
        $r = new \App\Models\Data\TeamRelation;
        $r->local_id = $localId;
        $r->out_id = $outId;
        $r->championship_id = $champId;
        $r->id = ++$this->id;
        $this->relations[$champId][$outId] = $localId;
        return $r->id;
    }

    public function GetRelation($idExt, $idChamp)
    {
        if(key_exists($idChamp, $this->relations)){
            return $this->relations[$idChamp][$idExt];
        }
    }

}
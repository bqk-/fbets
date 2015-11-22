<?php namespace App\Repositories\Contracts;

interface ITeamRepository
{
    public function GetTeamsForDropdownBySport($sportid);
    
    public function GetAllTeamsForDropdown();
    
    public function GetTeamsWithRelations($sportId);
    
    public function Create($name, $idlogo, $sport);
    
    public function RegisterRelation($localId, $outId, $champId);
    
    public function GetRelations($id);
}

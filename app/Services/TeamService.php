<?php namespace App\Services;

use App\Models\Data\Team;
use App\Models\Data\TeamRelation;

class TeamService {

    private $_imageService;
    private $_teamRepository;

    public function __construct(\App\Repositories\Contracts\ITeamRepository $teamRepository, 
            ImageService $imageService)
    {
        $this->_imageService = $imageService;
        $this->_teamRepository = $teamRepository;
    }

    public function GetTeamsForDropdown($sportId = null)
    {
        if($sportId == null)
        {
            return $this->_teamRepository->GetAllTeamsForDropdown();
        }
        
        return $this->_teamRepository->GetTeamsForDropDown($sportId);
    }

    public function GetTeamsWithRelations($sportId)
    {
        return $this->_teamRepository->GetTeamsWithRelations($sportId);
    }

    private function Create($name, $logo, $sport)
    {
        $idlogo = $this->_imageService->UploadLogo($logo);
        return $this->_teamRepository->Create($name, $idlogo, $sport);
    }
    
    public function GetRelations($id)
    {
        return $this->_teamRepository->GetRelations($id);
    }

    public function SaveTeamsWithRelations($teams, $actions, $champId, $sportId)
    {
        $teamsId = array();
        foreach ($teams as $team)
        {
            if(array_key_exists($team->id, $actions))
            {
                if($actions[$team->id] == 0)
                {
                    $id = $this->Create($team, $sportId);
                    $this->_teamRepository->RegisterRelation($id, $team->id, $champId);

                }
                else
                {
                    $this->_teamRepository->RegisterRelation($actions[$team->id], $team->id, $champId);
                    $id = $actions[$team->id];
                }

                $teamsId[$team->id] = $id;
            }
        }

        return $teamsId;
    }
}
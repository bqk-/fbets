<?php namespace App\Services;

use App\Services\Contracts\IImageService;

class TeamService {

    private $_imageService;
    private $_teamRepository;

    public function __construct(\App\Repositories\Contracts\ITeamRepository $teamRepository, 
            IImageService $imageService)
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
        
        return $this->_teamRepository->GetTeamsForDropdownBySport($sportId);
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

    public function SaveTeam(\App\Models\Admin\TournamentClasses\Team $team, $champId, $sportId)
    {
        $id = $this->Create($team->Name, $team->LogoUrl, $sportId);
        $this->_teamRepository->RegisterRelation($id, $team->Id, $champId);

        return $id;
    }
}
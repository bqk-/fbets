<?php namespace App\Services;

use App\Models\Data\Sport;

class SportService {
    
    private $_sportRepository;

    public function __construct(\App\Repositories\Contracts\ISportRepository $sportRepository)
    {
        $this->_sportRepository = $sportRepository;
    }
    
    public function GetSportsForDropdown()
    {
        return $this->_sportRepository->GetSports();
    }

    public function GetSport($id)
    {
        return $this->_sportRepository->Get($id);
    }
    
    public function SportExists($id)
    {
        return $this->_sportRepository->Get($id) !== null;
    }

    public function GetAll()
    {
        return $this->_sportRepository->GetAll();
    }

    public function Create($name, $idLogo)
    {
        //Check name here
        return $this->_sportRepository->Create($name, $idLogo);
    }
}
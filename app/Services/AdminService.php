<?php namespace App\Services;

use App\Models\Services\ChampionshipConstructorParams;
use App\Repositories\Contracts\IChampionshipRepository;
use App\Repositories\Contracts\IGameRepository;
use App\Repositories\Contracts\IScoreRepository;

class AdminService
{
    private $_championshipRepository;
    private $_gameRepository;
    private $_scoreRepository;

    public function __construct(IChampionshipRepository $championshipRepository,
                    IGameRepository $gameRepository, IScoreRepository $scoreRepository)
    {
        $this->_championshipRepository = $championshipRepository;
        $this->_gameRepository = $gameRepository;
        $this->_scoreRepository = $scoreRepository;
    }

    public function GetMissingResults()
    {
        return $this->_gameRepository->GetGamesWithNoScore();
    }

    public function GetMissingResultsForChampionship($champ)
    {
        $championship = $this->_championshipRepository->Get($champ);
        return $this->_gameRepository->GetGamesWithNoScore($championship->id);
    }

    public function ToggleActiveChampionship($id)
    {
        if($this->_championshipRepository->IsActive($id))
        {
            $this->_championshipRepository->UnActivateChampionship($id);
        }
        else
        {
            $this->_championshipRepository->ActivateChampionship($id);
        }
    }

    public function GetAvailableClasses()
    {
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR
            . 'Admin' . DIRECTORY_SEPARATOR . 'Tournament';
        $dh  = opendir($dir);

        $files = array();
        while (false !== ($filename = readdir($dh))) {
            if(strtolower(substr($filename, 0, 1)) != 'i' && filetype($dir . DIRECTORY_SEPARATOR . $filename) != 'dir')
            {
                $files[substr($filename, 0, -4)] = substr($filename, 0, -4);
            }
        }

        return $files;
    }

    public function GetWorkingClassForChampionship($idChampionship)
    {
        $champ = $this->_championshipRepository->Get($idChampionship);
        $className = $this->GetFullClassName($champ->type);
        $reflectionObj = new \ReflectionClass($className);

        $workingClass = $reflectionObj->newInstanceArgs($champ->params);

        return $workingClass;
    }

    public function GetChampionshipConstructorParams($id)
    {
        $championship = $this->_championshipRepository->Get($id);
        $arrayParams = $this->GetConstructorParamsFromName($championship->type);

        return new ChampionshipConstructorParams($championship, $arrayParams, $championship->params);
    }
    
    public function UpdateChampionshipParams($id, array $arrayParams)
    {
        if(!is_array($arrayParams))
        {
            throw new InvalidArgumentException('array of params', $arrayParams);
        }

        $this->_championshipRepository->UpdateChampionshipParams($id, $arrayParams);
    }
    
    public function DropGamesForChampionship($id)
    {
        return $this->_gameRepository->DropGamesForChampionship($id);
    }
    
    public function ActivateChampionship($id)
    {
        $this->_championshipRepository->ActivateChampionship($id);
    }
    
    public function GetConstructorParamsFromClassname($name)
    {
        return $this->GetConstructorParamsFromName($name);
    }

    private function GetFullClassName($name)
    {
        return 'App\Models\Admin\Tournament\\'. $name;
    }

    private function GetConstructorParamsFromName($name)
    {
        $className = $this->GetFullClassName($name);
        $modelConstruct = new \ReflectionMethod($className, '__construct');
        $arrayParams = $modelConstruct->getParameters();

        return $arrayParams;
    }
}

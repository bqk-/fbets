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
            . 'Admin';
        $dh  = opendir($dir);

        $files = array();
        while (false !== ($filename = readdir($dh))) {
            if(substr($filename, 0, 1) != 'I' && filetype($dir . DIRECTORY_SEPARATOR . $filename) != 'dir')
            {
                $files[$filename] = substr($filename, 0, -4);
            }
        }

        return $files;
    }

    public function GetWorkingClassForChampionship($idChampionship)
    {
        $champ = $this->_championshipRepository->Get($idChampionship);
        $className = $this->GetFullClassName($champ->type);
        $reflectionObj = new \ReflectionClass($className);

        if($champ->params == null)
        {
            $workingClass = $reflectionObj->newInstanceArgs(array());
        }
        else
        {
            $workingClass = $reflectionObj->newInstanceArgs($champ->params);
        }

        return $workingClass;
    }

    public function UpdateAllActiveChampionships()
    {
        \Artisan::call('down');
        file_put_contents(\Config::get('view.paths')[0].'/ajax/progress.php', '0');

        $championships = $this->_championshipRepository->GetAllActive();
        $total = count($championships);
        $done = 0;
        foreach($championships as $championship)
        {
            $workingClass = $this->GetWorkingClassForChampionship($championship->id);
            $games = $this->_gameRepository->GetGamesWithNoScore($championship->id);
            foreach($games as $game)
            {
                if($game->date != $workingClass->getGameTime($game->team1, $game->team2))
                {
                    $this->_gameRepository->UpdateGameTime($game->id, $workingClass->getGameTime($game->team1,
                        $game->team2));
                }

                $score = $workingClass->getScore($game->team1, $game->team2);
                if($score)
                {
                    $this->_scoreRepository->AddScore($game->id, explode('-',$score)[0], explode('-',$score)[1]);
                }
            }
            file_put_contents(\Config::get('view.paths')[0].'/ajax/progress.php', round($done++/$total*100));
        }

        \Artisan::call('up');

        return $done;
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

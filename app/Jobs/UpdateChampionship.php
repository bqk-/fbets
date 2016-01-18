<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Services\AdminService;
use App\Services\ChampionshipService;
use App\Services\GameService;

class UpdateChampionship extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var App\Services\AdminService
     */
    private $AdminService;
    
    /**
     * @var App\Services\ChampionshipService
     */
    private $ChampionshipService;
    
    /**
     * @var App\Services\GameService
     */
    private $GameService;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AdminService $adminService,
        ChampionshipService $championshipService,
        GameService $gameService)
    {
        $this->AdminService = $adminService;
        $this->ChampionshipService = $championshipService;
        $this->GameService = $gameService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle($champId)
    {
        $championship = $this->ChampionshipService->Get($champId);
        if($championship == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Cannot find championship: ' . $champId);
        }
        
        if($this->ChampionshipService->HasGames($champId))
        {
            $this->UpdateChampionship($championship);
        }
        else
        {
            $this->InitializeChampionship($championship);
        }
    }
    
    private function UpdateChampionship($championship)
    {
        $workingClass = $this->AdminService->GetWorkingClassForChampionship($championship->id);
        $games = $this->GameService->GetGamesWithNoScore($championship->id);
        foreach($games as $game)
        {
            if($game->date != $workingClass->getGameTime($game->team1, $game->team2))
            {
                $this->GameService->UpdateGameTime($game->id, $workingClass->getGameTime($game->team1,
                    $game->team2));
            }

            $score = $workingClass->getScore($game->team1, $game->team2);
            $state = $workingClass->getGameStateFromScore($score->TeamHome, $score->TeamVisit);
            
            if($score)
            {
                $this->GameService->AddScore($game->id, 
                        $score->TeamHome, 
                        $score->TeamVisit,
                        $state);
            }
        }
    }
    
    private function InitializeChampionship($championship)
    {
        //TODO: move add games / save here
    }
}

<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Services\AdminService;
use App\Services\ChampionshipService;
use App\Services\GameService;
use App\Services\TeamService;

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
     * @var App\Services\TeamService
     */
    private $TeamService;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    
    private $ChampId;
    
    public function __construct($champId)
    {
        $this->ChampId = $champId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AdminService $adminService,
        ChampionshipService $championshipService,
        GameService $gameService,
        TeamService $teamService)
    {
        $this->AdminService = $adminService;
        $this->ChampionshipService = $championshipService;
        $this->GameService = $gameService;
        $this->TeamService = $teamService;
        
        $championship = $this->ChampionshipService->Get($this->ChampId);
        if($championship == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Cannot find championship: ' . $this->ChampId);
        }
        
        if($this->ChampionshipService->HasGames($this->ChampId))
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
            if($score == null)
            {
                continue;
            }
            
            $state = $workingClass->getGameStateFromScore($score->TeamHome, $score->TeamVisit);
            
            if($state)
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
        $workingClass = $this->AdminService->GetWorkingClassForChampionship($championship->id);

        $games = $workingClass->getGames();
        $teams = $workingClass->getTeams();
        $relations = array();

        foreach($teams as $team)
        {
            $relations[$team->Id] = $this->TeamService->SaveTeam($team,                              
                    $championship->id, 
                    $championship->id_sport);
        }

        foreach ($games as $game)
        {
            $this->GameService->Create($game, $championship->id, $relations);
            $score = $workingClass->getScore($game->Id);
            if($score == null)
            {
                continue;
            }
            
            $state = $workingClass->getGameStateFromScore($score->TeamHome, $score->TeamVisit);
            if($state)
            {
                $this->GameService->AddScore($game->id, 
                        $score->TeamHome, 
                        $score->TeamVisit,
                        $state);
            }
        }
    }
}

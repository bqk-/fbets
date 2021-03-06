<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Services\AdminService;
use App\Services\ChampionshipService;
use App\Services\GameService;
use App\Services\BetService;
use App\Services\UserService;
use App\Services\GroupService;

class ProcessBets extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    public $MAXPOINTS = 25;
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
     * @var App\Services\BetService
     */
    private $BetService;
    
    /**
     * @var App\Services\UserService
     */
    private $UserService;
    
    /**
     * @var App\Services\GroupService
     */
    private $GroupService;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    
    private $GameId;
    
    public function __construct($gameId)
    {
        $this->GameId = $gameId;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AdminService $adminService,
        ChampionshipService $championshipService,
        GameService $gameService,
        BetService $betService,
        UserService $userService,
        GroupService $groupService)
    {
        $this->AdminService = $adminService;
        $this->ChampionshipService = $championshipService;
        $this->GameService = $gameService;
        $this->BetService = $betService;
        $this->UserService = $userService;
        $this->GroupService = $groupService;
        
        $game = $this->GameService->Get($this->GameId);
        if($game == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Cannot find gane: ' . $this->GameId);
        }
        
        $championship = $this->ChampionshipService->Get($game->id_champ);
        if($championship == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Cannot find championship: ' . $game->id_champ);
        }
        
        $score = $this->GameService->GetScore($this->GameId);
        if($score == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Cannot find score associated to game: ' . $this->GameId);
        }
        
        $rates = $this->BetService->GetRates($this->GameId);
        
        $bets = $this->BetService->GetBetsToProcessOnGame($this->GameId);
        
        if($bets->count() == 0)
        {
            return;
        }

        $this->Process($championship, $bets, $score, $rates);
    }
    
    private function Process($championship, $bets, $score, $rates)
    {
        $workingClass = $this->AdminService->GetWorkingClassForChampionship($championship->id);
        $state = $workingClass->getGameStateFromScore($score->team1, $score->team2);
        $ratesArray = array();
        $ratesArray[\App\Models\Types\GameStates::HOME] = $rates->HomeRate;
        $ratesArray[\App\Models\Types\GameStates::VISITOR] = $rates->VisitRate;
        $ratesArray[\App\Models\Types\GameStates::DRAW] = $rates->DrawRate;
        
        foreach ($bets as $bet)
        {
            $stateBet = $bet->bet;
            
            if ($stateBet == $state)
            {
                $this->UserService->AddPoints($bet->id_user, ceil($this->MAXPOINTS * $ratesArray[$stateBet]));
                $this->GroupService->AddPointsGroupsGame($bet->id_user, $bet->id_game, ceil($this->MAXPOINTS * $ratesArray[$stateBet]));
                $this->BetService->MarkAsDone($bet->id, \App\Models\Types\BetStates::WIN);
            }
            else
            {
                $this->UserService->RemovePoints($bet->id_user, ceil($this->MAXPOINTS * $ratesArray[$stateBet]));
                $this->GroupService->RemovePointsGroupsGame($bet->id_user, $bet->id_game, ceil($this->MAXPOINTS * $ratesArray[$stateBet]));
                $this->BetService->MarkAsDone($bet->id, \App\Models\Types\BetStates::LOOSE);
            }    
            
            $this->GameService->SaveRates($bet->id_game, $rates->HomeRate, $rates->DrawRate, $rates->VisitRate);
        }
    }
}
